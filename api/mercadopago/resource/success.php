<?php
   
    require 'class/class.php'; 

    loadModuleConfigs('donation.mercadopago');
    
    $raw_post_data = file_get_contents('php://input');
    $json_string = json_decode($raw_post_data, true);
    
    $myPost = array();
    $id = $json_string['data']['id'];
    $action = $json_string['action'];
    
    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "\n\n=== NEW WEBHOOK ===\n".date('Y-m-d H:i:s')."\n", FILE_APPEND);
    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "Action: {$action}\n", FILE_APPEND);
    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "Payment ID: {$id}\n", FILE_APPEND);

    if($action == "payment.created" || $action == "payment.updated"){
     
        $ACCESS_TOKEN = mconfig('access_token');;
        $url = 'https://api.mercadopago.com/v1/payments/'.$id.'?access_token='.$ACCESS_TOKEN;

        $response = file_get_contents($url);

        if($response){
        
            $json = json_decode($response, true);
            
            @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "Payment Status: ".$json["status"]."\n", FILE_APPEND);
            @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "External Reference: ".$json["external_reference"]."\n", FILE_APPEND);
            
            //IP DEL COMPRADOR
            $check_ip_payed = $json["additional_info"]["ip_address"];
            //USUARIO DE MERCADO PAGO DEL COMPRADOR
            $check_user_mc_payed = $json["statement_descriptor"];
            //ID DE LA COMPRA
            $check_id_bought = $json["id"];
            //DESCRIPCION DE LA COMPRA (ya no la usamos para datos)
            $check_description = $json["description"];
            //NOMBRE DE LA TARJETA UTILIZADA
            $check_card = $json["payment_method_id"];
            //METODO DE PAGO
            $check_method_payed = $json["payment_type_id"];
            //Nombre propietario de la tarjeta
            $check_card_name = $json["card"]["cardholder"]["name"];
            //DNI propietario de la tarjeta
            $check_card_dni = $json["card"]["cardholder"]["identification"]["number"];
            //Fecha de cración
            $check_date_create = $json["date_created"];

            //Fecha de ultima actualización
            $check_last_date_update = $json["card"]["date_last_updated"];
            //MONTO PAGADO
            $check_total_payed = $json["transaction_amount"];
            //TIPO DE MONEDA DE PAGO
            $check_type_coin = $json["currency_id"];
            //ESTADO DE LA COMPRA
            $check_state = $json["status"];
            //DETALLE DEL ESTADO DE LA COMPRA
            $check_detail_state = $json["status_detail"];
            //Dato aprobado
            $check_date_approved = $json["date_approved"];
            
            //CORRECCIÓN: Leer desde external_reference en lugar de description
            $external_ref = $json["external_reference"] ?? '';
            $datoCompra = explode ("|", (string)$external_ref);
            $userMu = trim((string)($datoCompra[0] ?? ''));
            $Credits = isset($datoCompra[1]) ? (int)$datoCompra[1] : 0;
            $currencyConfig = isset($datoCompra[2]) ? (int)$datoCompra[2] : (int)mconfig('credit_config');

            // Log para debugging
            @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "External Ref: {$external_ref}\n", FILE_APPEND);
            @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "User: {$userMu}, Credits: {$Credits}, CurrencyID: {$currencyConfig}\n", FILE_APPEND);

            //Buscamos el user Id
            $checkUserID = access_db::userID($userMu);
            $userID = null;
            
            if($checkUserID && is_array($checkUserID)){
                if(isset($checkUserID['memb_guid'])){
                    $userID = $checkUserID['memb_guid'];
                } elseif(isset($checkUserID[0]) && is_array($checkUserID[0]) && isset($checkUserID[0]['memb_guid'])) {
                    $userID = $checkUserID[0]['memb_guid'];
                }
            }

            if(!$userID && $checkUserID && !is_array($checkUserID)){
                $userID = $checkUserID;
            }
            
            @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "UserID found: ".($userID ? $userID : 'NULL')."\n", FILE_APPEND);
            
            // Si no encontramos el userID, salir
            if(!$userID){
                @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "ERROR: UserID not found, aborting\n\n", FILE_APPEND);
                die('User not found');
            }

            //creamos un array con toda la info
            $data = array(
                'ip_payed' => $check_ip_payed,
                'userID' => $userID,
                'user_mercadoPago' => $check_user_mc_payed,
                'id_compra' => $check_id_bought,
                'userMu' => $userMu,
                'Credits' => $Credits,
                'descript' => $check_description,
                'card' => $check_card,
                'card_method' => $check_method_payed,
                'card_name' => $check_card_name,
                'card_dni' => $check_card_dni,
                'card_date_create' => $check_date_create,
                'card_date_last_update' => $check_last_date_update,
                'card_mount' => $check_total_payed,
                'type_coin_payed' => $check_type_coin,
                'state_compra' => $check_state,
                'detail_compra' => $check_detail_state,
                'dato_aprobado' => $check_date_approved,
            );

            //Si no existe una base de datos de Mercado Pago La creamos!
            access_db::checkMcDbStatus();

                //BUSCAMOS EN LA DB QUE ESE ID ESTE REGISTRADO
                $checkDbId = access_db::checkDbId($id);

                //BUSCAMOS EN LA DB SI ESTA REGISTRADO COMO PAGADO
                $checkDbStatus = access_db::checkDbStatus($id);


            //MP INFORMA QUE EL PAGO ESTA APROBADO Y ACREDITADO 
            if($check_state === "approved" && $check_detail_state === "accredited"){
                
                //SI NO SE ENCUENTRÒ EL ID EN LA BASE DE DATOS, REGISTRAMOS EL NUEVO PAGO EN CASHOPDATA
                if(!$checkDbId){
                    // Registrar primero en DB para evitar duplicados
                    access_db::registerPayDb($data);
                    // Luego acreditar los coins
                    access_db::success($userID, $Credits, $currencyConfig);

                }else{//<--- EL PAGO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS
                    if(!$checkDbStatus){//<-- EL PAGO NO ESTA REGISTRADO COMO Approved EN LA DB
                        // Actualizar primero el estado
                        access_db::Update($id, $check_state, $check_detail_state, $check_date_approved);
                        // Luego acreditar los coins
                        access_db::success($userID, $Credits, $currencyConfig);
						
                    }
                }
            //Si el pago esta Pendiente u otro Motivo
            }else{
                //SI NO SE ENCUENTRÒ EL ID EN LA BASE DE DATOS, REGISTRAMOS EN LA DB DE MERCADO PAGO (Q EL PAGO NO ESTA SUCCESS)
                if(!$checkDbId){
                    access_db::registerPayDb($data);

                }
            }
           
            

             
        }
     
    }
?>