<?php
/**
 * Referral System
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w641ba01901cb0925ec50f543e59acbd
 */

function saveChanges() {
    global $_POST;
	
    $xmlPath = __PATH_REFERRALSYSTEM_ROOT__.'config.xml';
    $xml = simplexml_load_file($xmlPath);
	
	if(!Validator::UnsignedNumber($_POST['setting_1'])) throw new Exception('Submitted setting is not valid (required_level)');
	$xml->required_level = $_POST['setting_1'];
	
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Submitted setting is not valid (required_master_level)');
	$xml->required_master_level = $_POST['setting_2'];
	
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Submitted setting is not valid (required_resets)');
	$xml->required_resets = $_POST['setting_3'];
	
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Submitted setting is not valid (time_limit_days)');
	$xml->time_limit_days = $_POST['setting_4'];
	
	if(!Validator::UnsignedNumber($_POST['setting_5'])) throw new Exception('Submitted setting is not valid (reward_credit_config)');
	$xml->reward_credit_config = $_POST['setting_5'];
	
	if(!Validator::UnsignedNumber($_POST['setting_6'])) throw new Exception('Submitted setting is not valid (reward_credit_amount)');
	$xml->reward_credit_amount = $_POST['setting_6'];
	
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
		$ReferralSystem = new \Plugin\ReferralSystem\ReferralSystem();
		$ReferralSystem->checkPluginUsercpLinks();
		message('success', 'UserCP Links Successfully Added!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// load configs
$pluginConfig = simplexml_load_file(__PATH_REFERRALSYSTEM_ROOT__.'config.xml');
if(!$pluginConfig) throw new Exception('Error loading config file.');

// credit system
$creditSystem = new CreditSystem();

?>
<h2>Referral System Settings</h2>
<form action="" method="post">

	<table class="table table-striped table-bordered table-hover module_config_tables">
        <tr>
            <th>Required Level<br/><span>Minimum level the referred player needs to reach for the referral to receive the reward. Set to 0 to disable.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_1" value="<?php echo $pluginConfig->required_level; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Required Master Level<br/><span>Minimum master level the referred player needs to reach for the referral to receive the reward. Set to 0 to disable.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo $pluginConfig->required_master_level; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Required Resets<br/><span>Minimum resets the referred player needs to reach for the referral to receive the reward. Set to 0 to disable.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_3" value="<?php echo $pluginConfig->required_resets; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Time Limit (days)<br/><span>Amount of days the referred player has to reach the requirements. Set to 0 to disable.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_4" value="<?php echo $pluginConfig->time_limit_days; ?>"/>
            </td>
        </tr>
		
        <tr>
            <th>Credits Configuration<br/><span>Select which type of credits to reward the referral.</span></th>
            <td>
				<?php echo $creditSystem->buildSelectInput('setting_5', $pluginConfig->reward_credit_config, 'form-control'); ?>
            </td>
        </tr>
        <tr>
            <th>Credits Amount<br/><span>Set the amount of credits to reward the referral.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_6" value="<?php echo $pluginConfig->reward_credit_amount; ?>"/>
            </td>
        </tr>
		
		<tr>
            <td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
        </tr>
    </table>
</form>

<h2>UserCP Links</h2>
<p>Click the button below to automatically add the plugin's links to the user control panel menu.</p>
<a href="<?php echo admincp_base('referralsystem&page=settings&checkusercplinks=1'); ?>" class="btn btn-primary">Add UserCP Links</a>