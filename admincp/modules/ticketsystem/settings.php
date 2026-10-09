<?php
/**
 * Ticket Support
 * https://webenginecms.org/
 * 
 * @version 1.5.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w3c8c718b75a0f1fa1a557f7f9d70877
 */

echo '<h2>Ticket System: Settings</h2>';

function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_CONFIGS__.'ticketsystem.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->subject_min_limit = $_POST['setting_2'];
	$xml->message_min_limit = $_POST['setting_3'];
	$xml->message_max_limit = $_POST['setting_4'];
	$xml->staff_nick = $_POST['setting_5'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

if(check_value($_POST['submit_changes'])) {
	saveChanges();
}

if(check_value($_GET['checkusercplinks'])) {
	try {
		$ticketSystem = new ticketSystem();
		$ticketSystem->checkPluginUsercpLinks();
		message('success', 'UserCP Links Successfully Added!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

gconfig('ticketsystem',false);
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the ticket system modules.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',$gconfig['active'],'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Subject Minimum Length<br/><span>Minimum amount of characters the ticket subject can contain.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_2" value="<?=$gconfig['subject_min_limit']?>"/>
			</td>
		</tr>
		<tr>
			<th>Message Minimum Length<br/><span>Minimum amount of characters the ticket message can contain.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_3" value="<?=$gconfig['message_min_limit']?>"/>
			</td>
		</tr>
		<tr>
			<th>Message Maximum Length<br/><span>Maximum amount of characters the ticket message can contain.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_4" value="<?=$gconfig['message_max_limit']?>"/>
			</td>
		</tr>
		<tr>
			<th>Staff Nickname<br/><span>Staff nickname for the ticket replies.</span></th>
			<td>
				<input class="input-mini" type="text" name="setting_5" value="<?=$gconfig['staff_nick']?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>

<h2>UserCP Links</h2>
<p>Click the button below to automatically add the plugin's links to the user control panel menu.</p>
<a href="<?php echo admincp_base('ticketsystem&page=settings&checkusercplinks=1'); ?>" class="btn btn-primary">Add UserCP Links</a>