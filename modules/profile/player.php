<?php
/**
 * WebEngine CMS - Player Profile
 * Integrado con switches configurables en rankings.xml
 * 
 * @version 1.2.6 modded
 * @mod ConfigServerMU.net
 * @copyright (c) 2013-2026
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 */

echo '<div class="page-title"><span>'.lang('profiles_txt_2',true).'</span></div>';

// Cargamos configuración de switches desde rankings.xml
$cfg = simplexml_load_file(__PATH_MODULE_CONFIGS__ . 'rankings.xml');

if($cfg->active == 1) {
	if(isset($_GET['req'])) {
		try {
			$weProfiles = new weProfiles();
			$weProfiles->setType("player");
			$weProfiles->setRequest($_GET['req']);
			$cData = $weProfiles->data();
			
			$onlineStatus = 0;
			$onlineCharactersCache = loadCache('online_characters.cache');
			if(is_array($onlineCharactersCache) && in_array($cData[1], $onlineCharactersCache)) {
				$onlineStatus = 1;
			}
			
			echo '<div class="profiles_player_card '.$custom['character_class'][$cData[2]][1].'">';
				echo '<div class="profiles_player_content">';
					echo '<table class="profiles_player_table">';
						echo '<tr><td class="cname">'.$cData[1].'</td></tr>';
						echo '<tr><td class="cclass">'.$custom['character_class'][$cData[2]][0].'</td></tr>';
					echo '</table>';
					
					# tabla de información
					echo '<table class="profiles_player_table profiles_player_table_info">';

						if($cfg->show_profile_level == 1) {
							echo '<tr><td>'.lang('profiles_txt_7',true).'</td><td>'.number_format($cData[3]).'</td></tr>';
						}

						if($cfg->show_profile_master == 1) {
							echo '<tr><td>'.lang('profiles_txt_20',true).'</td><td>'.number_format($cData[14]).'</td></tr>';
						}

						if($cfg->show_profile_resets == 1 && check_value($cData[4])) {
							echo '<tr><td>'.lang('profiles_txt_8',true).'</td><td>'.number_format($cData[4]).'</td></tr>';
						}

						if($cfg->show_profile_gr == 1 && check_value($cData[11])) {
							echo '<tr><td>'.lang('profiles_txt_9',true).'</td><td>'.number_format($cData[11]).'</td></tr>';
						}

						if($cfg->show_profile_stats == 1) {
							echo '<tr><td>'.lang('profiles_txt_10',true).'</td><td>'.number_format($cData[5]).'</td></tr>';
							echo '<tr><td>'.lang('profiles_txt_11',true).'</td><td>'.number_format($cData[6]).'</td></tr>';
							echo '<tr><td>'.lang('profiles_txt_12',true).'</td><td>'.number_format($cData[7]).'</td></tr>';
							echo '<tr><td>'.lang('profiles_txt_13',true).'</td><td>'.number_format($cData[8]).'</td></tr>';
						}

						if($cfg->show_profile_cmd == 1 && $custom['character_class'][$cData[2]]['base_stats']['cmd'] > 0) {
							echo '<tr><td>'.lang('profiles_txt_14',true).'</td><td>'.number_format($cData[9]).'</td></tr>';
						}

						if($cfg->show_profile_kills == 1) {
							echo '<tr><td>'.lang('profiles_txt_15',true).'</td><td>'.number_format($cData[10]).'</td></tr>';
						}

						if($cfg->show_profile_guild == 1 && check_value($cData[12])) {
							echo '<tr><td>'.lang('profiles_txt_16',true).'</td><td>'.guildProfile($cData[12]).'</td></tr>';
						}

						if($cfg->show_profile_status == 1) {
							echo '<tr><td>'.lang('profiles_txt_17',true).'</td>';
							echo '<td class="'.($onlineStatus ? 'isonline' : 'isoffline').'">'.lang($onlineStatus ? 'profiles_txt_18' : 'profiles_txt_19',true).'</td></tr>';
						}

					echo '</table>';
				echo '</div>';
			echo '</div>';

		} catch(Exception $e) {
			message('error', $e->getMessage());
		}
	} else {
		message('error', lang('error_25',true));
	}
} else {
	message('error', lang('error_47',true));
}
