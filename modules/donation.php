<?php
(!isLoggedIn()) ? redirect(1,'login') : null;

try {

	if(!mconfig('active')) throw new Exception(lang('error_47',true));

	echo '<div class="page-title"><span>'.lang('module_titles_txt_11',true).'</span></div>';

	// --- Estilo para centrar botones de pago ---
	echo '
	<style>
		.payment-select {
			display:flex;
			justify-content:center;
			align-items:center;
			gap:30px;                /* espacio entre iconos */
			margin-top:25px;
			flex-wrap:wrap;
			text-align:center;
		}
		.payment-select img {
			width:160px;             /* ajustá el tamaño si querés más grande/chico */
			transition:.2s;
		}
		.payment-select img:hover {
			transform:scale(1.05);
			filter:brightness(1.15);
		}
	</style>
	';

	echo '<div class="payment-select">';

		// Verificar si MercadoPago está activo
		loadModuleConfigs('donation.mercadopago');
		$mercadopagoActive = mconfig('active');
		if($mercadopagoActive) {
			echo '<a href="'.__BASE_URL__.'donation/mercadopago/" class="thumbnail" style="border:none;background:none;">';
				echo '<img src="'.__PATH_TEMPLATE_IMG__.'donation/mercadopago.png" alt="MercadoPago">';
			echo '</a>';
		}

		// Verificar si PayPal está activo
		loadModuleConfigs('donation.paypal');
		$paypalActive = mconfig('active');
		if($paypalActive) {
			echo '<a href="'.__BASE_URL__.'donation/paypal/" class="thumbnail" style="border:none;background:none;">';
				echo '<img src="'.__PATH_TEMPLATE_IMG__.'donation/paypal.png" alt="PayPal">';
			echo '</a>';
		}

		// Transferencia Bancaria (siempre disponible)
		echo '<a href="'.__BASE_URL__.'donation/transferencia/" class="thumbnail" style="border:none;background:none;">';
			echo '<img src="https://i.imgur.com/9mZPlSd.jpeg" alt="Transferencia Bancaria">';
		echo '</a>';

	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}