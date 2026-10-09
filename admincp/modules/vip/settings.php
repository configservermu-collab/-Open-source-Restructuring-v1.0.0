<?php
/**
 * VIP
 * https://webenginecms.org/
 * 
 * @version 1.1.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * @build w3c8c718b75a0f1fa1a557f7f9d70877
 */

function saveChanges() {
    global $_POST;
	
    $xmlPath = __PATH_VIP_ROOT__.'config.xml';
    $xml = simplexml_load_file($xmlPath);
	
	if(!is_writable($xmlPath)) throw new Exception('The configuration file is not writable.');
	
	// VIP Type 1
	if(!Validator::UnsignedNumber($_POST['setting_1'])) throw new Exception('Submitted setting is not valid (enable_vip_type_1)');
	if(!in_array($_POST['setting_1'], array(1, 0))) throw new Exception('Submitted setting is not valid (enable_vip_type_1)');
	$xml->enable_vip_type_1 = $_POST['setting_1'];
	
	if(!check_value($_POST['vip_name_1'])) throw new Exception('VIP Name is required (vip_name_1)');
	$xml->vip_name_1 = htmlspecialchars($_POST['vip_name_1'], ENT_QUOTES);
	
	$xml->vip_image_1 = htmlspecialchars($_POST['vip_image_1'], ENT_QUOTES);
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['setting_2'])) throw new Exception('Submitted setting is not valid (vip_packages_1)');
	$xml->vip_packages_1 = $_POST['setting_2'];
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['vip_prices_1'])) throw new Exception('Submitted setting is not valid (vip_prices_1)');
	$xml->vip_prices_1 = $_POST['vip_prices_1'];
	
	$xml->vip_benefits_1 = htmlspecialchars($_POST['vip_benefits_1'], ENT_QUOTES);
	
	// VIP Type 2
	if(!Validator::UnsignedNumber($_POST['setting_4'])) throw new Exception('Submitted setting is not valid (enable_vip_type_2)');
	if(!in_array($_POST['setting_4'], array(1, 0))) throw new Exception('Submitted setting is not valid (enable_vip_type_2)');
	$xml->enable_vip_type_2 = $_POST['setting_4'];
	
	if(!check_value($_POST['vip_name_2'])) throw new Exception('VIP Name is required (vip_name_2)');
	$xml->vip_name_2 = htmlspecialchars($_POST['vip_name_2'], ENT_QUOTES);
	
	$xml->vip_image_2 = htmlspecialchars($_POST['vip_image_2'], ENT_QUOTES);
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['setting_5'])) throw new Exception('Submitted setting is not valid (vip_packages_2)');
	$xml->vip_packages_2 = $_POST['setting_5'];
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['vip_prices_2'])) throw new Exception('Submitted setting is not valid (vip_prices_2)');
	$xml->vip_prices_2 = $_POST['vip_prices_2'];
	
	$xml->vip_benefits_2 = htmlspecialchars($_POST['vip_benefits_2'], ENT_QUOTES);
	
	// VIP Type 3
	if(!Validator::UnsignedNumber($_POST['setting_7'])) throw new Exception('Submitted setting is not valid (enable_vip_type_3)');
	if(!in_array($_POST['setting_7'], array(1, 0))) throw new Exception('Submitted setting is not valid (enable_vip_type_3)');
	$xml->enable_vip_type_3 = $_POST['setting_7'];
	
	if(!check_value($_POST['vip_name_3'])) throw new Exception('VIP Name is required (vip_name_3)');
	$xml->vip_name_3 = htmlspecialchars($_POST['vip_name_3'], ENT_QUOTES);
	
	$xml->vip_image_3 = htmlspecialchars($_POST['vip_image_3'], ENT_QUOTES);
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['setting_8'])) throw new Exception('Submitted setting is not valid (vip_packages_3)');
	$xml->vip_packages_3 = $_POST['setting_8'];
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['vip_prices_3'])) throw new Exception('Submitted setting is not valid (vip_prices_3)');
	$xml->vip_prices_3 = $_POST['vip_prices_3'];
	
	$xml->vip_benefits_3 = htmlspecialchars($_POST['vip_benefits_3'], ENT_QUOTES);
	
	// VIP Type 4
	if(!Validator::UnsignedNumber($_POST['setting_10'])) throw new Exception('Submitted setting is not valid (enable_vip_type_4)');
	if(!in_array($_POST['setting_10'], array(1, 0))) throw new Exception('Submitted setting is not valid (enable_vip_type_4)');
	$xml->enable_vip_type_4 = $_POST['setting_10'];
	
	if(!check_value($_POST['vip_name_4'])) throw new Exception('VIP Name is required (vip_name_4)');
	$xml->vip_name_4 = htmlspecialchars($_POST['vip_name_4'], ENT_QUOTES);
	
	$xml->vip_image_4 = htmlspecialchars($_POST['vip_image_4'], ENT_QUOTES);
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['setting_11'])) throw new Exception('Submitted setting is not valid (vip_packages_4)');
	$xml->vip_packages_4 = $_POST['setting_11'];
	
	if(!preg_match('/^\d+(?:,\d+)*$/', $_POST['vip_prices_4'])) throw new Exception('Submitted setting is not valid (vip_prices_4)');
	$xml->vip_prices_4 = $_POST['vip_prices_4'];
	
	$xml->vip_benefits_4 = htmlspecialchars($_POST['vip_benefits_4'], ENT_QUOTES);
	
	// credits
	if(!Validator::UnsignedNumber($_POST['setting_13'])) throw new Exception('Submitted setting is not valid (credit_config)');
	$xml->credit_config = $_POST['setting_13'];
	
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
		$VIP = new \Plugin\VIP\VIP();
		$VIP->checkPluginUsercpLinks();
		message('success', 'UserCP Links Successfully Added!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// load configs
$pluginConfig = simplexml_load_file(__PATH_VIP_ROOT__.'config.xml');
if(!$pluginConfig) throw new Exception('Error loading config file.');

// credit system
$creditSystem = new CreditSystem();

// server files
if(strtolower(config('server_files', true)) != 'igcn') {
	message('info', 'By default, the "platinum" VIP type is only available when using IGCN files.');
}
?>
<h2>VIP Settings</h2>
<form action="" method="post">

	<table class="table table-striped table-bordered table-hover module_config_tables">
		<thead>
			<tr>
				<th colspan="2" class="text-center" style="background-color: #cd7f32; color: white;">VIP Type 1</th>
			</tr>
		</thead>
		<tr>
            <th>Enable VIP Type 1<br/><span>Enables / disables this VIP type</span></th>
            <td>
				<?php enabledisableCheckboxes('setting_1', $pluginConfig->enable_vip_type_1, 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>VIP Name<br/><span>Custom name for this VIP type (e.g., Bronze, Starter, Basic, etc.)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_name_1" value="<?php echo htmlspecialchars($pluginConfig->vip_name_1); ?>" placeholder="Bronze VIP"/>
            </td>
        </tr>
		<tr>
            <th>VIP Image<br/><span>Image filename in img/vip/ folder (250x250px max, PNG format recommended)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_image_1" value="<?php echo htmlspecialchars($pluginConfig->vip_image_1); ?>" placeholder="vip1.png"/>
            </td>
        </tr>
        <tr>
            <th>VIP Packages (Days)<br/><span>Amount of days available for purchase, separated by commas (no spaces).<br /><br />Example: 5,10,15,30</span></th>
            <td>
				<input class="form-control" type="text" name="setting_2" value="<?php echo $pluginConfig->vip_packages_1; ?>" placeholder="5,10,15,30"/>
            </td>
        </tr>
        <tr>
            <th>Package Prices (Coins)<br/><span>Fixed price for each package in coins, separated by commas (no spaces). Must match the number of packages.<br /><br />Example: 75,150,300,600</span></th>
            <td>
				<input class="form-control" type="text" name="vip_prices_1" value="<?php echo $pluginConfig->vip_prices_1; ?>" placeholder="75,150,300,600"/>
            </td>
        </tr>
		<tr>
            <th>VIP Benefits<br/><span>List of benefits separated by pipe (|). These will be displayed to users.<br /><br />Example: +10% EXP|+5% Drop Rate|Access to VIP Zone|Priority Support</span></th>
            <td>
				<textarea class="form-control" name="vip_benefits_1" rows="4" placeholder="+10% EXP|+5% Drop Rate|Access to VIP Zone"><?php echo htmlspecialchars($pluginConfig->vip_benefits_1); ?></textarea>
            </td>
        </tr>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<thead>
			<tr>
				<th colspan="2" class="text-center" style="background-color: #c0c0c0; color: white;">VIP Type 2</th>
			</tr>
		</thead>
		<tr>
            <th>Enable VIP Type 2<br/><span>Enables / disables this VIP type</span></th>
            <td>
				<?php enabledisableCheckboxes('setting_4', $pluginConfig->enable_vip_type_2, 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>VIP Name<br/><span>Custom name for this VIP type (e.g., Silver, Premium, Advanced, etc.)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_name_2" value="<?php echo htmlspecialchars($pluginConfig->vip_name_2); ?>" placeholder="Silver VIP"/>
            </td>
        </tr>
		<tr>
            <th>VIP Image<br/><span>Image filename in img/vip/ folder (250x250px max, PNG format recommended)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_image_2" value="<?php echo htmlspecialchars($pluginConfig->vip_image_2); ?>" placeholder="vip2.png"/>
            </td>
        </tr>
		<tr>
            <th>VIP Packages (Days)<br/><span>Amount of days available for purchase, separated by commas (no spaces).<br /><br />Example: 5,10,15,30</span></th>
            <td>
				<input class="form-control" type="text" name="setting_5" value="<?php echo $pluginConfig->vip_packages_2; ?>" placeholder="5,10,15,30"/>
            </td>
        </tr>
		<tr>
            <th>Package Prices (Coins)<br/><span>Fixed price for each package in coins, separated by commas (no spaces). Must match the number of packages.<br /><br />Example: 100,200,400,800</span></th>
            <td>
				<input class="form-control" type="text" name="vip_prices_2" value="<?php echo $pluginConfig->vip_prices_2; ?>" placeholder="100,200,400,800"/>
            </td>
        </tr>
		<tr>
            <th>VIP Benefits<br/><span>List of benefits separated by pipe (|). These will be displayed to users.<br /><br />Example: +20% EXP|+10% Drop Rate|Special Items</span></th>
            <td>
				<textarea class="form-control" name="vip_benefits_2" rows="4" placeholder="+20% EXP|+10% Drop Rate|Special Items"><?php echo htmlspecialchars($pluginConfig->vip_benefits_2); ?></textarea>
            </td>
        </tr>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<thead>
			<tr>
				<th colspan="2" class="text-center" style="background-color: #ffd700; color: white;">VIP Type 3</th>
			</tr>
		</thead>
		<tr>
            <th>Enable VIP Type 3<br/><span>Enables / disables this VIP type</span></th>
            <td>
				<?php enabledisableCheckboxes('setting_7', $pluginConfig->enable_vip_type_3, 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>VIP Name<br/><span>Custom name for this VIP type (e.g., Gold, Elite, Pro, etc.)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_name_3" value="<?php echo htmlspecialchars($pluginConfig->vip_name_3); ?>" placeholder="Gold VIP"/>
            </td>
        </tr>
		<tr>
            <th>VIP Image<br/><span>Image filename in img/vip/ folder (250x250px max, PNG format recommended)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_image_3" value="<?php echo htmlspecialchars($pluginConfig->vip_image_3); ?>" placeholder="vip3.png"/>
            </td>
        </tr>
		<tr>
            <th>VIP Packages (Days)<br/><span>Amount of days available for purchase, separated by commas (no spaces).<br /><br />Example: 30,60,90</span></th>
            <td>
				<input class="form-control" type="text" name="setting_8" value="<?php echo $pluginConfig->vip_packages_3; ?>" placeholder="30,60,90"/>
            </td>
        </tr>
		<tr>
            <th>Package Prices (Coins)<br/><span>Fixed price for each package in coins, separated by commas (no spaces). Must match the number of packages.<br /><br />Example: 1500,3000,4500</span></th>
            <td>
				<input class="form-control" type="text" name="vip_prices_3" value="<?php echo $pluginConfig->vip_prices_3; ?>" placeholder="1500,3000,4500"/>
            </td>
        </tr>
		<tr>
            <th>VIP Benefits<br/><span>List of benefits separated by pipe (|). These will be displayed to users.<br /><br />Example: +30% EXP|+15% Drop Rate|Premium Events</span></th>
            <td>
				<textarea class="form-control" name="vip_benefits_3" rows="4" placeholder="+30% EXP|+15% Drop Rate|Premium Events"><?php echo htmlspecialchars($pluginConfig->vip_benefits_3); ?></textarea>
            </td>
        </tr>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<thead>
			<tr>
				<th colspan="2" class="text-center" style="background-color: #e5e4e2; color: #333;">VIP Type 4 (IGCN ONLY)</th>
			</tr>
		</thead>
		<tr>
            <th>Enable VIP Type 4 (IGCN ONLY)<br/><span>Enables / disables this VIP type</span></th>
            <td>
				<?php enabledisableCheckboxes('setting_10', $pluginConfig->enable_vip_type_4, 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>VIP Name<br/><span>Custom name for this VIP type (e.g., Platinum, Ultimate, Supreme, etc.)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_name_4" value="<?php echo htmlspecialchars($pluginConfig->vip_name_4); ?>" placeholder="Platinum VIP"/>
            </td>
        </tr>
		<tr>
            <th>VIP Image<br/><span>Image filename in img/vip/ folder (250x250px max, PNG format recommended)</span></th>
            <td>
				<input class="form-control" type="text" name="vip_image_4" value="<?php echo htmlspecialchars($pluginConfig->vip_image_4); ?>" placeholder="vip4.png"/>
            </td>
        </tr>
		<tr>
            <th>VIP Packages (Days) (IGCN ONLY)<br/><span>Amount of days available for purchase, separated by commas (no spaces).<br /><br />Example: 30,60,90</span></th>
            <td>
				<input class="form-control" type="text" name="setting_11" value="<?php echo $pluginConfig->vip_packages_4; ?>" placeholder="30,60,90"/>
            </td>
        </tr>
		<tr>
            <th>Package Prices (Coins) (IGCN ONLY)<br/><span>Fixed price for each package in coins, separated by commas (no spaces). Must match the number of packages.<br /><br />Example: 2000,4000,6000</span></th>
            <td>
				<input class="form-control" type="text" name="vip_prices_4" value="<?php echo $pluginConfig->vip_prices_4; ?>" placeholder="2000,4000,6000"/>
            </td>
        </tr>
		<tr>
            <th>VIP Benefits<br/><span>List of benefits separated by pipe (|). These will be displayed to users.<br /><br />Example: +50% EXP|+25% Drop Rate|Exclusive Rewards</span></th>
            <td>
				<textarea class="form-control" name="vip_benefits_4" rows="4" placeholder="+50% EXP|+25% Drop Rate|Exclusive Rewards"><?php echo htmlspecialchars($pluginConfig->vip_benefits_4); ?></textarea>
            </td>
        </tr>
	</table>
        </tr>
	</table>
	
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Credit Configuration<br/><span>Type of credits used to pay for VIP.</span></th>
			<td>
				<?php echo $creditSystem->buildSelectInput("setting_13", $pluginConfig->credit_config, "form-control"); ?>
			</td>
		</tr>
		<tr>
            <td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
        </tr>
    </table>
</form>

<hr>

<div class="alert alert-info">
	<h4><i class="fa fa-info-circle"></i> New VIP System Features</h4>
	<ul>
		<li><strong>Custom VIP Names:</strong> You can now name each VIP type whatever you want (e.g., "Starter", "Pro", "Ultimate").</li>
		<li><strong>Fixed Prices:</strong> Set specific prices for each package instead of using daily rates and discounts.</li>
		<li><strong>Benefits Display:</strong> List VIP benefits that will be displayed to users. Use the pipe symbol (|) to separate benefits.</li>
		<li><strong>Independent Offers:</strong> Each VIP type can have different packages, prices, and benefits completely independent from each other.</li>
	</ul>
</div>

<hr>

<h2>UserCP Links</h2>
<p>Click the button below to automatically add the plugin's links to the user control panel menu.</p>
<a href="<?php echo admincp_base('vip&page=settings&checkusercplinks=1'); ?>" class="btn btn-primary">Add UserCP Links</a>