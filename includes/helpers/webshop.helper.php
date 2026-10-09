<?php
/**
 * ╔════════════════════════════════════════════════════════════════════════════╗
 * ║             WEBSHOP HELPER - LOADER DINÁMICO DE CLASES                     ║
 * ╚════════════════════════════════════════════════════════════════════════════╝
 * 
 * @version     1.0.0
 * @date        2026-04-17
 * @description Helper para cargar dinámicamente la clase webshop correcta
 *              según la versión de MU Online configurada
 */

/**
 * Obtiene la versión de MU configurada
 * @return string 'muemu' o 'ex101kor'
 */
function getWebshopMuVersion() {
    $config = loadConfigurations('webshop');
    return isset($config['mu_version']) ? $config['mu_version'] : 'muemu';
}

/**
 * Carga la clase webshop correcta según la versión MU
 * @return object Instancia de la clase webshop
 * @throws Exception Si la clase no existe
 */
function loadWebshopClass() {
    $muVersion = getWebshopMuVersion();
    
    switch($muVersion) {
        case 'ex101kor':
            $classFile = __PATH_INCLUDES__ . 'classes/class.webshop.ex101kor.php';
            if(!file_exists($classFile)) {
                throw new Exception('⚠️ Clase EX101KOR no encontrada: ' . $classFile);
            }
            require_once($classFile);
            return new WebShopEX101KOR();
            
        case 'muemu':
        default:
            $classFile = __PATH_INCLUDES__ . 'classes/class.webshop.php';
            if(!file_exists($classFile)) {
                throw new Exception('⚠️ Clase MuEMU no encontrada: ' . $classFile);
            }
            require_once($classFile);
            return new WebShopNew();
    }
}

/**
 * Verifica si una feature está soportada en la versión actual
 * @param string $feature 'harmony', 'sockets', 'op380'
 * @return bool true si está soportada
 */
function webshopSupportsFeature($feature) {
    $muVersion = getWebshopMuVersion();
    
    // EX101KOR NO soporta: Harmony, Sockets, Op380
    if($muVersion === 'ex101kor') {
        $unsupportedFeatures = ['harmony', 'sockets', 'op380'];
        return !in_array(strtolower($feature), $unsupportedFeatures);
    }
    
    // MuEMU soporta todo
    return true;
}

/**
 * Obtiene nombre descriptivo de la versión MU
 * @return string Nombre descriptivo
 */
function getWebshopMuVersionName() {
    $muVersion = getWebshopMuVersion();
    
    $versions = [
        'muemu' => '🎮 MuEMU (Season 6+)',
        'ex101kor' => '🎮 SSEmu EX101KOR'
    ];
    
    return isset($versions[$muVersion]) ? $versions[$muVersion] : 'Unknown';
}

/**
 * Obtiene mensaje de warning para features no soportadas
 * @return string HTML con mensaje de warning o string vacío
 */
function getWebshopVersionWarning() {
    $muVersion = getWebshopMuVersion();
    
    if($muVersion === 'ex101kor') {
        return '<div class="alert alert-warning" style="margin-bottom: 20px;">
            <strong>⚠️ Modo EX101KOR Activado</strong><br>
            Las opciones <b>Harmony</b>, <b>Sockets</b> y <b>Op380</b> NO están disponibles en esta versión.
        </div>';
    }
    
    return '';
}
