<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h1 class="page-header">Email Settings</h1>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_CONFIGS__.'email.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->send_from = $_POST['setting_2'];
	$xml->send_name = $_POST['setting_3'];
	$xml->smtp_active = $_POST['setting_4'];
	$xml->smtp_host = $_POST['setting_5'];
	$xml->smtp_port = $_POST['setting_6'];
	$xml->smtp_user = $_POST['setting_7'];
	$xml->smtp_pass = $_POST['setting_8'];
	
	$save = $xml->asXML($xmlPath);
	if($save) {
		message('success','Settings successfully saved.');
	} else {
		message('error','There has been an error while saving changes.');
	}
}

if(isset($_POST['submit_changes'])) {
	saveChanges();
}

// Load SMTP Configs
$emailConfigs = gconfig('email',true);
if(!is_array($emailConfigs)) $emailConfigs = array();
$emailActive = isset($emailConfigs['active']) ? $emailConfigs['active'] : 0;
$emailSendFrom = isset($emailConfigs['send_from']) ? $emailConfigs['send_from'] : '';
$emailSendName = isset($emailConfigs['send_name']) ? $emailConfigs['send_name'] : '';
$emailSmtpActive = isset($emailConfigs['smtp_active']) ? $emailConfigs['smtp_active'] : 0;
$emailSmtpHost = isset($emailConfigs['smtp_host']) ? $emailConfigs['smtp_host'] : '';
$emailSmtpPort = isset($emailConfigs['smtp_port']) ? $emailConfigs['smtp_port'] : '';
$emailSmtpUser = isset($emailConfigs['smtp_user']) ? $emailConfigs['smtp_user'] : '';
$emailSmtpPass = isset($emailConfigs['smtp_pass']) ? $emailConfigs['smtp_pass'] : '';
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Email System<br/><span>Enable/disable the email system.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_1',$emailActive,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Send Email From<br/><span></span></th>
			<td>
				<input type="text" name="setting_2" value="<?php echo htmlspecialchars($emailSendFrom,ENT_QUOTES,'UTF-8'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>Send Email From Name<br/><span></span></th>
			<td>
				<input type="text" name="setting_3" value="<?php echo htmlspecialchars($emailSendName,ENT_QUOTES,'UTF-8'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP Status<br/><span>Enable/disable the SMTP system.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_4',$emailSmtpActive,'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>SMTP Host<br/><span></span></th>
			<td>
				<input type="text" name="setting_5" value="<?php echo htmlspecialchars($emailSmtpHost,ENT_QUOTES,'UTF-8'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP Port<br/><span></span></th>
			<td>
				<input type="text" class="input-mini" name="setting_6" value="<?php echo htmlspecialchars($emailSmtpPort,ENT_QUOTES,'UTF-8'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP User<br/><span></span></th>
			<td>
				<input type="text" name="setting_7" value="<?php echo htmlspecialchars($emailSmtpUser,ENT_QUOTES,'UTF-8'); ?>"/>
			</td>
		</tr>
		<tr>
			<th>SMTP Password<br/><span></span></th>
			<td>
				<input type="text" name="setting_8" value="<?php echo htmlspecialchars($emailSmtpPass,ENT_QUOTES,'UTF-8'); ?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>