<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * WebEngine CMS
 * https://webenginecms.org/
 */

require_once(__DIR__ . '/../webengine.php'); // Corrección de ruta

$file_name = basename(__FILE__);

require_once(__PATH_INCLUDES__ . 'classes/class.rankings.php');

loadModuleConfigs('rankings');

if(mconfig('active')) {
    if(mconfig('rankings_enable_bloodcastle')) {
        $Rankings = new Rankings();
        $Rankings->UpdateRankingCache('bloodcastle'); // ← CORRECTO
    }
}

updateCronLastRun($file_name);
