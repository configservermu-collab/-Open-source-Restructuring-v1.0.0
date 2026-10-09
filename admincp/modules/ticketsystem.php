<?php
tSys_checkTables();

if(check_value($_REQUEST['page'])) {
	$subPagePath = __PATH_ADMINCP_MODULES__.$_REQUEST['module'].'/'.$_REQUEST['page'].'.php';
	if(file_exists($subPagePath)) {
		$tConf = gconfig('ticketsystem');
		$tSys = new ticketSystem();
		include($subPagePath);
	} else {
		message('error','Invalid request.');
	}
} else {
	message('error','Invalid request.');
}
?>