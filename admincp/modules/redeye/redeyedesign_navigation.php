<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * RedEye Design Module - Navigation Configuration
 * Gestión de navegación personalizada
 */

if(!defined('access') or !access) die();

if(!defined('__PATH_CACHE__')) {
    define('__PATH_CACHE__', dirname(__FILE__) . '/../../../includes/cache/');
}

// Cargar configuración actual
$navigationFile = __PATH_CACHE__ . 'redeyedesign_navigation.cache';
$navigation = array();

if(file_exists($navigationFile)) {
    $navigation = json_decode(file_get_contents($navigationFile), true);
}

// Inicializar valores por defecto
$navigation = array_merge(array(
    'show_home' => true,
    'show_login' => true,
    'show_register' => true,
    'show_downloads' => true,
    'show_guides' => true,
    'show_rankings' => true,
    'show_castle' => true,
    'show_news' => true,
    'sticky_navbar' => true,
    'navbar_transparency' => 0.9,
    'mobile_menu' => true,
    'search_enabled' => true
), $navigation);

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_navigation'])) {
    $navigation['show_home'] = isset($_POST['show_home']) ? 1 : 0;
    $navigation['show_login'] = isset($_POST['show_login']) ? 1 : 0;
    $navigation['show_register'] = isset($_POST['show_register']) ? 1 : 0;
    $navigation['show_downloads'] = isset($_POST['show_downloads']) ? 1 : 0;
    $navigation['show_guides'] = isset($_POST['show_guides']) ? 1 : 0;
    $navigation['show_rankings'] = isset($_POST['show_rankings']) ? 1 : 0;
    $navigation['show_castle'] = isset($_POST['show_castle']) ? 1 : 0;
    $navigation['show_news'] = isset($_POST['show_news']) ? 1 : 0;
    $navigation['sticky_navbar'] = isset($_POST['sticky_navbar']) ? 1 : 0;
    $navigation['navbar_transparency'] = isset($_POST['navbar_transparency']) ? floatval($_POST['navbar_transparency']) / 100 : 0.9;
    $navigation['mobile_menu'] = isset($_POST['mobile_menu']) ? 1 : 0;
    $navigation['search_enabled'] = isset($_POST['search_enabled']) ? 1 : 0;
    
    // Guardar en caché
    file_put_contents($navigationFile, json_encode($navigation, JSON_PRETTY_PRINT));
    $message = 'Configuración de navegación guardada correctamente';
}

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Opciones Visibles en la Navegación</h3>
    </div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_home" <?php echo $navigation['show_home'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Inicio</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_login" <?php echo $navigation['show_login'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Login</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_register" <?php echo $navigation['show_register'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Registro</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_downloads" <?php echo $navigation['show_downloads'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Descargas</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_guides" <?php echo $navigation['show_guides'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Guías</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_rankings" <?php echo $navigation['show_rankings'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Rankings</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_castle" <?php echo $navigation['show_castle'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Castle Siege</strong>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="show_news" <?php echo $navigation['show_news'] ? 'checked' : ''; ?>>
                            <strong>Mostrar Noticias</strong>
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label class="col-sm-2 control-label">Navbar Fija (Sticky)</label>
                <div class="col-sm-10">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="sticky_navbar" <?php echo $navigation['sticky_navbar'] ? 'checked' : ''; ?>>
                        Mantener navbar en la parte superior al hacer scroll
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Transparencia Navbar (%)</label>
                <div class="col-sm-10">
                    <input type="range" name="navbar_transparency" class="form-control" min="0" max="100" value="<?php echo intval($navigation['navbar_transparency'] * 100); ?>">
                    <small class="form-text text-muted">0% = Transparente, 100% = Opaco</small>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Menú Móvil</label>
                <div class="col-sm-10">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="mobile_menu" <?php echo $navigation['mobile_menu'] ? 'checked' : ''; ?>>
                        Habilitar menú desplegable en dispositivos móviles
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Búsqueda</label>
                <div class="col-sm-10">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="search_enabled" <?php echo $navigation['search_enabled'] ? 'checked' : ''; ?>>
                        Habilitar barra de búsqueda en la navegación
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="save_navigation" class="btn btn-primary">
                        <i class="fa fa-save"></i> Guardar Configuración
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
