<?php
/**
 * ╔════════════════════════════════════════════════════════════════════════════╗
 * ║                    WEBSHOP CLASS FOR EX101KOR VERSION                       ║
 * ║                   MU Online EX101 Korean Client Edition                     ║
 * ╚════════════════════════════════════════════════════════════════════════════╝
 * 
 * @version     1.0.0
 * @date        2026-04-17
 * @author      WebEngine CMS + EX101KOR Integration
 * @description Clase especializada para generación de items compatibles con
 *              la versión EX101KOR de MU Online.
 * 
 * ⚠️ IMPORTANTE - LIMITACIONES DE EX101KOR:
 * ❌ NO SOPORTA: Harmony Options (bytes 20-21 = 00)
 * ❌ NO SOPORTA: Socket Options (bytes 22-31 = 00)
 * ❌ NO SOPORTA: Op380 Flag (byte 19 = 0)
 * ✅ SOPORTA: Level (+0 a +15), Skill, Luck, Life, Excellent (6 opciones), Ancient
 * 
 * FORMATO: 32 caracteres hexadecimales (16 bytes)
 * - Bytes 0-18: Datos del item
 * - Bytes 19-31: PADDING DE CEROS (13 bytes)
 */

class WebShopEX101KOR {

    private $_configs;
    private $common;
    private $dB;
    private $creditSystem;

    /**
     * Constructor - Inicializa el sistema de webshop para EX101KOR
     */
    function __construct() {
        $config = loadConfigurations('webshop');
        if(!is_array($config)) {
            throw new Exception(lang('error_98'));
        }
        $this->_configs = $config;
        $this->common = new common();
        $this->dB = Connection::Database('MuOnline');
        $this->creditSystem = new CreditSystem();
        $this->initializeArrays();
    }

    /**
     * Inicializa los arrays de configuración
     */
    private function initializeArrays() {
        $this->iLevel_Array = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        $this->iOption_Array = array(1, 2, 3, 4, 5, 6, 7);
        $this->iType_Weapon_Array = array(0, 1, 2, 3, 4, 5);
        $this->iType_Set_Array = array(6, 7, 8, 9, 10, 11);
        $this->iIndex_Wings2_Array = array(3, 4, 5, 6, 42, 49);
        $this->iIndex_Wings3_Array = array(36, 37, 38, 39, 40, 43, 50);
        $this->iIndex_Pendants_Array = array(12, 13, 25, 26, 27, 28);
        $this->iIndex_Rings_Array = array(8, 9, 21, 22, 23, 24);
    }

    /**
     * Genera serial único con sistema de 3 niveles de fallback
     * @return int Serial único
     */
    private function GenerarSerial() {
        // NIVEL 1: Stored procedure
        try {
            $result = $this->dB->query_fetch_single("EXEC WZ_GetItemSerial2");
            if (isset($result["ItemSerial"]) && $result["ItemSerial"] > 0) {
                return $result["ItemSerial"];
            }
        } catch (Exception $e) {}

        // NIVEL 2: MAX(Serial) + 1
        try {
            $query = "SELECT ISNULL(MAX(Serial), 0) + 1 AS NextSerial FROM warehouse WHERE Serial > 0";
            $result = $this->dB->query_fetch_single($query);
            if (isset($result["NextSerial"]) && $result["NextSerial"] > 0) {
                return $result["NextSerial"];
            }
        } catch (Exception $e) {}

        // NIVEL 3: Timestamp + random
        return time() + rand(1, 9999);
    }

    /**
     * Genera item en formato hexadecimal de 32 caracteres (EX101KOR)
     * 
     * @param int $Level       Nivel del item (0-15)
     * @param int $Durabilidad Durabilidad (0-255)
     * @param int $Skill       Skill flag (0 o 1)
     * @param int $Luck        Luck flag (0 o 1)
     * @param int $Life        Option Life (0-7)
     * @param int $ExeOP       Excellent Options bitmask (0-63)
     * @param int $SetOP       Ancient option (0, 1, 2)
     * @param int $Item_Group  Grupo del item (0-15)
     * @param int $Item_Index  Índice del item (0-511)
     * @return string Hex de 32 caracteres
     */
    public function GenerarHexItem($Level, $Durabilidad, $Skill, $Luck, $Life, $ExeOP, $SetOP, $Item_Group, $Item_Index) {
        
        // Generar serial único
        $SerialItem = $this->GenerarSerial();

        // Calcular Attribute1
        $Attribute1 = 0;
        if ($Luck == 1) $Attribute1 += 4;
        if ($Skill == 1) $Attribute1 += 128;
        if ($Life >= 4) {
            $Attribute1 += ($Life - 4);
        } else {
            $Attribute1 += $Life;
        }
        if ($Level > 0) $Attribute1 += ($Level * 8);

        // Manejar overflow de Item_Index
        if ($Item_Index >= 256) {
            $Item_Index = $Item_Index - 256;
        }

        // Construir hex (bytes 0-18)
        $ItemHex = "";
        $ItemHex .= sprintf("%02X", $Item_Index);     // Bytes 0-1
        $ItemHex .= sprintf("%02X", $Attribute1);     // Bytes 2-3
        $ItemHex .= sprintf("%02X", $Durabilidad);    // Bytes 4-5
        $ItemHex .= sprintf("%08X", $SerialItem);     // Bytes 6-13
        $ItemHex .= sprintf("%02X", $ExeOP);          // Bytes 14-15
        $ItemHex .= sprintf("%02X", $SetOP);          // Bytes 16-17
        $ItemHex .= dechex($Item_Group);              // Byte 18
        $ItemHex .= "0";                              // Byte 19 (sin Op380)
        
        // Padding de 13 bytes (26 caracteres) - EX101KOR no usa Harmony/Sockets
        $ItemHex .= "0000000000000000000000000";

        return strtoupper($ItemHex);
    }

    /**
     * Inserta item en warehouse del usuario
     */
    public function InsertItemWarehouse($Usuario, $Level, $Durabilidad, $Skill, $Luck, $Life, $ExeOP, $SetOP, 
                                       $Item_Group, $Item_Index, $PrecioTotalItem, $Moneda, $Moneda_V, $Price,
                                       $ExeOP1, $ExeOP2, $ExeOP3, $ExeOP4, $ExeOP5, $ExeOP6, $Item_NAME) {
        
        // Generar item
        $ItemHex = $this->GenerarHexItem($Level, $Durabilidad, $Skill, $Luck, $Life, $ExeOP, $SetOP, $Item_Group, $Item_Index);

        // Verificar saldo
        $coins = $this->GetCoins($Usuario);
        $currentBalance = $this->getCreditBalance($coins);
        $Saldo = $currentBalance - $PrecioTotalItem;

        // Verificar cuenta offline
        if ($this->common->accountOnline($Usuario)) {
            message('error', '<b>[WebShop EX101KOR]</b> La cuenta debe estar desconectada del juego.');
            return;
        }

        // Verificar saldo suficiente
        if ($currentBalance < $PrecioTotalItem) {
            $faltante = $PrecioTotalItem - $currentBalance;
            message('error', '<b>[WebShop EX101KOR]</b> No tienes suficientes ' . $Moneda_V . '. Te faltan ' . number_format($faltante, 0, ',', '.') . ' ' . $Moneda_V);
            return;
        }

        // Verificar warehouse existe
        $warehouseExists = $this->dB->query_fetch_single("SELECT AccountID FROM warehouse WHERE AccountID = ?", [$Usuario]);
        if (!$warehouseExists) {
            message('error', '<b>[WebShop EX101KOR]</b> Debes conectarte al juego y abrir tu Warehouse al menos una vez.');
            return;
        }

        // Leer warehouse
        $Baul = $this->dB->query_fetch_single("SELECT CONVERT(VARCHAR(MAX), Items, 2) AS Baul FROM warehouse WHERE AccountID = ?", [$Usuario]);
        if (!$Baul || !isset($Baul["Baul"])) {
            message('error', '<b>[WebShop EX101KOR]</b> No se pudo leer tu Warehouse.');
            return;
        }
        $BaulHex = $Baul["Baul"];

        // Obtener dimensiones del item
        $ItemInfo = $this->ObtainItemsByGroup($Item_Group, $Item_Index);
        if (!$ItemInfo || !isset($ItemInfo["X"]) || !isset($ItemInfo["Y"])) {
            message('error', '<b>[WebShop EX101KOR]</b> Item no encontrado en la base de datos.');
            return;
        }

        // Buscar slot disponible
        $slot = $this->SmartSearch($Usuario, $BaulHex, $ItemInfo["X"], $ItemInfo["Y"]);
        if ($slot == 1337) {
            message('error', '<b>[WebShop EX101KOR]</b> No hay espacio en tu warehouse.');
            return;
        }

        // Insertar item
        $byteOffset = $slot * 32;
        $NuevoBaul = substr_replace($BaulHex, $ItemHex, $byteOffset, 32);
        $NuevoBaul = "0x" . $NuevoBaul;

        // Actualizar warehouse
        $update = $this->dB->query("UPDATE warehouse SET Items = CONVERT(VARBINARY(MAX), ?, 1) WHERE AccountID = ?", [$NuevoBaul, $Usuario]);
        if (!$update) {
            message('error', '<b>[WebShop EX101KOR]</b> Error al actualizar el warehouse.');
            return;
        }

        // Descontar créditos
        $this->SubstractCoins($this->_configs['moneda'], $PrecioTotalItem, $Usuario);

        // Logs (con valores 0 para Harmony/Sockets/Op380 que no existen en EX101KOR)
        $this->AddWebshopLogs($Usuario, 'WAREHOUSE', $Level, $Skill, $Luck, $Life, $SetOP, 0, 0, 0, 0, 0, 0, 0,
                             $PrecioTotalItem, $this->_configs['moneda_v'], $Saldo, $Item_Group, 
                             $ExeOP1, $ExeOP2, $ExeOP3, $ExeOP4, $ExeOP5, $ExeOP6, $Item_NAME);

        message('success', '<b>[WebShop EX101KOR]</b> ¡Compra exitosa! Item agregado a tu warehouse.');
        message('info', 'Se descontaron ' . number_format($PrecioTotalItem, 0, ',', '.') . ' ' . $this->_configs['moneda_v'] . '. Saldo: ' . number_format($Saldo, 0, ',', '.'));
    }

    /**
     * Busca slot disponible en warehouse (8×15 grid, 120 slots)
     */
    public function SmartSearch($username, $whbin, $itemX, $itemY) {
        if (substr($whbin, 0, 2) == "0x") $whbin = substr($whbin, 2);
        $items = str_repeat("0", 120);

        // Marcar slots ocupados
        for ($i = 0; $i < 120; $i++) {
            $_item = substr($whbin, 32 * $i, 32);
            $itemIndex = hexdec(substr($_item, 0, 2));
            $itemGroup = hexdec(substr($_item, 18, 1));
            $exeOP = hexdec(substr($_item, 14, 2));
            if ($exeOP >= 128) $itemIndex += 256;

            $res = $this->ObtainItemsByGroup($itemGroup, $itemIndex);
            if ($res && is_array($res) && isset($res["Y"]) && isset($res["X"])) {
                for ($y = 0; $y < $res["Y"]; $y++) {
                    for ($x = 0; $x < $res["X"]; $x++) {
                        $slotPos = $i + $x + ($y * 8);
                        if ($slotPos < 120) $items = substr_replace($items, "1", $slotPos, 1);
                    }
                }
            }
        }

        // Buscar espacio
        $nx = 0;
        $ny = 0;
        for ($i = 0; $i < 120; $i++) {
            if ($nx == 8) {
                $ny++;
                $nx = 0;
            }
            $canFit = true;
            if (($itemX + $nx) > 8 || ($itemY + $ny) > 15) $canFit = false;
            if ($canFit) {
                for ($y = 0; $y < $itemY; $y++) {
                    for ($x = 0; $x < $itemX; $x++) {
                        $checkPos = $i + $x + ($y * 8);
                        if ($checkPos >= 120 || $items[$checkPos] == "1") {
                            $canFit = false;
                            break 2;
                        }
                    }
                }
            }
            if ($canFit) return $i;
            $nx++;
        }
        return 1337;
    }

    /**
     * Registra compra en logs
     */
    public function AddWebshopLogs($Usuario, $Character, $Level, $Skill, $Luck, $Life, $SetOP, $HHOP, $Op380,
                                   $Socket1, $Socket2, $Socket3, $Socket4, $Socket5, $PrecioTotalItem, $Moneda_V,
                                   $CoinsRestantes, $Item_Group, $ExeOP1, $ExeOP2, $ExeOP3, $ExeOP4, $ExeOP5, $ExeOP6, $Item_NAME) {
        
        $OpExeTx = "$ExeOP1-$ExeOP2-$ExeOP3-$ExeOP4-$ExeOP5-$ExeOP6";
        $Fecha = date('Y-m-d H:i:s');

        $AddItemData = array(
            'iGrupo' => $Item_Group, 'iUser' => $Usuario, 'iChar' => $Character, 'iName' => $Item_NAME,
            'iLevel' => $Level, 'iSkill' => $Skill, 'iLuck' => $Luck, 'iLife' => $Life, 'iOpExe' => $OpExeTx,
            'iAcc' => $SetOP, 'iHaromny' => $HHOP, 'iOp380' => $Op380, 'iSocket1' => $Socket1, 'iSocket2' => $Socket2,
            'iSocket3' => $Socket3, 'iSocket4' => $Socket4, 'iSocket5' => $Socket5, 'iPrice' => $PrecioTotalItem,
            'iMoneda' => $Moneda_V, 'iSaldo' => $CoinsRestantes
        );

        $this->dB->query(
            "INSERT INTO WEBENGINE_WEBSHOP_LOGS (Grupo, Usuario, Personaje, Item, NivelItem, Skill, Luck, OpLife, OpExe, Acc, Harmony, Op380, 
             Socket1, Socket2, Socket3, Socket4, Socket5, PrecioTotal, Moneda, SaldoRestante, Fecha) 
             VALUES (:iGrupo, :iUser, :iChar, :iName, :iLevel, :iSkill, :iLuck, :iLife, :iOpExe, :iAcc, :iHaromny, :iOp380, 
             :iSocket1, :iSocket2, :iSocket3, :iSocket4, :iSocket5, :iPrice, :iMoneda, :iSaldo, '$Fecha')",
            $AddItemData
        );
    }

    /** Obtener items activos */
    public function ObtainItems() {
        $result = $this->dB->query_fetch("SELECT * FROM WEBENGINE_ITEMS_WEBSHOP WHERE Activo = 1 ORDER BY iGrupo ASC, iIndex ASC");
        return is_array($result) ? $result : array();
    }

    /** Obtener todos los items */
    public function ObtainItemsEditor() {
        $result = $this->dB->query_fetch("SELECT * FROM WEBENGINE_ITEMS_WEBSHOP ORDER BY iGrupo ASC, iIndex ASC, Id ASC");
        return is_array($result) ? $result : array();
    }

    /** Obtener item por grupo e índice */
    public function ObtainItemsByGroup($iGroup, $iIndex) {
        $result = $this->dB->query_fetch_single("SELECT * FROM WEBENGINE_ITEMS_WEBSHOP WHERE iGrupo = ? AND iIndex = ?", [$iGroup, $iIndex]);
        return is_array($result) ? $result : null;
    }

    /** Obtener logs */
    public function GetLogs() {
        $result = $this->dB->query_fetch("SELECT * FROM WEBENGINE_WEBSHOP_LOGS ORDER BY Id DESC");
        return is_array($result) ? $result : array();
    }

    /** Nombre del grupo */
    public function GrupoName($gGrupo) {
        $grupos = array(0=>"Swords",1=>"Axes",2=>"Maces",3=>"Spears",4=>"Bows",5=>"Staffs",6=>"Shields",
                       7=>"Helmets",8=>"Armors",9=>"Pants",10=>"Gloves",11=>"Boots",12=>"Wings",13=>"Miscs",14=>"Scrolls",15=>"Specials");
        return isset($grupos[$gGrupo]) ? $grupos[$gGrupo] : "Unknown";
    }

    /** Obtener créditos */
    public function GetCoins($username) {
        return $this->creditSystem->getUserCredits($username);
    }

    /** Balance de créditos */
    public function getCreditBalance($coins) {
        $currency = $this->_configs['moneda'];
        return isset($coins[$currency]) ? $coins[$currency] : 0;
    }

    /** Restar créditos */
    public function SubstractCoins($currency, $amount, $username) {
        return $this->creditSystem->subtractCredits($username, $currency, $amount);
    }
}
