<?php
require_once('../../includes/config.php');

header('Content-Type: application/json');

$guidesDir = __DIR__ . '/../../guides/';
$files = glob($guidesDir . '*.json');
$guides = [];

foreach ($files as $file) {
    $content = json_decode(file_get_contents($file), true);
    if (!$content || !isset($content['guides_id'])) continue;

    $guides[] = [
        'guides_id' => $content['guides_id'],
        'guides_title' => $content['guides_title'],
        'guides_author' => $content['guides_author'],
        'guides_date' => $content['guides_date'],
        'translations' => isset($content['translations']) ? array_keys($content['translations']) : []
    ];
}

echo json_encode(['success' => true, 'data' => $guides], JSON_UNESCAPED_UNICODE);
