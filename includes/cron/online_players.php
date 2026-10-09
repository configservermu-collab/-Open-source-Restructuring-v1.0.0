<?php
/**
 * Online Players
 * https://webenginecms.org/
 * 
 * @version 1.0.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

// File Name
$file_name = basename(__FILE__);

// Load Plugin
if(!@include_once(__PATH_PLUGINS__.'onlineplayers/loader.php')) die('Failed to load OnlinePlayers plugin.');

// Run Cron
$OnlinePlayers = new \Plugin\OnlinePlayers\OnlinePlayers();
$OnlinePlayers->updateCache();

// UPDATE CRON
updateCronLastRun($file_name);