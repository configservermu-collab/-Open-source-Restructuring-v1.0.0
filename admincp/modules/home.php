<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 * @author Lautaro Angelico <http://lautaroangelico.com/>
 * @copyright (c) 2013-2026 Lautaro Angelico, All Rights Reserved
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 */

// check install directory
if(file_exists(__ROOT_DIR__ . 'install/')) {
	message('warning', 'Your WebEngine CMS <strong>install</strong> directory still exists, it is recommended that you rename or delete it.', 'WARNING');
}

// Get database connections
$database = (config('SQL_USE_2_DB',true) ? $dB2 : $dB);

// Get license status
$webengineConfig = webengineConfigs();
$licenseStatus = isset($webengineConfig['license_status']) && $webengineConfig['license_status'] == 'active' ? 'ON' : 'OFF';
$licenseType = isset($webengineConfig['license_type']) ? $webengineConfig['license_type'] : 'Lifetime License';

// Get statistics
$totalAccounts = $database->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_MI_);
$totalCharacters = $dB->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_CHR_);
$totalGuilds = $dB->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_GUILD_);
$bannedAccounts = $database->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_MI_." WHERE "._CLMN_BLOCCODE_." = 1");

// Get connections today - Disabled if ConnectStat table doesn't exist
$connectionsToday = array('result' => 0);
try {
    if(defined('_TBL_CONNECTSTAT_')) {
        $connectionsToday = $dB->query_fetch_single("SELECT COUNT(DISTINCT cs."._CLMN_CHR_ACCID_.") as result FROM "._TBL_AC_." ac
            INNER JOIN "._TBL_CONNECTSTAT_." cs ON ac.Number = cs.Number 
            WHERE CAST(cs.ConnectTM as DATE) = CAST(GETDATE() as DATE)");
    }
} catch(Exception $e) {}

// Get connections this month - Disabled if ConnectStat table doesn't exist
$connectionsThisMonth = array('result' => 0);
try {
    if(defined('_TBL_CONNECTSTAT_')) {
        $connectionsThisMonth = $dB->query_fetch_single("SELECT COUNT(DISTINCT cs."._CLMN_CHR_ACCID_.") as result FROM "._TBL_AC_." ac
            INNER JOIN "._TBL_CONNECTSTAT_." cs ON ac.Number = cs.Number 
            WHERE YEAR(cs.ConnectTM) = YEAR(GETDATE()) AND MONTH(cs.ConnectTM) = MONTH(GETDATE())");
    }
} catch(Exception $e) {}

// Get connections this year - Disabled if ConnectStat table doesn't exist  
$connectionsThisYear = array('result' => 0);
try {
    if(defined('_TBL_CONNECTSTAT_')) {
        $connectionsThisYear = $dB->query_fetch_single("SELECT COUNT(DISTINCT cs."._CLMN_CHR_ACCID_.") as result FROM "._TBL_AC_." ac
            INNER JOIN "._TBL_CONNECTSTAT_." cs ON ac.Number = cs.Number 
            WHERE YEAR(cs.ConnectTM) = YEAR(GETDATE())");
    }
} catch(Exception $e) {}

// Get online accounts - Check if MEMB_STAT table exists
$online = array('result' => 0);
try {
    if(defined('_TBL_MS_') && defined('_CLMN_CONNSTAT_')) {
        $online = $database->query_fetch_single("SELECT COUNT(DISTINCT "._CLMN_MS_MEMBID_.") as result FROM "._TBL_MS_." WHERE "._CLMN_CONNSTAT_." = 1");
    }
} catch(Exception $e) {}

// Get disconnected users (registered but never connected)
$disconnectedUsers = array('result' => 0);
try {
    $disconnectedUsers = $database->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_MI_." 
        WHERE "._CLMN_USERNM_." NOT IN (SELECT DISTINCT "._CLMN_CHR_ACCID_." FROM "._TBL_CHR_.")");
} catch(Exception $e) {}

// Get guild info - Disabled if GuildInfo table doesn't exist
$guildWithCastle = array(_CLMN_GUILD_NAME_ => 'SIN DUEÑO');
$guildMasterCS = array(_CLMN_CHR_NAME_ => '-');
try {
    if(defined('_TBL_GUILDINFO_')) {
        $guildWithCastle = $dB->query_fetch_single("SELECT "._CLMN_GUILD_NAME_." FROM "._TBL_GUILD_." WHERE (SELECT SEIZE FROM "._TBL_GUILDINFO_." WHERE GUILD_NAME = "._CLMN_GUILD_NAME_.") = 1");
        $guildMasterCS = $dB->query_fetch_single("SELECT "._CLMN_GUILDMEMB_CHAR_." FROM "._TBL_GUILDMEMB_." WHERE (SELECT SEIZE FROM "._TBL_GUILDINFO_." WHERE GUILD_NAME = "._CLMN_GUILDMEMB_NAME_.") = 1 AND "._CLMN_GUILDMEMB_STATUS_." = 128");
    }
} catch(Exception $e) {}

// Get recent registrations (last 5 by ID)
$recentRegistrations = $database->query_fetch("SELECT TOP 5 "._CLMN_MEMBID_.", "._CLMN_USERNM_.", "._CLMN_MEMBNAME_.", "._CLMN_EMAIL_.", "._CLMN_BLOCCODE_.", appl_days FROM "._TBL_MI_." ORDER BY "._CLMN_MEMBID_." DESC");

// Get VIP statistics
$activeVips = array('result' => 0);
$vipsByType = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);
try {
    if(defined('_TBL_VIP_') && defined('_CLMN_VIP_TYPE_') && defined('_CLMN_VIP_DATE_')) {
        // Count active VIPs (expiration date in future and type > 0)
        $activeVips = $dB2->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_VIP_." WHERE "._CLMN_VIP_TYPE_." > 0 AND "._CLMN_VIP_DATE_." > GETDATE()");
        
        // Count VIPs by type
        $vipTypes = $dB2->query_fetch("SELECT "._CLMN_VIP_TYPE_." as vip_type, COUNT(*) as count FROM "._TBL_VIP_." WHERE "._CLMN_VIP_TYPE_." > 0 AND "._CLMN_VIP_DATE_." > GETDATE() GROUP BY "._CLMN_VIP_TYPE_);
        if(is_array($vipTypes)) {
            foreach($vipTypes as $vipType) {
                $vipsByType[$vipType['vip_type']] = $vipType['count'];
            }
        }
    }
} catch(Exception $e) {}

// Get donation statistics
$paypalTotal = 0;
$mercadopagoTotal = 0;
try {
    $paypalResult = $database->query_fetch_single("SELECT ISNULL(SUM(CAST(payment_amount as FLOAT)), 0) as total FROM ".WEBENGINE_PAYPAL_TRANSACTIONS." WHERE transaction_status = 1");
    $paypalTotal = $paypalResult['total'] ?? 0;
} catch(Exception $e) {}

try {
    $mpResult = $database->query_fetch_single("SELECT ISNULL(SUM(CAST(card_mount as FLOAT)), 0) as total FROM WEBENGINE_MERCADOPAGO_TRANSACTIONS WHERE state_compra = 'approved'");
    $mercadopagoTotal = $mpResult['total'] ?? 0;
} catch(Exception $e) {}

?>

<style>
/* AdminCP dashboard — layout independent of Bootstrap's 12-column grid */
.admincp-home {color:#e9eef2; width:100%; min-width:0;}
.admincp-home *, .admincp-home *::before, .admincp-home *::after {box-sizing:border-box;}
.admincp-home .license-box,
.admincp-home .info-box {background:#192733; border:1px solid rgba(126,184,220,.2); border-radius:12px; padding:20px; margin-bottom:20px; color:#e9eef2;}
.admincp-home .license-box {text-align:center; padding:23px;}
.admincp-home .license-box h3 {margin:0; font-size:24px;}
/* The statistics section does not use .row, floats, or .col-md-* */
.admincp-home .stat-grid {display:grid !important; width:100%; gap:16px; margin:18px 0 20px; padding:0; clear:both; min-width:0;}
.admincp-home .stat-grid-main {grid-template-columns:repeat(4,minmax(0,1fr));}
.admincp-home .stat-grid-vip {grid-template-columns:repeat(5,minmax(0,1fr));}
.admincp-home .stat-grid > .stat-item {min-width:0; width:auto; float:none; margin:0; padding:0;}
.admincp-home .stat-card {height:140px; width:100%; margin:0; padding:19px; border-radius:12px; border:1px solid #294358; color:#fff; background:linear-gradient(135deg,#123653,#075c82); box-shadow:0 9px 24px rgba(0,0,0,.17); display:flex; flex-direction:column; justify-content:center; align-items:flex-start; transition:transform .2s,border-color .2s;}
.admincp-home .stat-card:hover {transform:translateY(-3px);border-color:#00d9ff;}
.admincp-home .stat-card i {font-size:26px; color:#38bdf8; opacity:1; margin-bottom:8px;}
.admincp-home .stat-number {font-size:30px;line-height:1.12;font-weight:700;margin:0 0 6px;}
.admincp-home .stat-label {font-size:13px; line-height:1.3;color:#d3e0e9;}
.admincp-home .stat-grid-vip > .stat-item:nth-child(2) i {color:#cd7f32;}
.admincp-home .stat-grid-vip > .stat-item:nth-child(3) i {color:#c0c0c0;}
.admincp-home .stat-grid-vip > .stat-item:nth-child(4) i {color:#ffd700;}
.admincp-home .stat-grid-vip > .stat-item:nth-child(5) i {color:#81ded0;}
.admincp-home .info-box h4 {color:#00d9ff;border-bottom:1px solid rgba(126,184,220,.2);padding-bottom:12px;margin:0 0 12px;}
.admincp-home .info-box small {color:#aeb9c4 !important;}
.admincp-home .info-box {overflow-x:auto;}
.admincp-home .info-row {display:flex;justify-content:space-between;gap:12px;padding:9px 0;border-bottom:1px solid rgba(126,184,220,.16);color:#e9eef2;}
.admincp-home .info-box .info-row[style*="background"] {background:rgba(13,20,28,.82) !important;}
.admincp-home .donation-card {padding:20px;border-radius:10px;color:#fff;margin-bottom:15px;}
.admincp-home .donation-mercadopago {background:linear-gradient(135deg,#00b4d8,#0077b6);}
.admincp-home .donation-paypal {background:linear-gradient(135deg,#dc2626,#991b1b);}
.admincp-home .table-recent {color:#e9eef2;background:#131f2a;border-radius:8px;}
.admincp-home .table-recent > thead > tr > th {color:#00d9ff;background:#162330;border-color:#294358;}
.admincp-home .table-recent > tbody > tr > td {color:#e9eef2;background:#131f2a;border-color:#294358;vertical-align:middle;}
.admincp-home .table-recent > tbody > tr:hover > td {background:#213b4b;}
@media (max-width:1199px) {
 .admincp-home .stat-grid-main {grid-template-columns:repeat(2,minmax(0,1fr));}
 .admincp-home .stat-grid-vip {grid-template-columns:repeat(3,minmax(0,1fr));}
}
@media (max-width:767px) {
 .admincp-home .stat-grid-main, .admincp-home .stat-grid-vip {grid-template-columns:repeat(2,minmax(0,1fr));}
 .admincp-home .stat-grid {gap:12px;}
 .admincp-home .stat-card {height:125px;padding:14px;}
 .admincp-home .stat-number {font-size:26px;}
 .admincp-home .table-recent {min-width:560px;}
}
@media (max-width:420px) {
 .admincp-home .stat-grid-main,.admincp-home .stat-grid-vip {grid-template-columns:1fr;}
}
@media (prefers-reduced-motion:reduce) { .admincp-home .stat-card {transition:none;} }
</style>

<div class="admincp-home">
<!-- License Status -->
<div class="row">
    <div class="col-md-12">
        <div class="license-box">
            <h3 style="margin-top:0;">Bienvenido/a al ADMINCP</h3>
        </div>
    </div>
</div>

<!-- Main Statistics: four equal cards -->
<div class="stat-grid stat-grid-main">
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-users" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($totalAccounts['result']); ?></div>
            <div class="stat-label">Total Registrados</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-user" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($totalCharacters['result']); ?></div>
            <div class="stat-label">Total Personajes</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-shield" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($totalGuilds['result']); ?></div>
            <div class="stat-label">Total Guilds</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-ban" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($bannedAccounts['result']); ?></div>
            <div class="stat-label">Total Baneados</div>
        </div>
    </div>
</div>

<!-- VIP Statistics: five equal cards -->
<div class="stat-grid stat-grid-vip">
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-star" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($activeVips['result']); ?></div>
            <div class="stat-label">VIPs Activos</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-trophy" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($vipsByType[1]); ?></div>
            <div class="stat-label">Bronze VIP</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-trophy" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($vipsByType[2]); ?></div>
            <div class="stat-label">Silver VIP</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-trophy" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($vipsByType[3]); ?></div>
            <div class="stat-label">Gold VIP</div>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-card">
            <i class="fa fa-trophy" aria-hidden="true"></i>
            <div class="stat-number"><?php echo number_format($vipsByType[4]); ?></div>
            <div class="stat-label">Platinum VIP</div>
        </div>
    </div>
</div>

<!-- Donations and Recent Registrations -->
<div class="row">
    <!-- Donation Statistics -->
    <div class="col-md-6">
        <div class="info-box">
            <h4><i class="fa fa-dollar"></i> Estadísticas de Donaciones</h4>
            <small style="color: #666;">Estadísticas basadas en métodos Automáticos</small>
            
            <div class="donation-card donation-mercadopago" style="margin-top: 20px;">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-credit-card" style="font-size: 32px;"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div style="font-size: 24px; font-weight: bold;">$<?php echo number_format($mercadopagoTotal, 0); ?> ARS</div>
                        <div>Mercadopago</div>
                    </div>
                </div>
            </div>
            
            <div class="donation-card donation-paypal">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-paypal" style="font-size: 32px;"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div style="font-size: 24px; font-weight: bold;">$<?php echo number_format($paypalTotal, 2); ?> USD</div>
                        <div>Paypal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Registrations -->
    <div class="col-md-6">
        <div class="info-box">
            <h4><i class="fa fa-user-plus"></i> Últimos Registros</h4>
            <small style="color: #666;">Estadísticas basadas en los últimos 5 minutos</small>
            
            <table class="table table-striped table-recent" style="margin-top: 20px;">
                <thead>
                    <tr>
                        <th>País</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(is_array($recentRegistrations) && count($recentRegistrations) > 0) {
                        // Mapa simple de países por email
                        $countryMap = ['ar' => 'ar', 'cl' => 'cl', 'pe' => 'pe', 'mx' => 'mx', 'us' => 'us'];
                        
                        foreach($recentRegistrations as $reg) {
                            $country = 'ar'; // default
                            if(isset($reg[_CLMN_EMAIL_])) {
                                foreach($countryMap as $code => $domain) {
                                    if(stripos($reg[_CLMN_EMAIL_], '.'.$domain) !== false) {
                                        $country = $code;
                                        break;
                                    }
                                }
                            }
                            
                            $status = ($reg[_CLMN_BLOCCODE_] == 1) ? '<span class="label label-danger">Baneado</span>' : '<span class="label label-success">Activo</span>';
                            $username = htmlspecialchars($reg[_CLMN_MEMBNAME_] ?? $reg[_CLMN_USERNM_]);
                            $date = date('d/m/Y', strtotime($reg['appl_days'] ?? 'now'));
                            
                            echo '<tr>';
                            echo '<td><img src="'.__PATH_COUNTRY_FLAGS__.$country.'.gif" width="20" alt="'.$country.'"></td>';
                            echo '<td>'.$username.'</td>';
                            echo '<td>'.$date.'</td>';
                            echo '<td>'.$status.'</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center">No hay registros recientes</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info Guilds and Connections -->
<div class="row">
    <!-- Info Guilds -->
    <div class="col-md-6">
        <div class="info-box">
            <h4><i class="fa fa-shield"></i> Info Guilds</h4>
            <small style="color: #666;">Estadísticas útiles sobre clanes</small>
            
            <div class="info-row" style="margin-top: 20px;">
                <span><strong>Total Guilds creados:</strong></span>
                <span style="color: #667eea; font-weight: bold;"><?php echo number_format($totalGuilds['result']); ?></span>
            </div>
            
            <div class="info-row">
                <span><strong>Total Personajes con Guilds:</strong></span>
                <?php 
                $charsWithGuild = array('result' => 0);
                try {
                    // G_Name is the column in Character table that stores guild name
                    $result = $dB->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_CHR_." WHERE G_Name IS NOT NULL AND G_Name != ''");
                    if(is_array($result) && isset($result['result'])) {
                        $charsWithGuild = $result;
                    }
                } catch(Exception $e) {}
                ?>
                <span style="color: #1dd1a1; font-weight: bold;"><?php echo number_format($charsWithGuild['result']); ?></span>
            </div>
            
            <div class="info-row">
                <span><strong>Total Personajes sin Guilds:</strong></span>
                <?php 
                $charsWithoutGuild = array('result' => 0);
                try {
                    // G_Name is the column in Character table that stores guild name
                    $result = $dB->query_fetch_single("SELECT COUNT(*) as result FROM "._TBL_CHR_." WHERE G_Name IS NULL OR G_Name = ''");
                    if(is_array($result) && isset($result['result'])) {
                        $charsWithoutGuild = $result;
                    }
                } catch(Exception $e) {}
                ?>
                <span style="color: #ff6b6b; font-weight: bold;"><?php echo number_format($charsWithoutGuild['result']); ?></span>
            </div>
            
            <div class="info-row">
                <span><strong>Más score:</strong></span>
                <?php 
                $topGuild = array(_CLMN_GUILD_NAME_ => 'N/A');
                try {
                    $result = $dB->query_fetch_single("SELECT TOP 1 "._CLMN_GUILD_NAME_.", "._CLMN_GUILD_SCORE_." FROM "._TBL_GUILD_." ORDER BY "._CLMN_GUILD_SCORE_." DESC");
                    if(is_array($result)) {
                        $topGuild = $result;
                    }
                } catch(Exception $e) {}
                ?>
                <span style="color: #feca57; font-weight: bold;"><?php echo htmlspecialchars($topGuild[_CLMN_GUILD_NAME_] ?? 'N/A'); ?></span>
            </div>
            
            <div class="info-row">
                <span><strong>Menos score:</strong></span>
                <?php 
                $bottomGuild = array(_CLMN_GUILD_NAME_ => 'N/A');
                try {
                    $result = $dB->query_fetch_single("SELECT TOP 1 "._CLMN_GUILD_NAME_.", "._CLMN_GUILD_SCORE_." FROM "._TBL_GUILD_." WHERE "._CLMN_GUILD_SCORE_." > 0 ORDER BY "._CLMN_GUILD_SCORE_." ASC");
                    if(is_array($result)) {
                        $bottomGuild = $result;
                    }
                } catch(Exception $e) {}
                ?>
                <span style="color: #ee5a6f; font-weight: bold;"><?php echo htmlspecialchars($bottomGuild[_CLMN_GUILD_NAME_] ?? 'N/A'); ?></span>
            </div>
            
            <div class="info-row">
                <span><i class="fa fa-trophy"></i> <strong>Guild Owner Castle Siege:</strong></span>
                <span style="color: #ff9ff3; font-weight: bold;"><?php echo htmlspecialchars($guildWithCastle[_CLMN_GUILD_NAME_] ?? 'SIN DUEÑO'); ?></span>
            </div>
            
            <div class="info-row">
                <span><i class="fa fa-user"></i> <strong>Guild Master CS:</strong></span>
                <span style="color: #54a0ff; font-weight: bold;"><?php echo htmlspecialchars($guildMasterCS[_CLMN_CHR_NAME_] ?? '-'); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Info Conexiones -->
    <div class="col-md-6">
        <div class="info-box">
            <h4><i class="fa fa-plug"></i> Info Conexiones</h4>
            <small style="color: #666;">Información general sobre Status</small>
            
            <div class="info-row" style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-radius: 5px;">
                <span><strong>Conexiones este año:</strong></span>
                <span style="color: #1e88e5; font-weight: bold; font-size: 18px;"><?php echo number_format($connectionsThisYear['result']); ?></span>
            </div>
            
            <div class="info-row" style="padding: 15px; background: #f3e5f5; border-radius: 5px; margin-top: 10px;">
                <span><strong>Conexiones este mes:</strong></span>
                <span style="color: #8e24aa; font-weight: bold; font-size: 18px;"><?php echo number_format($connectionsThisMonth['result']); ?></span>
            </div>
            
            <div class="info-row" style="padding: 15px; background: #e8f5e9; border-radius: 5px; margin-top: 10px;">
                <span><strong>Conexiones hoy:</strong></span>
                <span style="color: #43a047; font-weight: bold; font-size: 18px;"><?php echo number_format($connectionsToday['result']); ?></span>
            </div>
            
            <div class="info-row" style="padding: 15px; background: #fff3e0; border-radius: 5px; margin-top: 10px;">
                <span><strong>Conectados Ahora:</strong></span>
                <span style="color: #ef6c00; font-weight: bold; font-size: 18px;"><?php echo number_format($online['result']); ?></span>
            </div>
            
            <div class="info-row" style="padding: 15px; background: #ffebee; border-radius: 5px; margin-top: 10px;">
                <span><strong>Usuarios Desconectados:</strong></span>
                <span style="color: #c62828; font-weight: bold; font-size: 18px;"><?php echo number_format($disconnectedUsers['result']); ?></span>
            </div>
        </div>
    </div>
</div>
</div>