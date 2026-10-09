<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * WebEngine CMS
 * AdminCP - RedEye Design Configuration
 * 
 * Configuración de apariencia y contenido para template RedEye
 * 
 * @version 1.0
 */

if(!defined('access') or !access or access != 'admincp') die();

// Determinar qué sección mostrar
$section = isset($_GET['section']) ? $_GET['section'] : 'appearance';

?>
<style>
.nav-tabs-custom {
    background: #fff;
    border-radius: 5px;
    margin-bottom: 20px;
}
.nav-tabs-custom > .nav-tabs {
    margin: 0;
    border-bottom: 2px solid #f4f4f4;
}
.nav-tabs-custom > .nav-tabs > li.active {
    border-top: 3px solid #e74c3c;
}
.nav-tabs-custom > .nav-tabs > li.active > a {
    border-top-color: transparent;
    border-left-color: #f4f4f4;
    border-right-color: #f4f4f4;
}
</style>

<h2><i class="fa fa-paint-brush"></i> Diseño RedEye - Configuración</h2>
<hr>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li <?php if($section == 'appearance') echo 'class="active"'; ?>>
            <a href="?module=redeye/redeyedesign&section=appearance">
                <i class="fa fa-palette"></i> Apariencia
            </a>
        </li>
        <li <?php if($section == 'features') echo 'class="active"'; ?>>
            <a href="?module=redeye/redeyedesign&section=features">
                <i class="fa fa-star"></i> Características
            </a>
        </li>
        <li <?php if($section == 'navigation') echo 'class="active"'; ?>>
            <a href="?module=redeye/redeyedesign&section=navigation">
                <i class="fa fa-bars"></i> Navegación
            </a>
        </li>
        <li <?php if($section == 'rankings') echo 'class="active"'; ?>>
            <a href="?module=redeye/redeyedesign&section=rankings">
                <i class="fa fa-trophy"></i> Rankings
            </a>
        </li>
        <li <?php if($section == 'infocards') echo 'class="active"'; ?>>
            <a href="?module=redeye/redeyedesign&section=infocards">
                <i class="fa fa-th-large"></i> Info Cards
            </a>
        </li>
    </ul>
</div>

<?php
// Cargar la sección correspondiente
switch($section) {
    case 'features':
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_features.php');
        break;
    case 'serverinfo':
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_serverinfo.php');
        break;
    case 'navigation':
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_navigation.php');
        break;
    case 'social':
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_social.php');
        break;
    case 'rankings':
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_rankings.php');
        break;
    case 'infocards':
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_infocards.php');
        break;
    case 'appearance':
    default:
        include(__PATH_ADMINCP_MODULES__ . 'redeye/redeyedesign_appearance.php');
        break;
}
?>
