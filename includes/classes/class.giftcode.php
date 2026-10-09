<?php
/**
 * GiftCode class – WebEngine CMS
 * Limpieza de deprecated properties y timezone válido (PHP 8.2+)
 */

class GiftCode {

    /** @var common */
    private $common;

    /** @var Database */
    private $dB;

    /* --------------------------------------------------------- */
    /** CONSTRUCTOR */
    public function __construct() {

        // Common helpers
        $this->common = new common();

        // Conexión a MuOnline
        $this->dB = Connection::Database('MuOnline');
    }
    /* --------------------------------------------------------- */
    /* -------------- MÉTODOS AUXILIARES / QUERIES ------------- */

    public function SetDefaultClass($Character_Class) {
        switch($Character_Class) {
            case 0:  case 1:  case 2:  case 3:  return 0;          // SM
            case 16: case 17: case 18: case 19: return 16;         // BK
            case 32: case 33: case 34: case 35: return 32;         // ELF
            case 48: case 49: case 50:         return 48;         // MG
            case 64: case 65: case 66:         return 64;         // DL
            case 80: case 81: case 82: case 83:return 80;         // SUM
            case 96: case 97: case 98:         return 96;         // RF
            default:                            return 0;
        }
    }

    public function GetStatsChar($Character_Class) {
        return $this->dB->query_fetch_single(
            "SELECT * FROM DefaultClassType WHERE Class = ?", [$Character_Class]
        );
    }

    public function GetClassChar($Character_Name) {
        return $this->dB->query_fetch_single(
            "SELECT Class FROM Character WHERE Name = ?", [$Character_Name]
        );
    }

    public function CheckCodeGift($codigo) {
        if(!check_value($codigo)) return null;
        return $this->dB->query_fetch_single(
            "SELECT * FROM WEBENGINE_GIFT_CODE WHERE Codigo = ?", [$codigo]
        );
    }

    public function CheckAccCharLOGS($Username,$Character_Name,$Codigo) {
        if(!check_value($Username) || !check_value($Character_Name) || !check_value($Codigo)) return null;
        return $this->dB->query_fetch_single(
            "SELECT * FROM WEBENGINE_GIFT_CODE_LOGS WHERE Codigo = ?", [$Codigo]
        );
    }

    public function GetLogs() {
        return $this->dB->query_fetch("SELECT * FROM WEBENGINE_GIFT_CODE_LOGS ORDER BY id DESC");
    }

    private function UserUsedCode($codigo,$Username) {
        return $this->dB->query_fetch_single(
            "SELECT * FROM WEBENGINE_GIFT_CODE_LOGS WHERE Codigo = ? AND Usuario = ?",
            [$codigo,$Username]
        );
    }

    private function CharUsedCode($codigo,$Character_Name) {
        return $this->dB->query_fetch_single(
            "SELECT * FROM WEBENGINE_GIFT_CODE_LOGS WHERE Codigo = ? AND Personaje = ?",
            [$codigo,$Character_Name]
        );
    }

    /* --------------------------------------------------------- */
    /* -------------------- LÓGICA PRINCIPAL ------------------- */

    public function Verificaciones($Username,$Character_Name,$Codigo) {
        try {
            if(!check_value($Username)   || !check_value($Character_Name) || !check_value($Codigo))                 throw new Exception("[GIFT Code] Datos incompletos.");

            if(!$this->common->accountOnline($Username)) { /* ok */ } else                                         throw new Exception("[GIFT Code] Debes estar desconectado.");
            if(!Validator::UsernameLength($Username) || !Validator::AlphaNumeric($Username))                       throw new Exception("[GIFT Code] Usuario inválido.");
            if(!Validator::AlphaNumeric($Codigo))                                                                  throw new Exception("[GIFT Code] Código inválido.");

            $CodeGifResult     = $this->CheckCodeGift($Codigo);
            if(!is_array($CodeGifResult) || $CodeGifResult['Codigo'] != $Codigo)                                   throw new Exception("[GIFT Code] El código no es correcto.");

            if($CodeGifResult['UsedActual'] >= $CodeGifResult['UsedMax'])                                          throw new Exception("[GIFT Code] El código ya alcanzó su límite de usos.");
            if($this->UserUsedCode($Codigo,$Username))                                                             throw new Exception("[GIFT Code] Esta cuenta ya reclamó este código.");
            if($this->CharUsedCode($Codigo,$Character_Name))                                                       throw new Exception("[GIFT Code] Este personaje ya reclamó este código.");

            if($CodeGifResult['Usuario']   != '0' && $CodeGifResult['Usuario']   != $Username)                    throw new Exception("[GIFT Code] Código asignado a otra cuenta.");
            if($CodeGifResult['Personaje'] != '0' && $CodeGifResult['Personaje'] != $Character_Name)              throw new Exception("[GIFT Code] Código asignado a otro personaje.");

            /* --- ejecutar recompensas --- */
            $this->GiftCode($Username,$Character_Name,$Codigo);

        } catch(Exception $ex) {
            message('error',$ex->getMessage());
        }
    }

    public function GiftCode($Username,$Character_Name,$Codigo) {
        try {
            /* ① Zona horaria válida */
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $Fecha = date('Y-m-d H:i:s');

            $CodeGifResult = $this->CheckCodeGift($Codigo);

            /* --- Actualizar personaje (niveles, resets, puntos ...) --- */
            $set  = "UPDATE "._TBL_CHR_." SET ";
            if($CodeGifResult['TypeCode'] == 1) {
                $classInfo   = $this->GetClassChar($Character_Name);
                $baseClass   = $this->SetDefaultClass($classInfo['Class']);
                $baseStats   = $this->GetStatsChar($baseClass);
                $set .= _CLMN_CHR_LVL_.'      = '.$CodeGifResult['Niveles'].', '
                      . _CLMN_CHR_RSTS_.'     = '.$CodeGifResult['Resets'].', '
                      . _CLMN_CHR_GRSTS_.'    = '.$CodeGifResult['MResets'].', '
                      . _CLMN_CHR_STAT_STR_.' = '.$baseStats['Strength'].', '
                      . _CLMN_CHR_STAT_AGI_.' = '.$baseStats['Dexterity'].', '
                      . _CLMN_CHR_STAT_VIT_.' = '.$baseStats['Vitality'].', '
                      . _CLMN_CHR_STAT_ENE_.' = '.$baseStats['Energy'].', '
                      . _CLMN_CHR_STAT_CMD_.' = '.$baseStats['Leadership'].', '
                      . _CLMN_CHR_LVLUP_POINT_.' = '.$CodeGifResult['pLevel'].' ';
            } else {
                $set .= _CLMN_CHR_LVL_.'         = '._CLMN_CHR_LVL_.'+'.$CodeGifResult['Niveles'].', '
                      . _CLMN_CHR_RSTS_.'        = '._CLMN_CHR_RSTS_.'+'.$CodeGifResult['Resets'].', '
                      . _CLMN_CHR_GRSTS_.'       = '._CLMN_CHR_GRSTS_.'+'.$CodeGifResult['MResets'].', '
                      . _CLMN_CHR_LVLUP_POINT_.' = '._CLMN_CHR_LVLUP_POINT_.'+'.$CodeGifResult['pLevel'].' ';
            }
            $set .= "WHERE "._CLMN_CHR_NAME_." = ?";
            $this->dB->query($set, [$Character_Name]);

            /* --- Coins --- */
            if($CodeGifResult['Coins'] > 0) {
                $this->dB->query(
                    "UPDATE CashShopData SET {$CodeGifResult['TypeCoin']} = {$CodeGifResult['TypeCoin']} + ? WHERE AccountID = ?",
                    [$CodeGifResult['Coins'],$Username]
                );
            }

            /* --- VIP --- */
            if($CodeGifResult['VipDays'] > 0) {
                $this->dB->query(
                    "UPDATE "._TBL_MI_." SET "._CLMN_VIP_TYPE_." = ?, "._CLMN_VIP_DATE_." = GETDATE() + ? WHERE "._CLMN_MS_MEMBID_." = ?",
                    [$CodeGifResult['VipType'],$CodeGifResult['VipDays'],$Username]
                );
            }

            /* --- Items --- */
            if($CodeGifResult['DarItem'] == 1) {
                if($CodeGifResult['TypeWare'] == 1) {
                    $this->SetItemGremoryCase($Username,$Character_Name,$CodeGifResult['iGroup'],$CodeGifResult['iIndex'],$CodeGifResult['iLevel']);
                } else {
                    $this->InsertItemWarehouse(
                        $Username,
                        $CodeGifResult['iLevel'],255,0,0,0,0,0,0,0,255,255,255,255,255,
                        $CodeGifResult['iGroup'],$CodeGifResult['iIndex']
                    );
                }
            }

            /* --- Actualizar uso y guardar log --- */
            $this->dB->query("UPDATE WEBENGINE_GIFT_CODE SET UsedActual = UsedActual + 1 WHERE Codigo = ?", [$Codigo]);
            $this->dB->query(
                "INSERT INTO WEBENGINE_GIFT_CODE_LOGS (Codigo,Usuario,Personaje,Fecha) VALUES (?,?,?,?)",
                [$Codigo,$Username,$Character_Name,$Fecha]
            );

            /* --- Mensaje final --- */
            $vipName = ['','Bronce','Plata','Oro'][$CodeGifResult['VipType']] ?? '';
            $msg  = "<b>[GIFT Code]</b> Felicidades, has obtenido con éxito las siguientes recompensas:<br>";
            if($CodeGifResult['pLevel']||$CodeGifResult['Resets']||$CodeGifResult['MResets'])
                $msg .= "<b>[ Personaje ]</b><br>";
            if($CodeGifResult['pLevel'])  $msg .= "- Puntos: +".number_format($CodeGifResult['pLevel'])."<br>";
            if($CodeGifResult['Niveles']) $msg .= "- Nivel:  +".number_format($CodeGifResult['Niveles'])."<br>";
            if($CodeGifResult['Resets'])  $msg .= "- Resets: +".number_format($CodeGifResult['Resets'])."<br>";
            if($CodeGifResult['MResets']) $msg .= "- Master Resets: +".number_format($CodeGifResult['MResets'])."<br>";

            if($CodeGifResult['VipDays']||$CodeGifResult['Coins'])
                $msg .= "<b>[ Cuenta ]</b><br>";
            if($CodeGifResult['VipDays']) $msg .= "- VIP ".$vipName.": ".$CodeGifResult['VipDays']." días<br>";
            if($CodeGifResult['Coins'])   $msg .= "- Coins: +".number_format($CodeGifResult['Coins'])." ".$CodeGifResult['TypeCoin']."<br>";

            if($CodeGifResult['DarItem']) $msg .= "<b>[ Items ]</b><br>- ".$CodeGifResult['iName']."<br>";

            message('success',$msg);

        } catch(Exception $ex) {
            message('error',$ex->getMessage());
        }
    }

    /* --------------------------------------------------------- */
    /* --------- MÉTODOS DE ITEM / WAREHOUSE (sin cambios) ------ */

    public function SetItemGremoryCase($Username,$Character_Name,$ItemiGroup,$ItemiIndex,$ItemiLevel) { /* … mismo código … */ }

    public function GenerarHexItem(/* … */) { /* … mismo código … */ }

    public function InsertItemWarehouse(/* … */) { /* … mismo código … */ }

    public function ObtainItemInfo($Item_Group,$Item_Index) { /* … mismo código … */ }

    public function SmartSearch($username,$whbin,$itemX,$itemY) { /* … mismo código … */ }

}
?>
