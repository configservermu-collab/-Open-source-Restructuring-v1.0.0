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
 */

if(!defined('access') or !access or access != 'install') die();

/**
 * INSTALLER_VERSION
 */
define('INSTALLER_VERSION', '1.2.6');

/**
 * WEBENGINE_CONFIGURATION_FILE
 */
define('WEBENGINE_CONFIGURATION_FILE', 'webengine.json');

/**
 * WEBENGINE_WRITABLE_PATHS_FILE
 */
define('WEBENGINE_WRITABLE_PATHS_FILE', 'writable.paths.json');

/**
 * WEBENGINE_DEFAULT_CONFIGURATION_FILE
 */
define('WEBENGINE_DEFAULT_CONFIGURATION_FILE', 'webengine.json.default');

$install['PDO_DSN'] = array(
	1 => 'dblib',
	2 => 'sqlsrv',
	3 => 'odbc',
);

// INICIO SQL_LIST
$install['sql_list'] = array(
	'CashShopData' => 'CashShopData',
	'MasterSkillTree' => 'MasterSkillTree',
	'Gens_Rank' => 'Gens_Rank',
    'WEBENGINE_ACCOUNT_COUNTRY' => 'WEBENGINE_ACCOUNT_COUNTRY',
    'WEBENGINE_BAN_LOG' => 'WEBENGINE_BAN_LOG',
    'WEBENGINE_BANS' => 'WEBENGINE_BANS',
    'WEBENGINE_BLOCKED_IP' => 'WEBENGINE_BLOCKED_IP',
    'WEBENGINE_CHANGELOGS' => 'WEBENGINE_CHANGELOGS',
    'WEBENGINE_CHANGELOGS_TRANSLATIONS' => 'WEBENGINE_CHANGELOGS_TRANSLATIONS',
    'WEBENGINE_CREDITS_CONFIG' => 'WEBENGINE_CREDITS_CONFIG',
    'WEBENGINE_CREDITS_LOGS' => 'WEBENGINE_CREDITS_LOGS',
    'WEBENGINE_CRON' => 'WEBENGINE_CRON',
    'WEBENGINE_DOWNLOADS' => 'WEBENGINE_DOWNLOADS',
    'WEBENGINE_EXCHANGERESETS' => 'WEBENGINE_EXCHANGERESETS',
    'WEBENGINE_FLA' => 'WEBENGINE_FLA',
    'WEBENGINE_GIFT_CODE' => 'WEBENGINE_GIFT_CODE',
    'WEBENGINE_GIFT_CODE_ITEMS' => 'WEBENGINE_GIFT_CODE_ITEMS',
    'WEBENGINE_GIFT_CODE_LOGS' => 'WEBENGINE_GIFT_CODE_LOGS',
    'WEBENGINE_GIFT_CODE_PACKS' => 'WEBENGINE_GIFT_CODE_PACKS',
    'WEBENGINE_GUIDES' => 'WEBENGINE_GUIDES',
    'WEBENGINE_GUIDES_TRANSLATIONS' => 'WEBENGINE_GUIDES_TRANSLATIONS',
    'WEBENGINE_MERCADOPAGO_TRANSACTIONS' => 'WEBENGINE_MERCADOPAGO_TRANSACTIONS',
    'WEBENGINE_NEWS' => 'WEBENGINE_NEWS',
    'WEBENGINE_NEWS_TRANSLATIONS' => 'WEBENGINE_NEWS_TRANSLATIONS',
    'WEBENGINE_PASSCHANGE_REQUEST' => 'WEBENGINE_PASSCHANGE_REQUEST',
    'WEBENGINE_PAYPAL_TRANSACTIONS' => 'WEBENGINE_PAYPAL_TRANSACTIONS',
    'WEBENGINE_PLUGIN_TICKETSYSTEM' => 'WEBENGINE_PLUGIN_TICKETSYSTEM',
    'WEBENGINE_PLUGIN_TICKETSYSTEM_CONTENT' => 'WEBENGINE_PLUGIN_TICKETSYSTEM_CONTENT',
    'WEBENGINE_PLUGINS' => 'WEBENGINE_PLUGINS',
    'WEBENGINE_REDEEMCODE' => 'WEBENGINE_REDEEMCODE',
    'WEBENGINE_REDEEMCODE_LOGS' => 'WEBENGINE_REDEEMCODE_LOGS',
    'WEBENGINE_REFERRALSYSTEM' => 'WEBENGINE_REFERRALSYSTEM',
    'WEBENGINE_REGISTER_ACCOUNT' => 'WEBENGINE_REGISTER_ACCOUNT',
    'WEBENGINE_VOTE_LOGS' => 'WEBENGINE_VOTE_LOGS',
    'WEBENGINE_VOTE_SITES' => 'WEBENGINE_VOTE_SITES',
    'WEBENGINE_VOTES' => 'WEBENGINE_VOTES',
    'WEBENGINE_ITEMS_WEBSHOP' => 'WEBENGINE_ITEMS_WEBSHOP',
    'WEBENGINE_WEBSHOP_LOGS' => 'WEBENGINE_WEBSHOP_LOGS'
);
// FIN SQL_LIST


$install['step_list'] = array(
	array('install_intro.php', 'Intro'),
	array('install_step_1.php', 'Web Server Requirements'),
	array('install_step_2.php', 'Database Connection'),
	array('install_step_3.php', 'Create Tables'),
	array('install_step_4.php', 'Add Cron Jobs'),
	array('install_step_5.php', 'Website Configuration'),
);

$install['cron_jobs'] = array(
	// cron_name,cron_description,cron_file_run,cron_run_time,cron_status,cron_protected,cron_file_md5
	array('Levels Ranking','Scheduled task to update characters level ranking','levels_ranking.php','300','1','0'),
	array('Resets Ranking','Scheduled task to update characters reset ranking','resets_ranking.php','300','1','0'),
	array('Killers Ranking','Scheduled task to update top killers ranking','killers_ranking.php','300','1','0'),
	array('Master Level Ranking','Scheduled task to update characters master level ranking','masterlevel_ranking.php','300','1','0'),
	array('Guilds Ranking','Scheduled task to update top guilds ranking','guilds_ranking.php','300','1','0'),
	array('MasterReset Ranking','Scheduled task to update characters MasterReset ranking','grandresets_ranking.php','300','1','0'),
	array('Online Ranking','Scheduled task to update top online ranking','online_ranking.php','300','1','0'),
	array('Gens Ranking','Scheduled task to update gens ranking','gens_ranking.php','300','1','0'),
	array('Votes Ranking','Scheduled task to update vote rankings','votes_ranking.php','300','1','0'),
	array('Castle Siege','Saves castle siege information in cache','castle_siege.php','300','1','0'),
	array('Ban System','Scheduled task to lift temporal bans','temporal_bans.php','300','1','0'),
	array('Server Info','Scheduled task to update the sidebar statistics information','server_info.php','300','1','0'),
	array('Account Country','Scheduled task to detect the accounts country by their ip address','account_country.php','60','1','0'),
	array('Character Country','Scheduled task to cache characters country','character_country.php','300','1','0'),
	array('Online Characters','Scheduled task to cache online characters','online_characters.php','300','1','0'),
	array('General Ranking','Scheduled task to update characters General Ranking','general_ranking.php','300','1','0'),
	array('Blood Castle Ranking','Scheduled task to update characters BC Ranking','bloodcastle_ranking.php','300','1','0'),
	array('Chaos Castle Ranking','Scheduled task to update characters CC Ranking','chaoscastle_ranking.php','300','1','0'),
	array('Devil Square Ranking','Scheduled task to update characters DS Ranking','devilsquare_ranking.php','300','1','0'),
	array('Illusion Temple Ranking','Scheduled task to update characters IT Ranking','illusiontemple_ranking.php','300','1','0'),
	array('Duel Ranking','Scheduled task to update characters Duel ranking','duel_ranking.php','300','1','0'),
	// TvT queda fuera hasta que exista un cron y una consulta compatibles con su módulo.
);

$install['PDO_PWD_ENCRYPT'] = array(
	'none',
	'wzmd5',
	'phpmd5',
	'sha256',
);