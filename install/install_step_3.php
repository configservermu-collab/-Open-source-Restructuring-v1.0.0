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
?>
<h3>Create Tables</h3>
<br />
<?php
try {
	if(isset($_POST['install_step_3_submit'])) {
		if(!webengineValidateCsrf(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '', 'install_csrf_token')) throw new Exception('Invalid security token.');
		if(!isset($_POST['install_step_3_error'])) {
			# move to next step
			$_SESSION['install_cstep']++;
			header('Location: install.php');
			die();
		} else {
			echo '<div class="alert alert-danger" role="alert">One or more errors have been logged, cannot continue.</div>';
		}
	}

	// Conexión a base de datos
	if(isset($_SESSION['install_sql_db2'])) {
		$mudb = new dB(
			$_SESSION['install_sql_host'],
			$_SESSION['install_sql_port'],
			$_SESSION['install_sql_db2'],
			$_SESSION['install_sql_user'],
			$_SESSION['install_sql_pass'],
			$_SESSION['install_sql_dsn']
		);
	} else {
		$mudb = new dB(
			$_SESSION['install_sql_host'],
			$_SESSION['install_sql_port'],
			$_SESSION['install_sql_db1'],
			$_SESSION['install_sql_user'],
			$_SESSION['install_sql_pass'],
			$_SESSION['install_sql_dsn']
		);
	}

	if($mudb->dead) throw new Exception("Could not connect to database.");

	// Validar SQL list
	if(!is_array($install['sql_list'])) throw new Exception('Could not load WebEngine CMS SQL tables list.');

	foreach($install['sql_list'] as $sqlFileName => $sqlTableName) {
		if(!file_exists('sql/' . $sqlFileName . '.sql')) {
			throw new Exception('Missing SQL file: sql/' . $sqlFileName . '.sql');
		}
	}

	$error = false;
	echo '<div class="list-group">';

	foreach($install['sql_list'] as $sqlFileName => $sqlTableName) {
		$sqlFileContents = file_get_contents('sql/' . $sqlFileName . '.sql');
		if(!$sqlFileContents) continue;

		$query = str_replace('{TABLE_NAME}', $sqlTableName, $sqlFileContents);
		if(!$query) continue;

		$protectedTables = array('CashShopData', 'MasterSkillTree');
		if(isset($_GET['force']) && $_GET['force'] == 1 && !in_array($sqlTableName, $protectedTables, true)) {
			$mudb->query("DROP TABLE " . $sqlTableName);
		}

		$tableExists = $mudb->query_fetch_single(
			"SELECT * FROM sysobjects WHERE xtype = 'U' AND name = ?",
			array($sqlTableName)
		);

		if(!$tableExists) {
			$create = $mudb->query($query);
			if($create) {
				echo '<div class="list-group-item">'.$sqlTableName.'<span class="label label-success pull-right">Created</span></div>';
			} else {
				echo '<div class="list-group-item">'.$sqlTableName.'<span class="label label-danger pull-right">Error</span></div>';
				$error = true;
			}
		} else {
			echo '<div class="list-group-item">'.$sqlTableName.'<span class="label label-default pull-right">Already Exists</span></div>';
		}
	}
	echo '</div>';

	// Botones
	echo '<form method="post">';
	echo '<input type="hidden" name="csrf_token" value="'.htmlspecialchars(webengineCsrfToken('install_csrf_token'), ENT_QUOTES, 'UTF-8').'">';
		if($error) echo '<input type="hidden" name="install_step_3_error" value="1"/>';
		echo '<a href="'.__INSTALL_URL__.'install.php" class="btn btn-default">Re-Check</a> ';
		echo '<button type="submit" name="install_step_3_submit" value="continue" class="btn btn-success">Continue</button>';
		echo '<a href="'.__INSTALL_URL__.'install.php?force=1" class="btn btn-danger pull-right">Delete Tables and Create Again</a>';
	echo '</form>';

} catch (Exception $ex) {
	echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
}
?>
