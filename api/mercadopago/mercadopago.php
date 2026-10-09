<?php
// Mercado Pago SDK
require __DIR__ .  '/vendor/autoload.php';

$YOUR_ACCESS_TOKEN = 'TEST-8828999425313737-021723-21e7547256e7c0bb93a25b1dbd99fd87-716952461';
// Add Your credentials
MercadoPago\SDK::setAccessToken($YOUR_ACCESS_TOKEN);

// Create a preference object
$preference = new MercadoPago\Preference();

// Create a preference item
$item = new MercadoPago\Item();
$item->id = "12345";
$item->title = 'ruben|800|Servicio de WCoinC';

$item->description = "Servicio asociado al Mu Online";
$item->category_id = "home";

$item->quantity = 1;
$item->unit_price = 380;
$preference->items = array($item);

$preference->notification_url = "http://181.228.156.66/webserver/api/mercadopago/resource/success.php?source_news=webhooks";

$preference->save();
?>

