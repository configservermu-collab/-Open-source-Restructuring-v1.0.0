<?php
/**
 * VIP
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w3c8c718b75a0f1fa1a557f7f9d70877
 */

echo '<h1 class="page-header">VIP Purchase Logs</h1>';

echo '<div class="row">';

	echo '<div class="col-md-12">';
		
		echo '<div class="panel panel-default">';
		echo '<div class="panel-heading">';
			echo '<i class="fa fa-list"></i> VIP Purchase History';
		echo '</div>';
		echo '<div class="panel-body">';
		
			// Read JSON logs
			$logFile = '../includes/logs/vip_purchases.json';
			$vipLogs = array();
			
			if(file_exists($logFile)) {
				$jsonContent = file_get_contents($logFile);
				$vipLogs = json_decode($jsonContent, true);
				if(!is_array($vipLogs)) $vipLogs = array();
			}
			
			if(!empty($vipLogs)) {
				// Sort logs by timestamp (newest first)
				usort($vipLogs, function($a, $b) {
					return $b['timestamp'] - $a['timestamp'];
				});
				
				echo '<table id="vip_logs" class="table table-condensed table-hover table-striped">';
				echo '<thead>';
					echo '<tr>';
						echo '<th>ID</th>';
						echo '<th>Username</th>';
						echo '<th>VIP Type</th>';
						echo '<th>Days</th>';
						echo '<th>Credits</th>';
						echo '<th>Email</th>';
						echo '<th>IP Address</th>';
						echo '<th>Date</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
				
				foreach($vipLogs as $log) {
					echo '<tr>';
						echo '<td>#'.$log['id'].'</td>';
						echo '<td><strong>'.$log['username'].'</strong></td>';
						echo '<td>';
							echo '<span class="label" style="background-color: ';
							switch($log['vip_type']) {
								case 1: echo '#cd7f32'; break; // Bronze
								case 2: echo '#c0c0c0'; break; // Silver
								case 3: echo '#ffd700'; break; // Gold
								case 4: echo '#63ffbb'; break; // Platinum
								default: echo '#333';
							}
							echo ';">';
							echo htmlspecialchars($log['vip_name']);
							echo '</span>';
						echo '</td>';
						echo '<td>'.$log['days'].' days</td>';
						echo '<td><span class="label label-danger">-'.$log['credits'].'</span></td>';
						echo '<td>'.$log['email'].'</td>';
						echo '<td>'.$log['ip'].'</td>';
						echo '<td>'.date("Y-m-d H:i:s", $log['timestamp']).'</td>';
					echo '</tr>';
				}
				
				echo '</tbody>';
				echo '</table>';
				
				echo '<script>
				$(document).ready(function() {
					$("#vip_logs").DataTable({
						"order": [[ 0, "desc" ]],
						"pageLength": 25,
						"language": {
							"search": "Search:",
							"lengthMenu": "Show _MENU_ entries",
							"info": "Showing _START_ to _END_ of _TOTAL_ entries",
							"paginate": {
								"first": "First",
								"last": "Last",
								"next": "Next",
								"previous": "Previous"
							}
						}
					});
				});
				</script>';
				
			} else {
				echo '<div class="alert alert-info">';
					echo '<i class="fa fa-info-circle"></i> No VIP purchases have been made yet.';
				echo '</div>';
			}
			
		echo '</div>';
		echo '</div>';
		
	echo '</div>';
	
echo '</div>';

// Statistics Panel
if(!empty($vipLogs)) {
	echo '<div class="row" style="margin-top: 20px;">';
		echo '<div class="col-md-3">';
			echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">';
					echo '<i class="fa fa-shopping-cart"></i> Total Purchases';
				echo '</div>';
				echo '<div class="panel-body text-center">';
					echo '<h2>'.count($vipLogs).'</h2>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="col-md-3">';
			echo '<div class="panel panel-success">';
				echo '<div class="panel-heading">';
					echo '<i class="fa fa-money"></i> Total Credits';
				echo '</div>';
				echo '<div class="panel-body text-center">';
					$totalCredits = 0;
					foreach($vipLogs as $log) {
						$totalCredits += $log['credits'];
					}
					echo '<h2>'.number_format($totalCredits).'</h2>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="col-md-3">';
			echo '<div class="panel panel-warning">';
				echo '<div class="panel-heading">';
					echo '<i class="fa fa-users"></i> Unique Users';
				echo '</div>';
				echo '<div class="panel-body text-center">';
					$uniqueUsers = array();
					foreach($vipLogs as $log) {
						$uniqueUsers[$log['username']] = true;
					}
					echo '<h2>'.count($uniqueUsers).'</h2>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		
		echo '<div class="col-md-3">';
			echo '<div class="panel panel-info">';
				echo '<div class="panel-heading">';
					echo '<i class="fa fa-calendar"></i> Today\'s Sales';
				echo '</div>';
				echo '<div class="panel-body text-center">';
					$todaySales = 0;
					$todayStart = strtotime('today');
					foreach($vipLogs as $log) {
						if($log['timestamp'] >= $todayStart) {
							$todaySales++;
						}
					}
					echo '<h2>'.$todaySales.'</h2>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}