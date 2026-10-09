<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * RedEye Design Module - Social Media Configuration
 * Gestión de redes sociales
 */

if(!defined('access') or !access) die();

if(!defined('__PATH_CACHE__')) {
    define('__PATH_CACHE__', dirname(__FILE__) . '/../../../includes/cache/');
}

// Cargar configuración actual
$socialFile = __PATH_CACHE__ . 'redeyedesign_social.cache';
$social = array();

if(file_exists($socialFile)) {
    $decoded = json_decode(file_get_contents($socialFile), true);
    if(is_array($decoded)) {
        $social = $decoded;
    }
}

// Inicializar valores por defecto
$defaultSocial = array(
    'facebook' => array('enabled' => true, 'url' => '', 'icon' => 'fa-facebook'),
    'twitter' => array('enabled' => true, 'url' => '', 'icon' => 'fa-twitter'),
    'instagram' => array('enabled' => true, 'url' => '', 'icon' => 'fa-instagram'),
    'tiktok' => array('enabled' => true, 'url' => '', 'icon' => 'fa-tiktok'),
    'youtube' => array('enabled' => true, 'url' => '', 'icon' => 'fa-youtube'),
    'discord' => array('enabled' => true, 'url' => '', 'icon' => 'fa-discord'),
    'twitch' => array('enabled' => false, 'url' => '', 'icon' => 'fa-twitch'),
);

$social = array_merge($defaultSocial, $social);

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_social'])) {
    foreach(array_keys($defaultSocial) as $network) {
        $social[$network]['enabled'] = isset($_POST[$network.'_enabled']) ? 1 : 0;
        $social[$network]['url'] = isset($_POST[$network.'_url']) ? trim($_POST[$network.'_url']) : '';
    }
    
    // Guardar en caché
    file_put_contents($socialFile, json_encode($social, JSON_PRETTY_PRINT));
    $message = 'Redes sociales guardadas correctamente';
}

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Configuración de Redes Sociales</h3>
    </div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal">
            
            <?php foreach($social as $network => $data): ?>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <label>
                            <input type="checkbox" name="<?php echo $network; ?>_enabled" <?php echo $data['enabled'] ? 'checked' : ''; ?>>
                            <i class="fas <?php echo $data['icon']; ?>" style="margin-right: 10px;"></i>
                            <?php echo ucfirst($network); ?>
                        </label>
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">URL de <?php echo ucfirst($network); ?></label>
                        <div class="col-sm-10">
                            <input type="url" name="<?php echo $network; ?>_url" class="form-control" 
                                   value="<?php echo $data['url']; ?>" 
                                   placeholder="https://<?php echo $network; ?>.com/usuario">
                            <small class="form-text text-muted">URL completa del perfil</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" name="save_social" class="btn btn-primary">
                        <i class="fa fa-save"></i> Guardar Configuración
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
