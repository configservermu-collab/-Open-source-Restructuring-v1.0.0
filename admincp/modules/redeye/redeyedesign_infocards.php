<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * RedEye Design Module - Info Cards Configuration
 * Gestión de tarjetas de información
 */

if(!defined('access') or !access) die();

if(!defined('__PATH_CACHE__')) {
    define('__PATH_CACHE__', dirname(__FILE__) . '/../../../includes/cache/');
}

// Cargar configuración actual
$infoCardsFile = __PATH_CACHE__ . 'redeyedesign_infocards.cache';
$infoCards = array();

if(file_exists($infoCardsFile)) {
    $decoded = json_decode(file_get_contents($infoCardsFile), true);
    if(is_array($decoded)) {
        $infoCards = $decoded;
    }
}

// Inicializar valores por defecto
$infoCards = array_merge(array(
    'cards' => array()
), $infoCards);

// Procesar acciones
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if(isset($_POST['add_card'])) {
        $newCard = array(
            'id' => uniqid(),
            'title' => isset($_POST['title']) ? trim($_POST['title']) : 'Nueva Tarjeta',
            'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
            'icon' => isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-info-circle',
            'color' => isset($_POST['color']) ? trim($_POST['color']) : '#cc0000',
            'link' => isset($_POST['link']) ? trim($_POST['link']) : '',
            'new_window' => isset($_POST['new_window']) ? 1 : 0
        );
        $infoCards['cards'][] = $newCard;
        $message = 'Tarjeta agregada correctamente';
    }
    
    if(isset($_POST['delete_card'])) {
        $cardId = isset($_POST['card_id']) ? trim($_POST['card_id']) : '';
        $infoCards['cards'] = array_filter($infoCards['cards'], function($card) use ($cardId) {
            return $card['id'] !== $cardId;
        });
        $infoCards['cards'] = array_values($infoCards['cards']);
        $message = 'Tarjeta eliminada correctamente';
    }
    
    if(isset($_POST['save_infocards'])) {
        file_put_contents($infoCardsFile, json_encode($infoCards, JSON_PRETTY_PRINT));
        $message = 'Tarjetas guardadas correctamente';
    }
}

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Tarjetas de Información</h3>
            </div>
            <div class="panel-body">
                
                <?php if(isset($message)): ?>
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <?php if(isset($infoCards['cards']) && is_array($infoCards['cards']) && count($infoCards['cards']) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Icono</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Color</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($infoCards['cards'] as $index => $card): ?>
                        <tr>
                            <td>
                                <i class="fas <?php echo $card['icon']; ?>" style="color: <?php echo $card['color']; ?>; font-size: 20px;"></i>
                            </td>
                            <td><?php echo $card['title']; ?></td>
                            <td><?php echo substr($card['description'], 0, 50) . (strlen($card['description']) > 50 ? '...' : ''); ?></td>
                            <td>
                                <span style="display: inline-block; width: 30px; height: 30px; background-color: <?php echo $card['color']; ?>;"></span>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
                                    <button type="submit" name="delete_card" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta tarjeta?');">
                                        <i class="fa fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="alert alert-info">
                    No hay tarjetas configuradas. Crea una nueva tarjeta.
                </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Agregar Nueva Tarjeta</h3>
            </div>
            <div class="panel-body">
                <form method="POST" class="form-horizontal">
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Título</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" class="form-control" placeholder="Ej: Seguridad" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Descripción</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" rows="3" placeholder="Describe la tarjeta..."></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Icono (Font Awesome)</label>
                        <div class="col-sm-10">
                            <input type="text" name="icon" class="form-control" value="fa-info-circle" 
                                   placeholder="Ej: fa-shield, fa-users, fa-cog">
                            <small class="form-text text-muted">
                                <a href="https://fontawesome.com/icons" target="_blank">Ver iconos disponibles</a>
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Color</label>
                        <div class="col-sm-10">
                            <input type="color" name="color" class="form-control" value="#cc0000" style="height: 40px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Enlace (opcional)</label>
                        <div class="col-sm-10">
                            <input type="text" name="link" class="form-control" placeholder="Ej: /guides">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="new_window">
                                Abrir enlace en ventana nueva
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" name="add_card" class="btn btn-success">
                                <i class="fa fa-plus"></i> Agregar Tarjeta
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
