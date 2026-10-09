<?php
/**
 * API de Rankings para Launcher
 * Lee el cache de WebEngine y genera JSON
 */

// Incluir funciones de WebEngine
require_once '../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

try {
    // Usar la función LoadCacheData de WebEngine
    $ranking_data = LoadCacheData('rankings_general.cache');
    
    if (!is_array($ranking_data)) {
        throw new Exception('No se pudo cargar el cache de rankings');
    }
    
    $topResets = [];
    
    // Tomar top 10
    $count = 0;
    foreach ($ranking_data as $rdata) {
        if ($count >= 10) break;
        
        $topResets[] = [
            'name' => $rdata[0],
            'resets' => (int)$rdata[1],
            'level' => (int)$rdata[2],
            'class' => (int)$rdata[3],
            'guild' => $rdata[4] ?? '',
            'masterLevel' => (int)($rdata[7] ?? 0),
            'grandResets' => (int)($rdata[9] ?? 0)
        ];
        
        $count++;
    }
    
    $response = [
        'success' => true,
        'topGeneral' => $topResets,
        'lastUpdate' => date('Y-m-d H:i:s')
    ];
    
    // Guardar en rankings.json para cache
    file_put_contents(__DIR__ . '/rankings.json', json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
