<?php
// Activar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar sesión de admin
session_start();
if(!isset($_SESSION['admincp_user'])) {
    http_response_code(403);
    die(json_encode(['error' => 'No autorizado']));
}

// Incluir configuración y clases necesarias
require_once('../../includes/config.php');
require_once('../../includes/functions.php');
require_once('../../includes/classes/class.guides.php');

header('Content-Type: application/json');

try {
    if(!isset($_POST['title'], $_POST['content'], $_POST['author']))
        throw new Exception('Faltan datos');

    $Guides = new Guides();

    if(!$Guides->isGuidesDirWritable())
        throw new Exception('La carpeta de caché no es escribible.');

    $Guides->addGuides($_POST['title'], $_POST['content'], $_POST['author'], 0);
    $Guides->cacheGuides();
    $Guides->updateGuidesCacheIndex();

    echo json_encode(['success' => true]);
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
