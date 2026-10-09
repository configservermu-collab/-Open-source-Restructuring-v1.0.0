<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
?>
<h2>Configurar Guias</h2>
<?php
function saveChanges() {
	global $_POST;
	foreach($_POST as $setting) {
		if(!check_value($setting)) {
			message('error','Missing data (complete all fields).');
			return;
		}
	}
	$xmlPath = __PATH_MODULE_CONFIGS__.'guides.xml';
	$xml = simplexml_load_file($xmlPath);
	
	$xml->active = $_POST['setting_1'];
	$xml->guides_expanded = $_POST['setting_2'];
	$xml->guides_list_limit = $_POST['setting_3'];
	$xml->guides_short = $_POST['setting_6'];
	$xml->guides_short_char_limit = $_POST['setting_7'];
	
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

loadModuleConfigs('guides');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the guides module.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_1',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Expanded guides<br/><span>Amount of guides you want to display expanded. If less than the display guides limit configuration, then the rest of the guides will not display expanded.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_2" value="<?=mconfig('guides_expanded')?>"/>
			</td>
		</tr>
		<tr>
			<th>Shown guides Limit<br/><span>Amount of guides to display in the guides page.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_3" value="<?=mconfig('guides_list_limit')?>"/>
			</td>
		</tr>
		<tr>
			<th>Short guides<br/><span>Enable/disable the short guides feature.</span></th>
			<td>
				<? enabledisableCheckboxes('setting_6',mconfig('guides_short'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>Short guides Character Limit<br/><span>Amount of characters to show in the short version of guides.</span></th>
			<td>
				<input class="form-control" type="text" name="setting_7" value="<?=mconfig('guides_short_char_limit')?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>