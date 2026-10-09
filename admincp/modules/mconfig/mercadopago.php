
<h1 class="page-header">MercadoPago Settings</h1>
<?php
function saveChanges() {
	global $_POST;

	$xmlPath = __PATH_MODULE_CONFIGS__.'donation.mercadopago.xml';
	$xml = @simplexml_load_file($xmlPath);
	if(!$xml) {
		message('error','[MercadoPago] No se pudo cargar el archivo de configuración.');
		return;
	}

	$xml->active = isset($_POST['setting_2']) ? $_POST['setting_2'] : '0';
	$xml->access_token = isset($_POST['setting_3']) ? trim($_POST['setting_3']) : '';
	$xml->mercadopago_title = isset($_POST['setting_4']) ? trim($_POST['setting_4']) : '';
	$xml->mercadopago_description = isset($_POST['setting_5']) ? trim($_POST['setting_5']) : '';
	$xml->mercadopago_currency = isset($_POST['setting_6']) ? trim($_POST['setting_6']) : '';
	$xml->mercadopago_return_url = isset($_POST['setting_7']) ? trim($_POST['setting_7']) : '';
	$xml->mercadopago_api_return_url = isset($_POST['setting_8']) ? trim($_POST['setting_8']) : '';

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
		message('success','[MercadoPago] Settings successfully saved.');
	} else {
		message('error','[MercadoPago] There has been an error while saving changes.');
	}

}

if(isset($_POST['submit_changes']) && check_value($_POST['submit_changes'])) {
	saveChanges();
}

loadModuleConfigs('donation.mercadopago');

$creditSystem = new CreditSystem();
?>
<form action="" method="post">
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
			<th>Status<br/><span>Habilitar o Deshabilitar el modulo de MercadoPago.</span></th>
			<td>
				<?php enabledisableCheckboxes('setting_2',mconfig('active'),'Enabled','Disabled'); ?>
			</td>
		</tr>
		<tr>
			<th>MercadoPago ACCESS TOKEN<br/><span>Ingresa el ACCESS TOKEN de tu cuenta de MercadoPago.<a href="https://www.mercadopago.com.ar/developers/panel/credentials" target="_blank">click here</a>.</span></span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_3" value="<?=mconfig('access_token')?>"/>
			</td>
		</tr>
		<tr>
			<th>MercadoPago Donations Title<br/><span>Titulo de la compra. Ejemplo: "Servicio de WCoinC".</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_4" value="<?=mconfig('mercadopago_title')?>"/>
			</td>
		</tr>
		<tr>
	<th>Terminos y Condiciones<br/><span>Texto que se mostrara al usuario antes de pagar.</span></th>
	<td>
		<textarea class="form-control" name="setting_32" rows="4"><?=mconfig('terms_and_conditions')?></textarea>
	</td>
</tr>

<tr>
	<th>Contacto / Soporte<br/><span>Email o enlace de contacto para dudas o reclamos.</span></th>
	<td>
		<input style="width: 100%;" type="text" name="setting_33" value="<?=mconfig('contact_info')?>" />
	</td>
</tr>

		<tr>
			<th>MercadoPago Donations Description<br/><span>Descripción de la compra. Ejemplo: "Servicio asociado al Mu Online".</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_5" value="<?=mconfig('mercadopago_description')?>"/>
			</td>
		</tr>
		<tr>
			<th>Currency Code<br/><span>Elije la moneda de tu País: </span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_6" value="<?=mconfig('mercadopago_currency')?>"/>
			</td>
		</tr>
		<tr>
		<tr>
			<th>Return/Cancel URL<br/><span>URL en donde el cliente va a volver si cancela o compra el producto.</span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_7" value="<?=mconfig('mercadopago_return_url')?>"/>
			</td>
		</tr>
		<tr>
			<th>IPN Notify URL<br/><span>URL en donde se encuentra alojada la API <br>(si se realiza una compra o se cancela la API noficara en la DB dicho proceso).<br/></span></th>
			<td>
				<input style="width: 100%;" class="input-xxlarge" type="text" name="setting_8" value="<?=mconfig('mercadopago_api_return_url')?>"/>
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
			<th>Configurar Pack <?=$i?><br/><span>Elije el precio, los créditos y la moneda digital asignados a este pack (0 = deshabilita el pack).</span></th>
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
						<?=$creditSystem->buildSelectInput('pack_'.$i.'_currency', $packCurrency, 'form-control pack-currency');?>
						<small class="text-muted">Usa las monedas configuradas en Credit Manager.</small>
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