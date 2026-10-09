<?php
/**
 * Rename Character
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
	
    $xmlPath = __PATH_RENAMECHARACTER_ROOT__.'config.xml';
    $xml = simplexml_load_file($xmlPath);
	
	if(!is_writable($xmlPath)) throw new Exception('The configuration file is not writable.');
	
	
	if(!Validator::UnsignedNumber($_POST['setting_1'])) throw new Exception('Submitted setting is not valid (rename_cost)');
	$xml->rename_cost = $_POST['setting_1'];
	
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Submitted setting is not valid (config_id)');
	$xml->config_id = $_POST['setting_2'];
	
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Submitted setting is not valid (min_len)');
	$xml->min_len = $_POST['setting_3'];
	
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Submitted setting is not valid (max_len)');
	$xml->max_len = $_POST['setting_4'];
	
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
		$RenameCharacter = new \Plugin\RenameCharacter\RenameCharacter();
		$RenameCharacter->checkPluginUsercpLinks();
		message('success', 'UserCP Links Successfully Added!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// load configs
$pluginConfig = simplexml_load_file(__PATH_RENAMECHARACTER_ROOT__.'config.xml');
if(!$pluginConfig) throw new Exception('Error loading config file.');

// credit system
$creditSystem = new CreditSystem();
?>
<h2>Rename Character Settings</h2>
<form action="" method="post">

	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Credit Configuration<br/><span>Type of credits used to pay for renaming a character.</span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_2", $pluginConfig->config_id, "form-control"); ?>
			</td>
		</tr>
        <tr>
            <th>Rename Cost<br/><span>Amount of credits required to rename a character's name.</span></th>
            <td>
				<input class="form-control" type="text" name="setting_1" value="<?php echo $pluginConfig->rename_cost; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Minimum Character Name Length<br/><span>Recommended value: 3</span></th>
            <td>
				<input class="form-control" type="text" name="setting_3" value="<?php echo $pluginConfig->min_len; ?>"/>
            </td>
        </tr>
        <tr>
            <th>Maximum Character Name Length<br/><span>Recommended value: 10</span></th>
            <td>
				<input class="form-control" type="text" name="setting_4" value="<?php echo $pluginConfig->max_len; ?>"/>
            </td>
        </tr>
		<tr>
            <td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
        </tr>
    </table>
</form>

<h2>UserCP Links</h2>
<p>Click the button below to automatically add the plugin's links to the user control panel menu.</p>
<a href="<?php echo admincp_base('renamecharacter&page=settings&checkusercplinks=1'); ?>" class="btn btn-primary">Add UserCP Links</a>