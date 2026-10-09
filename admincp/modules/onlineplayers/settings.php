<?php
/**
 * Online Players
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
    $xmlPath = __PATH_ONLINEPLAYERS_ROOT__.'config.xml';
    $xml = simplexml_load_file($xmlPath);
	
	if(!Validator::UnsignedNumber($_POST['setting_1'])) throw new Exception('Submitted setting is not valid (show_class)');
	if(!in_array($_POST['setting_1'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_class)');
	$xml->show_class = $_POST['setting_1'];
	
	if(!Validator::UnsignedNumber($_POST['setting_2'])) throw new Exception('Submitted setting is not valid (show_level)');
	if(!in_array($_POST['setting_2'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_level)');
	$xml->show_level = $_POST['setting_2'];
	
	if(!Validator::UnsignedNumber($_POST['setting_3'])) throw new Exception('Submitted setting is not valid (show_location)');
	if(!in_array($_POST['setting_3'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_location)');
	$xml->show_location = $_POST['setting_3'];
	
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Submitted setting is not valid (show_master)');
	if(!in_array($_POST['setting_4'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_master)');
	$xml->show_master = $_POST['setting_4'];
	
	if(!Validator::UnsignedNumber($_POST['setting_5'])) throw new Exception('Submitted setting is not valid (combine_level)');
	if(!in_array($_POST['setting_5'], array(1, 0))) throw new Exception('Submitted setting is not valid (combine_level)');
	$xml->combine_level = $_POST['setting_5'];
	
	if(!Validator::UnsignedNumber($_POST['setting_6'])) throw new Exception('Submitted setting is not valid (show_guild)');
	if(!in_array($_POST['setting_6'], array(1, 0))) throw new Exception('Submitted setting is not valid (show_guild)');
	$xml->show_guild = $_POST['setting_6'];
	
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
		$OnlinePlayers = new \Plugin\OnlinePlayers\OnlinePlayers();
		$OnlinePlayers->checkPluginUsercpLinks();
		message('success', 'UserCP Links Successfully Added!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// load configs
$pluginConfig = simplexml_load_file(__PATH_ONLINEPLAYERS_ROOT__.'config.xml');
if(!$pluginConfig) throw new Exception('Error loading config file.');
?>
<h2>Online Players Settings</h2>
<form action="" method="post">

	<table class="table table-striped table-bordered table-hover module_config_tables">
        <tr>
            <th>Show Class<br/><span></span></th>
            <td>
				<?php enabledisableCheckboxes('setting_1', $pluginConfig->show_class, 'Yes', 'No'); ?>
            </td>
        </tr>
        <tr>
            <th>Show Level<br/><span></span></th>
            <td>
				<?php enabledisableCheckboxes('setting_2', $pluginConfig->show_level, 'Yes', 'No'); ?>
            </td>
        </tr>
        <tr>
            <th>Show Last Location<br/><span></span></th>
            <td>
				<?php enabledisableCheckboxes('setting_3', $pluginConfig->show_location, 'Yes', 'No'); ?>
            </td>
        </tr>
        <tr>
            <th>Show Master Level<br/><span></span></th>
            <td>
				<?php enabledisableCheckboxes('setting_4', $pluginConfig->show_master, 'Yes', 'No'); ?>
            </td>
        </tr>
        <tr>
            <th>Combine Regular Level + Master Level<br/><span></span></th>
            <td>
				<?php enabledisableCheckboxes('setting_5', $pluginConfig->combine_level, 'Yes', 'No'); ?>
            </td>
        </tr>
        <tr>
            <th>Show Guild<br/><span></span></th>
            <td>
				<?php enabledisableCheckboxes('setting_6', $pluginConfig->show_guild, 'Yes', 'No'); ?>
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
<a href="<?php echo admincp_base('onlineplayers&page=settings&checkusercplinks=1'); ?>" class="btn btn-primary">Add UserCP Links</a>