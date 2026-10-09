<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$configFile = __DIR__ . '/../cache/guides_module_config.json';

// Validaci贸n del POST
if (!isset($_POST['config']) || !is_array($_POST['config'])) {
    echo json_encode(['success' => false, 'message' => 'POST inv谩lido.']);
    exit;
}

// Leer config anterior (si existe)
$currentConfig = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];

// Normalizar guides_short como booleano
$_POST['config']['guides_short'] = isset($_POST['config']['guides_short']) && $_POST['config']['guides_short'] === 'on';

// Merge con lo nuevo
$mergedConfig = array_merge($currentConfig, $_POST['config']);

// Validar permisos de escritura
if (!is_writable(dirname($configFile))) {
    echo json_encode(['success' => false, 'message' => 'La carpeta cache/ no tiene permisos de escritura.']);
    exit;
}

// Guardar el archivo
if (file_put_contents($configFile, json_encode($mergedConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo.']);
}
