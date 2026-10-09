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
if(!check_value($_GET['id'])) redirect(1, 'tickets/list');

echo '<div class="page-title"><span>'.lang('plugin_ticketsupport_3', true).'</span></div>';

if(check_value($_POST['deleteticket'])) {
	try {
		$ticketSystem = new ticketSystem();
		$ticketSystem->setId($_GET['id']);
		$isStaff = $ticketSystem->isStaffUserId($_SESSION['userid']) || $ticketSystem->isStaffUsername($_SESSION['username']);
		if(!$isStaff) throw new Exception(lang('plugin_ticketsupport_35', true));
		$ticketSystem->deleteTicket($_GET['id']);
		message('success', lang('plugin_ticketsupport_37', true));
		redirect(1, 'tickets/staff');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

if(check_value($_POST['submit'])) {
	try {
		
		$ticketSystem = new ticketSystem();
		$ticketSystem->setId($_GET['id']);
		$isStaff = $ticketSystem->isStaffUserId($_SESSION['userid']) || $ticketSystem->isStaffUsername($_SESSION['username']);
		if($isStaff) {
			$ticketSystem->setUsername($ticketSystem->getStaffNickname());
			$ticketSystem->isAdmin();
		} else {
			$ticketSystem->setUsername($_SESSION['username']);
			if(!$ticketSystem->canUserReplyByCharacterId($_SESSION['username'])) throw new Exception(lang('plugin_ticketsupport_34', true));
		}
		$ticketSystem->setMessage($_POST['reply_message']);
		$ticketSystem->reply();
		
		message('success', lang('plugin_ticketsupport_16', true));
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

try {
	
	$ticketSystem = new ticketSystem();
	$ticketSystem->setId($_GET['id']);
	$isStaff = $ticketSystem->isStaffUserId($_SESSION['userid']) || $ticketSystem->isStaffUsername($_SESSION['username']);
	
	$ticketData = $ticketSystem->getTicketData();
	if(!is_array($ticketData)) throw new Exception(lang('plugin_ticketsupport_17', true));
	if(!$isStaff && $ticketData['ticket_author'] != $_SESSION['username']) throw new Exception(lang('plugin_ticketsupport_18', true));
	
	$ticketReplies = $ticketSystem->getTicketReplies();
	if(!is_array($ticketReplies)) throw new Exception(lang('plugin_ticketsupport_19', true));
	
	$canReply = $isStaff ? true : $ticketSystem->canUserReplyByCharacterId($_SESSION['username']);
	
	echo '<h2>'.$ticketData['ticket_subject'].'</h2>';
	if($isStaff) {
		$deleteConfirm = addslashes(lang('plugin_ticketsupport_39', true));
		echo '<form action="" method="post" style="margin-bottom: 10px;" onsubmit="return confirm(\''.$deleteConfirm.'\');">';
			echo '<input type="hidden" name="deleteticket" value="'.$ticketData['id'].'"/>';
			echo '<button type="submit" class="btn btn-xs btn-danger">'.lang('plugin_ticketsupport_38', true).'</button>';
		echo '</form>';
	}
	foreach($ticketReplies as $reply) {
		echo '<div class="panel panel-general">';
			echo '<div class="panel-body">';
				echo '<div class="row" style="border-bottom: 1px solid #1f1f1f;padding: 0px 15px;">';
					echo '<div class="col-xs-6" style="padding: 10px 0px;font-weight: bold;">';
						echo $reply['reply_author'];
					echo '</div>';
					echo '<div class="col-xs-6 text-right" style="padding: 10px 0px;font-style: italic;color: #777;font-size:11px;">';
						echo date("Y-m-d H:i",$reply['reply_date']);
					echo '</div>';
				echo '</div>';
				echo '<div class="row">';
					echo '<div class="col-xs-12" style="padding: 15px;">';
						echo nl2br(htmlspecialchars($reply['reply_content']));
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	
	if($ticketData['ticket_status'] == 0) {
		if(!$canReply) {
			message('error', lang('plugin_ticketsupport_34', true));
		} else {
		echo '<div class="panel panel-general">';
			echo '<div class="panel-body">';
				echo '<form action="" method="post">';
					echo '<div class="form-group">';
						echo '<label for="contactInput2">'.lang('plugin_ticketsupport_5', true).'</label>';
						echo '<textarea class="form-control" id="contactInput2" style="height:150px;" name="reply_message"></textarea>';
					echo '</div>';
					echo '<button type="submit" name="submit" value="submit" class="btn btn-primary">'.lang('plugin_ticketsupport_14', true).'</button>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
		}
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
