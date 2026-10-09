<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID inválido.']);
    exit;
}

$file = __DIR__ . '/../../guides/' . $id . '.json';
if (!file_exists($file)) {
    echo json_encode(['success' => false, 'message' => 'La guía no existe.']);
    exit;
}

if (unlink($file)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el archivo.']);
}
