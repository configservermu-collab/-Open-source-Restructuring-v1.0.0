<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.5
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(isLoggedIn()) redirect();

echo '<div class="page-title"><span>'.lang('module_titles_txt_1',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_17',true));
	
	// Generar captcha numérico para el formulario
	if(!isset($_SESSION['register_captcha'])) {
		$_SESSION['register_captcha'] = array(
			'num1' => rand(1, 10),
			'num2' => rand(1, 10)
		);
	}
	
	// Register Process
	if(isset($_POST['webengineRegister_submit'])) {
		try {
			$Account = new Account();
			
			// Validar aceptación de términos y condiciones
			if(!isset($_POST['webengineRegister_terms']) || $_POST['webengineRegister_terms'] !== 'accepted') {
				throw new Exception('Debes aceptar los términos y condiciones para continuar.');
			}
			
			// Validar captcha numérico
			if(!isset($_SESSION['register_captcha']) || !isset($_POST['webengineRegister_captcha'])) {
				throw new Exception('Error: Captcha no válido.');
			}
			
			$captchaAnswer = (int)$_POST['webengineRegister_captcha'];
			$expectedAnswer = $_SESSION['register_captcha']['num1'] + $_SESSION['register_captcha']['num2'];
			
			if($captchaAnswer !== $expectedAnswer) {
				// Regenerar captcha
				$_SESSION['register_captcha'] = array(
					'num1' => rand(1, 10),
					'num2' => rand(1, 10)
				);
				throw new Exception('Captcha incorrecto. Por favor, intenta nuevamente.');
			}
			
			// Limpiar captcha después de validación exitosa
			unset($_SESSION['register_captcha']);

			// ==========================================================
			// PASSWORD MAX LENGTH = 10 (solo letras o numeros)
			// ==========================================================
			$pwd  = isset($_POST['webengineRegister_pwd']) ? trim($_POST['webengineRegister_pwd']) : '';
			$pwdc = isset($_POST['webengineRegister_pwdc']) ? trim($_POST['webengineRegister_pwdc']) : '';

			// Max 10 caracteres
			if(strlen($pwd) > 10) {
				throw new Exception('La contraseña no puede superar los 10 caracteres.');
			}

			// Solo letras o numeros (sin simbolos, sin espacios)
			if(!preg_match('/^[a-zA-Z0-9]+$/', $pwd)) {
				throw new Exception('La contraseña solo puede contener letras y números (sin símbolos).');
			}

			// También validar confirmación con mismas reglas
			if(strlen($pwdc) > 10) {
				throw new Exception('La confirmación no puede superar los 10 caracteres.');
			}
			if(!preg_match('/^[a-zA-Z0-9]+$/', $pwdc)) {
				throw new Exception('La confirmación solo puede contener letras y números (sin símbolos).');
			}
			
			if(mconfig('register_enable_recaptcha')) {
				if(!@include_once(__PATH_CLASSES__ . 'recaptcha/autoload.php')) throw new Exception(lang('error_60'));
				$recaptcha = new \ReCaptcha\ReCaptcha(mconfig('register_recaptcha_secret_key'));
				
				$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
				if(!$resp->isSuccess()) {
					# recaptcha failed
					$errors = $resp->getErrorCodes();
					throw new Exception(lang('error_18',true));
				}
			}
			
			$Account->registerAccount($_POST['webengineRegister_user'], $pwd, $pwdc, $_POST['webengineRegister_email']);
			
			// Regenerar captcha después de registro exitoso
			$_SESSION['register_captcha'] = array(
				'num1' => rand(1, 10),
				'num2' => rand(1, 10)
			);
			
		} catch (Exception $ex) {
			// Regenerar captcha en caso de error
			$_SESSION['register_captcha'] = array(
				'num1' => rand(1, 10),
				'num2' => rand(1, 10)
			);
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-xs-8 col-xs-offset-2" style="margin-top:30px;">';
		echo '<form class="form-horizontal" action="" method="post">';
			echo '<div class="form-group">';
				echo '<label for="webengineRegistration1" class="col-sm-4 control-label">'.lang('register_txt_1',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="text" class="form-control" id="webengineRegistration1" name="webengineRegister_user" required>';
					echo '<span id="helpBlock" class="help-block">'.langf('register_txt_6', array(config('username_min_len', true), config('username_max_len', true))).'</span>';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="webengineRegistration2" class="col-sm-4 control-label">'.lang('register_txt_2',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="password" class="form-control" id="webengineRegistration2" name="webengineRegister_pwd" maxlength="10" pattern="[A-Za-z0-9]{1,10}" required>';
					echo '<span id="helpBlock" class="help-block">Contraseña: máximo 10 caracteres, solo letras y números.</span>';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="webengineRegistration3" class="col-sm-4 control-label">'.lang('register_txt_3',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="password" class="form-control" id="webengineRegistration3" name="webengineRegister_pwdc" maxlength="10" pattern="[A-Za-z0-9]{1,10}" required>';
					echo '<span id="helpBlock" class="help-block">'.lang('register_txt_8',true).'</span>';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="webengineRegistration4" class="col-sm-4 control-label">'.lang('register_txt_4',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="text" class="form-control" id="webengineRegistration4" name="webengineRegister_email" required>';
					echo '<span id="helpBlock" class="help-block">'.lang('register_txt_9',true).'</span>';
				echo '</div>';
			echo '</div>';
			
			if(mconfig('register_enable_recaptcha')) {
				# recaptcha v2
				echo '<div class="form-group">';
					echo '<div class="col-sm-offset-4 col-sm-8">';
						echo '<div class="g-recaptcha" data-sitekey="'.mconfig('register_recaptcha_site_key').'"></div>';
					echo '</div>';
				echo '</div>';
				echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
			}
			
			echo '<div class="form-group">';
				echo '<label for="webengineRegistrationCaptcha" class="col-sm-4 control-label">Verificación</label>';
				echo '<div class="col-sm-8">';
					echo '<div style="margin-bottom: 10px;">';
						echo '<span style="display: inline-block; background: #1a1d29; padding: 10px 20px; border-radius: 8px; font-size: 18px; font-weight: 600; color: #4fc3f7; border: 2px solid rgba(79, 195, 247, 0.3);">';
							echo $_SESSION['register_captcha']['num1'] . ' + ' . $_SESSION['register_captcha']['num2'] . ' = ?';
						echo '</span>';
					echo '</div>';
					echo '<input type="number" class="form-control" id="webengineRegistrationCaptcha" name="webengineRegister_captcha" placeholder="¿Cuánto es el resultado?" required style="max-width: 200px;">';
					echo '<span id="helpBlock" class="help-block">Por favor, resuelve la operación matemática.</span>';
				echo '</div>';
			echo '</div>';
			
			echo '<div class="form-group">';
				echo '<div class="col-sm-offset-4 col-sm-8">';
					echo '<div style="background: #1a1d29; padding: 15px; border-radius: 8px; border: 2px solid rgba(79, 195, 247, 0.3); margin-bottom: 15px;">';
						echo '<label style="margin-bottom: 0; font-weight: normal; cursor: pointer; display: flex; align-items: start; gap: 10px;">';
							echo '<input type="checkbox" name="webengineRegister_terms" value="accepted" required style="margin-top: 3px; width: 18px; height: 18px; cursor: pointer;">';
							echo '<span style="flex: 1;">'.langf('register_txt_10', array(__BASE_URL__.'tos')).'</span>';
						echo '</label>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<div class="col-sm-offset-4 col-sm-8">';
					echo '<button type="submit" name="webengineRegister_submit" value="submit" class="btn btn-primary">'.lang('register_txt_5',true).'</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';

} catch(Exception $ex) {
	message('error', $ex->getMessage());
}
