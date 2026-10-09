<?php
// Encabezado JSON
header('Content-Type: application/json');

require_once(__DIR__ . '/../../includes/ajax_bootstrap.php');

if(!isset($_SESSION['admincp_user'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

function guide_error($message) {
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

function guide_upload_image($field, $directory, $prefix) {
    if(!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return '';
    if($_FILES[$field]['error'] !== UPLOAD_ERR_OK) guide_error('No se pudo recibir la imagen.');
    if($_FILES[$field]['size'] > 8 * 1024 * 1024) guide_error('La imagen no puede superar los 8 MB.');

    $imageInfo = @getimagesize($_FILES[$field]['tmp_name']);
    if($imageInfo === false) guide_error('El archivo recibido no es una imagen válida.');

    $mimeExtensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif'
    ];
    if(!isset($mimeExtensions[$imageInfo['mime']])) guide_error('Formato de imagen no permitido.');
    if(!is_dir($directory) && !@mkdir($directory, 0775, true)) guide_error('No se pudo crear la carpeta de imágenes.');
    if(!is_writable($directory)) guide_error('La carpeta de imágenes no es escribible.');

    $filename = $prefix . '_' . bin2hex(random_bytes(8)) . '.' . $mimeExtensions[$imageInfo['mime']];
    $destination = $directory . DIRECTORY_SEPARATOR . $filename;
    if(!move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
        guide_error('No se pudo guardar la imagen subida.');
    }

    return 'includes/uploads/guides/' . $filename;
}

function guide_update_meta($content, $cover, $thumb) {
    $meta = '<!--GUIDE_META';
    if($cover !== '') $meta .= ' cover="' . htmlspecialchars($cover, ENT_QUOTES, 'UTF-8') . '"';
    if($thumb !== '') $meta .= ' thumb="' . htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8') . '"';
    $meta .= ' -->';

    if(preg_match('/<!--\s*GUIDE_META.*?-->/is', $content)) {
        return preg_replace('/<!--\s*GUIDE_META.*?-->/is', $meta, $content, 1);
    }
    return $meta . "\n" . $content;
}

// Directorio de guías
$guidesDir = __DIR__ . '/../../guides/';
if (!is_dir($guidesDir) && !@mkdir($guidesDir, 0775, true)) guide_error('No se pudo crear la carpeta de guías.');

// Verificar si vienen datos vía POST
$data = $_POST;
if (empty($data['guides_title']) || empty($data['guides_author']) || empty($data['guides_content'])) {
    echo json_encode(['success' => false, 'message' => '❌ Datos incompletos.']);
    exit;
}

// Determinar ID (nuevo o edición)
$id = (isset($data['guides_id']) && is_numeric($data['guides_id'])) ? intval($data['guides_id']) : null;

if (!$id) {
    // Crear nuevo ID secuencial
    $existing = glob($guidesDir . '*.json');
    $ids = array_map(function($f) {
        return (int)basename($f, '.json');
    }, $existing);
    $id = $ids ? max($ids) + 1 : 1;
}

// Ruta final
$guideFile = $guidesDir . $id . '.json';

// Preparar datos
$guideData = [
    'guides_id' => $id,
    'guides_title' => trim($data['guides_title']),
    'guides_author' => trim($data['guides_author']),
    'guides_date' => time(),
    'guides_content' => $data['guides_content']
];

// Conservar traducciones anteriores si existen
if (file_exists($guideFile)) {
    $existingData = json_decode(file_get_contents($guideFile), true);
    if (isset($existingData['translations'])) {
        $guideData['translations'] = $existingData['translations'];
    }
}

// Conservar metadatos anteriores y reemplazarlos solo cuando se sube una imagen nueva.
$cover = '';
$thumb = '';
if(isset($existingData['guides_content'])) {
    if(preg_match('/cover="([^"]+)"/i', $existingData['guides_content'], $match)) $cover = $match[1];
    if(preg_match('/thumb="([^"]+)"/i', $existingData['guides_content'], $match)) $thumb = $match[1];
}
$uploadDir = __DIR__ . '/../../includes/uploads/guides';
$newCover = guide_upload_image('guide_cover', $uploadDir, 'cover');
$newThumb = guide_upload_image('guide_thumb', $uploadDir, 'thumb');
if($newCover !== '') $cover = $newCover;
if($newThumb !== '') $thumb = $newThumb;
$guideData['guides_content'] = guide_update_meta($guideData['guides_content'], $cover, $thumb);

// Guardar archivo
if (!is_writable(dirname($guideFile))) {
    guide_error('El directorio /guides/ no tiene permisos de escritura.');
}

if (file_put_contents($guideFile, json_encode($guideData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
    guide_error('No se pudo guardar la guía.');
}

// Éxito
echo json_encode(['success' => true, 'id' => $id]);
exit;
