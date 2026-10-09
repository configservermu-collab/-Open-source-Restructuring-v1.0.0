<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * RedEye Design Module - Rankings Configuration
 * Gestión de rankings dinámicos a mostrar en el index
 */

if(!defined('access') or !access) die();

if(!defined('__PATH_CACHE__')) {
    define('__PATH_CACHE__', dirname(__FILE__) . '/../../../includes/cache/');
}

// Definir rankings disponibles
$availableRankings = array(
    'level' => array('name' => 'Top Nivel', 'description' => 'Jugadores con mayor nivel'),
    'resets' => array('name' => 'Top Resets', 'description' => 'Jugadores con más resets'),
    'grandresets' => array('name' => 'Grand Resets', 'description' => 'Jugadores con más grand resets'),
    'master' => array('name' => 'Top Master', 'description' => 'Mejores masters del servidor'),
    'guilds' => array('name' => 'Top Guilds', 'description' => 'Guilds más poderosas'),
    'pk' => array('name' => 'Top PK', 'description' => 'Jugadores con más kills'),
    'online' => array('name' => 'Jugadores Online', 'description' => 'Quién está jugando ahora'),
);

// Cargar configuración actual
$rankingsFile = __PATH_CACHE__ . 'redeyedesign_rankings_display.cache';
$config = array();

if(file_exists($rankingsFile)) {
    $content = file_get_contents($rankingsFile);
    $config = @unserialize($content);
    if(!is_array($config)) {
        $config = array();
    }
}

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_rankings'])) {
    $newConfig = array();
    
    foreach($availableRankings as $key => $info) {
        $newConfig[$key] = array(
            'enabled' => isset($_POST['ranking_' . $key]) ? 1 : 0,
            'name' => $info['name']
        );
    }
    
    // Guardar en cache con serialize
    $serialized = serialize($newConfig);
    if(file_put_contents($rankingsFile, $serialized)) {
        message('success', 'Configuración de rankings guardada correctamente.');
    } else {
        message('error', 'Error al guardar la configuración.');
    }
}

// Aplicar defaults
foreach($availableRankings as $key => $info) {
    if(!isset($config[$key])) {
        $config[$key] = array('enabled' => 1, 'name' => $info['name']);
    }
}

?>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Rankings a Mostrar en el Índice</h3>
            </div>
            <div class="panel-body">
                <form method="POST" class="form-horizontal">
                    <p style="color: #666; margin-bottom: 20px;">Selecciona qué rankings deseas mostrar en la página principal. Solo se mostrarán los que estén habilitados y tengan datos disponibles.</p>
                    
                    <?php foreach($availableRankings as $key => $info): ?>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="ranking_<?php echo $key; ?>" 
                                            <?php echo isset($config[$key]['enabled']) && $config[$key]['enabled'] ? 'checked' : ''; ?>>
                                        <strong><?php echo htmlspecialchars($info['name']); ?></strong>
                                        <br>
                                        <small style="color: #999; margin-left: 20px;"><?php echo htmlspecialchars($info['description']); ?></small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" name="save_rankings" class="btn btn-success">
                                <i class="fa fa-save"></i> Guardar Configuración
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Información</h3>
            </div>
            <div class="panel-body">
                <p><strong>¿Dónde aparecen?</strong></p>
                <p>Los rankings habilitados aparecerán en la página principal (índice) del template RedEye en la sección "TOP #1 RANKINGS".</p>
                
                <p><strong>¿Cómo se actualizan?</strong></p>
                <p>Los rankings se actualizan automáticamente desde los archivos cache del servidor cada vez que se refresca la página.</p>
                
                <p><strong>Datos mostrados</strong></p>
                <p>Se muestra el TOP 1 (primer lugar) de cada ranking seleccionado con:
                <ul>
                    <li>Nombre del jugador</li>
                    <li>Clase/Raza</li>
                    <li>Valor principal (Nivel, Resets, etc)</li>
                    <li>Avatar de la raza</li>
                </ul>
                </p>
            </div>
        </div>
    </div>
</div>
