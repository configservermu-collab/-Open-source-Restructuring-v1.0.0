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

if(!isLoggedIn()) redirect(1,'login');

// CSS integrado básico para UserCP
echo '<style>
/* UserCP - Estilos básicos universales */
.page-title, .page-title span { color: #FFFFFF !important; }
article div, article span, article p, section div, section span, section p { color: #FFFFFF !important; }
.form-control { color: #333 !important; }
.alert { color: #FFFFFF !important; }
.btn-primary { color: #ff0000; background-color: transparent; border-color: #ff0000; }
.btn-primary:hover { color: #fff !important; background-color: #140c17 !important; border-color: #140c17 !important; }
.col-xs-8 { background: rgba(0,0,0,0.3); border-radius: 8px; padding: 20px; margin-top: 20px; }
</style>';

echo '<div class="page-title"><span>'.lang('module_titles_txt_5',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	if(isset($_POST['webengineEmail_submit'])) {
		try {
			$Account = new Account();
			$Account->changeEmailAddress($_SESSION['userid'], $_POST['webengineEmail_newemail'], $_SERVER['REMOTE_ADDR']);
			if(mconfig('require_verification')) {
				message('success', lang('success_19',true));
			} else {
				message('success', lang('success_20',true));
			}
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="col-xs-8 col-xs-offset-2" style="margin-top:30px;">';
		echo '<form class="form-horizontal" action="" method="post">';
			echo '<div class="form-group">';
				echo '<label for="webengineEmail" class="col-sm-4 control-label">'.lang('changemail_txt_1',true).'</label>';
				echo '<div class="col-sm-8">';
					echo '<input type="text" class="form-control" id="webengineEmail" name="webengineEmail_newemail">';
				echo '</div>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<div class="col-sm-offset-4 col-sm-8">';
					echo '<button type="submit" name="webengineEmail_submit" value="submit" class="btn btn-primary">'.lang('changemail_txt_1',true).'</button>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}