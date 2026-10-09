<?php
// Activar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar sesión admin
session_start();
if(!isset($_SESSION['admincp_user'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado.']);
    exit;
}

// Includes
require_once('../../includes/config.php');
require_once('../../includes/functions.php');
require_once('../../includes/classes/class.guides.php');

header('Content-Type: application/json');

try {
    if(!isset($_POST['id'], $_POST['title'], $_POST['content'], $_POST['author'], $_POST['date']))
        throw new Exception('Faltan datos obligatorios.');

    $guideId = (int)$_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];
    $date = $_POST['date'];

    $Guides = new Guides();

    if(!$Guides->isGuidesDirWritable())
        throw new Exception('La carpeta de guías no es escribible.');

    $Guides->editGuides($guideId, $title, $content, $author, 0, $date);
    $Guides->cacheGuides();
    $Guides->updateGuidesCacheIndex();

    echo json_encode(['status' => 'success', 'message' => '✅ Guía actualizada correctamente.']);
} catch(Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
