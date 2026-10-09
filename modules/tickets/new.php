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

echo '<div class="page-title"><span>'.lang('plugin_ticketsupport_1', true).'</span></div>';
		
if(check_value($_POST['submit'])) {
	try {
		$ticketSystem = new ticketSystem();
		$ticketSystem->setUsername($_SESSION['username']);
		$ticketSystem->setSubject($_POST['ticket_subject']);
		$ticketSystem->setMessage($_POST['ticket_message']);
		$ticketSystem->create();
		
		redirect(1, 'tickets/list');
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="panel panel-general">';
	echo '<div class="panel-body">';
		echo '<form action="" method="post">';
			echo '<div class="form-group">';
				echo '<label for="contactInput1">'.lang('plugin_ticketsupport_4', true).'</label>';
				echo '<input type="text" class="form-control" id="contactInput1" name="ticket_subject" maxlength="50" required autofocus>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="contactInput2">'.lang('plugin_ticketsupport_5', true).'</label>';
				echo '<textarea class="form-control" id="contactInput2" style="height:250px;" name="ticket_message"></textarea>';
			echo '</div>';
			echo '<button type="submit" name="submit" value="submit" class="btn btn-primary">'.lang('plugin_ticketsupport_6', true).'</button>';
		echo '</form>';
	echo '</div>';
echo '</div>';