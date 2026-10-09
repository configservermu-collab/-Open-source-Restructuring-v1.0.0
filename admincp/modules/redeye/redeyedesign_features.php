<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * WebEngine CMS
 * AdminCP - RedEye Design - Features Configuration
 * 
 * Configuración de características destacadas
 * 
 * @version 1.0
 */

if(!defined('access') or !access or access != 'admincp') die();

// Procesar formulario
if(isset($_POST['submit_features']) || isset($_POST['autosave'])) {
    try {
        $features = array();
        if(isset($_POST['feature_icon']) && is_array($_POST['feature_icon'])) {
            for($i = 0; $i < count($_POST['feature_icon']); $i++) {
                if(empty($_POST['feature_title'][$i])) continue;
                $features[] = array(
                    'icon' => trim($_POST['feature_icon'][$i]),
                    'title' => trim($_POST['feature_title'][$i]),
                    'description' => trim($_POST['feature_description'][$i]),
                    'color' => trim($_POST['feature_color'][$i])
                );
            }
        }
        
        $cacheFile = __PATH_CACHE__ . 'redeyedesign_features.cache';
        $serialized = serialize($features);
        if(file_put_contents($cacheFile, $serialized)) {
            if(!isset($_POST['autosave'])) {
                message('success', 'Las características han sido guardadas exitosamente.');
            }
        }
    } catch(Exception $ex) {
        if(!isset($_POST['autosave'])) {
            message('error', $ex->getMessage());
        }
    }
}

$cacheFile = __PATH_CACHE__ . 'redeyedesign_features.cache';
$currentFeatures = array();
if(file_exists($cacheFile)) {
    $content = file_get_contents($cacheFile);
    $currentFeatures = @unserialize($content);
    if(!is_array($currentFeatures)) $currentFeatures = array();
}

if(empty($currentFeatures)) {
    $currentFeatures = array(
        array('icon' => 'fa-bolt', 'title' => 'Experiencia Alta', 'description' => 'Sube de nivel rápidamente', 'color' => '#cc0000'),
        array('icon' => 'fa-gem', 'title' => 'Drop Épico', 'description' => 'Items valiosos garantizados', 'color' => '#ff3333'),
        array('icon' => 'fa-shield-halved', 'title' => 'Anti-Hack', 'description' => 'Seguridad avanzada', 'color' => '#cc0000'),
        array('icon' => 'fa-server', 'title' => 'Estabilidad 24/7', 'description' => 'Servidor siempre disponible', 'color' => '#ff3333'),
        array('icon' => 'fa-users', 'title' => 'Comunidad', 'description' => 'Miles de jugadores', 'color' => '#cc0000'),
        array('icon' => 'fa-calendar-days', 'title' => 'Eventos Diarios', 'description' => 'Participa y gana', 'color' => '#ff3333')
    );
}
?>
<style>
.feature-row {
    background: #f9f9f9;
    padding: 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    border-left: 4px solid #e74c3c;
}
.feature-number {
    background: #e74c3c;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 10px;
}
.icon-preview {
    display: inline-block;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border-radius: 50%;
    color: white;
    text-align: center;
    line-height: 40px;
    font-size: 18px;
    margin-right: 10px;
}
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-star"></i> Configuración de Características - RedEye
        </h3>
    </div>
    <div class="panel-body">
        <div class="alert alert-info">
            <strong><i class="fa fa-info-circle"></i> Información:</strong><br>
            Define las características destacadas de tu servidor. Usa iconos de <a href="https://fontawesome.com/search?o=r&m=free" target="_blank">Font Awesome 6</a>.
        </div>
        
        <form action="" method="post" id="featuresForm">
            <div id="featuresContainer">
                <?php
                $index = 0;
                foreach($currentFeatures as $feature) {
                    ?>
                    <div class="feature-row" data-index="<?php echo $index; ?>">
                        <div style="margin-bottom: 15px;">
                            <span class="feature-number"><?php echo ($index + 1); ?></span>
                            <span class="icon-preview">
                                <i class="fas <?php echo htmlspecialchars($feature['icon']); ?>"></i>
                            </span>
                            <strong style="vertical-align: middle;">Característica #<?php echo ($index + 1); ?></strong>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Icono</label>
                                    <input type="text" name="feature_icon[]" value="<?php echo htmlspecialchars($feature['icon']); ?>" 
                                           class="form-control" placeholder="fa-star" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Título</label>
                                    <input type="text" name="feature_title[]" value="<?php echo htmlspecialchars($feature['title']); ?>" 
                                           class="form-control" placeholder="Título" required>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <input type="text" name="feature_description[]" value="<?php echo htmlspecialchars($feature['description']); ?>" 
                                           class="form-control" placeholder="Descripción" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Color</label>
                                    <input type="color" name="feature_color[]" value="<?php echo htmlspecialchars($feature['color']); ?>" 
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $index++;
                }
                ?>
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" name="submit_features" class="btn btn-primary">
                    <i class="fa fa-save"></i> Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
