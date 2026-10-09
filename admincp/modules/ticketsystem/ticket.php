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

$tSys->setId($_REQUEST['id']);
$ticketDATA = $tSys->getTicketData();

// new reply
if(check_value($_POST['ticket_submit_reply'])) {
	try {
		$ticketSystem = new ticketSystem();
		$ticketSystem->setId($_GET['id']);
		$ticketSystem->setUsername($tConf['staff_nick']);
		$ticketSystem->setMessage($_POST['ticket_reply_message']);
		$ticketSystem->isAdmin();
		$ticketSystem->reply();
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// delete reply
if(check_value($_REQUEST['deletereply'])) {
	try {
		$ticketSystem = new ticketSystem();
		$ticketSystem->deleteReply($_REQUEST['deletereply']);
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// toggle ticket status
if(check_value($_REQUEST['togglestatus'])) {
	try {
		$newStatus = $ticketDATA['ticket_status'] == 0 ? 1 : 0;
		$ticketSystem = new ticketSystem();
		$result = $ticketSystem->updateTicketStatus($_REQUEST['id'], $newStatus);
		if($result) {
			message('success','Ticket updated.');
		} else {
			message('error','There was a problem updating the ticket.');
		}
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// reload ticket data
$ticketDATA = $tSys->getTicketData();

if(is_array($ticketDATA)) {
	
	echo '<h2>Ticket: #'.$_GET['id'].'</h2>';
	echo '<div class="row">';
		echo '<div class="col-xs-12 col-md-10 col-lg-8">';
			
			// ticket info
			echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">Ticket Information</div>';
				echo '<div class="panel-body">';

					echo '<table class="table">';
						echo '<tr><th>Subject</th><td>'.$ticketDATA['ticket_subject'].'</td></tr>';
						echo '<tr><th>Author</th><td>'.$ticketDATA['ticket_author'].'</td></tr>';
						echo '<tr><th>Create Date</th><td>'.date("Y-m-d h:i A",$ticketDATA['ticket_create_date']).'</td></tr>';
						
						if($ticketDATA['ticket_status'] == 0) {
							echo '<tr><th>Status</th><td>Open <a href="'.__PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page=ticket&id='.$_REQUEST['id'].'&togglestatus=ok" class="btn btn-danger btn-xs">close</a></td></tr>';
						} else {
							echo '<tr><th>Status</th><td>Closed <a href="'.__PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page=ticket&id='.$_REQUEST['id'].'&togglestatus=ok" class="btn btn-success btn-xs">open</a></td></tr>';
						}
					echo '</table>';
					
				echo '</div>';
			echo '</div>';
			
			// ticket replies
			echo '<div class="panel panel-primary">';
				echo '<div class="panel-heading">Messages</div>';
				echo '<div class="panel-body">';

					$ticketREPLIES = $tSys->getTicketReplies();
					if(is_array($ticketREPLIES)) {
						foreach($ticketREPLIES as $thisREPLY) {
							$deleteReplyLINK = __PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page=ticket&id='.$_REQUEST['id'].'&deletereply='.$thisREPLY['reply_id'];
							echo '<table class="table table-striped table-bordered">';
							echo '<tr><th><a>'.$thisREPLY['reply_author'].'</a>: <div style="float:right;"><a href="'.$deleteReplyLINK.'" class="btn btn-danger btn-xs">delete</a></div></th></tr>';
							echo '<tr><td>'.nl2br(htmlspecialchars($thisREPLY['reply_content'])).'
							<br /><br />
							<div style="text-align:right;font-size: 11px;"><i>'.date("Y-m-d h:i A",$thisREPLY['reply_date']).'</i></div>
							</td></tr>';
							echo '</table>';
						}
					}
					
					echo '<form action="'.__PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page=ticket&id='.$_REQUEST['id'].'" method="post">';
						echo '<strong>Message:</strong><br />';
						echo '<textarea class="form-control" name="ticket_reply_message"></textarea><br />';
						echo '<input type="submit" class="btn btn-primary" name="ticket_submit_reply" value="Reply"/>';
					echo '</form>';
					
				echo '</div>';
			echo '</div>';

		echo '</div>';
	echo '</div>';
	
} else {
	message('error','Failed to load ticket data.');
}