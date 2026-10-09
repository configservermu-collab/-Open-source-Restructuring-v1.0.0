<?php
/**
 * WebEngine CMS - AJAX Bootstrap
 * Inicializaci¨®n m¨ªnima para endpoints AJAX sin cargar Handler
 * 
 * USO: require_once('../../includes/ajax_bootstrap.php');
 */

// Session
session_name('WebEngine126');
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Paths (copiadas de webengine.php)
if(!defined('HTTP_HOST')) {
    define('HTTP_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
}
if(!defined('SERVER_PROTOCOL')) {
    define('SERVER_PROTOCOL', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://');
}
if(!defined('__ROOT_DIR__')) {
    define('__ROOT_DIR__', str_replace('\\','/',dirname(dirname(__FILE__))).'/');
}
if(!defined('__RELATIVE_ROOT__')) {
    define('__RELATIVE_ROOT__', (!empty($_SERVER['SCRIPT_NAME'])) ? str_ireplace(rtrim(str_replace('\\','/', realpath(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['SCRIPT_FILENAME']))), '/'), '', __ROOT_DIR__) : '/');
}
if(!defined('__BASE_URL__')) {
    define('__BASE_URL__', SERVER_PROTOCOL.HTTP_HOST.__RELATIVE_ROOT__);
}

// Private Paths
if(!defined('__PATH_INCLUDES__')) {
    define('__PATH_INCLUDES__', __ROOT_DIR__.'includes/');
}
if(!defined('__PATH_CLASSES__')) {
    define('__PATH_CLASSES__', __PATH_INCLUDES__.'classes/');
}
if(!defined('__PATH_CONFIGS__')) {
    define('__PATH_CONFIGS__', __PATH_INCLUDES__.'config/');
}
if(!defined('__PATH_MODULE_CONFIGS__')) {
    define('__PATH_MODULE_CONFIGS__', __PATH_CONFIGS__.'modules/');
}
if(!defined('__PATH_LOGS__')) {
    define('__PATH_LOGS__', __PATH_INCLUDES__.'logs/');
}
if(!defined('WEBENGINE_DATABASE_ERRORLOG')) {
    define('WEBENGINE_DATABASE_ERRORLOG', __PATH_LOGS__.'database_errors.log');
}
if(!defined('WEBENGINE_PHP_ERRORLOG')) {
    define('WEBENGINE_PHP_ERRORLOG', __PATH_LOGS__.'php_errors.log');
}

// PHP Error Logs
ini_set('log_errors', 1);
ini_set('error_log', WEBENGINE_PHP_ERRORLOG);

// Cargar clases base (en orden de dependencia)
require_once(__PATH_CLASSES__ . 'class.database.php');
require_once(__PATH_CLASSES__ . 'class.common.php');
require_once(__PATH_CLASSES__ . 'class.validator.php');
require_once(__PATH_CLASSES__ . 'class.connection.php');
require_once(__PATH_CLASSES__ . 'class.character.php');

// Cargar functions
require_once(__PATH_INCLUDES__ . 'functions.php');

// Definir variable global $lang para evitar warnings en functions.php
if(!isset($lang)) {
    $lang = array();
}

// Cargar configuraciones WebEngine
$config = webengineConfigs();
