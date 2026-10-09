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

echo '<h2>Referrals</h2>';

$ReferralSystem = new \Plugin\ReferralSystem\ReferralSystem();

message('info', 'If an account is not verified within 12 hours of the initial registration, the referral is automatically removed.');

echo '<h3>Unverified Accounts:</h3>';
$unverifiedAccounts = $ReferralSystem->getReferralListByStatus(2);
if(is_array($unverifiedAccounts)) {
	echo '<table class="table table-bordered table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>Referral Username</th>';
				echo '<th>Referral Character</th>';
				echo '<th>Referred Username</th>';
				echo '<th>Registration Date</th>';
				echo '<th>Last Check</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($unverifiedAccounts as $row) {
			echo '<tr>';
				echo '<td>'.$row['referral_username'].'</td>';
				echo '<td>'.$row['referral_character'].'</td>';
				echo '<td>'.$row['referred_username'].'</td>';
				echo '<td>'.$row['referred_registration_date'].'</td>';
				echo '<td>'.$row['referred_last_check'].'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
} else {
	message('warning', 'There are no results to display.');
}

echo '<h3>In Progress:</h3>';
$inProgress = $ReferralSystem->getReferralListByStatus(0);
if(is_array($inProgress)) {
	echo '<table class="table table-bordered table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>Referral Username</th>';
				echo '<th>Referral Character</th>';
				echo '<th>Referred Username</th>';
				echo '<th>Registration Date</th>';
				echo '<th>Last Check</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($inProgress as $row) {
			echo '<tr>';
				echo '<td>'.$row['referral_username'].'</td>';
				echo '<td>'.$row['referral_character'].'</td>';
				echo '<td>'.$row['referred_username'].'</td>';
				echo '<td>'.$row['referred_registration_date'].'</td>';
				echo '<td>'.$row['referred_last_check'].'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
} else {
	message('warning', 'There are no results to display.');
}

echo '<h3>Completed:</h3>';
$completedReferrals = $ReferralSystem->getReferralListByStatus(1);
if(is_array($completedReferrals)) {
	echo '<table class="table table-bordered table-hover">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>Referral Username</th>';
				echo '<th>Referral Character</th>';
				echo '<th>Referred Username</th>';
				echo '<th>Referred Character</th>';
				echo '<th>Registration Date</th>';
				echo '<th>Last Check</th>';
				echo '<th>Completed Date</th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($completedReferrals as $row) {
			echo '<tr>';
				echo '<td>'.$row['referral_username'].'</td>';
				echo '<td>'.$row['referral_character'].'</td>';
				echo '<td>'.$row['referred_username'].'</td>';
				echo '<td>'.$row['referred_character'].'</td>';
				echo '<td>'.$row['referred_registration_date'].'</td>';
				echo '<td>'.$row['referred_last_check'].'</td>';
				echo '<td>'.$row['referred_complete_date'].'</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
} else {
	message('warning', 'There are no results to display.');
}