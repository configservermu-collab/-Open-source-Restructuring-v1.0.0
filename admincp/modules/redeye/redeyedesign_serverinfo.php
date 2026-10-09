<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * RedEye Design Module - Server Info Configuration
 * Gestión de información del servidor
 */

if(!defined('access') or !access) die();

if(!defined('__PATH_CACHE__')) {
    define('__PATH_CACHE__', dirname(__FILE__) . '/../../../includes/cache/');
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Cargar configuración actual
$serverInfoFile = __PATH_CACHE__ . 'redeyedesign_serverinfo.cache';
$serverInfo = array();

if(file_exists($serverInfoFile)) {
    $serverInfo = json_decode(file_get_contents($serverInfoFile), true);
}

// Inicializar valores por defecto
$serverInfo = array_merge(array(
    'server_name' => config('server_name'),
    'server_type' => 'PvP',
    'max_level' => 400,
    'exp_rate' => 1,
    'drop_rate' => 1,
    'server_version' => '1.0',
    'release_date' => date('Y-m-d'),
    'status' => 'online',
    'description' => 'Servidor de Mu Online',
    'language' => 'es'
), $serverInfo);

// Procesar formulario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_serverinfo'])) {
    $serverInfo['server_name'] = isset($_POST['server_name']) ? trim($_POST['server_name']) : '';
    $serverInfo['server_type'] = isset($_POST['server_type']) ? trim($_POST['server_type']) : '';
    $serverInfo['max_level'] = isset($_POST['max_level']) ? intval($_POST['max_level']) : 400;
    $serverInfo['exp_rate'] = isset($_POST['exp_rate']) ? floatval($_POST['exp_rate']) : 1;
    $serverInfo['drop_rate'] = isset($_POST['drop_rate']) ? floatval($_POST['drop_rate']) : 1;
    $serverInfo['server_version'] = isset($_POST['server_version']) ? trim($_POST['server_version']) : '';
    $serverInfo['release_date'] = isset($_POST['release_date']) ? trim($_POST['release_date']) : date('Y-m-d');
    $serverInfo['status'] = isset($_POST['status']) ? trim($_POST['status']) : 'online';
    $serverInfo['description'] = isset($_POST['description']) ? trim($_POST['description']) : '';
    $serverInfo['language'] = isset($_POST['language']) ? trim($_POST['language']) : 'es';
    
    // Guardar en caché
    file_put_contents($serverInfoFile, json_encode($serverInfo, JSON_PRETTY_PRINT));
    $message = 'Información del servidor guardada correctamente';
}

?>

<form method="POST" class="form-horizontal">
    
    <div class="form-group">
        <label class="col-sm-2 control-label">Nombre del Servidor</label>
        <div class="col-sm-10">
            <input type="text" name="server_name" class="form-control" value="<?php echo $serverInfo['server_name']; ?>" required>
            <small class="form-text text-muted">Nombre visible del servidor</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Tipo de Servidor</label>
        <div class="col-sm-10">
            <select name="server_type" class="form-control" required>
                <option value="PvP" <?php echo $serverInfo['server_type'] == 'PvP' ? 'selected' : ''; ?>>PvP</option>
                <option value="PvE" <?php echo $serverInfo['server_type'] == 'PvE' ? 'selected' : ''; ?>>PvE</option>
                <option value="PvP/PvE" <?php echo $serverInfo['server_type'] == 'PvP/PvE' ? 'selected' : ''; ?>>PvP/PvE</option>
                <option value="Hardcore" <?php echo $serverInfo['server_type'] == 'Hardcore' ? 'selected' : ''; ?>>Hardcore</option>
                <option value="Casual" <?php echo $serverInfo['server_type'] == 'Casual' ? 'selected' : ''; ?>>Casual</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Nivel Máximo</label>
        <div class="col-sm-10">
            <input type="number" name="max_level" class="form-control" value="<?php echo $serverInfo['max_level']; ?>" min="1" max="999" required>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Experiencia Rate</label>
        <div class="col-sm-10">
            <input type="number" name="exp_rate" class="form-control" value="<?php echo $serverInfo['exp_rate']; ?>" min="0.1" step="0.1" required>
            <small class="form-text text-muted">Ej: 1 = 1x, 2 = 2x, 0.5 = 0.5x</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Drop Rate</label>
        <div class="col-sm-10">
            <input type="number" name="drop_rate" class="form-control" value="<?php echo $serverInfo['drop_rate']; ?>" min="0.1" step="0.1" required>
            <small class="form-text text-muted">Ej: 1 = 1x, 2 = 2x, 0.5 = 0.5x</small>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Versión del Servidor</label>
        <div class="col-sm-10">
            <input type="text" name="server_version" class="form-control" value="<?php echo $serverInfo['server_version']; ?>" placeholder="1.0">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Fecha de Lanzamiento</label>
        <div class="col-sm-10">
            <input type="date" name="release_date" class="form-control" value="<?php echo $serverInfo['release_date']; ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Estado del Servidor</label>
        <div class="col-sm-10">
            <select name="status" class="form-control" required>
                <option value="online" <?php echo $serverInfo['status'] == 'online' ? 'selected' : ''; ?>>Online</option>
                <option value="offline" <?php echo $serverInfo['status'] == 'offline' ? 'selected' : ''; ?>>Offline</option>
                <option value="maintenance" <?php echo $serverInfo['status'] == 'maintenance' ? 'selected' : ''; ?>>Mantenimiento</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Idioma</label>
        <div class="col-sm-10">
            <select name="language" class="form-control" required>
                <option value="es" <?php echo $serverInfo['language'] == 'es' ? 'selected' : ''; ?>>Español</option>
                <option value="en" <?php echo $serverInfo['language'] == 'en' ? 'selected' : ''; ?>>Inglés</option>
                <option value="pt" <?php echo $serverInfo['language'] == 'pt' ? 'selected' : ''; ?>>Portugués</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Descripción</label>
        <div class="col-sm-10">
            <textarea name="description" class="form-control" rows="5"><?php echo $serverInfo['description']; ?></textarea>
            <small class="form-text text-muted">Descripción breve del servidor (máximo 500 caracteres)</small>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" name="save_serverinfo" class="btn btn-primary">
                <i class="fa fa-save"></i> Guardar Configuración
            </button>
        </div>
    </div>

</form>
