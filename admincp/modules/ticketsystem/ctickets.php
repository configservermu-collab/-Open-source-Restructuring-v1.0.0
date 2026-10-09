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

echo '<h2>Closed Tickets</h2>';

$oTickets = $tSys->acp_getClosedTickets();

if(is_array($oTickets)) {
	
	echo '<table class="table">';
		echo '<thead>';
			echo '<tr>';
				echo '<th>Subject</th>';
				echo '<th>Author</th>';
				echo '<th>Create Date</th>';
				echo '<th>Last Reply Date</th>';
				echo '<th>Last Reply By</th>';
				echo '<th>Status</th>';
				echo '<th></th>';
			echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach($oTickets as $thisTicket) {
			
			$viewTicketLINK = __PATH_ADMINCP_HOME__.'?module='.$_REQUEST['module'].'&page=ticket&id='.$thisTicket['id'];
			
			if(!is_null($thisTicket['ticket_last_reply_date'])) {
				$thisTicket['ticket_last_reply_date'] = date("Y-m-d h:i A",$thisTicket['ticket_last_reply_date']);
			} else {
				$thisTicket['ticket_last_reply_date'] = '<i>never</i>';
			}
			
			switch($thisTicket['ticket_status']) {
				case 0:
					$status = '<span class="label label-success">open</span>';
				break;
				case 1:
					$status = '<span class="label label-danger">closed</span>';
				break;
				default:
					$status = '<span class="label">unknown</span>';
				break;
			}
			
			echo '<tr>';
			echo '<td>'.$thisTicket['ticket_subject'].'</td>';
			echo '<td>'.$thisTicket['ticket_author'].'</td>';
			echo '<td>'.date("Y-m-d h:i A",$thisTicket['ticket_create_date']).'</td>';
			echo '<td>'.$thisTicket['ticket_last_reply_date'].'</td>';
			echo '<td>'.$thisTicket['ticket_last_reply_user'].'</td>';
			echo '<td>'.$status.'</td>';
			echo '<td>
			<a class="btn btn-xs btn-primary" href="'.$viewTicketLINK.'">view</a>
			</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
	
} else {
	message('warning','There are no closed tickets.');
}