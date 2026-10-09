<?php
// Este archivo es opcional y solo sirve si querés regenerar un index global
// por ejemplo para performance. No es necesario si todo funciona por AJAX directo.

// Cargar config webengine.json
$config = json_decode(file_get_contents(__DIR__ . '/../../includes/config/webengine.json'), true);
header('Content-Type: application/json');

$guidesDir = __DIR__ . '/../../guides/';
$indexFile = $guidesDir . 'index.json';
$files = glob($guidesDir . '*.json');
$index = [];

foreach ($files as $file) {
    if(basename($file) == 'index.json') continue;
    $content = json_decode(file_get_contents($file), true);
    if (!$content || !isset($content['guides_id'])) continue;

    $index[] = [
        'guides_id' => $content['guides_id'],
        'guides_title' => $content['guides_title'],
        'guides_author' => $content['guides_author'],
        'guides_date' => $content['guides_date'],
        'translations' => isset($content['translations']) ? array_keys($content['translations']) : []
    ];
}

file_put_contents($indexFile, json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['success' => true, 'message' => 'Index regenerado correctamente.']);
