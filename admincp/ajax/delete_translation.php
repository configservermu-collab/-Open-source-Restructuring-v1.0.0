<?php
// Cargar config webengine.json
$config = json_decode(file_get_contents(__DIR__ . '/../../includes/config/webengine.json'), true);
header('Content-Type: application/json');

$guideId = $_POST['id'] ?? null;
$language = $_POST['language'] ?? null;
$guidesDir = __DIR__ . '/../../guides/';

if (!is_numeric($guideId) || !$language) {
    echo json_encode(['success' => false, 'message' => 'Parámetros inválidos.']);
    exit;
}

$file = $guidesDir . intval($guideId) . '.json';
if (!file_exists($file)) {
    echo json_encode(['success' => false, 'message' => 'Guía no encontrada.']);
    exit;
}

$guideData = json_decode(file_get_contents($file), true);

if (!isset($guideData['translations'][$language])) {
    echo json_encode(['success' => false, 'message' => 'Traducción no encontrada.']);
    exit;
}

unset($guideData['translations'][$language]);
file_put_contents($file, json_encode($guideData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true]);
