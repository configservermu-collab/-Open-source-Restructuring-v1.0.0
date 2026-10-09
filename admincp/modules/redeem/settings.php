<?php
/**
 * Redeem Code
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

function saveChanges() {
    global $_POST;
    foreach($_POST as $setting) {
        if(!check_value($setting)) {
            message('error', 'Missing data (complete all fields).');
            return;
        }
    }
    $xmlPath = __PATH_REDEEMCODE_ROOT__.'config.xml';
    $xml = simplexml_load_file($xmlPath);
	
	if(!Validator::UnsignedNumber($_POST['setting_1'])) throw new Exception('Submitted setting is not valid (check_ip)');
	if(!in_array($_POST['setting_1'], array(1, 0))) throw new Exception('Submitted setting is not valid (check_ip)');
	$xml->check_ip = $_POST['setting_1'];
	
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Submitted setting is not valid (required_level)');
	$xml->required_level = $_POST['setting_2'];
	
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Submitted setting is not valid (required_resets)');
	$xml->required_resets = $_POST['setting_3'];
	
    $save = @$xml->asXML($xmlPath);
	if(!$save) throw new Exception('There has been an error while saving changes.');
}

if(check_value($_POST['submit_changes'])) {
	try {
		
		saveChanges();
		message('success', 'Settings successfully saved.');
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

if(check_value($_GET['checkusercplinks'])) {
	try {
		$RedeemCode = new \Plugin\RedeemCode\RedeemCode();
		$RedeemCode->checkPluginUsercpLinks();
		message('success', 'UserCP Links Successfully Added!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// load configs
$pluginConfig = simplexml_load_file(__PATH_REDEEMCODE_ROOT__.'config.xml');
if(!$pluginConfig) throw new Exception('Error loading config file.');
?>
<h2>Redeem Code Settings</h2>
<form action="" method="post">

	<table class="table table-striped table-bordered table-hover module_config_tables">
        <tr>
            <th>Check IP Address<br/><span>If enabled, the player's ip address will be used to check if the same redeem code has already been redeemed using the ip address.</span></th>
            <td>
				<?php enabledisableCheckboxes('setting_1', $pluginConfig->check_ip, 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>Level Requirement<br/><span>Minimum character level required to be able to redeem a code (any character). Set to 0 to disable.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo $pluginConfig->required_level; ?>"/>
            </td>
        </tr>
		<tr>
            <th>Resets Requirement<br/><span>Minimum amount of resets required to be able to redeem a code (any character). Set to 0 to disable.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_3" value="<?php echo $pluginConfig->required_resets; ?>"/>
            </td>
        </tr>
		<tr>
            <td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
        </tr>
    </table>
</form>

<hr>

<h2>UserCP Links</h2>
<p>Click the button below to automatically add the plugin's links to the user control panel menu.</p>
<a href="<?php echo admincp_base('redeem&page=settings&checkusercplinks=1'); ?>" class="btn btn-primary">Add UserCP Links</a>