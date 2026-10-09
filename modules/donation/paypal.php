<?php
(!isLoggedIn()) ? redirect(1,'login') : null;

// Load PayPal Settings
loadModuleConfigs('donation.paypal');

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
    // Ignore catalog errors, fallback labels will be used.
}

$packs = [];
for($i = 1; $i <= 10; $i++) {
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

$paypalCurrency = mconfig('paypal_currency');
$paypalAction = mconfig('paypal_enable_sandbox') ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

$pageTitle = mconfig('paypal_title') ?: lang('module_titles_txt_21',true);
echo '<div class="page-title">'.lang('module_titles_txt_11',true).' &rarr; '.$pageTitle.'</div>';

        if(mconfig('active')) {

            if(empty($packs)) {
                message('warning', 'No hay paquetes configurados.');
            } else {
                $defaultImage = '';
                echo '<style>
.donation-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;margin-top:20px}
.donation-card-form{margin:0}
.donation-card{background:#0e1017;border:1px solid #1f2330;border-radius:12px;padding:18px;display:flex;flex-direction:column;align-items:center;text-align:center;box-shadow:0 12px 24px rgba(0,0,0,.35)}
.donation-card-image{width:150px;height:150px;margin-bottom:14px;display:flex;align-items:center;justify-content:center;border-radius:12px;background:linear-gradient(135deg,#1c2230,#0d1018);overflow:hidden}
.donation-card-image img{width:150px;height:150px;object-fit:cover}
.donation-card-title{font-size:18px;font-weight:700;color:#f5f6fb;margin-bottom:8px}
.donation-card-price{background:#1f2a3a;color:#d6def0;padding:6px 12px;border-radius:999px;font-size:13px;margin-bottom:16px}
.donation-card button{background:#f8b400;color:#12141d;border:none;padding:10px 18px;border-radius:999px;font-weight:600;cursor:pointer;transition:transform .2s ease,box-shadow .2s ease}
.donation-card button:hover{transform:translateY(-2px);box-shadow:0 6px 18px rgba(248,180,0,.35)}
.donation-card-placeholder{width:150px;height:150px;border-radius:12px;background:linear-gradient(135deg,#1c2230,#0d1018);display:flex;align-items:center;justify-content:center;color:#63708a;font-size:12px;text-transform:uppercase;font-weight:600}
                </style>';
                echo '<div class="donation-grid">';

                foreach($packs as $pack) {
                    $itemNumber = sprintf('pack%02dc%02d-%s', $pack['index'], max(0, $pack['currencyId']), uniqid());
                    $amountValue = number_format($pack['price'], 2, '.', '');
                    $imageUrl = $pack['imageUrl'] !== '' ? $pack['imageUrl'] : $defaultImage;
                    $resolvedImage = htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8');
                    $title = htmlspecialchars($pack['credits'].' '.$pack['currencyLabel'], ENT_QUOTES, 'UTF-8');
                    $priceLabel = htmlspecialchars('Precio: '.$paypalCurrency.' '.$amountValue, ENT_QUOTES, 'UTF-8');

                    echo '<form action="'.$paypalAction.'" method="post" class="donation-card-form">';
                    echo '<input type="hidden" name="cmd" value="_xclick" />';
                    echo '<input type="hidden" name="business" value="'.htmlspecialchars(mconfig('paypal_email'), ENT_QUOTES, 'UTF-8').'" />';
                    echo '<input type="hidden" name="item_name" value="'.htmlspecialchars(mconfig('paypal_title').' - Pack '.$pack['index'], ENT_QUOTES, 'UTF-8').'" />';
                    echo '<input type="hidden" name="item_number" value="'.$itemNumber.'" />';
                    echo '<input type="hidden" name="currency_code" value="'.htmlspecialchars($paypalCurrency, ENT_QUOTES, 'UTF-8').'" />';
                    echo '<input type="hidden" name="amount" value="'.$amountValue.'" />';
                    echo '<input type="hidden" name="no_shipping" value="1" />';
                    echo '<input type="hidden" name="shipping" value="0.00" />';
                    echo '<input type="hidden" name="return" value="'.htmlspecialchars(mconfig('paypal_return_url'), ENT_QUOTES, 'UTF-8').'" />';
                    echo '<input type="hidden" name="cancel_return" value="'.htmlspecialchars(mconfig('paypal_return_url'), ENT_QUOTES, 'UTF-8').'" />';
                    echo '<input type="hidden" name="notify_url" value="'.htmlspecialchars(mconfig('paypal_notify_url'), ENT_QUOTES, 'UTF-8').'" />';
                    echo '<input type="hidden" name="custom" value="'.$_SESSION['userid'].'" />';
                    echo '<input type="hidden" name="no_note" value="1" />';
                    echo '<input type="hidden" name="tax" value="0.00" />';

                    echo '<div class="donation-card">';
                    if($imageUrl !== '') {
                        echo '<div class="donation-card-image"><img src="'.$resolvedImage.'" alt="'.$title.'"></div>';
                    } else {
                        echo '<div class="donation-card-placeholder">150x150</div>';
                    }
                        echo '<div class="donation-card-title">'.$title.'</div>';
                        echo '<div class="donation-card-price">'.$priceLabel.'</div>';
                        echo '<button type="submit">Comprar Ahora!</button>';
                    echo '</div>';
                    echo '</form>';
                }

                echo '</div>';

                $terms = mconfig('terms_and_conditions');
                $contact = mconfig('contact_info');

                echo '<div class="terms-contact" style="margin: 40px auto 0 auto; padding: 20px; background: #111; color: #ccc; border-radius: 5px; max-width: 800px; text-align: center;">';

                if(!empty($terms)) {
                    echo '<p style="margin-bottom: 15px;"><strong>⚠️ Términos y Condiciones:</strong><br>' . nl2br(htmlspecialchars($terms, ENT_QUOTES, 'UTF-8')) . '</p>';
                }

                if(!empty($contact)) {
                    echo '<p>';
                    $contactHref = filter_var($contact, FILTER_VALIDATE_EMAIL) ? 'mailto:'.$contact : $contact;
                    echo '<a href="'.htmlspecialchars($contactHref, ENT_QUOTES, 'UTF-8').'" target="_blank" style="display:inline-block; margin-top:10px; padding:8px 16px; background:#28a745; color:#fff; text-decoration:none; border-radius:4px;">Contactar ahora</a>';
                    echo '</p>';
                }

                echo '</div>';
            }

        } else {
            message('error', lang('error_47',true));
        }

?>
