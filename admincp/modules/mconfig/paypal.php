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

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<h1 class="page-header">PayPal Settings</h1>
<?php
function saveChanges() {
	global $_POST;

	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.paypal.xml';
	$xml = @simplexml_load_file($xmlPath);
	if(!$xml) {
		message('error','[PayPal] Unable to load configuration file.');
		return;
	}

	$xml->active = isset($_POST['setting_2']) ? $_POST['setting_2'] : '0';
	$xml->paypal_enable_sandbox = isset($_POST['setting_3']) ? $_POST['setting_3'] : '0';
	$xml->paypal_email = isset($_POST['setting_4']) ? trim($_POST['setting_4']) : '';
	$xml->paypal_title = isset($_POST['setting_5']) ? trim($_POST['setting_5']) : '';
	$xml->paypal_currency = isset($_POST['setting_6']) ? trim($_POST['setting_6']) : '';
	$xml->paypal_return_url = isset($_POST['setting_7']) ? trim($_POST['setting_7']) : '';
	$xml->paypal_notify_url = isset($_POST['setting_8']) ? trim($_POST['setting_8']) : '';

	for($i=1;$i<=10;$i++) {
		$priceKey = 'pack_'.$i.'_price';
		$creditsKey = 'pack_'.$i.'_credits';
		$currencyKey = 'pack_'.$i.'_currency';
		$imageKey = 'pack_'.$i.'_image';
		$xml->{$priceKey} = isset($_POST[$priceKey]) ? trim($_POST[$priceKey]) : '0';
		$xml->{$creditsKey} = isset($_POST[$creditsKey]) ? trim($_POST[$creditsKey]) : '0';
		$xml->{$currencyKey} = isset($_POST[$currencyKey]) ? trim($_POST[$currencyKey]) : '0';
		$xml->{$imageKey} = isset($_POST[$imageKey]) ? trim($_POST[$imageKey]) : '';
	}
	$xml->terms_and_conditions = isset($_POST['setting_32']) ? trim($_POST['setting_32']) : '';
	$xml->contact_info = isset($_POST['setting_33']) ? trim($_POST['setting_33']) : '';

	if($xml->asXML($xmlPath)) {
		message('success','[PayPal] Settings successfully saved.');
	} else {
		message('error','[PayPal] There has been an error while saving changes.');
	}

}

if(isset($_POST['submit_changes']) && check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.paypal');

$creditSystem = new CreditSystem();
$creditConfigs = $creditSystem->showConfigs();

// Helper function to build credit select without multiple DB calls
function buildCreditSelect($name, $selectedValue, $creditConfigs) {
	$html = '<select name="'.$name.'" class="form-control pack-currency">';
	$html .= '<option value="0"'.($selectedValue == 0 ? ' selected' : '').'>none</option>';
	if(is_array($creditConfigs)) {
		foreach($creditConfigs as $config) {
			$selected = ($selectedValue == $config['config_id']) ? ' selected' : '';
			$html .= '<option value="'.$config['config_id'].'"'.$selected.'>'.$config['config_title'].'</option>';
		}
	}
	$html .= '</select>';
	return $html;
}
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Enable/disable the paypal donation gateway.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_2',mconfig('active'),'Enabled','Disabled'); ?>

			</td>
		</tr>
		<tr>
			<th>PayPal Sandbox Mode<br/><span>Enable/disable PayPal's IPN testing mode.<br/><br/>More info:<br/><a href="https://developer.paypal.com/" target="_blank">https://developer.paypal.com/</a></span></th>
			<td>
				<?php enabledisableCheckboxes('setting_3',mconfig('paypal_enable_sandbox'),'Enabled','Disabled'); ?>

			</td>
		</tr>
		<tr>
			<th>PayPal Email<br/><span>PayPal email where you will receive the donations.</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_4" value="<?=mconfig('paypal_email')?>"/>
			</td>
		</tr>
		<tr>
	<th>Terms and Conditions<br/><span>Message to display before payment.</span></th>
	<td>
		<textarea class="form-control" name="setting_32" rows="4"><?=mconfig('terms_and_conditions')?></textarea>
	</td>
</tr>

<tr>
	<th>Contact / Support<br/><span>Email or contact link for user inquiries.</span></th>
	<td>
		<input style="width: 100%;" type="text" name="setting_33" value="<?=mconfig('contact_info')?>" />
	</td>
</tr>
		<tr>
			<th>PayPal Donations Title<br/><span>Title of the PayPal donation. Example: "Donation for MU Credits".</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_5" value="<?=mconfig('paypal_title')?>"/>
			</td>
		</tr>
		<tr>
			<th>Currency Code<br/><span>List of available PayPal currencies: <a href="https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_currency_codes" target="_blank">click here</a>.</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_6" value="<?=mconfig('paypal_currency')?>"/>
			</td>
		</tr>
		<tr>
			<th>Return/Cancel URL<br/><span>URL where the client will be redirected to if the donation is cancelled or completed.</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_7" value="<?=mconfig('paypal_return_url')?>"/>
			</td>
		</tr>
		<tr>
			<th>IPN Notify URL<br/><span>URL of WebEngine's PayPal API.<br/><br/> By default it has to be in: <b>http://YOURWEBSITE.COM/api/paypal.php</b></span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_8" value="<?=mconfig('paypal_notify_url')?>"/>
			</td>
		</tr>
		<?php for($i=1;$i<=10;$i++): 
			$packPrice = mconfig('pack_'.$i.'_price');
			$packCredits = mconfig('pack_'.$i.'_credits');
			$packCurrency = mconfig('pack_'.$i.'_currency');
			$packImage = mconfig('pack_'.$i.'_image');
			
			$packPrice = (string)($packPrice ?? '0');
			$packCredits = (string)($packCredits ?? '0');
			$packCurrency = (int)($packCurrency ?? 0);
			$packImage = (string)($packImage ?? '');
		?>
		<tr>
			<th>Configurar Pack <?=$i?><br/><span>Elije el precio, los créditos y la moneda digital que se asignarán al completar el pago (0 = deshabilita el pack).</span></th>
			<td>
				<div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
					<div style="min-width:140px;">
						<label>Precio</label>
						<input class="form-control" type="text" name="pack_<?=$i?>_price" value="<?=htmlspecialchars($packPrice, ENT_QUOTES, 'UTF-8')?>" />
					</div>
					<div style="min-width:140px;">
						<label>Créditos</label>
						<input class="form-control" type="text" name="pack_<?=$i?>_credits" value="<?=htmlspecialchars($packCredits, ENT_QUOTES, 'UTF-8')?>" />
					</div>
					<div style="min-width:200px;">
						<label>Moneda digital</label>
						<?=buildCreditSelect('pack_'.$i.'_currency', $packCurrency, $creditConfigs)?>
						<small class="text-muted">Selecciona el crédito creado en Credit Manager.</small>
					</div>
					<div style="min-width:220px;">
						<label>Imagen (150x150)</label>
						<input class="form-control" type="text" name="pack_<?=$i?>_image" value="<?=htmlspecialchars($packImage, ENT_QUOTES, 'UTF-8')?>" placeholder="URL o ruta de imagen" />
						<small class="text-muted">Ej: templates/default/img/pack<?=$i?>.png</small>
					</div>
				</div>
			</td>
		</tr>
		<?php endfor; ?>
		<tr>
			<td colspan="2"><input type="submit" name="submit_changes" value="Save Changes" class="btn btn-success"/></td>
		</tr>
	</table>
</form>