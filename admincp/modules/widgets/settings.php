<?php
/**
 * Widgets System - Settings
 * WebEngine CMS Module
 * 
 * @version 1.0.0
 * @author WebEngine Widgets System
 * @copyright (c) 2026
 * @modified-by ConfigServerMU <https://configservermu.net/>
 * @copyright (c) 2026 ConfigServerMU, All Rights Reserved
 */

function saveWidgetsConfig() {
    global $_POST;
	
    $configPath = __PATH_ADMINCP__ . 'data/widgets_config.json';
    
    // Debug: verificar ruta
    if(!file_exists(dirname($configPath))) {
        throw new Exception('Directory does not exist: ' . dirname($configPath));
    }
    
    if(!file_exists($configPath)) {
        // Create default config if doesn't exist
        $defaultConfig = [
            'wanted_widget' => [
                'enabled' => true,
                'title' => 'WANTED',
                'min_pk_count' => 1,
                'rank_berserker' => 100,
                'rank_killer' => 50,
                'rank_hunter' => 20,
                'background_color' => '#8B0000,#DC143C',
                'text_color' => '#FFD700',
                'border_color' => 'rgba(255, 100, 100, 0.4)'
            ],
            'vicio_widget' => [
                'enabled' => true,
                'title' => 'EL MÁS VICIO',
                'criteria' => 'level',
                'background_color' => '#4B0082,#8A2BE2',
                'text_color' => '#FFD700',
                'border_color' => 'rgba(180, 130, 255, 0.35)'
            ],
            'online_widget' => [
                'enabled' => true,
                'title' => 'USUARIOS ONLINE',
                'refresh_interval' => 20,
                'fake_count' => 0,
                'background_color' => '#006400,#32CD32',
                'text_color' => '#FFD700',
                'border_color' => 'rgba(255, 221, 162, 0.25)',
                'variant' => 'badge'
            ],
            'general' => [
                'enabled' => true,
                'show_class_images' => true,
                'show_status' => true,
                'widget_effects' => true,
                'position' => 'hero'
            ]
        ];
        file_put_contents($configPath, json_encode($defaultConfig, JSON_PRETTY_PRINT));
    }
    
	if(!is_writable($configPath)) throw new Exception('The configuration file is not writable. Please check file permissions (should be 644).');
	
	// Build configuration array
	$config = [
		'wanted_widget' => [
			'enabled' => (isset($_POST['wanted_enabled']) && $_POST['wanted_enabled'] == 1),
			'title' => $_POST['wanted_title'] ?? 'WANTED',
			'min_pk_count' => (int)($_POST['wanted_min_pk'] ?? 1),
			'rank_berserker' => (int)($_POST['wanted_rank_berserker'] ?? 100),
			'rank_killer' => (int)($_POST['wanted_rank_killer'] ?? 50),
			'rank_hunter' => (int)($_POST['wanted_rank_hunter'] ?? 20),
			'background_color' => $_POST['wanted_bg_color'] ?? '#8B0000,#DC143C',
			'text_color' => $_POST['wanted_text_color'] ?? '#FFD700',
			'border_color' => $_POST['wanted_border_color'] ?? 'rgba(255, 100, 100, 0.4)'
		],
		'vicio_widget' => [
			'enabled' => (isset($_POST['vicio_enabled']) && $_POST['vicio_enabled'] == 1),
			'title' => $_POST['vicio_title'] ?? 'EL MÁS VICIO',
			'criteria' => 'level',
			'background_color' => $_POST['vicio_bg_color'] ?? '#4B0082,#8A2BE2',
			'text_color' => $_POST['vicio_text_color'] ?? '#FFD700',
			'border_color' => $_POST['vicio_border_color'] ?? 'rgba(180, 130, 255, 0.35)'
		],
		'online_widget' => [
			'enabled' => (isset($_POST['online_enabled']) && $_POST['online_enabled'] == 1),
			'title' => $_POST['online_title'] ?? 'USUARIOS ONLINE',
			'refresh_interval' => (int)($_POST['online_refresh'] ?? 20),
			'fake_count' => (int)($_POST['online_fake_count'] ?? 0),
			'background_color' => $_POST['online_bg_color'] ?? '#006400,#32CD32',
			'text_color' => $_POST['online_text_color'] ?? '#FFD700',
			'border_color' => $_POST['online_border_color'] ?? 'rgba(255, 221, 162, 0.25)',
			'variant' => 'badge'
		],
		'general' => [
			'enabled' => (isset($_POST['general_enabled']) && $_POST['general_enabled'] == 1),
			'show_class_images' => (isset($_POST['general_class_images']) && $_POST['general_class_images'] == 1),
			'show_status' => (isset($_POST['general_status']) && $_POST['general_status'] == 1),
			'widget_effects' => (isset($_POST['general_effects']) && $_POST['general_effects'] == 1),
			'position' => 'hero'
		]
	];
	
	// Validate numbers
	if($config['wanted_widget']['min_pk_count'] < 0) throw new Exception('Minimum PK count cannot be negative');
	if($config['wanted_widget']['rank_berserker'] < 0) throw new Exception('Berserker rank cannot be negative');
	if($config['wanted_widget']['rank_killer'] < 0) throw new Exception('Killer rank cannot be negative');
	if($config['wanted_widget']['rank_hunter'] < 0) throw new Exception('Hunter rank cannot be negative');
	if($config['online_widget']['refresh_interval'] < 1) throw new Exception('Refresh interval must be at least 1 second');
	if($config['online_widget']['fake_count'] < 0) throw new Exception('Fake online count cannot be negative');
	
    $save = file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT));
	if(!$save) throw new Exception('There has been an error while saving changes.');
}

if(isset($_POST['submit_changes']) || check_value($_POST['submit_changes'])) {
	try {
		saveWidgetsConfig();
		message('success', 'Widgets settings successfully saved!');
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// Load current config
$configPath = __PATH_ADMINCP__ . 'data/widgets_config.json';
if(file_exists($configPath)) {
    $widgetsConfig = json_decode(file_get_contents($configPath), true);
} else {
    // Default config
    $widgetsConfig = [
        'wanted_widget' => [
            'enabled' => true,
            'title' => 'WANTED',
            'min_pk_count' => 1,
            'rank_berserker' => 100,
            'rank_killer' => 50,
            'rank_hunter' => 20,
            'background_color' => '#8B0000,#DC143C',
            'text_color' => '#FFD700',
            'border_color' => 'rgba(255, 100, 100, 0.4)'
        ],
        'vicio_widget' => [
            'enabled' => true,
            'title' => 'EL MÁS VICIO',
            'criteria' => 'level',
            'background_color' => '#4B0082,#8A2BE2',
            'text_color' => '#FFD700',
            'border_color' => 'rgba(180, 130, 255, 0.35)'
        ],
        'online_widget' => [
            'enabled' => true,
            'title' => 'USUARIOS ONLINE',
            'refresh_interval' => 20,
            'fake_count' => 0,
            'background_color' => '#006400,#32CD32',
            'text_color' => '#FFD700',
            'border_color' => 'rgba(255, 221, 162, 0.25)',
            'variant' => 'badge'
        ],
        'general' => [
            'enabled' => true,
            'show_class_images' => true,
            'show_status' => true,
            'widget_effects' => true,
            'position' => 'hero'
        ]
    ];
}
?>

<h2><i class="fa fa-tachometer"></i> Widgets System Configuration</h2>

<div class="alert alert-info">
    <i class="fa fa-info-circle"></i> <strong>Info:</strong> Configure the widgets that appear on your website's main page. These widgets display player information from rankings cache (no SQL queries).
</div>

<form action="" method="post">

	<!-- GENERAL SETTINGS -->
	<h3><i class="fa fa-cog"></i> General Settings</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
            <th width="40%">Enable Widgets System<br/><span>Master switch to enable/disable all widgets</span></th>
            <td>
				<?php enabledisableCheckboxes('general_enabled', $widgetsConfig['general']['enabled'], 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>Show Class Images<br/><span>Display character class images in widgets</span></th>
            <td>
				<?php enabledisableCheckboxes('general_class_images', $widgetsConfig['general']['show_class_images'], 'Show', 'Hide'); ?>
            </td>
        </tr>
		<tr>
            <th>Show Online Status<br/><span>Display online/offline status indicators</span></th>
            <td>
				<?php enabledisableCheckboxes('general_status', $widgetsConfig['general']['show_status'], 'Show', 'Hide'); ?>
            </td>
        </tr>
		<tr>
            <th>Widget Effects<br/><span>Enable hover animations and visual effects</span></th>
            <td>
				<?php enabledisableCheckboxes('general_effects', $widgetsConfig['general']['widget_effects'], 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
	</table>

	<!-- WANTED WIDGET -->
	<h3><i class="fa fa-crosshairs"></i> WANTED Widget (Top PKs)</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
            <th width="40%">Enable WANTED Widget<br/><span>Shows the player with most PKs</span></th>
            <td>
				<?php enabledisableCheckboxes('wanted_enabled', $widgetsConfig['wanted_widget']['enabled'], 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>Widget Title<br/><span>Title displayed at the top of the widget</span></th>
            <td>
				<input class="form-control" type="text" name="wanted_title" value="<?php echo htmlspecialchars($widgetsConfig['wanted_widget']['title']); ?>" placeholder="WANTED"/>
            </td>
        </tr>
		<tr>
            <th>Minimum PK Count<br/><span>Minimum PKs required to display the widget</span></th>
            <td>
				<input class="form-control" type="number" name="wanted_min_pk" value="<?php echo $widgetsConfig['wanted_widget']['min_pk_count']; ?>" min="0"/>
            </td>
        </tr>
		<tr>
            <th>Berserker Rank (PKs)<br/><span>PKs needed to reach "BERSERKER" rank</span></th>
            <td>
				<input class="form-control" type="number" name="wanted_rank_berserker" value="<?php echo $widgetsConfig['wanted_widget']['rank_berserker']; ?>" min="0"/>
            </td>
        </tr>
		<tr>
            <th>Killer Rank (PKs)<br/><span>PKs needed to reach "KILLER" rank</span></th>
            <td>
				<input class="form-control" type="number" name="wanted_rank_killer" value="<?php echo $widgetsConfig['wanted_widget']['rank_killer']; ?>" min="0"/>
            </td>
        </tr>
		<tr>
            <th>Hunter Rank (PKs)<br/><span>PKs needed to reach "HUNTER" rank</span></th>
            <td>
				<input class="form-control" type="number" name="wanted_rank_hunter" value="<?php echo $widgetsConfig['wanted_widget']['rank_hunter']; ?>" min="0"/>
            </td>
        </tr>
		<tr>
            <th>Background Colors<br/><span>Gradient colors (color1,color2). Example: #8B0000,#DC143C</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="wanted_bg_color" id="wanted_bg_color" value="<?php echo $widgetsConfig['wanted_widget']['background_color']; ?>" placeholder="#8B0000,#DC143C"/>
					<div class="color-palette">
						<span class="color-option" data-colors="#8B0000,#DC143C" style="background: linear-gradient(135deg, #8B0000, #DC143C);" title="Red Dark"></span>
						<span class="color-option" data-colors="#FF0000,#8B0000" style="background: linear-gradient(135deg, #FF0000, #8B0000);" title="Red Bright"></span>
						<span class="color-option" data-colors="#B22222,#8B0000" style="background: linear-gradient(135deg, #B22222, #8B0000);" title="Firebrick"></span>
						<span class="color-option" data-colors="#8B0000,#4B0000" style="background: linear-gradient(135deg, #8B0000, #4B0000);" title="Dark Red"></span>
						<span class="color-option" data-colors="#FF6347,#DC143C" style="background: linear-gradient(135deg, #FF6347, #DC143C);" title="Tomato"></span>
						<span class="color-option" data-colors="#CD5C5C,#8B0000" style="background: linear-gradient(135deg, #CD5C5C, #8B0000);" title="Indian Red"></span>
					</div>
				</div>
            </td>
        </tr>
		<tr>
            <th>Text Color<br/><span>Color for text inside the widget</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="wanted_text_color" id="wanted_text_color" value="<?php echo $widgetsConfig['wanted_widget']['text_color']; ?>" placeholder="#FFD700"/>
					<div class="color-palette">
						<span class="color-option-single" data-color="#FFD700" style="background: #FFD700;" title="Gold"></span>
						<span class="color-option-single" data-color="#FFFFFF" style="background: #FFFFFF; border: 1px solid #ddd;" title="White"></span>
						<span class="color-option-single" data-color="#F0C15E" style="background: #F0C15E;" title="Light Gold"></span>
						<span class="color-option-single" data-color="#FFA500" style="background: #FFA500;" title="Orange"></span>
						<span class="color-option-single" data-color="#FF6347" style="background: #FF6347;" title="Tomato"></span>
						<span class="color-option-single" data-color="#F1D1D1" style="background: #F1D1D1;" title="Light Pink"></span>
						<span class="color-option-single" data-color="#CCCCCC" style="background: #CCCCCC;" title="Light Gray"></span>
						<span class="color-option-single" data-color="#000000" style="background: #000000;" title="Black"></span>
					</div>
				</div>
            </td>
        </tr>
		<tr>
            <th>Border Color<br/><span>Color for the widget border (supports rgba)</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="wanted_border_color" id="wanted_border_color" value="<?php echo $widgetsConfig['wanted_widget']['border_color']; ?>" placeholder="rgba(255, 100, 100, 0.4)"/>
					<div class="color-palette">
						<span class="color-option-single" data-color="rgba(255, 100, 100, 0.4)" style="background: rgba(255, 100, 100, 0.4); border: 1px solid #666;" title="Red Glow"></span>
						<span class="color-option-single" data-color="rgba(255, 0, 0, 0.5)" style="background: rgba(255, 0, 0, 0.5); border: 1px solid #666;" title="Red"></span>
						<span class="color-option-single" data-color="rgba(255, 165, 0, 0.4)" style="background: rgba(255, 165, 0, 0.4); border: 1px solid #666;" title="Orange"></span>
						<span class="color-option-single" data-color="rgba(255, 215, 0, 0.5)" style="background: rgba(255, 215, 0, 0.5); border: 1px solid #666;" title="Gold"></span>
						<span class="color-option-single" data-color="rgba(255, 255, 255, 0.3)" style="background: rgba(255, 255, 255, 0.3); border: 1px solid #666;" title="White"></span>
						<span class="color-option-single" data-color="rgba(0, 0, 0, 0.5)" style="background: rgba(0, 0, 0, 0.5); border: 1px solid #666;" title="Black"></span>
					</div>
				</div>
            </td>
        </tr>
	</table>

	<!-- VICIO WIDGET -->
	<h3><i class="fa fa-fire"></i> EL MÁS VICIO Widget (Top Level)</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
            <th width="40%">Enable VICIO Widget<br/><span>Shows the player with highest level</span></th>
            <td>
				<?php enabledisableCheckboxes('vicio_enabled', $widgetsConfig['vicio_widget']['enabled'], 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>Widget Title<br/><span>Title displayed at the top of the widget</span></th>
            <td>
				<input class="form-control" type="text" name="vicio_title" value="<?php echo htmlspecialchars($widgetsConfig['vicio_widget']['title']); ?>" placeholder="EL MÁS VICIO"/>
            </td>
        </tr>
		<tr>
            <th>Background Colors<br/><span>Gradient colors (color1,color2). Example: #4B0082,#8A2BE2</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="vicio_bg_color" id="vicio_bg_color" value="<?php echo $widgetsConfig['vicio_widget']['background_color']; ?>" placeholder="#4B0082,#8A2BE2"/>
					<div class="color-palette">
						<span class="color-option" data-colors="#4B0082,#8A2BE2" style="background: linear-gradient(135deg, #4B0082, #8A2BE2);" title="Purple Dark"></span>
						<span class="color-option" data-colors="#6A0DAD,#9370DB" style="background: linear-gradient(135deg, #6A0DAD, #9370DB);" title="Purple Bright"></span>
						<span class="color-option" data-colors="#800080,#DA70D6" style="background: linear-gradient(135deg, #800080, #DA70D6);" title="Purple Orchid"></span>
						<span class="color-option" data-colors="#4B0082,#2E0854" style="background: linear-gradient(135deg, #4B0082, #2E0854);" title="Deep Purple"></span>
						<span class="color-option" data-colors="#8A2BE2,#9932CC" style="background: linear-gradient(135deg, #8A2BE2, #9932CC);" title="Blue Violet"></span>
						<span class="color-option" data-colors="#9400D3,#8A2BE2" style="background: linear-gradient(135deg, #9400D3, #8A2BE2);" title="Dark Violet"></span>
					</div>
				</div>
            </td>
        </tr>
		<tr>
            <th>Text Color<br/><span>Color for text inside the widget</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="vicio_text_color" id="vicio_text_color" value="<?php echo $widgetsConfig['vicio_widget']['text_color']; ?>" placeholder="#FFD700"/>
					<div class="color-palette">
						<span class="color-option-single" data-color="#FFD700" style="background: #FFD700;" title="Gold"></span>
						<span class="color-option-single" data-color="#FFFFFF" style="background: #FFFFFF; border: 1px solid #ddd;" title="White"></span>
						<span class="color-option-single" data-color="#E8D5FF" style="background: #E8D5FF;" title="Light Purple"></span>
						<span class="color-option-single" data-color="#DDA0DD" style="background: #DDA0DD;" title="Plum"></span>
						<span class="color-option-single" data-color="#F0C15E" style="background: #F0C15E;" title="Light Gold"></span>
						<span class="color-option-single" data-color="#DA70D6" style="background: #DA70D6;" title="Orchid"></span>
						<span class="color-option-single" data-color="#CCCCCC" style="background: #CCCCCC;" title="Light Gray"></span>
						<span class="color-option-single" data-color="#000000" style="background: #000000;" title="Black"></span>
					</div>
				</div>
            </td>
        </tr>
		<tr>
            <th>Border Color<br/><span>Color for the widget border (supports rgba)</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="vicio_border_color" id="vicio_border_color" value="<?php echo $widgetsConfig['vicio_widget']['border_color']; ?>" placeholder="rgba(180, 130, 255, 0.35)"/>
					<div class="color-palette">
						<span class="color-option-single" data-color="rgba(180, 130, 255, 0.35)" style="background: rgba(180, 130, 255, 0.35); border: 1px solid #666;" title="Purple Glow"></span>
						<span class="color-option-single" data-color="rgba(138, 43, 226, 0.5)" style="background: rgba(138, 43, 226, 0.5); border: 1px solid #666;" title="Blue Violet"></span>
						<span class="color-option-single" data-color="rgba(147, 112, 219, 0.4)" style="background: rgba(147, 112, 219, 0.4); border: 1px solid #666;" title="Medium Purple"></span>
						<span class="color-option-single" data-color="rgba(218, 112, 214, 0.5)" style="background: rgba(218, 112, 214, 0.5); border: 1px solid #666;" title="Orchid"></span>
						<span class="color-option-single" data-color="rgba(255, 255, 255, 0.3)" style="background: rgba(255, 255, 255, 0.3); border: 1px solid #666;" title="White"></span>
						<span class="color-option-single" data-color="rgba(0, 0, 0, 0.5)" style="background: rgba(0, 0, 0, 0.5); border: 1px solid #666;" title="Black"></span>
					</div>
				</div>
            </td>
        </tr>
	</table>

	<!-- ONLINE WIDGET -->
	<h3><i class="fa fa-users"></i> USUARIOS ONLINE Widget</h3>
	<table class="table table-striped table-bordered table-hover module_config_tables">
		<tr>
            <th width="40%">Enable ONLINE Widget<br/><span>Shows count of online users</span></th>
            <td>
				<?php enabledisableCheckboxes('online_enabled', $widgetsConfig['online_widget']['enabled'], 'Enabled', 'Disabled'); ?>
            </td>
        </tr>
		<tr>
            <th>Widget Title<br/><span>Title displayed in the badge</span></th>
            <td>
				<input class="form-control" type="text" name="online_title" value="<?php echo htmlspecialchars($widgetsConfig['online_widget']['title']); ?>" placeholder="USUARIOS ONLINE"/>
            </td>
        </tr>
		<tr>
            <th>Refresh Interval (seconds)<br/><span>How often to update the count (via cache)</span></th>
            <td>
				<input class="form-control" type="number" name="online_refresh" value="<?php echo $widgetsConfig['online_widget']['refresh_interval']; ?>" min="1"/>
            </td>
        </tr>
		<tr>
            <th>Fake Online Users<br/><span>Add extra fake users to the real count. 0 = disabled</span></th>
            <td>
				<input class="form-control" type="number" name="online_fake_count" value="<?php echo isset($widgetsConfig['online_widget']['fake_count']) ? $widgetsConfig['online_widget']['fake_count'] : 0; ?>" min="0" placeholder="0"/>
				<span class="help-block" style="font-size: 11px; color: #999;">Example: If real = 5 and fake = 10, widget shows 15 users online</span>
            </td>
        </tr>
		<tr>
            <th>Background Colors<br/><span>Gradient colors (color1,color2). Example: #006400,#32CD32</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="online_bg_color" id="online_bg_color" value="<?php echo $widgetsConfig['online_widget']['background_color']; ?>" placeholder="#006400,#32CD32"/>
					<div class="color-palette">
						<span class="color-option" data-colors="#006400,#32CD32" style="background: linear-gradient(135deg, #006400, #32CD32);" title="Green Dark"></span>
						<span class="color-option" data-colors="#008000,#00FF00" style="background: linear-gradient(135deg, #008000, #00FF00);" title="Green Bright"></span>
						<span class="color-option" data-colors="#228B22,#90EE90" style="background: linear-gradient(135deg, #228B22, #90EE90);" title="Forest Green"></span>
						<span class="color-option" data-colors="#006400,#003200" style="background: linear-gradient(135deg, #006400, #003200);" title="Deep Green"></span>
						<span class="color-option" data-colors="#00FF00,#32CD32" style="background: linear-gradient(135deg, #00FF00, #32CD32);" title="Lime Green"></span>
						<span class="color-option" data-colors="#2E8B57,#3CB371" style="background: linear-gradient(135deg, #2E8B57, #3CB371);" title="Sea Green"></span>
					</div>
				</div>
            </td>
        </tr>
		<tr>
            <th>Text Color<br/><span>Color for text inside the badge</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="online_text_color" id="online_text_color" value="<?php echo $widgetsConfig['online_widget']['text_color']; ?>" placeholder="#FFD700"/>
					<div class="color-palette">
						<span class="color-option-single" data-color="#FFD700" style="background: #FFD700;" title="Gold"></span>
						<span class="color-option-single" data-color="#FFFFFF" style="background: #FFFFFF; border: 1px solid #ddd;" title="White"></span>
						<span class="color-option-single" data-color="#F1E7CF" style="background: #F1E7CF;" title="Light Cream"></span>
						<span class="color-option-single" data-color="#90EE90" style="background: #90EE90;" title="Light Green"></span>
						<span class="color-option-single" data-color="#F0C15E" style="background: #F0C15E;" title="Light Gold"></span>
						<span class="color-option-single" data-color="#00FF00" style="background: #00FF00;" title="Lime"></span>
						<span class="color-option-single" data-color="#CCCCCC" style="background: #CCCCCC;" title="Light Gray"></span>
						<span class="color-option-single" data-color="#000000" style="background: #000000;" title="Black"></span>
					</div>
				</div>
            </td>
        </tr>
		<tr>
            <th>Border Color<br/><span>Color for the badge border (supports rgba)</span></th>
            <td>
				<div class="color-picker-group">
					<input class="form-control color-input" type="text" name="online_border_color" id="online_border_color" value="<?php echo $widgetsConfig['online_widget']['border_color']; ?>" placeholder="rgba(255, 221, 162, 0.25)"/>
					<div class="color-palette">
						<span class="color-option-single" data-color="rgba(255, 221, 162, 0.25)" style="background: rgba(255, 221, 162, 0.25); border: 1px solid #666;" title="Gold Glow"></span>
						<span class="color-option-single" data-color="rgba(50, 205, 50, 0.5)" style="background: rgba(50, 205, 50, 0.5); border: 1px solid #666;" title="Lime Green"></span>
						<span class="color-option-single" data-color="rgba(144, 238, 144, 0.4)" style="background: rgba(144, 238, 144, 0.4); border: 1px solid #666;" title="Light Green"></span>
						<span class="color-option-single" data-color="rgba(0, 255, 0, 0.5)" style="background: rgba(0, 255, 0, 0.5); border: 1px solid #666;" title="Green"></span>
						<span class="color-option-single" data-color="rgba(255, 255, 255, 0.3)" style="background: rgba(255, 255, 255, 0.3); border: 1px solid #666;" title="White"></span>
						<span class="color-option-single" data-color="rgba(0, 0, 0, 0.5)" style="background: rgba(0, 0, 0, 0.5); border: 1px solid #666;" title="Black"></span>
					</div>
				</div>
            </td>
        </tr>
	</table>

	<div class="form-group">
		<button type="submit" name="submit_changes" class="btn btn-primary btn-lg">
			<i class="fa fa-save"></i> Save Configuration
		</button>
	</div>
</form>

<div class="alert alert-warning">
    <i class="fa fa-exclamation-triangle"></i> <strong>Important:</strong>
    <ul>
        <li>Rankings must be active and generating cache files for widgets to work</li>
        <li>Required rankings: <strong>Level</strong>, <strong>Killers (PKs)</strong>, and <strong>Online</strong></li>
        <li>The widgets system uses cached data - no additional SQL queries are made</li>
        <li>Color format supports HEX (#RRGGBB) and RGBA (rgba(r,g,b,a))</li>
        <li>Gradient format: two colors separated by comma (no spaces)</li>
    </ul>
</div>

<style>
.module_config_tables th {
    background-color: #f5f5f5;
    font-weight: 600;
}
.module_config_tables th span {
    font-weight: normal;
    font-size: 12px;
    color: #666;
    display: block;
    margin-top: 5px;
}
.module_config_tables h3 {
    margin-top: 30px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ddd;
}

/* Color Picker Styles */
.color-picker-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.color-input {
    width: 100%;
}
.color-palette {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    padding: 8px;
    background: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
}
.color-option,
.color-option-single {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
}
.color-option:hover,
.color-option-single:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 10;
}
.color-option:active,
.color-option-single:active {
    transform: scale(1.05);
}
.color-option::after,
.color-option-single::after {
    content: attr(title);
    position: absolute;
    bottom: -25px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
    z-index: 100;
}
.color-option:hover::after,
.color-option-single:hover::after {
    opacity: 1;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gradient color pickers (para background)
    document.querySelectorAll('.color-option').forEach(function(colorBox) {
        colorBox.addEventListener('click', function() {
            var colors = this.getAttribute('data-colors');
            var targetInput = this.closest('.color-picker-group').querySelector('.color-input');
            targetInput.value = colors;
            
            // Efecto visual de selección
            this.closest('.color-palette').querySelectorAll('.color-option').forEach(function(box) {
                box.style.border = 'none';
            });
            this.style.border = '3px solid #007bff';
        });
    });
    
    // Single color pickers (para text y border)
    document.querySelectorAll('.color-option-single').forEach(function(colorBox) {
        colorBox.addEventListener('click', function() {
            var color = this.getAttribute('data-color');
            var targetInput = this.closest('.color-picker-group').querySelector('.color-input');
            targetInput.value = color;
            
            // Efecto visual de selección
            this.closest('.color-palette').querySelectorAll('.color-option-single').forEach(function(box) {
                box.style.border = box.style.border.includes('1px solid #ddd') ? '1px solid #ddd' : 'none';
            });
            this.style.border = '3px solid #007bff';
        });
    });
});
</script>
