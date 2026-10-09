<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.4
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2022 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	echo '<div class="page-title"><span><i class="fa-solid fa-trophy"></i> Top MasterReset</span></div>';
	
	$Rankings = new Rankings();
	$Rankings->rankingsMenu();
	loadModuleConfigs('rankings');
	
	if(!mconfig('rankings_enable_gr')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_gr.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	$showPlayerCountry = mconfig('show_country_flags') ? true : false;
	$charactersCountry = loadCache('character_country.cache');
	if(!is_array($charactersCountry)) $showPlayerCountry = false;
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();
	
	if(mconfig('rankings_class_filter')) $Rankings->rankingsFilterMenu();
	
	echo '<table class="table dataTableChar dataTable no-footer general-rank text-center mt-2" style="white-space: nowrap;" id="RankingGrandResets" role="grid">';
	echo '<thead class="bg-primary text-white">';
	echo '<tr role="row">';
	if(mconfig('rankings_show_place_number')) {
		echo '<th class="text-center" style="width:60px;"><i class="fas fa-list-ol"></i> #</th>';
	}
	echo '<th class="text-start" style="width:100px;"><i class="fas fa-user"></i> '.lang('rankings_txt_10').'</th>';
	echo '<th class="text-center" style="width:100px;"><i class="fa-solid fa-retweet"></i> '.lang('rankings_txt_13').'</th>';
	echo '<th class="text-center" style="width:120px;"><i class="fa-solid fa-arrows-rotate"></i> '.lang('rankings_txt_21').'</th>';
	if(mconfig('show_location')) echo '<th class="text-center" style="width:120px;"><i class="fa-solid fa-location-dot"></i> '.lang('rankings_txt_34').'</th>';
	if($showPlayerCountry) echo '<th class="text-center" style="width:60px;"><i class="fa-solid fa-globe"></i></th>';
	echo '</tr>';
	echo '</thead>';
	$i = 0;
	echo '<tbody>';
	$displayedCount = 0;
	foreach($ranking_data as $rdata) {
		// Skip timestamp row
		if($displayedCount == 0 && !is_array($rdata)) {
			continue;
		}
		if(!is_array($rdata) || count($rdata) < 2) {
			continue;
		}
		$displayedCount++;
		
		$onlineStatus = mconfig('show_online_status') ? in_array($rdata[0], $onlineCharacters) ? 'aifos-char-online' : 'aifos-char-offline' : '';
		$characterIMG = '';
		if(function_exists('getPlayerClassAvatar')) {
			try {
				$characterIMG = getPlayerClassAvatar($rdata[3], true, true, 'rankings-class-image rounded-circle '.$onlineStatus.'');
			} catch(Exception $e) {
				$characterIMG = '';
			}
		}
		
		echo '<tr data-class-id="'.(isset($rdata[3]) ? $rdata[3] : '0').'">';
		if(mconfig('rankings_show_place_number')) {
			echo '<td class="text-center align-middle" style="font-weight:bold;"><i class="fas fa-medal text-warning"></i> '.$displayedCount.'</td>';
		}
		
		// Character name
		echo '<td class="text-start align-middle">';
		echo '<div class="d-flex align-items-center">';
		echo '<div class="me-2">'.$characterIMG.'</div>';
		echo '<div class="player-info">';
		echo '<div class="player-name">'.playerProfile($rdata[0]).'</div>';
		echo '</div></div></td>';
		
		echo '<td class="text-center align-middle" style="font-weight:bold;color:#6c757d;">'.number_format(isset($rdata[2]) ? $rdata[2] : 0).'</td>';
		echo '<td class="text-center align-middle" style="font-weight:bold;color:#dc3545;">'.number_format(isset($rdata[1]) ? $rdata[1] : 0).'</td>';
		if(mconfig('show_location')) echo '<td class="text-center align-middle" style="font-size:12px;">'.returnMapName(isset($rdata[4]) ? $rdata[4] : 0).'</td>';
		if($showPlayerCountry && isset($charactersCountry[$rdata[0]])) echo '<td class="text-center align-middle"><img src="'.getCountryFlag($charactersCountry[$rdata[0]]).'" height="16" class="rounded" /></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	if(mconfig('rankings_show_date')) {
		echo '<br><div class="alert alert-info m-t-10">';
		echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
		echo '</div>';
	}
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}