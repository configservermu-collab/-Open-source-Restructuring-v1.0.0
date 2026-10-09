<?php
/**
 * Referral System
 * https://webenginecms.org/
 * 
 * @version 1.0.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

try {
	if(!class_exists('Plugin\ReferralSystem\ReferralSystem')) throw new Exception('Plugin disabled.');
	$ReferralSystem = new Plugin\ReferralSystem\ReferralSystem();
	$ReferralSystem->loadModule('register');
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}