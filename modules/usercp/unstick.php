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
.page-title, .page-title span { color: #FFFFFF !important; }
article div, article span, article p, section div, section span, section p { color: #FFFFFF !important; }
.form-control { color: #333 !important; }
.alert { color: #FFFFFF !important; }
.btn-primary { color: #ff0000; background-color: transparent; border-color: #ff0000; }
.btn-primary:hover { color: #fff !important; background-color: #140c17 !important; border-color: #140c17 !important; }
table.table, .table td, .table th { color: #FFFFFF !important; }
.col-xs-8, .col-xs-12 { background: rgba(0,0,0,0.3); border-radius: 8px; padding: 20px; margin-top: 20px; }
</style>';

echo '<div class="page-title"><span>'.lang('module_titles_txt_16',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$Character = new Character();
	$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
	if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));
	
	if(isset($_POST['submit'])) {
		try {
			$Character->setUserid($_SESSION['userid']);
			$Character->setUsername($_SESSION['username']);
			$Character->setCharacter($_POST['character']);
			$Character->CharacterUnstick();
		} catch(Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<table class="table general-table-ui">';
		echo '<tr>';
			echo '<td></td>';
			echo '<td>'.lang('unstickcharacter_txt_1',true).'</td>';
			echo '<td>'.lang('unstickcharacter_txt_2',true).'</td>';
			echo '<td></td>';
		echo '</tr>';
		
		foreach($AccountCharacters as $thisCharacter) {
			$characterData = $Character->CharacterData($thisCharacter);
			$characterIMG = $Character->GenerateCharacterClassAvatar($characterData[_CLMN_CHR_CLASS_]);
			
			echo '<form action="" method="post">';
				echo '<input type="hidden" name="character" value="'.$characterData[_CLMN_CHR_NAME_].'"/>';
				echo '<tr>';
					echo '<td>'.$characterIMG.'</td>';
					echo '<td>'.$characterData[_CLMN_CHR_NAME_].'</td>';
					echo '<td>'.number_format($characterData[_CLMN_CHR_ZEN_]).'</td>';
					echo '<td><button name="submit" value="submit" class="btn btn-primary">'.lang('unstickcharacter_txt_3',true).'</button></td>';
				echo '</tr>';
			echo '</form>';
		}
	echo '</table>';
	
	echo '<div class="module-requirements text-center">';
		if(mconfig('zen_cost') > 0) echo '<p>'.langf('unstickcharacter_txt_4', array(number_format(mconfig('zen_cost')))).'</p>';
	echo '</div>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}