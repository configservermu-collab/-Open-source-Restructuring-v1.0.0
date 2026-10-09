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

echo '<h1 class="page-header">Webshop Settings</h1>';

function saveChanges() {
	global $_POST;

	$xmlPath = __PATH_MODULE_CONFIGS__.'webshop.xml';
	$xml = @simplexml_load_file($xmlPath);
	if(!$xml) {
		message('error','[Webshop] Unable to load configuration file.');
		return;
	}

	$xml->active = isset($_POST['setting_active']) ? $_POST['setting_active'] : '0';
	$xml->mu_version = isset($_POST['setting_mu_version']) ? $_POST['setting_mu_version'] : 'muemu';
	$xml->credit_config_id = isset($_POST['setting_credit_config_id']) ? $_POST['setting_credit_config_id'] : '0';
	$xml->moneda = isset($_POST['setting_moneda']) ? trim($_POST['setting_moneda']) : 'Credits';
	$xml->moneda_v = isset($_POST['setting_moneda_v']) ? trim($_POST['setting_moneda_v']) : 'Credits';
	$xml->type_vault = isset($_POST['setting_type_vault']) ? $_POST['setting_type_vault'] : '0';
	
	// Gremory Case settings
	$xml->days_gc = isset($_POST['setting_days_gc']) ? trim($_POST['setting_days_gc']) : '20';
	$xml->text_gc = isset($_POST['setting_text_gc']) ? trim($_POST['setting_text_gc']) : '9';
	
	// Precios de opciones
	$xml->option_itemlevel = isset($_POST['setting_option_itemlevel']) ? trim($_POST['setting_option_itemlevel']) : '20';
	$xml->option_life = isset($_POST['setting_option_life']) ? trim($_POST['setting_option_life']) : '10';
	$xml->option_luck = isset($_POST['setting_option_luck']) ? trim($_POST['setting_option_luck']) : '20';
	$xml->option_skill = isset($_POST['setting_option_skill']) ? trim($_POST['setting_option_skill']) : '70';
	$xml->option_exe = isset($_POST['setting_option_exe']) ? trim($_POST['setting_option_exe']) : '50';
	$xml->option_380 = isset($_POST['setting_option_380']) ? trim($_POST['setting_option_380']) : '100';
	$xml->option_ancient = isset($_POST['setting_option_ancient']) ? trim($_POST['setting_option_ancient']) : '300';
	$xml->option_socket = isset($_POST['setting_option_socket']) ? trim($_POST['setting_option_socket']) : '500';
	$xml->option_harmony = isset($_POST['setting_option_harmony']) ? trim($_POST['setting_option_harmony']) : '600';
	
	// Habilitar opciones
	$xml->enable_itemlevel = isset($_POST['setting_enable_itemlevel']) ? $_POST['setting_enable_itemlevel'] : '0';
	$xml->enable_oplife = isset($_POST['setting_enable_oplife']) ? $_POST['setting_enable_oplife'] : '0';
	$xml->enable_luck = isset($_POST['setting_enable_luck']) ? $_POST['setting_enable_luck'] : '0';
	$xml->enable_skill = isset($_POST['setting_enable_skill']) ? $_POST['setting_enable_skill'] : '0';
	$xml->enable_opexe = isset($_POST['setting_enable_opexe']) ? $_POST['setting_enable_opexe'] : '0';
	$xml->enable_op380 = isset($_POST['setting_enable_op380']) ? $_POST['setting_enable_op380'] : '0';
	$xml->enable_ancient = isset($_POST['setting_enable_ancient']) ? $_POST['setting_enable_ancient'] : '0';
	$xml->enable_harmony = isset($_POST['setting_enable_harmony']) ? $_POST['setting_enable_harmony'] : '0';
	$xml->enable_socket = isset($_POST['setting_enable_socket']) ? $_POST['setting_enable_socket'] : '0';
	$xml->enable_samesocket = isset($_POST['setting_enable_samesocket']) ? $_POST['setting_enable_samesocket'] : '0';
	
	// Categorías
	$xml->sword = isset($_POST['setting_sword']) ? $_POST['setting_sword'] : '0';
	$xml->axe = isset($_POST['setting_axe']) ? $_POST['setting_axe'] : '0';
	$xml->mace = isset($_POST['setting_mace']) ? $_POST['setting_mace'] : '0';
	$xml->spear = isset($_POST['setting_spear']) ? $_POST['setting_spear'] : '0';
	$xml->bow = isset($_POST['setting_bow']) ? $_POST['setting_bow'] : '0';
	$xml->staff = isset($_POST['setting_staff']) ? $_POST['setting_staff'] : '0';
	$xml->shield = isset($_POST['setting_shield']) ? $_POST['setting_shield'] : '0';
	$xml->helm = isset($_POST['setting_helm']) ? $_POST['setting_helm'] : '0';
	$xml->armor = isset($_POST['setting_armor']) ? $_POST['setting_armor'] : '0';
	$xml->pants = isset($_POST['setting_pants']) ? $_POST['setting_pants'] : '0';
	$xml->gloves = isset($_POST['setting_gloves']) ? $_POST['setting_gloves'] : '0';
	$xml->boots = isset($_POST['setting_boots']) ? $_POST['setting_boots'] : '0';
	$xml->wings = isset($_POST['setting_wings']) ? $_POST['setting_wings'] : '0';
	$xml->otros = isset($_POST['setting_otros']) ? $_POST['setting_otros'] : '0';
	
	// Límites
	$xml->item_maxlevel = isset($_POST['setting_item_maxlevel']) ? trim($_POST['setting_item_maxlevel']) : '13';
	$xml->item_maxopexe = isset($_POST['setting_item_maxopexe']) ? trim($_POST['setting_item_maxopexe']) : '2';

	if($xml->asXML($xmlPath)) {
		message('success','[Webshop] Settings successfully saved.');
	} else {
		message('error','[Webshop] There has been an error while saving changes.');
	}
}

if(isset($_POST['submit_changes']) && check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('webshop');
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the webshop module.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_active',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr class="table-info">
			<th colspan="2" style="background-color: #d1ecf1; font-weight: bold;">
				🎮 MU Online Version Selection
			</th>
		</tr>
		<tr>
			<th>
				MU Server Version<br/>
				<span>Select your MU Online server type. This determines which item generation class is used.</span>
			</th>
			<td>
				<?php
				$currentVersion = mconfig('mu_version') ? mconfig('mu_version') : 'muemu';
				?>
				<select class="form-control" name="setting_mu_version" id="mu_version_select" onchange="toggleVersionFeatures()">
					<option value="muemu" <?php echo ($currentVersion == 'muemu') ? 'selected' : ''; ?>>
						🎮 MuEMU (Season 6+) - Full Features
					</option>
					<option value="ex101kor" <?php echo ($currentVersion == 'ex101kor') ? 'selected' : ''; ?>>
						🎮 SSEmu EX101KOR - Limited Features
					</option>
				</select>
				<div class="alert alert-info mt-2" style="margin-top: 10px;">
					<h5 style="margin-top: 0;"><strong>ℹ️ Version Information:</strong></h5>
					<div id="version_info_muemu" style="display: <?php echo ($currentVersion == 'muemu') ? 'block' : 'none'; ?>;">
						<p><strong>🎮 MuEMU (Season 6+)</strong></p>
						<ul>
							<li>✅ <strong>Support ALL features</strong>: Level, Skill, Luck, Life, Excellent, Ancient, Harmony, Sockets, Op380</li>
							<li>✅ Uses <code>class.webshop.php</code> (WebShopNew)</li>
							<li>✅ Compatible with Season 6, 9, 10, 15</li>
							<li>✅ Full 32-character hex format</li>
						</ul>
					</div>
					<div id="version_info_ex101kor" style="display: <?php echo ($currentVersion == 'ex101kor') ? 'block' : 'none'; ?>;">
						<p><strong>🎮 SSEmu EX101KOR</strong></p>
						<ul>
							<li>✅ <strong>Supported</strong>: Level, Skill, Luck, Life, Excellent (6 opts), Ancient</li>
							<li>❌ <strong>NOT Supported</strong>: Harmony, Sockets, Op380</li>
							<li>✅ Uses <code>class.webshop.ex101kor.php</code> (WebShopEX101KOR)</li>
							<li>⚠️ Simplified format: Bytes 19-31 are zero padding</li>
						</ul>
					</div>
					<p style="margin-bottom: 0;"><strong>⚠️ Important:</strong> Changing version will automatically hide/show relevant options below.</p>
				</div>
			</td>
		</tr>
		<tr>
			<th>💰 Currency System<br/><span>Select which currency to use from Credit Configurations.</span></th>
			<td>
				<?php
				try {
					$creditSystem = new CreditSystem();
					$creditConfigs = $creditSystem->showConfigs(); // Correct method name
					$currentCreditId = mconfig('credit_config_id') ? mconfig('credit_config_id') : 0;
					
					if(is_array($creditConfigs) && count($creditConfigs) > 0) {
						echo '<select class="form-control" name="setting_credit_config_id" id="credit_select" onchange="updateCurrencyInfo()">';
						echo '<option value="0" '.($currentCreditId == 0 ? 'selected' : '').'>❌ Legacy Mode (Manual Configuration)</option>';
						foreach($creditConfigs as $config) {
							$selected = ($currentCreditId == $config['config_id']) ? 'selected' : '';
							echo '<option value="'.$config['config_id'].'" '.$selected.'>';
							echo '✅ '.$config['config_title'].' ('.$config['config_table'].'.'.$config['config_credits_col'].')';
							echo '</option>';
						}
						echo '</select>';
						echo '<div class="alert alert-info mt-2" style="margin-top:10px;"><strong>ℹ️ Modern System:</strong> Uses configurations from <a href="'.admincp_base('creditsconfigs').'" target="_blank">Credit Configurations</a>. Recommended for multi-currency support.</div>';
					} else {
						echo '<select class="form-control" name="setting_credit_config_id" id="credit_select" onchange="updateCurrencyInfo()">';
						echo '<option value="0" selected>❌ Legacy Mode (Manual Configuration)</option>';
						echo '</select>';
						echo '<div class="alert alert-warning mt-2" style="margin-top:10px;"><strong>⚠️ No Configurations:</strong> No credit configurations found. <a href="'.admincp_base('creditsconfigs').'" target="_blank">Create one here</a> to use the modern system.</div>';
					}
				} catch(Exception $e) {
					echo '<select class="form-control" name="setting_credit_config_id" id="credit_select" onchange="updateCurrencyInfo()">';
					echo '<option value="0" selected>❌ Legacy Mode (Manual Configuration)</option>';
					echo '</select>';
					echo '<div class="alert alert-danger mt-2" style="margin-top:10px;"><strong>⚠️ Error:</strong> '.$e->getMessage().'</div>';
				}
				?>
			</td>
		</tr>
		<tr id="legacy_currency_row" style="<?php echo (mconfig('credit_config_id') > 0) ? 'display:none;' : ''; ?>">
			<th>💵 Legacy: Currency Column Name<br/><span>⚠️ Only used if "Legacy Mode" is selected above. Name of database column (e.g., PcPoint, wcoinC).</span></th>
			<td>
				<input style="width: 100%;" class="form-control" type="text" name="setting_moneda" value="<?=mconfig('moneda')?>" id="legacy_moneda"/>
				<small class="text-muted">⚠️ Legacy mode uses hardcoded InGameCash table. Switch to Modern System above for better compatibility.</small>
			</td>
		</tr>
		<tr>
			<th>Display Name<br/><span>User-friendly name shown on the website (e.g., "Credits", "Donation Points").</span></th>
			<td>
				<input style="width: 100%;" class="form-control" type="text" name="setting_moneda_v" value="<?=mconfig('moneda_v')?>"/>
			</td>
		</tr>
		<tr>
			<th>Vault Type<br/><span>0 = Normal Warehouse, 1 = Gremory Case (Season 6+).</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_type_vault',mconfig('type_vault'),'Gremory Case','Warehouse'); ?>
			</td>
		</tr>
		<tr>
			<th>Gremory Case: Days<br/><span>Number of days items remain in Gremory Case (only if Gremory Case is enabled).</span></th>
			<td>
				<input class="form-control" type="number" min="1" max="365" name="setting_days_gc" value="<?=mconfig('days_gc')?>"/>
			</td>
		</tr>
		<tr>
			<th>Gremory Case: Text Code<br/><span>Text code identifier for Gremory items (usually 9).</span></th>
			<td>
				<input class="form-control" type="number" min="0" max="255" name="setting_text_gc" value="<?=mconfig('text_gc')?>"/>
			</td>
		</tr>
	</table>
	
	<h3>Item Options - Prices</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Item Level (+1 to +15)<br/><span>Cost per level.</span></th>
			<td><input class="form-control" type="number" name="setting_option_itemlevel" value="<?=mconfig('option_itemlevel')?>"/></td>
		</tr>
		<tr>
			<th>Option Life<br/><span>Cost per +4 life option.</span></th>
			<td><input class="form-control" type="number" name="setting_option_life" value="<?=mconfig('option_life')?>"/></td>
		</tr>
		<tr>
			<th>Luck<br/><span>Cost for luck option.</span></th>
			<td><input class="form-control" type="number" name="setting_option_luck" value="<?=mconfig('option_luck')?>"/></td>
		</tr>
		<tr>
			<th>Skill<br/><span>Cost for skill option.</span></th>
			<td><input class="form-control" type="number" name="setting_option_skill" value="<?=mconfig('option_skill')?>"/></td>
		</tr>
		<tr>
			<th>Excellent Options<br/><span>Cost per excellent option.</span></th>
			<td><input class="form-control" type="number" name="setting_option_exe" value="<?=mconfig('option_exe')?>"/></td>
		</tr>
		<tr class="version-specific" data-version-feature="op380">
			<th>Full Option (380)<br/><span>Cost for +16/+28 damage/defense options. ❌ Not supported in EX101KOR.</span></th>
			<td><input class="form-control" type="number" name="setting_option_380" value="<?=mconfig('option_380')?>"/></td>
		</tr>
		<tr>
			<th>Ancient Option<br/><span>Cost for ancient set.</span></th>
			<td><input class="form-control" type="number" name="setting_option_ancient" value="<?=mconfig('option_ancient')?>"/></td>
		</tr>
		<tr class="version-specific" data-version-feature="socket">
			<th>Socket<br/><span>Cost per socket. ❌ Not supported in EX101KOR.</span></th>
			<td><input class="form-control" type="number" name="setting_option_socket" value="<?=mconfig('option_socket')?>"/></td>
		</tr>
		<tr class="version-specific" data-version-feature="harmony">
			<th>Harmony<br/><span>Cost per harmony option. ❌ Not supported in EX101KOR.</span></th>
			<td><input class="form-control" type="number" name="setting_option_harmony" value="<?=mconfig('option_harmony')?>"/></td>
		</tr>
	</table>
	
	<h3>Enable/Disable Options</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Item Level</th>
			<td><?php enabledisableCheckboxes('setting_enable_itemlevel',mconfig('enable_itemlevel'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Option Life</th>
			<td><?php enabledisableCheckboxes('setting_enable_oplife',mconfig('enable_oplife'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Luck</th>
			<td><?php enabledisableCheckboxes('setting_enable_luck',mconfig('enable_luck'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Skill</th>
			<td><?php enabledisableCheckboxes('setting_enable_skill',mconfig('enable_skill'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Excellent Options</th>
			<td><?php enabledisableCheckboxes('setting_enable_opexe',mconfig('enable_opexe'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr class="version-specific" data-version-feature="op380">
			<th>Full Option (380)<br/><span>Enable +16/+28 damage/defense options. ❌ Not supported in EX101KOR.</span></th>
			<td><?php enabledisableCheckboxes('setting_enable_op380',mconfig('enable_op380'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Ancient Sets</th>
			<td><?php enabledisableCheckboxes('setting_enable_ancient',mconfig('enable_ancient'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr class="version-specific" data-version-feature="socket">
			<th>Sockets<br/><span>❌ Not supported in EX101KOR.</span></th>
			<td><?php enabledisableCheckboxes('setting_enable_socket',mconfig('enable_socket'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr class="version-specific" data-version-feature="harmony">
			<th>Harmony<br/><span>❌ Not supported in EXKor.</span></th>
			<td><?php enabledisableCheckboxes('setting_enable_harmony',mconfig('enable_harmony'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr class="version-specific" data-version-feature="socket">
			<th>Allow Same Socket Type<br/><span>❌ Not supported in EX101KOR.</span></th>
			<td><?php enabledisableCheckboxes('setting_enable_samesocket',mconfig('enable_samesocket'),'Enabled','Disabled'); ?></td>
		</tr>
	</table>
	
	<h3>Item Categories</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Swords</th>
			<td><?php enabledisableCheckboxes('setting_sword',mconfig('sword'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Axes</th>
			<td><?php enabledisableCheckboxes('setting_axe',mconfig('axe'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Maces</th>
			<td><?php enabledisableCheckboxes('setting_mace',mconfig('mace'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Spears</th>
			<td><?php enabledisableCheckboxes('setting_spear',mconfig('spear'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Bows</th>
			<td><?php enabledisableCheckboxes('setting_bow',mconfig('bow'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Staffs</th>
			<td><?php enabledisableCheckboxes('setting_staff',mconfig('staff'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Shields</th>
			<td><?php enabledisableCheckboxes('setting_shield',mconfig('shield'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Helms</th>
			<td><?php enabledisableCheckboxes('setting_helm',mconfig('helm'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Armors</th>
			<td><?php enabledisableCheckboxes('setting_armor',mconfig('armor'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Pants</th>
			<td><?php enabledisableCheckboxes('setting_pants',mconfig('pants'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Gloves</th>
			<td><?php enabledisableCheckboxes('setting_gloves',mconfig('gloves'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Boots</th>
			<td><?php enabledisableCheckboxes('setting_boots',mconfig('boots'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Wings</th>
			<td><?php enabledisableCheckboxes('setting_wings',mconfig('wings'),'Enabled','Disabled'); ?></td>
		</tr>
		<tr>
			<th>Others (Jewels, Rings, etc.)</th>
			<td><?php enabledisableCheckboxes('setting_otros',mconfig('otros'),'Enabled','Disabled'); ?></td>
		</tr>
	</table>
	
	<h3>Limits</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Maximum Item Level<br/><span>0-15</span></th>
			<td><input class="form-control" type="number" min="0" max="15" name="setting_item_maxlevel" value="<?=mconfig('item_maxlevel')?>"/></td>
		</tr>
		<tr>
			<th>Maximum Excellent Options<br/><span>0-6</span></th>
			<td><input class="form-control" type="number" min="0" max="6" name="setting_item_maxopexe" value="<?=mconfig('item_maxopexe')?>"/></td>
		</tr>
	</table>
	
	<button type="submit" name="submit_changes" value="submit" class="btn btn-success">💾 Save Changes</button>
</form>

<script>
function updateCurrencyInfo() {
	var select = document.getElementById('credit_select');
	var legacyRow = document.getElementById('legacy_currency_row');
	
	if(select.value == '0') {
		// Legacy mode - mostrar configuración manual
		if(legacyRow) legacyRow.style.display = '';
	} else {
		// Modern mode - ocultar configuración manual
		if(legacyRow) legacyRow.style.display = 'none';
	}
}

function toggleVersionFeatures() {
	var versionSelect = document.getElementById('mu_version_select');
	var selectedVersion = versionSelect.value;
	
	// Toggle version info divs
	document.getElementById('version_info_muemu').style.display = (selectedVersion == 'muemu') ? 'block' : 'none';
	document.getElementById('version_info_ex101kor').style.display = (selectedVersion == 'ex101kor') ? 'block' : 'none';
	
	// Obtener todas las filas con version-specific
	var versionRows = document.querySelectorAll('.version-specific');
	
	if(selectedVersion == 'ex101kor') {
		// EX101KOR - Ocultar Harmony, Sockets, Op380
		versionRows.forEach(function(row) {
			var feature = row.getAttribute('data-version-feature');
			if(feature == 'harmony' || feature == 'socket' || feature == 'op380') {
				row.style.display = 'none';
				row.style.backgroundColor = '#ffe6e6';
				// Deshabilitar inputs
				var inputs = row.querySelectorAll('input, select');
				inputs.forEach(function(input) {
					input.disabled = true;
				});
			}
		});
	} else {
		// MuEMU - Mostrar todo
		versionRows.forEach(function(row) {
			row.style.display = '';
			row.style.backgroundColor = '';
			// Habilitar inputs
			var inputs = row.querySelectorAll('input, select');
			inputs.forEach(function(input) {
				input.disabled = false;
			});
		});
	}
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
	updateCurrencyInfo();
	toggleVersionFeatures();
});
</script>
