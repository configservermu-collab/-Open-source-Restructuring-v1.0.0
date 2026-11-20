<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2025 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

if(!isLoggedIn()) redirect(1,'login');

echo '<div class="page-title"><span>'.lang('module_titles_txt_4').'</span></div>';

// module status
if(!mconfig('active')) throw new Exception(lang('error_47'));
	
// common class
$common = new common();

// Retrieve Account Information
$accountInfo = $common->accountInformation($_SESSION['userid']);
if(!is_array($accountInfo)) throw new Exception(lang('error_12'));

$vipDaysRemaining = 0;
if(defined('_CLMN_VIP_TYPE_') && defined('_CLMN_VIP_DATE_')) {
	$vipType = isset($accountInfo[_CLMN_VIP_TYPE_]) ? (int)$accountInfo[_CLMN_VIP_TYPE_] : 0;
	$vipExpiration = !empty($accountInfo[_CLMN_VIP_DATE_]) ? strtotime($accountInfo[_CLMN_VIP_DATE_]) : false;
	if($vipType > 0 && $vipExpiration !== false && $vipExpiration > time()) {
		$vipDaysRemaining = (int)ceil(($vipExpiration - time()) / 86400);
	}
}

# account online status
$onlineStatus = ($common->accountOnline($_SESSION['username']) ? '<span class="label label-success">'.lang('myaccount_txt_9').'</span>' : '<span class="label label-danger">'.lang('myaccount_txt_10').'</span>');

# account status
$accountStatus = ($accountInfo[_CLMN_BLOCCODE_] == 1 ? '<span class="label label-danger">'.lang('myaccount_txt_8').'</span>' : '<span class="label label-default">'.lang('myaccount_txt_7').'</span>');

# characters info
$Character = new Character();
$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);

// Account Information
echo '<table class="table myaccount-table">';
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_1').'</td>';
		echo '<td>'.$accountStatus.'</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_2').'</td>';
		echo '<td>'.$accountInfo[_CLMN_USERNM_].'</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_3').'</td>';
		echo '<td>'.$accountInfo[_CLMN_EMAIL_].' <a href="'.__BASE_URL__.'usercp/myemail/" class="btn btn-xs btn-primary pull-right">'.lang('myaccount_txt_6').'</a></td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_4').'</td>';
		echo '<td>&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226; <a href="'.__BASE_URL__.'usercp/mypassword/" class="btn btn-xs btn-primary pull-right">'.lang('myaccount_txt_6').'</a></td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td>'.lang('myaccount_txt_5').'</td>';
		echo '<td>'.$onlineStatus.'</td>';
	echo '</tr>';

	echo '<tr>';
		echo '<td>Días VIP</td>';
		echo '<td>'.($vipDaysRemaining > 0 ? number_format($vipDaysRemaining).' días restantes' : 'Sin VIP').'</td>';
	echo '</tr>';
	
	try {
		$creditSystem = new CreditSystem();
		$creditCofigList = $creditSystem->showConfigs();
		if(is_array($creditCofigList)) {
			foreach($creditCofigList as $myCredits) {
				if(!$myCredits['config_display']) continue;
				
				$creditSystem->setConfigId($myCredits['config_id']);
				switch($myCredits['config_user_col_id']) {
					case 'userid':
						$creditIdentifiers = array($accountInfo[_CLMN_MEMBID_]);
						break;
					case 'username':
						$creditIdentifiers = array($accountInfo[_CLMN_USERNM_]);
						break;
					case 'email':
						$creditIdentifiers = array($accountInfo[_CLMN_EMAIL_]);
						break;
					case 'character':
						$creditIdentifiers = is_array($AccountCharacters) ? $AccountCharacters : array();
						break;
					default:
						continue 2;
				}

				foreach($creditIdentifiers as $creditIdentifier) {
					$creditSystem->setIdentifier($creditIdentifier);
					$configCredits = $creditSystem->getCredits();
					$coinTitle = 'Coins - '.htmlspecialchars($myCredits['config_title'], ENT_QUOTES, 'UTF-8');
					if($myCredits['config_user_col_id'] == 'character') {
						$coinTitle .= ' ('.htmlspecialchars($creditIdentifier, ENT_QUOTES, 'UTF-8').')';
					}

					echo '<tr>';
						echo '<td>'.$coinTitle.'</td>';
						echo '<td>'.number_format($configCredits).'</td>';
					echo '</tr>';
				}
			}
		}
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
echo '</table>';

// Account Characters
echo '<div class="page-title"><span>'.lang('myaccount_txt_15').'</span></div>';
if(is_array($AccountCharacters)) {
	$onlineCharacters = loadCache('online_characters.cache') ? loadCache('online_characters.cache') : array();
	echo '<div class="row text-center">';
		foreach($AccountCharacters as $characterName) {
			$characterData = $Character->CharacterData($characterName);
			if(!is_array($characterData)) continue;
			
			if(defined('_TBL_MASTERLVL_')) {
				if(_TBL_MASTERLVL_ != _TBL_CHR_) {
					$characterMLData = $Character->getMasterLevelInfo($characterName);
					if(is_array($characterMLData)) {
						$characterData[_CLMN_CHR_LVL_] += $characterMLData[_CLMN_ML_LVL_];
					}
				} else {
					$characterData[_CLMN_CHR_LVL_] += $characterData[_CLMN_ML_LVL_];
				}
			}
			
			$characterClassAvatar = getPlayerClassAvatar($characterData[_CLMN_CHR_CLASS_], false);
			$characterOnlineStatus = in_array($characterName, $onlineCharacters) ? '<img src="'.__PATH_ONLINE_STATUS__.'" class="online-status-indicator"/>' : '<img src="'.__PATH_OFFLINE_STATUS__.'" class="online-status-indicator"/>';
			echo '<div class="col-xs-3">';
				echo '<div class="myaccount-character-name">'.playerProfile($characterName).$characterOnlineStatus.'</div>';
				echo '<div class="myaccount-character-block">';
					echo '<a href="'.playerProfile($characterName, true).'" target="_blank">';
						echo '<img src="'.$characterClassAvatar.'" />';
					echo '</a>';
				echo '</div>';
				echo '<div class="myaccount-character-block-location">'.returnMapName($characterData[_CLMN_CHR_MAP_]).'<br />'.$characterData[_CLMN_CHR_MAP_X_].', '.$characterData[_CLMN_CHR_MAP_Y_].'</div>';
				echo '<span class="myaccount-character-block-level">'.$characterData[_CLMN_CHR_LVL_].'</span>';
			echo '</div>';
		}
	echo '</div>';
} else {
	message('warning', lang('error_46'));
}

// Connection History (IGCN)
if(defined('_TBL_CH_')) {
	echo '<div class="page-title"><span>'.lang('myaccount_txt_16').'</span></div>';
	$me = Connection::Database('Me_MuOnline');
	$connectionHistory = $me->query_fetch("SELECT TOP 10 * FROM "._TBL_CH_." WHERE "._CLMN_CH_ACCID_." = ? ORDER BY "._CLMN_CH_ID_." DESC", array($_SESSION['username']));
	if(is_array($connectionHistory)) {
		echo '<table class="table table-condensed general-table-ui">';
			echo '<tr>';
				echo '<td>'.lang('myaccount_txt_13').'</td>';
				echo '<td>'.lang('myaccount_txt_17').'</td>';
				echo '<td>'.lang('myaccount_txt_18').'</td>';
				echo '<td>'.lang('myaccount_txt_19').'</td>';
			echo '</tr>';
			foreach($connectionHistory as $row) {
				echo '<tr>';
					echo '<td>'.$row[_CLMN_CH_DATE_].'</td>';
					echo '<td>'.$row[_CLMN_CH_SRVNM_].'</td>';
					echo '<td>'.$row[_CLMN_CH_IP_].'</td>';
					echo '<td>'.$row[_CLMN_CH_STATE_].'</td>';
				echo '</tr>';
			}
		echo '</table>';
	}
}
