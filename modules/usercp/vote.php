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

echo '<div class="page-title"><span>'.lang('module_titles_txt_7',true).'</span></div>';

try {
	
	if(!mconfig('active')) throw new Exception(lang('error_47',true));
	
	$vote = new Vote();
	
	if(isset($_POST['submit'])) {
		try {
			$vote->setUserid($_SESSION['userid']);
			$vote->setIp($_SERVER['REMOTE_ADDR']);
			$vote->setVotesiteId($_POST['voting_site_id']);
			$vote->vote();
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<table class="table general-table-ui">';
		echo '<tr>';
			echo '<td>'.lang('vfc_txt_1',true).'</td>';
			echo '<td>'.lang('vfc_txt_2',true).'</td>';
			echo '<td></td>';
		echo '</tr>';

		$vote_sites = $vote->retrieveVotesites();
		if(is_array($vote_sites)) {
			foreach($vote_sites as $thisVotesite) {
				echo '<form action="" method="post">';
					echo '<input type="hidden" name="voting_site_id" value="'.$thisVotesite['votesite_id'].'"/>';
					echo '<tr>';
						echo '<td>'.$thisVotesite['votesite_title'].'</td>';
						echo '<td>'.$thisVotesite['votesite_reward'].'</td>';
						echo '<td><button name="submit" value="submit" class="btn btn-primary">'.lang('vfc_txt_3',true).'</button></td>';
					echo '</tr>';
				echo '</form>';
			}
		}
	echo '</table>';
	
} catch(Exception $ex) {
	message('error', $ex->getMessage());
}