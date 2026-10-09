<?php

define('access', 'api');

include('../includes/webengine.php');

// common class
$common = new common();

// Load PayPal Settings
loadModuleConfigs('donation.paypal');

@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "\n\n=== NEW PAYPAL IPN ===\n".date('Y-m-d H:i:s')."\n", FILE_APPEND);

  $raw_post_data = file_get_contents('php://input');
  $raw_post_array = explode('&', $raw_post_data);
  $myPost = array();
  foreach ($raw_post_array as $keyval)
  {
      $keyval = explode ('=', $keyval);
      if (count($keyval) == 2)
         $myPost[$keyval[0]] = urldecode($keyval[1]);
  }
  $req = 'cmd=_notify-validate';
  $get_magic_quotes_exits = false;
  if(function_exists('get_magic_quotes_gpc'))
  {
       $get_magic_quotes_exits = true;
  } 
  foreach ($myPost as $key => $value)
  {        
       if($get_magic_quotes_exits == true && get_magic_quotes_gpc() == 1)
       { 
            $value = urlencode(stripslashes($value)); 
       }
       else
       {
            $value = urlencode($value);
       }
       $req .= "&$key=$value";
  }

$ch = curl_init();

/* check if sandbox is enabled */
if(mconfig('paypal_enable_sandbox')) {
	curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
} else {
	curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.paypal.com/cgi-bin/webscr');
}

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: company-name'));
$res = curl_exec($ch);
$curl_error = curl_error($ch);
curl_close($ch);

@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "IPN Verification Result: ".trim((string)$res)."\n", FILE_APPEND);
if($curl_error) {
	@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "CURL Error: {$curl_error}\n", FILE_APPEND);
}
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "POST Data: ".print_r($_POST, true)."\n", FILE_APPEND);
 
// assign posted variables to local variables
$item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
$item_number = isset($_POST['item_number']) ? $_POST['item_number'] : ''; // order id = md5(time())
$payment_status = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';
$payment_amount = isset($_POST['mc_gross']) ? (float)$_POST['mc_gross'] : 0;
$payment_currency = isset($_POST['mc_currency']) ? $_POST['mc_currency'] : '';
$txn_id = isset($_POST['txn_id']) ? $_POST['txn_id'] : '';
$txn_type = isset($_POST['txn_type']) ? $_POST['txn_type'] : '';
$receiver_email = isset($_POST['receiver_email']) ? $_POST['receiver_email'] : '';
$payer_email = isset($_POST['payer_email']) ? $_POST['payer_email'] : '';
$account_id = isset($_POST['custom']) ? trim($_POST['custom']) : '';
$tax = isset($_POST['tax']) ? (float)$_POST['tax'] : 0;

@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Payment Status: {$payment_status}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Transaction Type: {$txn_type}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Transaction ID: {$txn_id}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Item Number: {$item_number}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Amount: {$payment_amount} {$payment_currency}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Receiver Email: {$receiver_email}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Payer Email: {$payer_email}\n", FILE_APPEND);
@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Account ID (custom): {$account_id}\n", FILE_APPEND);

// Frontend can send plain userid in `custom`; keep backward compatibility with encoded values.
$user_id = 0;
if(Validator::UnsignedNumber($account_id)) {
	$user_id = (int)$account_id;
} elseif(function_exists('Decode')) {
	$decoded_user_id = Decode($account_id);
	if(Validator::UnsignedNumber($decoded_user_id)) {
		$user_id = (int)$decoded_user_id;
	}
}

@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "User ID (resolved): {$user_id}\n", FILE_APPEND);

if (trim((string)$res) === "VERIFIED") {
	@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "IPN VERIFIED\n", FILE_APPEND);
	
	if(strtolower($receiver_email) == strtolower(mconfig('paypal_email'))) {
		@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Email matched\n", FILE_APPEND);
		if(($txn_type == 'web_accept' OR $txn_type == 'subscr_payment') AND $payment_status == 'Completed') {
			@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Payment completed\n", FILE_APPEND);
			if($tax > 0) { $payment_amount -=$tax; }
			

		/* Donation amount */
		$add_credits = 0;
		$packId = 0;
		$packCurrencyId = (int)mconfig('credit_config');
		if(preg_match('/pack(\d{2})c(\d+)-/i', $item_number, $matchPack)) {
			$packId = (int)$matchPack[1];
			$hintCurrency = (int)$matchPack[2];
			if($hintCurrency > 0) $packCurrencyId = $hintCurrency;
		}
		if($packId >= 1 && $packId <= 10) {
			$add_credits = (int)mconfig('pack_'.$packId.'_credits');
			$configuredCurrency = (int)mconfig('pack_'.$packId.'_currency');
			if($configuredCurrency > 0) $packCurrencyId = $configuredCurrency;
		} else {
			for($i = 1; $i <= 10; $i++) {
				$price = (float)mconfig('pack_'.$i.'_price');
				$credits = (int)mconfig('pack_'.$i.'_credits');
				if($price > 0 && $credits > 0 && abs($price - (float)$payment_amount) < 0.01) {
					$add_credits = $credits;
					$packId = $i;
					$configuredCurrency = (int)mconfig('pack_'.$i.'_currency');
					if($configuredCurrency > 0) $packCurrencyId = $configuredCurrency;
					break;
				}
			}
		}
		if($add_credits <= 0) {
			@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "ERROR: No credits resolved from pack/amount\n\n", FILE_APPEND);
			return;
		}
		
			/* Add credits */
			try {
				# user id
				if(!Validator::UnsignedNumber($user_id)) throw new Exception("invalid userid");
				
				# account info
				$accountInfo = $common->accountInformation($user_id);
				if(!is_array($accountInfo)) throw new Exception("invalid account");
				
				$creditSystem = new CreditSystem();
				$creditSystem->setConfigId($packCurrencyId > 0 ? $packCurrencyId : mconfig('credit_config'));
				$configSettings = $creditSystem->showConfigs(true);
				switch($configSettings['config_user_col_id']) {
					case 'userid':
						$creditSystem->setIdentifier($accountInfo[_CLMN_MEMBID_]);
						break;
					case 'username':
						$creditSystem->setIdentifier($accountInfo[_CLMN_USERNM_]);
						break;
					case 'email':
						$creditSystem->setIdentifier($accountInfo[_CLMN_EMAIL_]);
						break;
					default:
						throw new Exception("invalid identifier");
				}
				
				$_GET['page'] = 'api';
				$_GET['subpage'] = 'paypal';
				
				$creditSystem->addCredits($add_credits);
				@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Credits added: {$add_credits}\n", FILE_APPEND);
			} catch (Exception $ex) {
				@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "ERROR in addCredits: ".$ex->getMessage()."\n\n", FILE_APPEND);
				die();
			}
			
			/* Create transaction */
			$common->paypal_transaction($txn_id,$user_id,$payment_amount,$payer_email,$item_number);
			@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Transaction logged\n\n", FILE_APPEND);
			
		} elseif($payment_status == 'Reversed' OR $payment_status == 'Refunded') {
			@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Payment reversed/refunded\n", FILE_APPEND);
			
			/* block account */
			$common->blockAccount($user_id);
			
			/* update transaction */
			$common->paypal_transaction_reversed_updatestatus($item_number);
			@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Transaction marked reversed\n\n", FILE_APPEND);
		} else {
			@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "Payment not completed: {$payment_status}\n\n", FILE_APPEND);
			
		}
	} else {
		@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "ERROR: Receiver email mismatch\n\n", FILE_APPEND);
	
	}
}
else if (trim((string)$res) === "INVALID") {
	@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "IPN INVALID\n\n", FILE_APPEND);
} else {
	@file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-paypal-debug.log', "IPN unknown response: ".trim((string)$res)."\n\n", FILE_APPEND);
}
