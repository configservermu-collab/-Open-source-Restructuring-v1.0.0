<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

define('access', 'install');



if(!@include_once('loader.php')) die('Could not load WebEngine CMS Installer.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>WebEngine <?php echo INSTALLER_VERSION; ?> + ConfigServerMU.net</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
	body {
		min-height: 100vh;
		color: #e9eef2;
		background-color: #05080c;
		background-image:
			linear-gradient(rgba(0, 157, 255, 0.045) 1px, transparent 1px),
			linear-gradient(90deg, rgba(0, 157, 255, 0.045) 1px, transparent 1px),
			radial-gradient(circle at 50% -10%, rgba(0, 107, 239, 0.2), transparent 38%);
		background-size: 34px 34px, 34px 34px, auto;
		font-size: 15px;
	}
	body.ui-crossfade {
		animation: ui-crossfade-in 420ms ease both;
	}
	.container {
		max-width: 1180px;
	}
	.installer-shell {
		margin: 34px 0;
		padding: 28px;
		border: 1px solid #27313b;
		background: rgba(5, 8, 12, 0.92);
		box-shadow: 0 26px 70px rgba(0, 0, 0, 0.55), inset 0 1px 0 rgba(233, 238, 242, 0.06);
	}
	.header {
		display: flex;
		align-items: center;
		gap: 18px;
		margin: 0 0 26px;
		padding: 0 0 20px;
		border-bottom: 1px solid #27313b;
	}
	.header-logo {
		width: 78px;
		height: 78px;
		object-fit: contain;
		filter: drop-shadow(0 0 16px rgba(0, 217, 255, 0.22));
		view-transition-name: webengine-brand;
		transition: transform 220ms ease, filter 220ms ease;
	}
	.header-logo:hover,
	.header-logo:focus-visible {
		transform: scale(1.06);
		filter: drop-shadow(0 0 22px rgba(0, 217, 255, 0.4));
	}
	h1, h2, h3, h4 {
		margin-top: 0;
		color: #ffffff;
		font-weight: 700;
	}
	h1 {
		margin-bottom: 4px;
		font-size: 30px;
		letter-spacing: 0.5px;
	}
	h3 {
		padding-bottom: 12px;
		border-bottom: 1px solid #27313b;
	}
	.brand-note {
		color: #00d9ff;
		font-size: 14px;
		font-weight: 600;
	}
	.header-subtitle {
		margin: 0;
		color: #aeb9c4;
		font-size: 13px;
	}
	.installer-panel,
	.installer-sidebar {
		min-height: 100%;
		padding: 26px;
		border: 1px solid #27313b;
		background: linear-gradient(150deg, rgba(16, 25, 35, 0.98), rgba(5, 8, 12, 0.98));
		box-shadow: inset 0 1px 0 rgba(233, 238, 242, 0.05), 0 16px 36px rgba(0, 0, 0, 0.3);
		backdrop-filter: blur(12px);
		-webkit-backdrop-filter: blur(12px);
	}
	.installer-sidebar {
		padding: 22px;
	}
	.installer-sidebar__brand {
		margin-bottom: 20px;
		padding-bottom: 18px;
		border-bottom: 1px solid #27313b;
	}
	.installer-sidebar__brand h2 {
		margin-bottom: 8px;
		font-size: 19px;
		color: #00d9ff;
	}
	.installer-sidebar__brand p {
		margin: 0;
		color: #aeb9c4;
		font-size: 13px;
		line-height: 1.6;
	}
	p, .help-block {
		color: #c7d0d9;
	}
	.help-block {
		font-size: 12px;
	}
	hr {
		border-color: #27313b;
	}
	.list-group-item,
	.panel,
	.panel-default > .panel-heading,
	.panel-default > .panel-body {
		border-color: #27313b;
		background: #0d141c;
		color: #e9eef2;
	}
	.list-group-item {
		margin-bottom: 5px;
	}
	.installer-panel .list-group {
		display: table;
		width: 100%;
		border-collapse: separate;
		border-spacing: 0 5px;
	}
	.installer-panel .list-group-item {
		display: table-row;
		transition: background-color 180ms ease, transform 180ms ease;
	}
	.installer-panel .list-group-item::before,
	.installer-panel .list-group-item::after {
		display: none;
	}
	.installer-panel .list-group-item > * {
		display: table-cell;
	}
	.installer-panel .list-group-item:hover {
		background: rgba(0, 217, 255, 0.08);
		transform: translateY(-1px);
	}
	.installer-panel table {
		width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		background: rgba(13, 20, 28, 0.72);
		backdrop-filter: blur(8px);
		-webkit-backdrop-filter: blur(8px);
	}
	.installer-panel table th,
	.installer-panel table td {
		padding: 13px 15px;
		border: 1px solid #27313b;
		vertical-align: middle;
	}
	.installer-panel table th {
		background: #162330;
		color: #00d9ff;
		text-align: left;
		font-size: 11px;
		letter-spacing: 0.08em;
		text-transform: uppercase;
	}
	.installer-panel table tr {
		transition: background-color 180ms ease, transform 180ms ease;
	}
	.installer-panel table tbody tr:hover {
		background: rgba(0, 217, 255, 0.08);
	}
	.list-group-item.active,
	.list-group-item.active:hover,
	.list-group-item.active:focus {
		border-color: #009dff;
		background: linear-gradient(90deg, #003c86, #071b32);
		color: #ffffff;
	}
	.form-control {
		height: 42px;
		border-color: #3a4652;
		background: #05080c;
		color: #ffffff;
		box-shadow: inset 0 1px 4px rgba(0, 0, 0, 0.55);
	}
	.form-control:focus {
		border-color: #00d9ff;
		box-shadow: 0 0 0 3px rgba(0, 217, 255, 0.13);
	}
	.control-label {
		color: #e9eef2;
	}
	.btn {
		border-radius: 2px;
		font-weight: 700;
	}
	.btn-success, .btn-primary {
		border-color: #009dff;
		background: #006bef;
		color: #ffffff;
	}
	.btn-success:hover, .btn-primary:hover {
		border-color: #00d9ff;
		background: #009dff;
	}
	.btn-default {
		border-color: #687684;
		background: #27313b;
		color: #ffffff;
	}
	.installer-actions {
		margin-top: 24px;
		padding-top: 18px;
		border-top: 1px solid #27313b;
	}
	.btn-danger {
		border-color: #6f4c25;
		background: #3a2020;
		color: #ffffff;
	}
	.label-success { background: #16804b; }
	.label-warning { background: #8b6914; }
	.label-danger { background: #9d3434; }
	.alert-danger {
		border-color: #9d3434;
		background: #301617;
		color: #ffffff;
	}
	.footer {
		margin: 26px 0 0;
		padding-top: 18px;
		border-top: 1px solid #27313b;
		color: #aeb9c4;
		font-size: 12px;
	}
	.footer a {
		color: #00d9ff;
	}
	@keyframes ui-crossfade-in {
		from { opacity: 0; }
		to { opacity: 1; }
	}
	@media (prefers-reduced-motion: reduce) {
		body.ui-crossfade {
			animation: none;
		}
		.header-logo,
		.installer-panel .list-group-item,
		.installer-panel table tr {
			transition: none;
		}
	}
	@media (max-width: 991px) {
		.installer-shell { margin: 18px 0; padding: 18px; }
		.installer-panel { margin-bottom: 20px; }
	}
	@media (max-width: 480px) {
		.header { align-items: flex-start; }
		.header-logo { width: 54px; height: 54px; }
		h1 { font-size: 23px; }
		.installer-panel, .installer-sidebar { padding: 18px; }
	}
	</style>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="ui-crossfade">

	<div class="container">
		<div class="installer-shell">
			<div class="row header">
				<img class="header-logo ui-shared-element ui-magnify" src="https://i.imgur.com/2SRHsS1.png" alt="ConfigServerMU logo">
				<div>
					<h1>WebEngine <?php echo INSTALLER_VERSION; ?> <span class="brand-note">+ ConfigServerMU.net</span></h1>
					<p class="header-subtitle">Panel de instalacion y configuracion del servidor</p>
				</div>
			</div>

			<div class="row">
				<div class="col-md-9">
					<div class="installer-panel">
						<?php
						try {
							if(array_key_exists($_SESSION['install_cstep'], $install['step_list'])) {
								$fileName = $install['step_list'][$_SESSION['install_cstep']][0];
								if(file_exists($fileName)) {
									if(!@include_once($fileName)) throw new Exception('Bad step file.');
								}
							}
						} catch (Exception $ex) {
							echo '<div class="alert alert-danger" role="alert">'.$ex->getMessage().'</div>';
						}
						?>
					</div>
				</div>
				<div class="col-md-3">
					<aside class="installer-sidebar">
						<div class="installer-sidebar__brand">
							<h2>CONFIGSERVERMU</h2>
							<p>Ofrecemos configuraciones personalizadas y optimizadas para servidores MU Online. Con 5 anos de experiencia, garantizamos un servicio estable, seguro y profesional.</p>
						</div>
						<?php stepListSidebar(); ?>
					</aside>
				</div>
			</div>

			<footer class="footer">
				<a href="https://configservermu.net/" target="_blank">&copy; WebEngine <?php echo INSTALLER_VERSION; ?> + ConfigServerMU.net</a>
			</footer>
		</div>
		</div>
	</div><!-- /container -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
