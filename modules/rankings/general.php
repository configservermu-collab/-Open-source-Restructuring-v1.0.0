<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2019 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */
 
$this->dB = Connection::Database('MuOnline');

try {
	echo '<h3 class="nk-decorated-h-2"><div class="page-title">Top General</div></h3>';
	
	$Rankings = new Rankings();
    
	$ranking_data = LoadCacheData('rankings_general.cache');
	
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	$showPlayerCountry = mconfig('show_country_flags') ? true : false;
	$showGens = mconfig('show_general_gens') ? true : false;
	$showResets = mconfig('show_general_resets') ? true : false;
	$showMResets = mconfig('show_general_mresets') ? true : false;
	$showLevel = mconfig('show_general_level') ? true : false;
	$showMasterLevel = mconfig('show_general_masterlevel') ? true : false;
	$showGuild = mconfig('show_general_guild') ? true : false;
	$ShowMapa = mconfig('show_location') ? true : false;

	$charactersCountry = loadCache('character_country.cache');
	if(!is_array($charactersCountry)) $showPlayerCountry = false;
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();

	$Rankings->rankingsMenu();

	// Add custom CSS for better table styling
	echo '<style>
		#RankingGeneral td {
			vertical-align: middle !important;
			padding: 8px 12px;
		}
		#RankingGeneral .player-info {
			min-width: 0;
		}
		#RankingGeneral .player-name {
			font-weight: 500;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		#RankingGeneral .guild-container {
			max-width: 100%;
			display: inline-flex !important;
			align-items: center !important;
		}
		#RankingGeneral .guild-logo {
			display: flex;
			align-items: center;
			flex-shrink: 0;
		}
		#RankingGeneral .guild-logo img {
			display: block;
			vertical-align: middle;
		}
		#RankingGeneral .guild-info {
			min-width: 0;
			text-align: left;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}
		#RankingGeneral .guild-name {
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		#RankingGeneral .rankings-class-image {
			width: 32px;
			height: 32px;
			object-fit: cover;
		}
		#RankingGeneral .gens-info {
			margin-top: 2px;
		}
		#RankingGeneral .rankings-gens-img {
			height: 16px;
			width: auto;
		}
	</style>';

	echo '<table class="table dataTableChar dataTable no-footer general-rank text-center mt-2" style="white-space: nowrap;" id="RankingGeneral" role="grid">';
	echo '<thead class="bg-primary text-white">';
	echo '		<tr role="row">';
	echo '		<th class="text-center" style="width:60px;"><i class="fas fa-list-ol"></i> #</th>';
	echo '		<th class="text-start" style="min-width:200px;"><i class="far fa-user"></i> Personaje</th>';
	if($showMResets) echo '		<th class="text-center" style="width:100px;"><i class="fas fa-redo-alt"></i> GResets</th>';
	if($showResets) echo '		<th class="text-center" style="width:100px;"><i class="fas fa-redo-alt"></i> Resets</th>';
	if($showLevel) echo '		<th class="text-center" style="width:80px;"><i class="fas fa-angle-up"></i> Level</th>';
	echo '		<th class="text-center" style="width:80px;"><i class="fas fa-angle-double-up"></i> MLevel</th>';
	if($showGuild) echo '		<th class="text-center" style="min-width:150px;"><i class="fas fa-shield-alt"></i> Guild</th>';
	if($ShowMapa) echo '		<th class="text-center" style="width:120px;"><i class="fas fa-map-marker-alt"></i> Mapa</th>';
	if($showPlayerCountry) echo '		<th class="text-center" style="width:60px;"><i class="fa fa-globe"></i></th>';
			echo '</tr>
			</thead>';
	$i = 0;
	echo '<tbody>';
	$displayedCount = 0;
	foreach($ranking_data as $rdata) {
		// Skip if this is the timestamp row
		if($displayedCount == 0 && !is_array($rdata)) {
			continue;
		}
		
		// Safety check for data integrity
		if(!is_array($rdata) || count($rdata) < 4) {
			continue;
		}
		
		$displayedCount++;
		
		$GuildMember = isset($rdata[6]) ? $rdata[6] : null;
 
	    if($GuildMember == 128){
	        $G_Status = '<span style="color:blue;font-weight:bold;">Guild Master</span>';
	    }else if($GuildMember == 64){
	        $G_Status = '<span style="color:green;font-weight:bold;">Assistant Master</span>';
	    }else if($GuildMember == 32){
	        $G_Status = '<span style="color:red;font-weight:bold;">Battle Master</span>';
	    }else if($GuildMember == 0){
	        $G_Status = '<span>Miembro</span>';
	    }else{
	        $G_Status = '<span>-</span>';
	    }
	    
		$onlineStatus = mconfig('show_online_status') ? (in_array($rdata[0], $onlineCharacters) ? 'aifos-char-online' : 'aifos-char-offline') : '';
		
		// Safe character image generation with fallback
		$characterIMG = '';
		if(function_exists('getPlayerClassAvatar')) {
			try {
				$characterIMG = getPlayerClassAvatar($rdata[3], true, true, 'rankings-class-image rounded-circle '.$onlineStatus.'');
			} catch(Exception $e) {
				$characterIMG = '';
			}
		}
		
		// Gens display with fallback
		$gensType = '';
		if($showGens && isset($rdata[8]) && !empty($rdata[8])) {
			if($rdata[8] == 1) {
				$gensType = '<img class="rankings-gens-img" src="'.__PATH_TEMPLATE_IMG__.'gens_1.png" title="'.lang('rankings_txt_26',true).'" height="20" alt="'.lang('rankings_txt_26',true).'"/>';
			} else {
				$gensType = '<img class="rankings-gens-img" src="'.__PATH_TEMPLATE_IMG__.'gens_2.png" title="'.lang('rankings_txt_27',true).'" height="20" alt="'.lang('rankings_txt_27',true).'"/>';
			}
		}
		
		echo '<tr data-class-id="'.(isset($rdata[3]) ? $rdata[3] : '0').'">';
	
		echo '<td class="text-center align-middle" style="font-weight:bold;"><i class="fas fa-medal text-warning"></i> '.$displayedCount.'</td>';
			
		// Character name with optional gens display
		echo '<td class="text-start align-middle">';
		echo '<div class="d-flex align-items-center">';
		echo '<div class="me-2">'.$characterIMG.'</div>';
		echo '<div class="player-info">';
		echo '<div class="player-name">'.playerProfile($rdata[0]).'</div>';
		if($showGens && !empty($gensType)) {
			echo '<div class="gens-info">'.$gensType.'</div>';
		}
		echo '</div></div></td>';
		
		if($showMResets) echo '<td class="text-center align-middle" style="font-weight:bold;color:#008aca;">'.number_format((float)(isset($rdata[9]) ? $rdata[9] : 0)).'</td>';
		if($showResets) echo '<td class="text-center align-middle" style="font-weight:bold;color:#28a745;">'.number_format((float)(isset($rdata[1]) ? $rdata[1] : 0)).'</td>';
		if($showLevel) echo '<td class="text-center align-middle" style="font-weight:bold;color:#6c757d;">'.number_format((float)(isset($rdata[2]) ? $rdata[2] : 0)).'</td>';
		echo '<td class="text-center align-middle" style="font-weight:bold;color:#dc3545;">'.number_format((float)(isset($rdata[7]) ? $rdata[7] : 0)).'</td>';
		if($showGuild){ 
			if(!empty($rdata[4])){
				echo '<td class="text-center align-middle">';
				echo '<div class="guild-container d-flex align-items-center justify-content-center">';
				echo '<div class="guild-logo me-2">'.returnGuildLogo(isset($rdata[5]) ? $rdata[5] : '', 24).'</div>';
				echo '<div class="guild-info">';
				echo '<div class="guild-name" style="font-size:12px;font-weight:bold;">'.$rdata[4].'</div>';
				echo '<div class="guild-status" style="font-size:10px;opacity:0.8;">'.$G_Status.'</div>';
				echo '</div></div></td>';
			} else {
				echo '<td class="text-center align-middle">';
				echo '<div class="no-guild" style="font-size:12px;color:#6c757d;">';
				echo '<i class="fas fa-minus-circle"></i> Sin Guild</div></td>';
			}
		}
		if($ShowMapa) echo '<td class="text-center align-middle" style="font-weight:bold;font-size:12px;color:#28a745;">'.returnMapName(isset($rdata[10]) ? $rdata[10] : 0).'</td>';
        if($showPlayerCountry && isset($charactersCountry[$rdata[0]])) echo '<td class="text-center align-middle"><img src="'.getCountryFlag($charactersCountry[$rdata[0]]).'" height="16" class="rounded"/></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';

		echo '<br><div class="alert alert-info m-t-10">';
		echo ''.lang('rankings_txt_20',true).' ' . date("m/d/Y - h:i A",$ranking_data[0][0]);
		echo '</div>';

	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}