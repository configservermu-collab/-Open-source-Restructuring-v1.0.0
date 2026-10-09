<?php
/**
 * WebEngine CMS
 * AdminCP - Dream Design Configuration (Extended)
 * 
 * Configuración de Rankings, Info Cards y Features para template CustomDreams
 * 
 * @version 2.0
 */

if(!defined('access') or !access or access != 'admincp') die();

// Determinar qué sección mostrar
$section = isset($_GET['section']) ? $_GET['section'] : 'features';

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
    border-top: 3px solid #5cb85c;
}
.nav-tabs-custom > .nav-tabs > li.active > a {
    border-top-color: transparent;
    border-left-color: #f4f4f4;
    border-right-color: #f4f4f4;
}
</style>

<h2><i class="fa fa-paint-brush"></i> Diseño Dreams - Configuración</h2>
<hr>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li <?php if($section == 'features') echo 'class="active"'; ?>>
            <a href="?module=dreamdesign&section=features">
                <i class="fa fa-star"></i> Features Cards
            </a>
        </li>
        <li <?php if($section == 'rankings') echo 'class="active"'; ?>>
            <a href="?module=dreamdesign&section=rankings">
                <i class="fa fa-trophy"></i> Rankings
            </a>
        </li>
        <li <?php if($section == 'infocards') echo 'class="active"'; ?>>
            <a href="?module=dreamdesign&section=infocards">
                <i class="fa fa-th-large"></i> Info Cards
            </a>
        </li>
        <li <?php if($section == 'appearance') echo 'class="active"'; ?>>
            <a href="?module=dreamdesign&section=appearance">
                <i class="fa fa-fill-drip"></i> Apariencia Visual
            </a>
        </li>
        <li <?php if($section == 'slider') echo 'class="active"'; ?>>
            <a href="?module=dreamdesign&section=slider">
                <i class="fa fa-images"></i> Slider
            </a>
        </li>
        <li <?php if($section == 'social') echo 'class="active"'; ?>>
            <a href="?module=dreamdesign&section=social">
                <i class="fa fa-share-nodes"></i> Redes Sociales
            </a>
        </li>
    </ul>
</div>

<?php
// Cargar la sección correspondiente
switch($section) {
    case 'rankings':
        include(__PATH_ADMINCP_MODULES__ . 'dreamdesign_rankings.php');
        break;
    case 'infocards':
        include(__PATH_ADMINCP_MODULES__ . 'dreamdesign_infocards.php');
        break;
    case 'appearance':
        include(__PATH_ADMINCP_MODULES__ . 'dreamdesign_appearance.php');
        break;
    case 'slider':
        include(__PATH_ADMINCP_MODULES__ . 'dreamdesign_slider.php');
        break;
    case 'social':
        include(__PATH_ADMINCP_MODULES__ . 'dreamdesign_social.php');
        break;
    case 'features':
    default:
        include(__PATH_ADMINCP_MODULES__ . 'dreamdesign_features.php');
        break;
}
