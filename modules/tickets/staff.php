<?php
/**
 * Ticket Support (Staff)
 * https://webenginecms.org/
 * 
 * @version 1.5.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w3c8c718b75a0f1fa1a557f7f9d70877
 */

if(!isLoggedIn()) redirect(1,'login');

try {
	$tSys = new ticketSystem();
	if(!$tSys->isStaffUserId($_SESSION['userid']) && !$tSys->isStaffUsername($_SESSION['username'])) throw new Exception(lang('plugin_ticketsupport_35', true));
} catch(Exception $ex) {
	message('error', $ex->getMessage());
	return;
}

if(check_value($_POST['deleteticket'])) {
	try {
		$ticketSystem = new ticketSystem();
		$ticketSystem->deleteTicket($_POST['deleteticket']);
		message('success', lang('plugin_ticketsupport_37', true));
		redirect(1, 'tickets/staff');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="page-title"><span>'.lang('plugin_ticketsupport_staff', true).'</span></div>';

echo '<h3>Open Tickets</h3>';
$oTickets = $tSys->acp_getOpenTickets();

if(is_array($oTickets)) {
	$deleteConfirm = addslashes(lang('plugin_ticketsupport_39', true));
	echo '<table class="general-table-ui" cellspacing="0">';
	echo '<tr>';
		echo '<td style="color:#000 !important;">Subject</td>';
		echo '<td style="color:#000 !important;">Author</td>';
		echo '<td style="color:#000 !important;">Create Date</td>';
		echo '<td style="color:#000 !important;">Last Reply</td>';
		echo '<td style="color:#000 !important;">Last Reply By</td>';
		echo '<td style="color:#000 !important;">Status</td>';
		echo '<td style="color:#000 !important;">Actions</td>';
	echo '</tr>';
	foreach($oTickets as $thisTicket) {
		$viewTicketLINK = __BASE_URL__.'tickets/view/id/'.$thisTicket['id'];
		$lastReplyDate = !is_null($thisTicket['ticket_last_reply_date']) ? date("Y-m-d H:i", $thisTicket['ticket_last_reply_date']) : '<i>never</i>';
		$status = $thisTicket['ticket_status'] == 0 ? '<span class="label label-success">open</span>' : '<span class="label label-danger">closed</span>';
		$deleteForm = '<form action="" method="post" style="display:inline;" onsubmit="return confirm(\''.$deleteConfirm.'\');">'
			.'<input type="hidden" name="deleteticket" value="'.$thisTicket['id'].'"/>'
			.'<button type="submit" class="btn btn-xs btn-danger">'.lang('plugin_ticketsupport_38', true).'</button>'
			.'</form>';
		echo '<tr>';
			echo '<td style="color:#000 !important;">'.$thisTicket['ticket_subject'].'</td>';
			echo '<td style="color:#000 !important;">'.$thisTicket['ticket_author'].'</td>';
			echo '<td style="color:#000 !important;">'.date("Y-m-d H:i",$thisTicket['ticket_create_date']).'</td>';
			echo '<td style="color:#000 !important;">'.$lastReplyDate.'</td>';
			echo '<td style="color:#000 !important;">'.$thisTicket['ticket_last_reply_user'].'</td>';
			echo '<td style="color:#000 !important;">'.$status.'</td>';
			echo '<td style="color:#000 !important;"><a class="btn btn-xs btn-primary" href="'.$viewTicketLINK.'">'.lang('plugin_ticketsupport_13', true).'</a> '.$deleteForm.'</td>';
		echo '</tr>';
	}
	echo '</table>';
} else {
	message('warning','There are no open tickets.');
}

echo '<h3>Closed Tickets</h3>';
$cTickets = $tSys->acp_getClosedTickets();

if(is_array($cTickets)) {
	$deleteConfirm = addslashes(lang('plugin_ticketsupport_39', true));
	echo '<table class="general-table-ui" cellspacing="0">';
	echo '<tr>';
		echo '<td style="color:#000 !important;">Subject</td>';
		echo '<td style="color:#000 !important;">Author</td>';
		echo '<td style="color:#000 !important;">Create Date</td>';
		echo '<td style="color:#000 !important;">Last Reply</td>';
		echo '<td style="color:#000 !important;">Last Reply By</td>';
		echo '<td style="color:#000 !important;">Status</td>';
		echo '<td style="color:#000 !important;">Actions</td>';
	echo '</tr>';
	foreach($cTickets as $thisTicket) {
		$viewTicketLINK = __BASE_URL__.'tickets/view/id/'.$thisTicket['id'];
		$lastReplyDate = !is_null($thisTicket['ticket_last_reply_date']) ? date("Y-m-d H:i", $thisTicket['ticket_last_reply_date']) : '<i>never</i>';
		$status = $thisTicket['ticket_status'] == 0 ? '<span class="label label-success">open</span>' : '<span class="label label-danger">closed</span>';
		$deleteForm = '<form action="" method="post" style="display:inline;" onsubmit="return confirm(\''.$deleteConfirm.'\');">'
			.'<input type="hidden" name="deleteticket" value="'.$thisTicket['id'].'"/>'
			.'<button type="submit" class="btn btn-xs btn-danger">'.lang('plugin_ticketsupport_38', true).'</button>'
			.'</form>';
		echo '<tr>';
			echo '<td style="color:#000 !important;">'.$thisTicket['ticket_subject'].'</td>';
			echo '<td style="color:#000 !important;">'.$thisTicket['ticket_author'].'</td>';
			echo '<td style="color:#000 !important;">'.date("Y-m-d H:i",$thisTicket['ticket_create_date']).'</td>';
			echo '<td style="color:#000 !important;">'.$lastReplyDate.'</td>';
			echo '<td style="color:#000 !important;">'.$thisTicket['ticket_last_reply_user'].'</td>';
			echo '<td style="color:#000 !important;">'.$status.'</td>';
			echo '<td style="color:#000 !important;"><a class="btn btn-xs btn-primary" href="'.$viewTicketLINK.'">'.lang('plugin_ticketsupport_13', true).'</a> '.$deleteForm.'</td>';
		echo '</tr>';
	}
	echo '</table>';
} else {
	message('warning','There are no closed tickets.');
}
