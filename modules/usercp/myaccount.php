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
 * 
 */

if(!isLoggedIn()) redirect(1,'login');

// CSS integrado para UserCP - Funciona con todos los templates
echo '<style>
/* ======== USERCP - CSS INTEGRADO UNIVERSAL ======== */

/* Forzar color blanco en todos los módulos del UserCP */
.page-title, .page-title span,
.page-title h1, .page-title h2, .page-title h3,
div[class*="page"] {
    color: #FFFFFF !important;
}

/* Tablas específicas del UserCP */
.myaccount-table, .myaccount-table td, .myaccount-table th,
.general-table-ui, .general-table-ui td, .general-table-ui th,
.table-condensed, .table-condensed td, .table-condensed th {
    color: #FFFFFF !important;
}

/* Contenido de caracteres del usercp */
.myaccount-character-name,
.myaccount-character-block,
.myaccount-character-block-location,
.myaccount-character-block-level {
    color: #FFFFFF !important;
}

/* Divs de información del usercp */
div[class*="myaccount"], 
div[class*="usercp"],
.col-xs-8 div, .col-xs-12 div {
    color: #FFFFFF !important;
}

/* Texto dentro del contenido principal */
article div, article span, article p, article strong,
section div, section span, section p, section strong {
    color: #FFFFFF !important;
}

/* Override específico para elementos b que estaban tomando el color del body */
article b, section b, div b, p b, span b {
    color: #FFFFFF !important;
}

/* Asegurar que los labels mantengan su color */
.label-success { background-color: #5cb85c !important; color: #FFFFFF !important; }
.label-danger { background-color: #d9534f !important; color: #FFFFFF !important; }
.label-default { background-color: #777 !important; color: #FFFFFF !important; }
.label-info { background-color: #5bc0de !important; color: #FFFFFF !important; }
.label-warning { background-color: #f0ad4e !important; color: #000000 !important; }

/* Mensajes de error/éxito */
.alert, .alert-success, .alert-info, .alert-warning, .alert-danger,
.rmsg, .rmsg.error, .rmsg.warn {
    color: #FFFFFF !important;
}

/* Enlaces dentro del usercp */
article a, section a {
    color: #4fc3f7 !important;
}

article a:hover, section a:hover {
    color: #ffffff !important;
}

/* Status indicators */
.online-status-indicator {
    display: inline-block !important;
}

/* ======== CONTENEDOR PRINCIPAL MEJORADO ======== */
.myaccount-main-container {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 12px;
    padding: 0;
    margin-top: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}

/* Header principal de cuenta */
.myaccount-header-section {
    background: linear-gradient(135deg, rgba(20, 12, 23, 0.95) 0%, rgba(40, 30, 45, 0.95) 100%);
    padding: 25px;
    border-bottom: 2px solid rgba(79, 195, 247, 0.3);
    position: relative;
}

.myaccount-header-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, rgba(79, 195, 247, 0.8), transparent);
}

/* Grid de información de cuenta */
.myaccount-info-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
    align-items: start;
}

/* Información básica de la cuenta */
.myaccount-basic-info {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.myaccount-basic-info h3 {
    color: #4fc3f7 !important;
    font-size: 18px;
    margin-bottom: 15px;
    text-align: center;
    text-shadow: 0 0 10px rgba(79, 195, 247, 0.5);
    border-bottom: 1px solid rgba(79, 195, 247, 0.3);
    padding-bottom: 10px;
}

/* Tabla de información de cuenta estilizada */
.myaccount-table {
    width: 100% !important;
    background: transparent !important;
    border: none !important;
    border-collapse: separate !important;
    border-spacing: 0 8px !important;
}

.myaccount-table tr {
    background: rgba(255, 255, 255, 0.05) !important;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.myaccount-table tr:hover {
    background: rgba(79, 195, 247, 0.1) !important;
    transform: translateX(5px);
}

.myaccount-table td {
    color: #FFFFFF !important;
    padding: 12px 15px !important;
    border: none !important;
    vertical-align: middle !important;
}

.myaccount-table td:first-child {
    border-radius: 6px 0 0 6px;
    font-weight: bold;
    color: #b0b0b0 !important;
    width: 40%;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.myaccount-table td:last-child {
    border-radius: 0 6px 6px 0;
    color: #FFFFFF !important;
    font-weight: 500;
}

/* ======== SISTEMA DE COINS/CRÉDITOS ======== */
.myaccount-coins-panel {
    background: linear-gradient(135deg, rgba(255, 206, 102, 0.1) 0%, rgba(79, 195, 247, 0.1) 100%);
    border-radius: 12px;
    padding: 20px;
    border: 2px solid rgba(255, 206, 102, 0.3);
    position: relative;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(255, 206, 102, 0.1);
}

.myaccount-coins-panel::before {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 206, 102, 0.1) 0%, transparent 70%);
    animation: coinGlow 3s ease-in-out infinite;
    opacity: 0.7;
}

@keyframes coinGlow {
    0%, 100% { transform: rotate(0deg) scale(1); opacity: 0.3; }
    50% { transform: rotate(180deg) scale(1.1); opacity: 0.7; }
}

.coins-header {
    text-align: center;
    margin-bottom: 20px;
    position: relative;
    z-index: 2;
}

.coins-header h3 {
    color: #ffce66 !important;
    font-size: 20px;
    margin: 0;
    text-shadow: 0 0 15px rgba(255, 206, 102, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.coins-header i {
    font-size: 24px;
    color: #ffce66;
    text-shadow: 0 0 10px rgba(255, 206, 102, 0.8);
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-5px); }
    60% { transform: translateY(-3px); }
}

.coins-list {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
    z-index: 2;
}

.coin-item {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 206, 102, 0.2);
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.coin-item::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(to bottom, #ffce66, #4fc3f7);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.coin-item:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 206, 102, 0.6);
    box-shadow: 0 5px 15px rgba(255, 206, 102, 0.2);
}

.coin-item:hover::before {
    transform: scaleY(1);
}

.coin-name {
    color: #b0b0b0 !important;
    font-size: 13px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.coin-amount {
    color: #ffce66 !important;
    font-size: 16px;
    font-weight: bold;
    text-shadow: 0 0 8px rgba(255, 206, 102, 0.6);
    display: flex;
    align-items: center;
    gap: 5px;
}

.coin-amount::before {
    content: "";
    width: 16px;
    height: 16px;
    background: radial-gradient(circle, #ffce66 30%, transparent 70%);
    border-radius: 50%;
    display: inline-block;
    animation: coinSpin 2s linear infinite;
}

@keyframes coinSpin {
    0% { transform: rotateY(0deg); }
    100% { transform: rotateY(360deg); }
}

.coins-total {
    background: linear-gradient(135deg, rgba(255, 206, 102, 0.2) 0%, rgba(79, 195, 247, 0.2) 100%);
    border: 2px solid rgba(255, 206, 102, 0.5);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    margin-top: 15px;
    position: relative;
    z-index: 2;
}

.coins-total-label {
    color: #b0b0b0 !important;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 5px;
}

.coins-total-amount {
    color: #ffce66 !important;
    font-size: 28px;
    font-weight: bold;
    text-shadow: 0 0 15px rgba(255, 206, 102, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

/* ======== SISTEMA DE PERSONAJES ======== */
.myaccount-characters-section {
    background: rgba(0, 0, 0, 0.2);
    margin-top: 30px;
    padding: 25px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.myaccount-characters-container {
    background: rgba(0, 0, 0, 0.4);
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.myaccount-character-card {
    background: linear-gradient(135deg, rgba(20, 12, 23, 0.9) 0%, rgba(40, 30, 45, 0.9) 100%);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-height: 220px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.myaccount-character-card:hover {
    transform: translateY(-5px);
    border-color: rgba(79, 195, 247, 0.6);
    box-shadow: 0 8px 25px rgba(79, 195, 247, 0.2);
}

.myaccount-character-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.myaccount-character-name {
    font-size: 16px !important;
    font-weight: bold !important;
    color: #FFFFFF !important;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
    margin: 0 !important;
}

.myaccount-character-content {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 15px;
}

.myaccount-character-block {
    flex-shrink: 0;
    position: relative;
}

.myaccount-character-block img {
    width: 80px !important;
    height: 80px !important;
    border-radius: 50%;
    border: 3px solid rgba(79, 195, 247, 0.6);
    object-fit: cover;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(79, 195, 247, 0.3);
}

.myaccount-character-info {
    flex: 1;
    color: #FFFFFF !important;
}

.myaccount-character-level {
    font-size: 24px !important;
    font-weight: bold !important;
    color: #ffce66 !important;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
    margin-bottom: 8px;
    display: block;
}

.myaccount-character-level::before {
    content: "Lv. ";
    font-size: 14px;
    color: #ffffff;
    font-weight: normal;
}

.myaccount-character-location {
    color: #b0b0b0 !important;
    font-size: 13px !important;
    line-height: 1.4;
    margin-bottom: 8px;
}

.myaccount-character-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.character-action-btn {
    padding: 6px 12px;
    background: rgba(79, 195, 247, 0.2);
    border: 1px solid rgba(79, 195, 247, 0.4);
    border-radius: 6px;
    color: #4fc3f7 !important;
    font-size: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.character-action-btn:hover {
    background: rgba(79, 195, 247, 0.4);
    color: #ffffff !important;
    transform: translateY(-1px);
}

/* ======== HISTORIAL DE CONEXIONES ======== */
.myaccount-history-section {
    background: rgba(0, 0, 0, 0.2);
    margin-top: 30px;
    padding: 25px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Responsive */
@media (max-width: 992px) {
    .myaccount-info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .myaccount-coins-panel {
        order: -1;
    }
    
}

@media (max-width: 768px) {
    .myaccount-character-content {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .myaccount-character-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .myaccount-character-footer {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    

}
</style>';

echo '<div class="page-title"><span>'.lang('module_titles_txt_4').'</span></div>';

// module status
if(!mconfig('active')) throw new Exception(lang('error_47'));
	
// common class
$common = new common();

// Retrieve Account Information
$accountInfo = $common->accountInformation($_SESSION['userid']);
if(!is_array($accountInfo)) throw new Exception(lang('error_12'));

# account online status
$onlineStatus = ($common->accountOnline($_SESSION['username']) ? '<span class="label label-success">'.lang('myaccount_txt_9').'</span>' : '<span class="label label-danger">'.lang('myaccount_txt_10').'</span>');

# account status
$accountStatus = ($accountInfo[_CLMN_BLOCCODE_] == 1 ? '<span class="label label-danger">'.lang('myaccount_txt_8').'</span>' : '<span class="label label-default">'.lang('myaccount_txt_7').'</span>');

# characters info
$Character = new Character();
$AccountCharacters = $Character->AccountCharacter($_SESSION['username']);

// Obtener información de créditos para el panel lateral
$creditsList = array();
$totalCredits = 0;
try {
	$creditSystem = new CreditSystem();
	$creditCofigList = $creditSystem->showConfigs();
	if(is_array($creditCofigList)) {
		foreach($creditCofigList as $myCredits) {
			if(!$myCredits['config_display']) continue;
			
			$creditSystem->setConfigId($myCredits['config_id']);
			switch($myCredits['config_user_col_id']) {
				case 'userid':
					$creditSystem->setIdentifier($accountInfo[_CLMN_MEMBID_]);
					break;
				case 'username':
					$creditSystem->setIdentifier($accountInfo[_CLMN_USERNM_]);
					break;
				case 'email':
					$creditSystem->setIdentifier($accountInfo[_CLMN_EMAIL_]);
					break;
				default:
					continue 2;
			}
			
			$configCredits = $creditSystem->getCredits();
			$creditsList[] = array(
				'name' => $myCredits['config_title'],
				'amount' => $configCredits
			);
			$totalCredits += $configCredits;
		}
	}
} catch(Exception $ex) {}

// Contenedor principal mejorado
echo '<div class="myaccount-main-container">';
	echo '<div class="myaccount-header-section">';
		echo '<div class="myaccount-info-grid">';
			// Información básica de la cuenta
			echo '<div class="myaccount-basic-info">';
				echo '<h3><i class="fa fa-user"></i> Información de la Cuenta</h3>';
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
				echo '</table>';
			echo '</div>';
			
			// Panel lateral de créditos/coins
			echo '<div class="myaccount-coins-panel">';
				echo '<div class="coins-header">';
					echo '<h3><i class="fa fa-coins"></i> Créditos</h3>';
				echo '</div>';
				
				if(!empty($creditsList)) {
					echo '<ul class="coins-list">';
					foreach($creditsList as $credit) {
						echo '<li class="coin-item">';
							echo '<span class="coin-name">'.$credit['name'].'</span>';
							echo '<span class="coin-amount">'.number_format($credit['amount']).'</span>';
						echo '</li>';
					}
					echo '</ul>';
					
					echo '<div class="coins-total">';
						echo '<div class="coins-total-label">Total de Créditos</div>';
						echo '<div class="coins-total-amount">'.number_format($totalCredits).'</div>';
					echo '</div>';
				} else {
					echo '<div class="coin-item">';
						echo '<span class="coin-name">Sin créditos</span>';
						echo '<span class="coin-amount">0</span>';
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';

// Account Characters
echo '<div class="myaccount-characters-section">';
	echo '<div class="page-title"><span>'.lang('myaccount_txt_15').'</span></div>';
	if(is_array($AccountCharacters)) {
		$onlineCharacters = loadCache('online_characters.cache') ? loadCache('online_characters.cache') : array();
		echo '<div class="myaccount-characters-container">';
			echo '<div class="row">';
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
					
					echo '<div class="col-xs-12">';
						echo '<div class="myaccount-character-card">';
							echo '<div class="myaccount-character-header">';
								echo '<div class="myaccount-character-name">'.playerProfile($characterName).$characterOnlineStatus.'</div>';
							echo '</div>';
							
							echo '<div class="myaccount-character-content">';
								echo '<div class="myaccount-character-block">';
									echo '<a href="'.playerProfile($characterName, true).'" target="_blank">';
										echo '<img src="'.$characterClassAvatar.'" alt="'.$characterName.'" />';
									echo '</a>';
								echo '</div>';
								
								echo '<div class="myaccount-character-info">';
									echo '<span class="myaccount-character-level">'.$characterData[_CLMN_CHR_LVL_].'</span>';
									echo '<div class="myaccount-character-location">';
										echo '<strong>Ubicación:</strong><br>';
										echo returnMapName($characterData[_CLMN_CHR_MAP_]).'<br>';
										echo 'Coord: '.$characterData[_CLMN_CHR_MAP_X_].', '.$characterData[_CLMN_CHR_MAP_Y_];
									echo '</div>';
								echo '</div>';
							echo '</div>';
							
							echo '<div class="myaccount-character-footer">';
								echo '<div class="myaccount-character-actions">';
									echo '<a href="'.playerProfile($characterName, true).'" target="_blank" class="character-action-btn">Ver Perfil</a>';
								echo '</div>';
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	} else {
		message('warning', lang('error_46'));
	}
echo '</div>';


// Connection History (IGCN)
if(defined('_TBL_CH_')) {
	echo '<div class="myaccount-history-section">';
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
		} else {
			echo '<div class="alert alert-info">No hay historial de conexiones disponible.</div>';
		}
	echo '</div>';
}

?>
