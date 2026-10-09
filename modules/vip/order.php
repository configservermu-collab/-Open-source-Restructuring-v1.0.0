<?php
/**
 * VIP
 * https://webenginecms.org/
 * 
 * @version 1.0.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w3c8c718b75a0f1fa1a557f7f9d70877
 */

try {
	if(!class_exists('Plugin\VIP\VIP')) throw new Exception('Plugin disabled.');
	$VIP = new Plugin\VIP\VIP();
	$VIP->loadModule('order');
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}