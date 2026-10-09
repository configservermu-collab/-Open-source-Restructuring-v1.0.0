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

try {
    echo '<div class="page-title"><span>Top Duelos</span></div>';
	
	$Rankings = new Rankings();
	$Rankings->rankingsMenu();
	loadModuleConfigs('rankings');
	
	if(!mconfig('rankings_enable_duelos')) throw new Exception(lang('error_44',true));
	if(!mconfig('active')) throw new Exception(lang('error_44',true));
	
	$ranking_data = LoadCacheData('rankings_duelos.cache');
	if(!is_array($ranking_data)) throw new Exception(lang('error_58',true));
	
	$showPlayerCountry = mconfig('show_country_flags') ? true : false;
	$charactersCountry = loadCache('character_country.cache');
	if(!is_array($charactersCountry)) $showPlayerCountry = false;
	
	if(mconfig('show_online_status')) $onlineCharacters = loadCache('online_characters.cache');
	if(!is_array($onlineCharacters)) $onlineCharacters = array();
		
	echo '<table class="table dataTableChar dataTable no-footer general-rank text-center mt-2" style="white-space: nowrap;" id="RankingGeneral" role="grid">';
	echo '<thead class="bg-primary text-white">';
	echo '<tr role="row">';
	if(mconfig('rankings_show_place_number')) {
		echo '<td style="font-weight:bold;"></td>';
	}
	echo '<td class="text-start"><i class="fas fa-user-alt"></i> '.lang('rankings_txt_10').'</td>';
	echo '<td><i class="fas fa-play-circle"></i> Jugadas</td>';
	echo '<td><i class="fa-solid fa-check"></i> Ganadas</td>';
	echo '<td><i class="fa-solid fa-xmark"></i> Perdidas</td>';
	echo '<td><i class="fas fa-percent"></i> Win Rate</td>';
	if($showPlayerCountry) echo '<td><i class="fa fa-globe"></i></td>';
	echo '</tr>';
	echo '</thead>';
	$i = 0;
	echo '<tbody>';
	foreach($ranking_data as $rdata) {
		$onlineStatus = mconfig('show_online_status') ? in_array($rdata[1], $onlineCharacters) ? 'aifos-char-online' : 'aifos-char-offline' : '';
		$characterIMG = getPlayerClassAvatar($rdata[0], true, true, 'rankings-class-image rounded-circle '.$onlineStatus.'');
		
		$Ganadas 		= 		$rdata[2];
		$Perdidas 		= 		$rdata[3];
		$Jugadas        =       $rdata[2] + $rdata[3];

		$TotalPartidas	=		number_format((float)$Ganadas) + number_format((float)$Perdidas);
		if($Jugadas > 0){
			$WR1 = number_format((float)$Ganadas) / number_format((float)$TotalPartidas) * 100.0;
		}
		if($WR1 != null){
    		$WR = number_format((float)$WR1, 2, '.', '');
		}else{
			$WR = "0.00";
		}

		if($WR < 50){
			$WinRate = "<span style='color:#dc3545;font-weight: 600;'>".$WR."%</span>";
		}
		elseif($WR >= 50){
			$WinRate = "<span style='color:#28a745;font-weight: 600;'>".$WR."%</span>";
		}

		if($i>=1) {
			echo '<tr data-class-id='.$rdata[1].'>';
			if(mconfig('rankings_show_place_number')) {
				echo '<td class="rankings-table-place"><i class="fas fa-medal"></i> '.$i.'</td>';
			}
			echo '<td class="text-start">'.$characterIMG.' '.playerProfile($rdata[1]).'</td>';
			echo '<td style="font-weight: 700;text-align: center;" class="text-primary">'.number_format($Jugadas).'</td>';
			echo '<td style="font-weight: 700;text-align: center;" class="text-success">'.number_format($rdata[2]).'</td>';
			echo '<td style="font-weight: 700;text-align: center;" class="text-danger">'.number_format($rdata[3]).'</td>';
			echo '<td style="font-weight: 700;text-align: center;" class="text-danger">'.$WinRate.'</td>';
			if($showPlayerCountry) echo '<td align="center"><img src="'.getCountryFlag($charactersCountry[$rdata[1]]).'" height="15"/></td>';
			echo '</tr>';
		}
		$i++;
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