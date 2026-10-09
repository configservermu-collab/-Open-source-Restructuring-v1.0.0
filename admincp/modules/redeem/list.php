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

echo '<h2>Redeemable Codes List</h2>';

if(check_value($_GET['disable'])) {
	try {
		
		$RedeemCodeDisable = new \Plugin\RedeemCode\RedeemCode();
		$RedeemCodeDisable->disableCode($_GET['disable']);
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

$RedeemCode = new \Plugin\RedeemCode\RedeemCode();
$codesList = $RedeemCode->getRedeemCodesList();
$creditSystem = new CreditSystem();

$creditConfigs = $creditSystem->showConfigs();
$creditConfigList[0] = 'None';
if(is_array($creditConfigs)) {
	foreach($creditConfigs as $configData) {
		$creditConfigList[$configData['config_id']] = $configData['config_title'];
	}
}

echo '<table class="table table-hover table-striped">';
	echo '<thead>';
		echo '<tr>';
			echo '<th>Code</th>';
			echo '<th>Type</th>';
			echo '<th>Limit</th>';
			echo '<th>User</th>';
			echo '<th>Credit Config</th>';
			echo '<th>Reward</th>';
			echo '<th>Status</th>';
			echo '<th>Actions</th>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';		
	foreach($codesList as $codeData) {
		
		$status = $codeData['status'] == 1 ? '<span class="label label-danger">expired</span>' : '<span class="label label-success">active</span>';
		echo '<tr>';
			echo '<td>'.$codeData['redeem_code'].'</td>';
			echo '<td>'.$codeData['redeem_type'].'</td>';
			echo '<td>'.$codeData['redeem_limit'].'</td>';
			echo '<td>'.$codeData['redeem_user'].'</td>';
			echo '<td>'.$creditConfigList[$codeData['redeem_credit_config_id']].'</td>';
			echo '<td>'.number_format($codeData['redeem_credit_amount']).'</td>';
			echo '<td>'.$status.'</td>';
			echo '<td>';
				if($codeData['status'] != 1) {
					echo '<a href="'.admincp_base('redeem&page=list&disable='.$codeData['id']).'" class="btn btn-danger btn-xs">disable</a>&nbsp;';
				}
				
				echo '<a href="'.admincp_base('redeem&page=logs&id='.$codeData['id']).'" class="btn btn-default btn-xs">logs</a>';
			echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
echo '</table>';