<?php
            define('access', 'api');
            require '../../../includes/webengine.php';

            
  class access_db{
    


            public function checkMcDbStatus(){
                 $conn = Connection::Database('MuOnline');
                 $check = $conn->query_fetch("SELECT * FROM WEBENGINE_MERCADOPAGO_TRANSACTIONS");
                 if(!$check){
                                $conn->query("create table WEBENGINE_MERCADOPAGO_TRANSACTIONS (
                                    id int IDENTITY(1,1) PRIMARY KEY,
                                    ip_payed varchar(50) NULL,
                                    userID varchar(25) NULL,
                                    user_mercadoPago varchar(50) NULL, 
                                    id_compra varchar(25) NULL,
                                    userMu varchar(50) NULL,
                                    Credits varchar(50) NULL,
                                    descript varchar(50) NULL,
                                    card varchar(50) NULL, 
                                    card_method varchar(50) NULL,
                                    card_name varchar(50) NULL, 
                                    card_dni varchar(50) NULL, 
                                    card_date_create varchar(50) NULL, 
                                    card_date_last_update varchar(50) NULL, 
                                    card_mount varchar(20) NULL, 
                                    type_coin_payed varchar(10) NULL,
                                    state_compra varchar(50) NULL,
                                    detail_compra varchar(50) NULL, 
                                    dato_aprobado varchar(50) NULL
                            );");
                    return true;
                  }
            }



            public function checkDbId($id){
                $conn = Connection::Database('MuOnline');
                $check = $conn->query_fetch("SELECT id_compra FROM WEBENGINE_MERCADOPAGO_TRANSACTIONS WHERE id_compra = '$id'");
                if(!$check){
                    return false;
                }else{
                    return true;
                }
            }


            public function userID($userMu){
                $conn = Connection::Database('MuOnline');
                $check = $conn->query_fetch("SELECT memb_guid FROM MEMB_INFO WHERE memb___id = '$userMu'");
                
                // Log para debugging
                @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', date('Y-m-d H:i:s')." - Looking for user: {$userMu}\n", FILE_APPEND);
                
                if(!$check || empty($check)){
                    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "User NOT found in database\n", FILE_APPEND);
                    return false;
                }else{
                    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "User found: ".print_r($check, true)."\n", FILE_APPEND);
                    return $check;
                }
            }
            
            public function checkDbStatus($id_compra){
                $conn = Connection::Database('MuOnline');
                $check = $conn->query_fetch("SELECT state_compra FROM WEBENGINE_MERCADOPAGO_TRANSACTIONS WHERE id_compra = '$id_compra'");
                foreach($check as $result){
                    $state_compra = $result['state_compra'];
                }
                if($state_compra == "approved"){
                    return true;
                }else{
                    return false;
                }
            }       

            public function success($userID, $Credits, $configId = null){
                try {
                    # user id
                    if(!Validator::UnsignedNumber($userID)) throw new Exception("invalid userid");
                    
                    // Log inicio
                    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', date('Y-m-d H:i:s')." - Success called: UserID={$userID}, Credits={$Credits}, ConfigID={$configId}\n", FILE_APPEND);
                    
                    // common class
                    $common = new common();
                    $accountInfo = $common->accountInformation($userID);
                    if(!is_array($accountInfo)) throw new Exception("invalid account");
                    
                    $creditSystem = new CreditSystem();
                    $selectedConfig = (int)$configId;
                    if($selectedConfig <= 0) {
                        $selectedConfig = (int)mconfig('credit_config');
                    }
                    
                    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "Selected Config: {$selectedConfig}\n", FILE_APPEND);
                    
                    $creditSystem->setConfigId($selectedConfig);
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
                    
                    $result = $creditSystem->addCredits($Credits);
                    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "Credits added successfully: {$Credits}\n\n", FILE_APPEND);
                    
                    return true;
                    
                } catch (Exception $ex) {
                    @file_put_contents(sys_get_temp_dir().DIRECTORY_SEPARATOR.'webengine-mercadopago-debug.log', "ERROR: ".$ex->getMessage()."\n\n", FILE_APPEND);
                    return false;
                }
            }    


            public function registerPayDb($data){
                                       
                //REGISTRAMOS EN LA BASE DE DATOS MCPAGO LOS DATOS
                $conn = Connection::Database('MuOnline');
              
                $query = "INSERT into WEBENGINE_MERCADOPAGO_TRANSACTIONS (ip_payed, userID, user_mercadoPago, id_compra, userMu, Credits, descript, card, 
                card_method, card_name, card_dni, card_date_create, card_date_last_update, card_mount, type_coin_payed, state_compra, detail_compra, dato_aprobado) "
                ."VALUES "  
			    . "(:ip_payed, :userID, :user_mercadoPago, :id_compra, :userMu, :Credits, :descript, :card, :card_method, :card_name, :card_dni, :card_date_create,
                :card_date_last_update, :card_mount, :type_coin_payed, :state_compra, :detail_compra, :dato_aprobado)";

                $saveConfig = $conn->query($query, $data);
            }

            public function Update($id, $check_state, $check_detail_state, $check_date_approved){
                                       
                //ACTUALIZAMOS LA DB
                $conn = Connection::Database('MuOnline');
				
				$data = array('id' => $id, 'check_state' => $check_state, 'check_detail_state' => $check_detail_state, 'check_date_approved' => $check_date_approved);
                $query = "UPDATE WEBENGINE_MERCADOPAGO_TRANSACTIONS SET state_compra = :check_state, detail_compra = :check_detail_state, 
				dato_aprobado = :check_date_approved WHERE id_compra = :id";

                $saveConfig = $conn->query($query, $data);
				
				return $saveConfig;

			}
 
    }

?>
