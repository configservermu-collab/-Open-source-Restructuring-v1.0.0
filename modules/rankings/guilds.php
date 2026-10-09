<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.2
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

try {
	
	echo '<div class="page-title"><span><i class="fa-solid fa-trophy"></i> Top Guild</span></div>';
	
	$Rankings = new Rankings();
	$Rankings->rankingsMenu();
	loadModuleConfigs('rankings');
	
	if(!mconfig('rankings_enable_guilds')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_guilds.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();
	
	echo '<table class="table dataTableChar dataTable no-footer general-rank text-center mt-2" style="white-space: nowrap;" id="RankingGuilds" role="grid">';
	echo '<thead class="bg-primary text-white">';
	echo '<tr role="row">';
	if(mconfig('rankings_show_place_number')) {
		echo '<th class="text-center" style="width:60px;"><i class="fas fa-list-ol"></i> #</th>';
	}
	echo '<th class="text-start" style="width:100px;"><i class="fa-solid fa-shield-halved"></i> '.lang('rankings_txt_17',true).'</th>';
	echo '<th class="text-center" style="width:80px;"><i class="fa-solid fa-shield-virus"></i> '.lang('rankings_txt_28',true).'</th>';
	echo '<th class="text-center" style="min-width:150px;"><i class="fa-solid fa-user-shield"></i> '.lang('rankings_txt_18',true).'</th>';
	echo '<th class="text-center" style="width:120px;"><i class="fa-solid fa-bolt-lightning"></i> '.lang('rankings_txt_19',true).'</th>';
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
		
		$onlineStatus = mconfig('show_online_status') ? in_array($rdata[1], $onlineCharacters) ? '<img src="'.__PATH_ONLINE_STATUS__.'" class="online-status-indicator"/>' : '<img src="'.__PATH_OFFLINE_STATUS__.'" class="online-status-indicator"/>' : '';
		$multiplier = mconfig('guild_score_formula') == 1 ? 1 : mconfig('guild_score_multiplier');
		
		echo '<tr class="align-middle">';
		if(mconfig('rankings_show_place_number')) {
			echo '<td class="text-center align-middle" style="font-weight:bold;"><i class="fas fa-medal text-warning"></i> '.$displayedCount.'</td>';
		}
		echo '<td class="text-start align-middle">'.guildProfile($rdata[0]).'</td>';
		echo '<td class="text-center align-middle">'.returnGuildLogo(isset($rdata[3]) ? $rdata[3] : '', 40).'</td>';
		echo '<td class="text-center align-middle">'.playerProfile($rdata[1]).' '.$onlineStatus.'</td>';
		echo '<td class="text-center align-middle" style="font-weight:bold;color:#6c757d;">'.number_format(floor((isset($rdata[2]) ? $rdata[2] : 0)*$multiplier)).'</td>';
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