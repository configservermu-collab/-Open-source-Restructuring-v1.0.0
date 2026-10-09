<?php
/**
 * Redeem Code
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

echo '<h2>Redeemable Codes Logs</h2>';

$RedeemCode = new \Plugin\RedeemCode\RedeemCode();

if(check_value($_GET['id'])) {
	$RedeemCode->setId($_GET['id']);
}

$logsList = $RedeemCode->getLogs();

$codesList = $RedeemCode->getRedeemCodesList();
if(is_array($codesList)) {
	foreach($codesList as $codeData) {
		$redeemCodesList[$codeData['id']] = $codeData['redeem_code'];
	}
}

echo '<table class="table table-striped table-hover">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Code</th>';
			echo '<th>Date Redeemed</th>';
			echo '<th>Ip Address</th>';
			echo '<th>User Identifier</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		
	foreach($logsList as $logData) {
		echo '<tr>';
			echo '<td><a href="'.admincp_base('redeem&page=logs&id='.$logData['code_id']).'">'.$redeemCodesList[$logData['code_id']].'</a></td>';
			echo '<td>'.$logData['date_redeemed'].'</td>';
			echo '<td>'.$logData['ip_address'].'</td>';
			echo '<td>'.$logData['user_identifier'].'</td>';
		echo '</tr>';
	}
	echo '</tbody>';
echo '</table>';