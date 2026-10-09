<?php
/**
 * WebEngine
 * http://muengine.net/
 * 
 * @version 1.0.9.7
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 * @build w3c8c718b75a0f1fa1a557f7f9d70877
 */

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('plugin_ticketsupport_2', true).'</span></div>';

try {
	
	$ticketSystem = new ticketSystem();
	$ticketSystem->setUsername($_SESSION['username']);
	
	$myTickets = $ticketSystem->getUserTickets();
	if(!is_array($myTickets)) throw new Exception(lang('plugin_ticketsupport_15', true));
	
	echo '<table class="general-table-ui" cellspacing="0" style="color:#000;">';
	echo '<tr>';
		echo '<td style="color:#000 !important;">'.lang('plugin_ticketsupport_7', true).'</td>';
		echo '<td style="color:#000 !important;">'.lang('plugin_ticketsupport_4', true).'</td>';
		echo '<td style="color:#000 !important;">'.lang('plugin_ticketsupport_8', true).'</td>';
		echo '<td style="color:#000 !important;">'.lang('plugin_ticketsupport_9', true).'</td>';
		echo '<td style="color:#000 !important;"></td>';
	echo '</tr>';
	foreach($myTickets as $ticket) {
		$status = $ticket['ticket_status'] == 1 ? '<span class="label label-danger">'.lang('plugin_ticketsupport_10', true).'</span>' : '<span class="label label-success">'.lang('plugin_ticketsupport_11', true).'</span>';
		$lastReply = check_value($ticket['ticket_last_reply_date']) ? date("Y-m-d H:i", $ticket['ticket_last_reply_date']).' by '.$ticket['ticket_last_reply_user'] : '<i>'.lang('plugin_ticketsupport_12', true).'</i>';
		
		echo '<tr>';
			echo '<td style="color:#000 !important;">'.date("Y-m-d", $ticket['ticket_create_date']).'</td>';
			echo '<td style="color:#000 !important;">'.$ticket['ticket_subject'].'</td>';
			echo '<td style="color:#000 !important;">'.$status.'</td>';
			echo '<td style="color:#000 !important;">'.$lastReply.'</td>';
			echo '<td style="color:#000 !important;"><a href="'.__BASE_URL__.'tickets/view/id/'.$ticket['id'].'" class="btn btn-xs btn-primary">'.lang('plugin_ticketsupport_13', true).'</a></td>';
		echo '</tr>';
	}
	echo '</table>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
