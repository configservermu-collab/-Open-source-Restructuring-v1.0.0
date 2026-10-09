<?php
(!isLoggedIn()) ? redirect(1,'login') : null;

include 'api/mercadopago/vendor/autoload.php';
loadModuleConfigs('donation.mercadopago');

$common = new common();
$accountInfo = $common->accountInformation($_SESSION['userid']);

$creditSelected = mconfig('credit_selected') ?: 'Credits';
$creditCatalog = [];

try {
    $creditSystem = new CreditSystem();
    $creditConfigs = $creditSystem->showConfigs();
    if(is_array($creditConfigs)) {
        foreach($creditConfigs as $cfg) {
            $creditCatalog[(int)$cfg['config_id']] = $cfg['config_title'];
        }
    }
} catch (Exception $ignored) {
    // fallback handled via default label
}

$packs = [];
for ($i = 1; $i <= 10; $i++) {
    $price = (float)mconfig('pack_'.$i.'_price');
    $credits = (int)mconfig('pack_'.$i.'_credits');
    $currencyId = (int)mconfig('pack_'.$i.'_currency');
    $imageRaw = trim((string)mconfig('pack_'.$i.'_image'));
    $imageUrl = '';
    if($imageRaw !== '') {
        if(preg_match('#^(https?:)?//#i', $imageRaw) || strpos($imageRaw, 'data:') === 0) {
            $imageUrl = $imageRaw;
        } else {
            $imageUrl = __BASE_URL__ . ltrim($imageRaw, '/');
        }
    }
    if($price <= 0 || $credits <= 0) continue;
    $packs[] = [
        'index' => $i,
        'price' => $price,
        'credits' => $credits,
        'currencyId' => $currencyId,
        'currencyLabel' => ($currencyId && isset($creditCatalog[$currencyId])) ? $creditCatalog[$currencyId] : $creditSelected,
        'imageUrl' => $imageUrl,
    ];
}

$token = mconfig('access_token');
$titulo = mconfig('mercadopago_title');
$description = mconfig('mercadopago_description');
$tipoDeMoneda = mconfig('mercadopago_currency'); 
$user = $accountInfo[_CLMN_USERNM_];
$success = mconfig('mercadopago_return_url');  
$returnApi = mconfig('mercadopago_api_return_url'); 
$packsAvailable = count($packs);
$mercadopagoActive = (bool)mconfig('active');
$paymentsReady = $mercadopagoActive && $token !== '';

if($paymentsReady) {
    MercadoPago\SDK::setAccessToken($token);
}

echo '<div class="page-title"><span>' . htmlspecialchars($titulo) . '</span></div>';  

        if(!$paymentsReady) {
            message('warning', 'MercadoPago no está configurado o se encuentra desactivado.');
        } elseif($packsAvailable === 0) {
            message('warning', 'No hay paquetes disponibles en MercadoPago.');
        } else {
            $defaultImage = '';
            echo '<style>
.donation-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
    margin-top:20px;
    justify-content:center;         /* Centra el contenido */
    text-align:center;              /* Centra texto */
    width:100%;
}

.donation-card{
    background:#0e1017;
    border:1px solid #1f2330;
    border-radius:12px;
    padding:18px;
    display:flex;
    flex-direction:column;
    align-items:center;
    text-align:center;
    box-shadow:0 12px 24px rgba(0,0,0,.35);
}

.donation-card-image{
    width:150px;
    height:150px;
    margin-bottom:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:12px;
    background:linear-gradient(135deg,#1c2230,#0d1018);
    overflow:hidden;
}

.donation-card-image img{
    width:150px;
    height:150px;
    object-fit:cover;
}

.donation-card-title{
    font-size:18px;
    font-weight:700;
    color:#f5f6fb;
    margin-bottom:8px;
}

.donation-card-price{
    background:#1f2a3a;
    color:#d6def0;
    padding:6px 12px;
    border-radius:999px;
    font-size:13px;
    margin-bottom:16px;
}

.donation-card a.donation-card-button{
    background:#09b56d;
    color:#12141d;
    border:none;
    padding:10px 18px;
    border-radius:999px;
    font-weight:600;
    cursor:pointer;
    transition:transform .2s ease,box-shadow .2s ease;
    text-decoration:none;
    display:inline-block;
}

.donation-card a.donation-card-button:hover{
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(9,181,109,.35);
}

.donation-card-placeholder{
    width:150px;
    height:150px;
    border-radius:12px;
    background:linear-gradient(135deg,#1c2230,#0d1018);
    display:flex;
    align-items:center;
    justify-content:center;
    color:#63708a;
    font-size:12px;
    text-transform:uppercase;
    font-weight:600;
}
</style>';
            echo '<div class="donation-grid">';
            foreach($packs as $pack) {
                $preference = new MercadoPago\Preference();
                $item = new MercadoPago\Item();
                
                // CORRECCIÓN: external_reference con formato username|credits|currencyId
                $payload = $user.'|'.$pack['credits'].'|'.$pack['currencyId'];
                
                $item->id = 'pack'.$pack['index'];
                $item->title = $titulo.' - '.$pack['credits'].' '.$pack['currencyLabel'];
                $item->description = $titulo.' - '.$pack['credits'].' '.$pack['currencyLabel'];
                $item->category_id = 'home';
                $item->quantity = 1;
                $item->unit_price = $pack['price'];
                if($pack['imageUrl'] !== '') {
                    $item->picture_url = $pack['imageUrl'];
                }
                $preference->items = array($item);
                
                // CORRECCIÓN: Agregar external_reference
                $preference->external_reference = $payload;
                
                $preference->back_urls = array(
                    'success' => $success,
                    'failure' => $success,
                    'pending' => $success
                );
                $preference->auto_return = 'approved';
                $preference->notification_url = $returnApi;
                $preference->save();

                $amountDisplay = number_format($pack['price'], 2, '.', '');
                $imageUrl = $pack['imageUrl'] !== '' ? $pack['imageUrl'] : $defaultImage;
                $resolvedImage = htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8');
                $title = htmlspecialchars($pack['credits'].' '.$pack['currencyLabel'], ENT_QUOTES, 'UTF-8');
                $priceLabel = htmlspecialchars('Precio: '.$tipoDeMoneda.' '.$amountDisplay, ENT_QUOTES, 'UTF-8');

                echo '<div class="donation-card">';
                if($imageUrl !== '') {
                    echo '<div class="donation-card-image"><img src="'.$resolvedImage.'" alt="'.$title.'"></div>';
                } else {
                    echo '<div class="donation-card-placeholder">150x150</div>';
                }
                    echo '<div class="donation-card-title">'.$title.'</div>';
                    echo '<div class="donation-card-price">'.$priceLabel.'</div>';
                    echo '<a class="donation-card-button" href="'.htmlspecialchars($preference->init_point, ENT_QUOTES, 'UTF-8').'" target="_blank" rel="noopener">Comprar Ahora!</a>';
                echo '</div>';
            }
            echo '</div>';
        }

        $terms = mconfig('terms_and_conditions');
        $contact = mconfig('contact_info');

        echo '<div class="terms-contact" style="margin-top: 40px; padding: 20px; background: #111; color: #ccc; border-radius: 5px; max-width: 800px; margin-left: auto; margin-right: auto; text-align: center;">';

        if (!empty($terms)) {
            echo '<p style="margin-bottom: 10px;"><strong>⚠️ Términos y Condiciones:</strong><br>' . nl2br(htmlspecialchars($terms, ENT_QUOTES, 'UTF-8')) . '</p>';
        }

        if (!empty($contact)) {
            echo '<p><strong>📧 Contacto:</strong><br>';
            $contactHref = filter_var($contact, FILTER_VALIDATE_EMAIL) ? 'mailto:'.$contact : $contact;
            echo '<a href="'.htmlspecialchars($contactHref, ENT_QUOTES, 'UTF-8').'" target="_blank" style="display:inline-block; margin-top:5px; padding:6px 12px; background:#28a745; color:#fff; text-decoration:none; border-radius:4px;">Contactar ahora</a>';
            echo '</p>';
        }

        echo '</div>';
?>