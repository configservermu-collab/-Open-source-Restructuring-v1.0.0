<h1 class="page-header">MercadoPago Donations</h1>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nombre de tabla (ajustá si usás otro)
define('WEBENGINE_MERCADOPAGO_TRANSACTIONS', 'WEBENGINE_MERCADOPAGO_TRANSACTIONS');

// Ruta config
$moduleConfigFile = __PATH_MODULE_CONFIGS__ . 'mercadopago.xml';

// Cargar config del módulo
if(file_exists($moduleConfigFile)) {
    $xml = simplexml_load_file($moduleConfigFile);
    $moduleConfig = json_decode(json_encode($xml), true);
} else {
    $moduleConfig = [];
}

// Guardar config si se envía el formulario
if(isset($_POST['save_config']) && is_array($_POST['config'])) {
    $newConfig = new SimpleXMLElement('<?xml version="1.0" encoding="ISO-8859-1"?><settings></settings>');
    foreach($_POST['config'] as $key => $value) {
        $newConfig->addChild($key, htmlspecialchars($value));
    }
    $newConfig->asXML($moduleConfigFile);
    message('success', 'Configuración guardada correctamente.');
    $moduleConfig = json_decode(json_encode(simplexml_load_file($moduleConfigFile)), true);
}

// Mostrar switch para activar/desactivar el módulo
$moduleActive = (isset($moduleConfig['active']) && $moduleConfig['active'] == 1);
echo '<form method="post">';
echo '<div class="form-group">';
echo '<label>Estado del Módulo MercadoPago:</label> ';
echo '<select name="config[active]" class="form-control" style="width:auto;display:inline-block;">';
echo '<option value="1" '.($moduleActive ? 'selected' : '').'>✅ Activado</option>';
echo '<option value="0" '.(!$moduleActive ? 'selected' : '').'>❌ Desactivado</option>';
echo '</select> ';
echo '<button type="submit" name="save_config" class="btn btn-primary btn-sm">Guardar</button>';
echo '</div>';
echo '</form>';

// Si está desactivado, salir
if(!$moduleActive) {
    message('warning', 'El módulo MercadoPago está desactivado.');
    return;
}

try {
    $database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);

    $mercadopagolDonations = $database->query_fetch("SELECT * FROM ".WEBENGINE_MERCADOPAGO_TRANSACTIONS." ORDER BY id DESC");
    if(!is_array($mercadopagolDonations)) throw new Exception("No hay donaciones de MercadoPago registradas.");

    echo '<table id="paypal_donations" class="table table-condensed table-hover">';
    echo '<thead>';
        echo '<tr>';
            echo '<th>Transaction ID</th>';
            echo '<th>Account</th>';
            echo '<th>Amount</th>';
            echo '<th>User MP</th>';
            echo '<th>Date</th>';
            echo '<th>Status</th>';
        echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach($mercadopagolDonations as $data) {
        $userData = $common->accountInformation($data['userID']);
        $accountName = (is_array($userData) && isset($userData[_CLMN_USERNM_])) ? $userData[_CLMN_USERNM_] : '<i>Desconocido</i>';
        $donation_status = ($data['state_compra'] == "approved" ? '<span class="badge badge-success">ok</span>' : '<span class="badge badge-important">reversed</span>');

        $fecha = $data['dato_aprobado'] ?: $data['card_date_create'];
        $convert = date('d-m-Y H:i:s', strtotime($fecha));

        echo '<tr>'; 
            echo '<td>'.$data['id_compra'].'</td>';
            echo '<td><a href="'.admincp_base("accountinfo&id=".$data['userID']).'">'.$accountName.'</a></td>';
            echo '<td>$'.$data['card_mount'].'</td>';
            echo '<td>'.$data['user_mercadoPago'].'</td>';
            echo '<td>'.$convert.'</td>';
            echo '<td>'.$donation_status.'</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} catch(Exception $ex) {
    message('error', $ex->getMessage());
}
?>
