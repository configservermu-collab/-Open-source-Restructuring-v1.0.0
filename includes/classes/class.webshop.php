<?php 
class WebShopNew{

    private $_configs;	 

    function __construct() {

        # Load Config
        $config = loadConfigurations('webshop');
		if(!is_array($config)) throw new Exception(lang('error_98'));
		$this->_configs = $config;

        #common
        $this->common = new common();
		
		# database
		$this->dB = Connection::Database('MuOnline');
		
		# Credit System (modern multi-currency)
		$this->creditSystem = new CreditSystem();
		
        # Arrays
	    $this->iLevel_Array	                =	array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);
        $this->iOption_Array                =   array(1, 2, 3, 4, 5, 6, 7);

        $this->iType_Weapon_Array           =   array(0, 1, 2, 3, 4, 5);
        $this->iType_Set_Array              =   array(6, 7, 8, 9, 10, 11);
        $this->iIndex_Wings2_Array          =   array(3, 4, 5, 6, 42, 49);
        $this->iIndex_Wings3_Array          =   array(36, 37, 38, 39, 40, 43, 50);

        $this->iIndex_Pendants_Array        =   array(12, 13, 25, 26, 27, 28);
        $this->iIndex_Rings_Array           =   array(8, 9, 21, 22, 23, 24);

        $this->iSocket_Weapons_Array        =   array(
            255     =>      '[SOCKETS] Sin sockets',
            254     =>      '[SOCKETS] Slot Vacio',
            200     =>      '[FIRE] (Level type)Attack/Wizardry increase',
            201     =>      '[FIRE] Attack speed increase',
            202     =>      '[FIRE] Maximum attack/Wizardry increase',
            203     =>      '[FIRE] Minimum attack/Wizardry increase',
            204     =>      '[FIRE] Attack/Wizardry increase',
            205     =>      '[FIRE] AG cost decrease',
            216     =>      '[ICE] Monster destruction for the Life increase',
            217     =>      '[ICE] Monster destruction for the Mana increase',
            218     =>      '[ICE] Skill attack increase',
            219     =>      '[ICE] Attack rating increase',
            220     =>      '[ICE] Item durability increase',
            229     =>      '[LIGTHING] Excellent damage increase',
            230     =>      '[LIGTHING] Excellent damage rate increase',
            231     =>      '[LIGTHING] Critical damage increase',
            232     =>      '[LIGTHING] Critical damage rate increase'
        );

        $this->iSocket_Set_Array        =   array(
            255     =>      '[SOCKETS] Sin sockets',
            254     =>      '[SOCKETS] Slot Vacio',
            210     =>      '[WATER] Block rating increase',
            211     =>      '[WATER] Defense increase',
            212     =>      '[WATER] Defense increase',
            213     =>      '[WATER] Damage reduction',
            214     =>      '[WATER] Damage reflection',
            221     =>      '[WIND] Automatic Life recovery increase',
            222     =>      '[WIND] Maximum Life increase',
            223     =>      '[WIND] Maximum Mana increase',
            224     =>      '[WIND] Automatic Mana recovery increase',
            225     =>      '[WIND] Maximum AG increase',
            226     =>      '[WIND] AG value increase',
            236     =>      '[EARTH] AG value increase'

        );

        $this->iHarmony_Weapons_Array       =   array(
            0  => 'Sin opcion Harmony',
            16 => 'Min. attack increase +2',
            17 => 'Min. attack increase +3',
            18 => 'Min. attack increase +4',
            19 => 'Min. attack increase +5',
            20 => 'Min. attack increase +6',
            21 => 'Min. attack increase +7',
            22 => 'Min. attack increase +9',
            23 => 'Min. attack increase +11',
            24 => 'Min. attack increase +12',
            25 => 'Min. attack increase +14',
            26 => 'Min. attack increase +15',
            27 => 'Min. attack increase +16',
            28 => 'Min. attack increase +17',
            29 => 'Min. attack increase +20',
            32 => 'Max. attack increase +3',
            33 => 'Max. attack increase +4',
            34 => 'Max. attack increase +5',
            35 => 'Max. attack increase +6',
            36 => 'Max. attack increase +7',
            37 => 'Max. attack increase +8',
            38 => 'Max. attack increase +10',
            39 => 'Max. attack increase +12',
            40 => 'Max. attack increase +14',
            41 => 'Max. attack increase +17',
            42 => 'Max. attack increase +20',
            43 => 'Max. attack increase +23',
            44 => 'Max. attack increase +26',
            45 => 'Max. attack increase +29',
            48 => 'Required strength decrease +6',
            49 => 'Required strength decrease +8',
            50 => 'Required strength decrease +10',
            51 => 'Required strength decrease +12',
            52 => 'Required strength decrease +14',
            53 => 'Required strength decrease +16',
            54 => 'Required strength decrease +20',
            55 => 'Required strength decrease +23',
            56 => 'Required strength decrease +26',
            57 => 'Required strength decrease +29',
            58 => 'Required strength decrease +32',
            59 => 'Required strength decrease +35',
            60 => 'Required strength decrease +37',
            61 => 'Required strength decrease +40',
            64 => 'Required agility decrease +6',
            65 => 'Required agility decrease +8',
            66 => 'Required agility decrease +10',
            67 => 'Required agility decrease +12',
            68 => 'Required agility decrease +14',
            69 => 'Required agility decrease +16',
            70 => 'Required agility decrease +20',
            71 => 'Required agility decrease +23',
            72 => 'Required agility decrease +26',
            73 => 'Required agility decrease +29',
            74 => 'Required agility decrease +32',
            75 => 'Required agility decrease +35',
            76 => 'Required agility decrease +37',
            77 => 'Required agility decrease +40',
            86 => 'Attack increase(Min,Max) +7',
            87 => 'Attack increase(Min,Max) +8',
            88 => 'Attack increase(Min,Max) +9',
            89 => 'Attack increase(Min,Max) +11',
            90 => 'Attack increase(Min,Max) +12',
            91 => 'Attack increase(Min,Max) +14',
            92 => 'Attack increase(Min,Max) +16',
            93 => 'Attack increase(Min,Max) +19',
            102 => 'Critical damage increase +12',
            103 => 'Critical damage increase +14',
            104 => 'Critical damage increase +16',
            105 => 'Critical damage increase +18',
            106 => 'Critical damage increase +20',
            107 => 'Critical damage increase +22',
            108 => 'Critical damage increase +24',
            109 => 'Critical damage increase +30',
            121 => 'Skill attack increase +12',
            122 => 'Skill attack increase +14',
            123 => 'Skill attack increase +16',
            124 => 'Skill attack increase +18',
            125 => 'Skill attack increase +22',
            137 => 'Attack sucess rate increase(PVP) +5',
            138 => 'Attack sucess rate increase(PVP) +7',
            139 => 'Attack sucess rate increase(PVP) +9',
            140 => 'Attack sucess rate increase(PVP) +11',
            141 => 'Attack sucess rate increase(PVP) +14',
            153 => 'SD decrement increase +3',
            154 => 'SD decrement increase +5',
            155 => 'SD decrement increase +7',
            156 => 'SD decrement increase +9',
            157 => 'SD decrement increase +10',
            173 => 'SD bypass rate increase +10'
        );

        $this->iHarmony_Staff_Array       =   array(
            0  => 'Sin opcion Harmony',
            16 => 'Wizardry increase +6',
            17 => 'Wizardry increase +8',
            18 => 'Wizardry increase +10',
            19 => 'Wizardry increase +12',
            20 => 'Wizardry increase +14',
            21 => 'Wizardry increase +16',
            22 => 'Wizardry increase +17',
            23 => 'Wizardry increase +18',
            24 => 'Wizardry increase +19',
            25 => 'Wizardry increase +21',
            26 => 'Wizardry increase +23',
            27 => 'Wizardry increase +25',
            28 => 'Wizardry increase +27',
            29 => 'Wizardry increase +31',
            32 => 'Required strength decrease +6',
            33 => 'Required strength decrease +8',
            34 => 'Required strength decrease +10',
            35 => 'Required strength decrease +12',
            36 => 'Required strength decrease +14',
            37 => 'Required strength decrease +16',
            38 => 'Required strength decrease +20',
            39 => 'Required strength decrease +23',
            40 => 'Required strength decrease +26',
            41 => 'Required strength decrease +29',
            42 => 'Required strength decrease +32',
            43 => 'Required strength decrease +35',
            44 => 'Required strength decrease +37',
            45 => 'Required strength decrease +40',
            48 => 'Required agility decrease +6',
            49 => 'Required agility decrease +8',
            50 => 'Required agility decrease +10',
            51 => 'Required agility decrease +12',
            52 => 'Required agility decrease +14',
            53 => 'Required agility decrease +16',
            54 => 'Required agility decrease +20',
            55 => 'Required agility decrease +23',
            56 => 'Required agility decrease +26',
            57 => 'Required agility decrease +29',
            58 => 'Required agility decrease +32',
            59 => 'Required agility decrease +35',
            60 => 'Required agility decrease +37',
            61 => 'Required agility decrease +40',
            70 => 'Skill attack increase +7',
            71 => 'Skill attack increase +10',
            72 => 'Skill attack increase +13',
            73 => 'Skill attack increase +16',
            74 => 'Skill attack increase +19',
            75 => 'Skill attack increase +22',
            76 => 'Skill attack increase +25',
            77 => 'Skill attack increase +30',
            86 => 'Critical damage increase +10',
            87 => 'Critical damage increase +12',
            88 => 'Critical damage increase +14',
            89 => 'Critical damage increase +16',
            90 => 'Critical damage increase +18',
            91 => 'Critical damage increase +20',
            92 => 'Critical damage increase +22',
            93 => 'Critical damage increase +28',
            105 => ' SD decrement increase +4',
            106 => ' SD decrement increase +6',
            107 => ' SD decrement increase +8',
            108 => ' SD decrement increase +10',
            109 => ' SD decrement increase +13',
            121 => ' Attack success rate increase(PVP) +5',
            122 => ' Attack success rate increase(PVP) +7',
            123 => ' Attack success rate increase(PVP) +9',
            124 => ' Attack success rate increase(PVP) +11',
            125 => ' Attack success rate increase(PVP) +14',
            141 => ' SD bypass rate increase +15'
        );

        $this->iHarmony_Set_Array       =   array(
            0  => 'Sin opcion Harmony',
            16 => 'Defense increase +3',
            17 => 'Defense increase +4',
            18 => 'Defense increase +5',
            19 => 'Defense increase +6',
            20 => 'Defense increase +7',
            21 => 'Defense increase +8',
            22 => 'Defense increase +10',
            23 => 'Defense increase +12',
            24 => 'Defense increase +14',
            25 => 'Defense increase +16',
            26 => 'Defense increase +18',
            27 => 'Defense increase +20',
            28 => 'Defense increase +22',
            29 => 'Defense increase +25',
            35 => 'Max. AG increase +4',
            36 => 'Max. AG increase +6',
            37 => 'Max. AG increase +8',
            38 => 'Max. AG increase +10',
            39 => 'Max. AG increase +12',
            40 => 'Max. AG increase +14',
            41 => 'Max. AG increase +16',
            42 => 'Max. AG increase +18',
            43 => 'Max. AG increase +20',
            44 => 'Max. AG increase +22',
            45 => 'Max. AG increase +25',
            51 => 'Max. HP increase +7',
            52 => 'Max. HP increase +9',
            53 => 'Max. HP increase +11',
            54 => 'Max. HP increase +13',
            55 => 'Max. HP increase +15',
            56 => 'Max. HP increase +17',
            57 => 'Max. HP increase +19',
            58 => 'Max. HP increase +21',
            59 => 'Max. HP increase +23',
            60 => 'Max. HP increase +25',
            61 => 'Max. HP increase +30',
            70 => 'Life auto increment increase +1',
            71 => 'Life auto increment increase +2',
            72 => 'Life auto increment increase +3',
            73 => 'Life auto increment increase +4',
            74 => 'Life auto increment increase +5',
            75 => 'Life auto increment increase +6',
            76 => 'Life auto increment increase +7',
            77 => 'Life auto increment increase +8',
            89 => 'Mana auto increment increase +1',
            90 => 'Mana auto increment increase +2',
            91 => 'Mana auto increment increase +3',
            92 => 'Mana auto increment increase +4',
            93 => 'Mana auto increment increase +5',
            105 => 'Defense success rate increase(PVP) +3',
            106 => 'Defense success rate increase(PVP) +4',
            107 => 'Defense success rate increase(PVP) +5',
            108 => 'Defense success rate increase(PVP) +6',
            109 => 'Defense success rate increase(PVP) +8',
            121 => 'Damage decrement increase +3',
            122 => 'Damage decrement increase +4',
            123 => 'Damage decrement increase +5',
            124 => 'Damage decrement increase +6',
            125 => 'Damage decrement increase +7',
            141 => 'SD ratio increase +5'
        );
	}

    public function GenerarHexItem($Level,$Durabilidad,$Skill,$Luck,$Life,$ExeOP,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$Item_Group,$Item_Index){

        // Intentar obtener serial del stored procedure
        try {
            $Serial         =   $this->dB->query_fetch_single("exec WZ_GetItemSerial2");
            $SerialItem     =   isset($Serial["ItemSerial"]) ? $Serial["ItemSerial"] : null;
        } catch (Exception $e) {
            $SerialItem = null;
        }
        
        // Si falla el stored procedure, generar serial alternativo
        if ($SerialItem === null || $SerialItem == 0) {
            // Obtener el máximo serial actual de la tabla warehouse
            $queryMaxSerial = "SELECT ISNULL(MAX(Serial), 0) + 1 AS NextSerial FROM warehouse WHERE Serial > 0";
            $resultSerial = $this->dB->query_fetch_single($queryMaxSerial);
            $SerialItem = isset($resultSerial["NextSerial"]) ? $resultSerial["NextSerial"] : rand(1, 999999);
            
            // Si aún es 0 o null, usar un valor aleatorio único
            if ($SerialItem == 0 || $SerialItem === null) {
                $SerialItem = time() + rand(1, 9999);
            }
        }

        $LevelLifeSkillLuck         =   0;

        if ($Luck == 1) { $LevelLifeSkillLuck += 4;  }
        if ($Skill == 1) {  $LevelLifeSkillLuck += 128; }
        if (4 <= $Life) {  $LevelLifeSkillLuck += $Life - 4; } 
        else { $LevelLifeSkillLuck += $Life; }
        if (0 < $Level) {  $LevelLifeSkillLuck += $Level * 8; }

        if (256 <= $Item_Index) {
            $Item_Index = $Item_Index - 256;
        }

        $ItemHex = sprintf("%02X", $Item_Index, 0);
        $ItemHex .= sprintf("%02X", $LevelLifeSkillLuck, 0);
        $ItemHex .= sprintf("%02X", $Durabilidad, 0);
        $ItemHex .= sprintf("%08X", $SerialItem, 0);
        $ItemHex .= sprintf("%02X", $ExeOP, 0);
        $ItemHex .= sprintf("%02X", $SetOP, 0);
        $ItemHex .= dechex($Item_Group);
        if ($Op380 == 128) {
            $ItemHex .= "8";
        } else {
            $ItemHex .= "0";
        }
        $ItemHex .= sprintf("%02X", $HHOP, 0);
        $ItemHex .= sprintf("%02X", $Socket1, 0);
        $ItemHex .= sprintf("%02X", $Socket2, 0);
        $ItemHex .= sprintf("%02X", $Socket3, 0);
        $ItemHex .= sprintf("%02X", $Socket4, 0);
        $ItemHex .= sprintf("%02X", $Socket5, 0);
        $ItemHex = strtoupper($ItemHex);

        return $ItemHex;
    }

    public function InsertItemWarehouse($Usuario,$Level,$Durabilidad,$Skill,$Luck,$Life,$ExeOP,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$Item_Group,$Item_Index,$PrecioTotalItem,$Moneda,$Moneda_V,$Price,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME){
        
        $ItemHex            =   $this->GenerarHexItem($Level,$Durabilidad,$Skill,$Luck,$Life,$ExeOP,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$Item_Group,$Item_Index);
        $coins              =   $this->GetCoins($Usuario);
        $currentBalance     =   $this->getCreditBalance($coins);
        $CoinsRestantes     =   $PrecioTotalItem - $currentBalance;
        $ItemInf            =   $this->ObtainItemsByGroup($Item_Group,$Item_Index);
        $Saldo              =   $currentBalance - $PrecioTotalItem;

        if(!$this->common->accountOnline($Usuario))
        {
            if($currentBalance >= $PrecioTotalItem ) {
                // Verificar si existe warehouse
                $warehouseExists = $this->dB->query_fetch_single("SELECT AccountID FROM warehouse WHERE AccountID = ?", [$Usuario]);
                
                if(!$warehouseExists) {
                    message('error', '<b>[Webshop]</b> Debes <b>conectarte al juego</b> y <b>abrir tu Warehouse</b> al menos una vez antes de poder comprar en la Webshop.');
                    return;
                }
                
                $Baul = $this->dB->query_fetch_single("SELECT CONVERT(VARCHAR(MAX), (SELECT Items FROM warehouse WHERE AccountID = ?), 2) AS Baul", [$Usuario]);
                
                if(!$Baul || !isset($Baul["Baul"])) {
                    message('error', '<b>[Webshop]</b> No se pudo leer tu Warehouse. Por favor <b>conectate al juego</b> y abre tu baúl al menos una vez.');
                    return;
                }
                
                $Baul = $Baul["Baul"];
                $ItemInfo = $this->ObtainItemsByGroup($Item_Group,$Item_Index);
                $test = 0;
                $slot = $this->SmartSearch($Usuario, $Baul, $ItemInfo["X"], $ItemInfo["Y"]);
                $test = $slot * 32;
                if ($slot == 1337) {
                    message('error', '<b>[Webshop]</b> No hay espacio en tu baul para comprar este item.');
                } else {
                    // UPDATE warehouse
                    $NuevoBaul = substr_replace($Baul, $ItemHex, $test, 32);
                    $NuevoBaul = "0x" . $NuevoBaul;
                    $update = $this->dB->query(
                        "UPDATE [warehouse] SET [Items] = CONVERT(VARBINARY(MAX), ?, 1) WHERE [AccountID] = ?",
                        [$NuevoBaul, $Usuario]
                    );
                    
                    if($update) {
                        // Descontar créditos con SubstractCoins
                        $this->SubstractCoins($this->_configs['moneda'],$PrecioTotalItem,$Usuario);
                        
                        // Logging
                        $Character = 'WAREHOUSE';
                        $this->AddWebshopLogs($Usuario,$Character,$Level,$Skill,$Luck,$Life,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$PrecioTotalItem,$this->_configs['moneda_v'],$Saldo,$Item_Group,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME);
                        
                        // Mensajes de éxito
                        message('success', '<b>[Webshop]</b> Has comprado correctamente este <b>Item</b>.');
                        message('error', '<b>[WebShop]</b> Se te ha descontado <b>'.number_format($PrecioTotalItem,0,',','.').'</b> de tus <b>'.$this->_configs['moneda_v'].'</b>.');
                    } else {
                        message('error', '<b>[Webshop]</b> Error al actualizar el warehouse. Por favor intenta nuevamente.');
                    }
                }
            } else {
                message('error', '<b>[WebShop]</b> No tienes suficientes <b>'.$this->_configs['moneda_v'].'</b>. Te faltan <b>'.number_format($CoinsRestantes,0,',','.').' '.$this->_configs['moneda_v'].'</b> para adquirir este item.');
            }
        } else{
            message('error', '<b>[Webshop]</b> La cuenta debe estar <b>desconectada del juego</b> para usar la <b>Webshop</b>.');
        }
     }
 
     public function SmartSearch($username, $whbin, $itemX, $itemY)
     {
         if (substr($whbin, 0, 2) == "0x") {
             $whbin = substr($whbin, 2);
         }
         $items = str_repeat("0", 120);
         $itemsm = str_repeat("1", 120);
         $i = 0;
         while ($i < 120) {
             $_item = substr($whbin, 32 * $i, 32);
             $sy = hexdec(substr($_item, 0, 2));
             $ioo = hexdec(substr($_item, 14, 2));
             $type = hexdec(substr($_item, 18, 1));
             if (128 <= $ioo) {
                 $sy = $sy + 256;
                 $ioo -= 128;
             }
             $res = $this->ObtainItemsByGroup($type,$sy);
             // ✅ FIX: Verificar que el item existe antes de acceder a sus propiedades
             if($res && is_array($res) && isset($res["Y"]) && isset($res["X"])) {
                 $y = 0;
                 while ($y < $res["Y"]) {
                     $y++;
                     $x = 0;
                     while ($x < $res["X"]) {
                         $items = substr_replace($items, "1", $i + $x + ($y - 1) * 8, 1);
                         $x++;
                     }
                 }
             }
             $i++;
         }
         $y = 0;
         while ($y < $itemY) {
             $y++;
             $x = 0;
             while ($x < $itemX) {
                 $x++;
                 $spacerq[$x + 8 * ($y - 1)] = true;
             }
         }
         $walked = 0;
         $i = 0;
         while ($i < 120) {
             if (isset($spacerq[$i])) {
                 $itemsm = substr_replace($itemsm, "0", $i - 1, 1);
                 $last = $i;
                 $walked++;
             }
             if ($walked == count($spacerq)) {
                 $i = 119;
             }
             $i++;
         }
         $useforlength = substr($itemsm, 0, $last);
         $findslotlikethis = "/^" . str_replace("1", "[01]", $useforlength) . "\$/i";
         $i = 0;
         $nx = 0;
         $ny = 0;
         while ($i < 120) {
             if ($nx == 8) {
                 $ny++;
                 $nx = 0;
             }
             if (preg_match($findslotlikethis, substr($items, $i, strlen($useforlength))) && $itemX + $nx < 9 && $itemY + $ny < 16) {
                 return $i;
             }
             $i++;
             $nx++;
         }
         return 1337;
     }

    public function AddWebshopLogs($Usuario,$Character,$Level,$Skill,$Luck,$Life,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$PrecioTotalItem,$Moneda_V,$CoinsRestantes,$Item_Group,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME){

        $OpExeTx    =   "".$ExeOP1."-".$ExeOP2."-".$ExeOP3."-".$ExeOP4."-".$ExeOP5."-".$ExeOP6."";
        $Fecha = date('Y-m-d H:i:s',time());
        $AddItemData = array(
            'iGrupo' 				=> $Item_Group,            
            'iUser' 				=> $Usuario,
            'iChar' 				=> $Character,
            'iName'                 => $Item_NAME,
            'iLevel' 				=> $Level,
            'iSkill' 				=> $Skill,
            'iLuck' 				=> $Luck,
            'iLife' 				=> $Life,
            'iOpExe' 				=> $OpExeTx,
            'iAcc'   				=> $SetOP,
            'iHaromny' 				=> $HHOP,
            'iOp380' 				=> $Op380,
            'iSocket1' 				=> $Socket1,
            'iSocket2' 				=> $Socket2,
            'iSocket3' 				=> $Socket3,
            'iSocket4' 				=> $Socket4,
            'iSocket5' 				=> $Socket5,
            'iPrice' 				=> $PrecioTotalItem,
            'iMoneda' 				=> $Moneda_V,
            'iSaldo' 				=> $CoinsRestantes,
        );

        $addItem = $this->dB->query("INSERT INTO WEBENGINE_WEBSHOP_LOGS (Grupo, Usuario, Personaje, Item, NivelItem, Skill, Luck, OpLife, OpExe, Acc, Harmony, Op380, Socket1, Socket2, Socket3, Socket4, Socket5, PrecioTotal, Moneda, SaldoRestante, Fecha) VALUES (:iGrupo, :iUser, :iChar, :iName, :iLevel, :iSkill, :iLuck, :iLife, :iOpExe, :iAcc, :iHaromny, :iOp380, :iSocket1, :iSocket2, :iSocket3, :iSocket4, :iSocket5, :iPrice, :iMoneda, :iSaldo, '".$Fecha."')", $AddItemData);
        if(!$addItem) throw new Exception("<b>[Webshop]</b> No se pudo guardar los Logs.");
    }

    public function ObtainItems()
    {
        $result = $this->dB->query_fetch("SELECT * FROM WEBENGINE_ITEMS_WEBSHOP WHERE Activo = 1 ORDER BY iGrupo ASC, iIndex ASC");
        if(!is_array($result)) return;
        return $result;
    }

    public function ObtainItemsEditor()
    {
        $result = $this->dB->query_fetch("SELECT * FROM WEBENGINE_ITEMS_WEBSHOP ORDER BY iGrupo ASC, iIndex ASC, Id ASC");
        if(!is_array($result)) return;
        return $result;
    }

    public function ObtainItemsByGroup($iGroup,$iIndex)
    {
        // ✅ FIX SECURITY: Usar prepared statements para prevenir SQL injection
        $result = $this->dB->query_fetch_single(
            "SELECT * FROM WEBENGINE_ITEMS_WEBSHOP WHERE iGrupo = ? AND iIndex = ?",
            [$iGroup, $iIndex]
        );
        if(!is_array($result)) return;
        return $result;
    }

    public function GetLogs()
    {
        $result = $this->dB->query_fetch("SELECT * FROM WEBENGINE_WEBSHOP_LOGS ORDER BY Id DESC");
        if(!is_array($result)) return;
        return $result;
    }

    public function GrupoName($gGrupo){
        switch($gGrupo){
            case 0:
                $GrupoName = "Swords";
                break;
            case 1:
                $GrupoName = "Axes";
                break;  
            case 2:
                $GrupoName = "Maces";
                break;
            case 3:
                $GrupoName = "Spears";
                break;  
            case 4:
                $GrupoName = "Bows";
                break;  
            case 5:
                $GrupoName = "Staffs";
                break;   
            case 6:
                $GrupoName = "Shields";
                break; 
            case 7:
                $GrupoName = "Helmets";
                break; 
            case 8:
                $GrupoName = "Armors";
                break; 
            case 9:
                $GrupoName = "Pants";
                break;    
            case 10:
                $GrupoName = "Gloves";
                break; 
            case 11:
                $GrupoName = "Boots";
                break; 
            case 12:
                $GrupoName = "Wings";
                break; 
            case 13:
                $GrupoName = "Jewel/Misc";
                break; 
            case 14:
                $GrupoName = "Wings and Orbs and Spheres";
                break; 
            case 15:
                $GrupoName = "Scrolls";
                break;           
            default:
                $GrupoName = "Sin Categoria";      
        }

        return $GrupoName;
    }

    public function Verificaciones($Usuario,$Character,$ConfigTexto,$TipoGremory,$ItemDuration,$DaysToUse,$Item_Group,$Item_Index,$ItemFinalIndex,$Durabilidad,$Level,$Skill,$Luck,$Life,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$ExeOP,$Item_NAME,$AcceptMismosSockets,$TypeVault){
        # Verificaciones
        $ItemInf            =   $this->ObtainItemsByGroup($Item_Group,$Item_Index);

        # Variables
        $Price					=		$ItemInf['Precio'];
        $ActivoItem             =       $ItemInf['Activo'];
		$Item_MinLevel			=		$ItemInf['MinLevel'];
		$Item_MaxLevel			=		$ItemInf['MaxLevel'];
		$Item_MinLevelLife		=		$ItemInf['MinLevelLife'];
		$Item_MaxLevelLife		=		$ItemInf['MaxLevelLife'];
		$Item_MinOpExe			=		$ItemInf['MinOpExe'];
		$Item_MaxOpExe			=		$ItemInf['MaxOpExe'];
		$Item_MaxSockets		=		$ItemInf['MaxSockets'];

		$CountOpExe		=	0;
		if($ExeOP1 > 0)	$CountOpExe++;
		if($ExeOP2 > 0)	$CountOpExe++;
		if($ExeOP3 > 0)	$CountOpExe++;
		if($ExeOP4 > 0)	$CountOpExe++;
		if($ExeOP5 > 0)	$CountOpExe++;
		if($ExeOP6 > 0)	$CountOpExe++;
        if(!$ActivoItem) throw new Exception('<b>[WebShop]</b> Este item no esta activo.');
		if($Item_Group < 5 && !array_key_exists($HHOP, $this->iHarmony_Weapons_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Harmony para esta <b>Arma</b>.');
		if($Item_Group == 5 && !array_key_exists($HHOP, $this->iHarmony_Staff_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Harmony para esta <b>Arma</b>.');
		if($Item_Group > 5 && !array_key_exists($HHOP, $this->iHarmony_Set_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Harmony para este <b>Set</b>.');
		if($Item_Group <= 5 && !array_key_exists($Socket1, $this->iSocket_Weapons_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para esta <b>Arma</b>.');
		if($Item_Group <= 5 && !array_key_exists($Socket2, $this->iSocket_Weapons_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para esta <b>Arma</b>.');
		if($Item_Group <= 5 && !array_key_exists($Socket3, $this->iSocket_Weapons_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para esta <b>Arma</b>.');
		if($Item_Group <= 5 && !array_key_exists($Socket4, $this->iSocket_Weapons_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para esta <b>Arma</b>.');
		if($Item_Group <= 5 && !array_key_exists($Socket5, $this->iSocket_Weapons_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para esta <b>Arma</b>.');
		if($Item_Group >= 6 && !array_key_exists($Socket1, $this->iSocket_Set_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para este <b>Set</b>.');
		if($Item_Group >= 6 && !array_key_exists($Socket2, $this->iSocket_Set_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para este <b>Set</b>.');
		if($Item_Group >= 6 && !array_key_exists($Socket3, $this->iSocket_Set_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para este <b>Set</b>.');
		if($Item_Group >= 6 && !array_key_exists($Socket4, $this->iSocket_Set_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para este <b>Set</b>.');
		if($Item_Group >= 6 && !array_key_exists($Socket5, $this->iSocket_Set_Array)) throw new Exception('<b>[WebShop]</b> No existe la opcion Socket para este <b>Set</b>.');
		if($Level < $Item_MinLevel) throw new Exception('<b>[WebShop]</b> El nivel de item no puede estar en menos de <b>+ '.$Item_MinLevel.'</b>.');
		if($Level > $Item_MaxLevel) throw new Exception('<b>[WebShop]</b> El nivel de item no puede estar a mas de <b>+ '.$Item_MaxLevel.'</b>.');
		if($Life < $Item_MinLevelLife) throw new Exception('<b>[WebShop]</b> El item no puede estar en menos de <b>+ '.$Item_MinLevelLife.' Option Life</b>.');
		if($Life > $Item_MaxLevelLife) throw new Exception('<b>[WebShop]</b> El item no puede estar en mas de <b>+ '.$Item_MaxLevelLife.' Option Life</b>.');
		if($CountOpExe < $Item_MinOpExe) throw new Exception('<b>[WebShop]</b> Debes elegir un minimo de <b>'.$Item_MinOpExe.' opciones Exe</b>.');
		if($CountOpExe > $Item_MaxOpExe) throw new Exception('<b>[WebShop]</b> Solo puede elegir un maximo de <b>'.$Item_MaxOpExe.' opciones Exe</b>.');
		if($ExeOP1 != 0 && $ExeOP1 != 32) throw new Exception('<b>[WebShop]</b> La opcion <b>Excellent</b> no es correcta o no existe.');
		if($ExeOP2 != 0 && $ExeOP2 != 16) throw new Exception('<b>[WebShop]</b> La opcion <b>Excellent</b> no es correcta o no existe.');
		if($ExeOP3 != 0 && $ExeOP3 != 8)  throw new Exception('<b>[WebShop]</b> La opcion <b>Excellent</b> no es correcta o no existe.');
		if($ExeOP4 != 0 && $ExeOP4 != 4)  throw new Exception('<b>[WebShop]</b> La opcion <b>Excellent</b> no es correcta o no existe.');
		if($ExeOP5 != 0 && $ExeOP5 != 2)  throw new Exception('<b>[WebShop]</b> La opcion <b>Excellent</b> no es correcta o no existe.');
		if($ExeOP6 != 0 && $ExeOP6 != 1)  throw new Exception('<b>[WebShop]</b> La opcion <b>Excellent</b> no es correcta o no existe.');
		if(!$AcceptMismosSockets){
			if($Socket1 != 255){ if($Socket1 != 254){ if( ( $Socket1 == $Socket2 ) || ( $Socket1 == $Socket3 ) || ( $Socket1 == $Socket4 ) || ( $Socket1 == $Socket5 )){ throw new Exception('<b>[WebShop]</b> No puedes repetir <b>Sockets</b> en el item.'); } } }
			if($Socket2 != 255){ if($Socket2 != 254){ if( ( $Socket2 == $Socket1 ) || ( $Socket2 == $Socket3 ) || ( $Socket2 == $Socket4 ) || ( $Socket2 == $Socket5 )){ throw new Exception('<b>[WebShop]</b> No puedes repetir <b>Sockets</b> en el item.'); } } }
			if($Socket3 != 255){ if($Socket3 != 254){ if( ( $Socket3 == $Socket1 ) || ( $Socket3 == $Socket2 ) || ( $Socket3 == $Socket4 ) || ( $Socket3 == $Socket5 )){ throw new Exception('<b>[WebShop]</b> No puedes repetir <b>Sockets</b> en el item.'); } } }
			if($Socket4 != 255){ if($Socket4 != 254){ if( ( $Socket4 == $Socket1 ) || ( $Socket4 == $Socket2 ) || ( $Socket4 == $Socket3 ) || ( $Socket4 == $Socket5 )){ throw new Exception('<b>[WebShop]</b> No puedes repetir <b>Sockets</b> en el item.'); } } }
			if($Socket5 != 255){ if($Socket5 != 254){ if( ( $Socket5 == $Socket1 ) || ( $Socket5 == $Socket2 ) || ( $Socket5 == $Socket3 ) || ( $Socket5 == $Socket4 )){ throw new Exception('<b>[WebShop]</b> No puedes repetir <b>Sockets</b> en el item.'); } } }
		}
		# Precios
		$PrecioIL		=		($this->_configs['option_itemlevel']*$Level);
		$PrecioOL		=		($this->_configs['option_life']*$Life);
		$PrecioLuck		=		($Luck == 1 ? $this->_configs['option_luck'] : 0); 
		$PrecioSkill	=		($Skill == 1 ? $this->_configs['option_skill'] : 0);
		$PrecioAncient	=		($SetOP	> 0 ? $this->_configs['option_ancient'] : 0);
		$Precio380		=		($Op380 > 0 ? $this->_configs['option_380'] : 0);
		$PrecioHarmony	=		($HHOP > 0 ? $this->_configs['option_harmony'] : 0);
		$PrecioExe1		=		($ExeOP1 == 0 ? 0 : $this->_configs['option_exe']);
		$PrecioExe2		=		($ExeOP2 == 0 ? 0 : $this->_configs['option_exe']);
		$PrecioExe3		=		($ExeOP3 == 0 ? 0 : $this->_configs['option_exe']);
		$PrecioExe4		=		($ExeOP4 == 0 ? 0 : $this->_configs['option_exe']);
		$PrecioExe5		=		($ExeOP5 == 0 ? 0 : $this->_configs['option_exe']);
		$PrecioExe6		=		($ExeOP6 == 0 ? 0 : $this->_configs['option_exe']);
		$PrecioSocket1	=		($Socket1 == 255 ? 0 : $this->_configs['option_socket']); 
		$PrecioSocket2	=		($Socket2 == 255 ? 0 : $this->_configs['option_socket']); 
		$PrecioSocket3	=		($Socket3 == 255 ? 0 : $this->_configs['option_socket']); 
		$PrecioSocket4	=		($Socket4 == 255 ? 0 : $this->_configs['option_socket']); 
		$PrecioSocket5	=		($Socket5 == 255 ? 0 : $this->_configs['option_socket']); 
		$TotalOpExe		=		($PrecioExe1+$PrecioExe2+$PrecioExe3+$PrecioExe4+$PrecioExe5+$PrecioExe6);
		$TotalOpSocket	=		($PrecioSocket1+$PrecioSocket2+$PrecioSocket3+$PrecioSocket4+$PrecioSocket5);

		$PrecioTotalItem	=	($PrecioIL+$PrecioOL+$PrecioLuck+$PrecioSkill+$PrecioAncient+$Precio380+$PrecioHarmony+$TotalOpExe+$TotalOpSocket+$Price);
		
        if($TypeVault) {
            $this->SetItemGremoryCase($Usuario,$Character,$TipoGremory,$ConfigTexto,$ItemFinalIndex,$Level,$Durabilidad,$Skill,$Luck,$Life,$ExeOP,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$ItemDuration,$DaysToUse,$PrecioTotalItem,$this->_configs['moneda'],$this->_configs['moneda_v'],$Price,$Item_Group,$Item_Index,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME);
        } else {
            $this->InsertItemWarehouse($Usuario,$Level,$Durabilidad,$Skill,$Luck,$Life,$ExeOP,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$Item_Group,$Item_Index,$PrecioTotalItem,$this->_configs['moneda'],$this->_configs['moneda_v'],$Price,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME);
        }
        
    }

    public function GetCoins($Usuario){
        // ✅ MODERN SYSTEM: Usar CreditSystem si está configurado
        if(isset($this->_configs['credit_config_id']) && $this->_configs['credit_config_id'] > 0) {
            try {
                $this->creditSystem->setConfigId($this->_configs['credit_config_id']);
                $this->creditSystem->setIdentifier($Usuario);
                $credits = $this->creditSystem->getCredits();
                
                if($credits === false) {
                    throw new Exception('<b>[WebShop]</b> Could not retrieve credits from modern system.');
                }
                
                // Obtener el nombre de la columna de créditos desde la configuración
                $config = $this->creditSystem->showConfigs(true);
                $creditColumnName = $config['config_credits_col'];
                
                // Retornar en formato compatible con código legacy
                return [$creditColumnName => $credits, '_modern' => true];
                
            } catch(Exception $e) {
                throw new Exception('<b>[WebShop]</b> Credit system error: '.$e->getMessage());
            }
        }
        
        // ❌ LEGACY SYSTEM: Fallback a InGameCash (deprecado)
        $result = $this->dB->query_fetch_single(
            "SELECT * FROM InGameCash WHERE AccountID = ?",
            [$Usuario]
        );
        if(!is_array($result)) return;
        $result['_modern'] = false;
        return $result;
    }
    
    // ✅ Helper para obtener el saldo actual (compatible con ambos sistemas)
    private function getCreditBalance($coins) {
        if(!is_array($coins)) return 0;
        
        // Sistema moderno: usa el nombre de columna dinámico
        if(isset($coins['_modern']) && $coins['_modern']) {
            foreach($coins as $key => $value) {
                if($key !== '_modern') return $value;
            }
        }
        
        // Sistema legacy: usa columna configurada
        if(isset($coins[$this->_configs['moneda']])) {
            return $coins[$this->_configs['moneda']];
        }
        
        return 0;
    }

    public function SubstractCoins($Moneda,$PrecioTotalItem,$Usuario){
        // ✅ MODERN SYSTEM: Usar CreditSystem si está configurado
        if(isset($this->_configs['credit_config_id']) && $this->_configs['credit_config_id'] > 0) {
            try {
                $this->creditSystem->setConfigId($this->_configs['credit_config_id']);
                $this->creditSystem->setIdentifier($Usuario);
                
                // Verificar saldo actual
                $currentCredits = $this->creditSystem->getCredits();
                if($currentCredits === false) {
                    throw new Exception('No se pudo obtener el saldo de créditos. Verifica la configuración del sistema de créditos en AdminCP.');
                }
                
                if($currentCredits < $PrecioTotalItem) {
                    throw new Exception('Saldo insuficiente. Tienes: '.$currentCredits.', necesitas: '.$PrecioTotalItem);
                }
                
                // Descontar créditos
                $result = $this->creditSystem->subtractCredits($PrecioTotalItem);
                
                // ✅ Solo fallar si retorna explícitamente false (no si retorna 0 o el nuevo saldo)
                if($result === false) {
                    throw new Exception('Error al descontar los créditos. Por favor contacta al administrador.');
                }
                
                return true;
                
            } catch(Exception $e) {
                throw new Exception('<b>[WebShop]</b> '.$e->getMessage());
            }
        }
        
        // ❌ LEGACY SYSTEM: Fallback a InGameCash (deprecado)
        // Validar columna de moneda (prevenir SQL injection vía AdminCP)
        $allowed_columns = ['PcPoint', 'wcoinC', 'wcoinP', 'GoblinPoint'];
        if(!in_array($this->_configs['moneda'], $allowed_columns)) {
            throw new Exception('Invalid currency column: '.$this->_configs['moneda']);
        }
        
        // Query con prepared statements
        $query = "UPDATE InGameCash 
                  SET [".$this->_configs['moneda']."] = [".$this->_configs['moneda']."] - ? 
                  WHERE AccountID = ?";
        
        $result = $this->dB->query($query, [$PrecioTotalItem, $Usuario]);
        
        if(!$result) {
            throw new Exception('<b>[WebShop]</b> Error al descontar créditos del sistema legacy (InGameCash).');
        }
        
        return $result;
    }

    public function SetItemGremoryCase($Usuario,$Character,$TipoGremory,$ConfigTexto,$ItemFinalIndex,$Level,$Durabilidad,$Skill,$Luck,$Life,$ExeOP,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$ItemDuration,$DaysToUse,$PrecioTotalItem,$Moneda,$Moneda_V,$Price,$Item_Group,$Item_Index,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME) {

        $coins              =   $this->GetCoins($Usuario);
        $currentBalance     =   $this->getCreditBalance($coins);
        $CoinsRestantes     =   $PrecioTotalItem - $currentBalance;
        $ItemInf            =   $this->ObtainItemsByGroup($Item_Group,$Item_Index);
        $Saldo              =   $currentBalance - $PrecioTotalItem;

        if($currentBalance >= $PrecioTotalItem )
        {
            // Query agregar ITEM
            $query = "declare @v_time datetime = '1970-01-01 00:00:00' ";
            $query .= "declare @v_date1 datetime = GETDATE(); ";
            $query .= "declare @v_date2 datetime = GETDATE() + ".$DaysToUse."; ";
            $query .= "declare @Final_date1 bigint ";
            $query .= "declare @Final_date2 bigint ";
            $query .= "select @Final_date1 = datediff(s, @v_time, @v_date1); ";
            $query .= "select @Final_date2 = datediff(s, @v_time, @v_date2); ";
            $query .= "EXEC WZ_GremoryCase_AddItem '".$Usuario."','".$Character."',".$TipoGremory.",".$ConfigTexto.",".$ItemFinalIndex.",".$Level.",".$Durabilidad.",".$Skill.",".$Luck.",".$Life.",".$ExeOP.",".$SetOP.",".$HHOP.",".$Op380.",".$Socket1.",".$Socket2.",".$Socket3.",".$Socket4.",".$Socket5.",".$ItemDuration.",@Final_date1,@Final_date2";
        
            $result = $this->dB->query_fetch_single($query);
            $this->SubstractCoins($this->_configs['moneda'],$PrecioTotalItem,$Usuario);
            if(!$result) throw new Exception('<b>[WebShop]</b> No se pudo adquirir este <b>Item</b>.');

            // success
            message('success', '<b>[WebShop]</b> Has comprado correctamente este <b>Item</b>.');
            message('error', '<b>[WebShop]</b> Se te ha descontado <b>'.number_format($PrecioTotalItem,0,',','.').'</b> de tus <b>'.$this->_configs['moneda_v'].'</b>.');
            $this->AddWebshopLogs($Usuario,$Character,$Level,$Skill,$Luck,$Life,$SetOP,$HHOP,$Op380,$Socket1,$Socket2,$Socket3,$Socket4,$Socket5,$PrecioTotalItem,$this->_configs['moneda_v'],$Saldo,$Item_Group,$ExeOP1,$ExeOP2,$ExeOP3,$ExeOP4,$ExeOP5,$ExeOP6,$Item_NAME);

        } else{
            message('error', '<b>[WebShop]</b> No tienes suficientes <b>'.$this->_configs['moneda_v'].'</b>. Te faltan <b>'.number_format($CoinsRestantes,0,',','.').' '.$this->_configs['moneda_v'].'</b> para adquirir este item.');
        }
	}

}