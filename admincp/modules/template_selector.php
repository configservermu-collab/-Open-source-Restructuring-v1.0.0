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

echo '<h1 class="page-header">Selecciona un Template</h1>';

// Handle template selection
if(isset($_POST['select_template'])) {
	try {
		if(!isset($_POST['template_name'])) throw new Exception('Template name not provided.');
		
		$templateName = $_POST['template_name'];
		$templatePath = __PATH_TEMPLATES__ . $templateName;
		
		// Validate template exists
		if(!is_dir($templatePath)) throw new Exception('The selected template doesn\'t exist.');
		if(!file_exists($templatePath . '/index.php')) throw new Exception('The selected template is invalid (missing index.php).');
		
		// Load and update configuration
		$webengineConfigurations = webengineConfigs();
		$webengineConfigurations['website_template'] = $templateName;
		
		// Save configuration
		$newWebEngineConfig = json_encode($webengineConfigurations, JSON_PRETTY_PRINT);
		$cfgFile = fopen(__PATH_CONFIGS__.'webengine.json', 'w');
		if(!$cfgFile) throw new Exception('Could not open configuration file.');
		
		fwrite($cfgFile, $newWebEngineConfig);
		fclose($cfgFile);
		
		message('success', 'Template changed successfully to <strong>' . htmlspecialchars($templateName) . '</strong>!');
		
	} catch(Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// Get current template
$currentTemplate = config('website_template', true);

// Scan templates directory
$templatesPath = __PATH_TEMPLATES__;
$templates = array();

if(is_dir($templatesPath)) {
	$items = scandir($templatesPath);
	foreach($items as $item) {
		if($item == '.' || $item == '..') continue;
		
		$itemPath = $templatesPath . $item;
		if(is_dir($itemPath)) {
			// Check if it has index.php (valid template)
			if(file_exists($itemPath . '/index.php')) {
				$templates[] = array(
					'name' => $item,
					'path' => $itemPath,
					'image' => file_exists($itemPath . '/Templete.jpg') ? __BASE_URL__ . 'templates/' . $item . '/Templete.jpg' : null,
					'active' => ($item == $currentTemplate)
				);
			}
		}
	}
}

// Sort templates alphabetically
usort($templates, function($a, $b) {
	return strcmp($a['name'], $b['name']);
});

?>

<style>
.template-selector-container {
	background: #1a1a2e;
	min-height: 100vh;
	padding: 40px 20px;
	margin: -20px -15px;
}
.template-selector-header {
	text-align: center;
	color: #ffffff;
	margin-bottom: 20px;
}
.template-selector-header h2 {
	font-size: 28px;
	font-weight: 600;
	margin-bottom: 10px;
	color: #ffffff;
}
.template-selector-header p {
	color: #a0a0b0;
	font-size: 14px;
}
.template-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
	gap: 30px;
	max-width: 1400px;
	margin: 0 auto;
	padding: 20px;
}
.template-card {
	background: #2a2a3e;
	border-radius: 12px;
	overflow: hidden;
	transition: transform 0.3s ease, box-shadow 0.3s ease;
	border: 2px solid transparent;
}
.template-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 10px 30px rgba(0,0,0,0.5);
	border-color: #4a4a5e;
}
.template-card.active {
	border-color: #4CAF50;
	box-shadow: 0 0 20px rgba(76, 175, 80, 0.3);
}
.template-image {
	width: 100%;
	height: 180px;
	object-fit: cover;
	background: #1a1a2e;
	border-bottom: 1px solid #3a3a4e;
}
.template-image.no-image {
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 48px;
	color: #4a4a5e;
}
.template-info {
	padding: 20px;
	text-align: center;
}
.template-name {
	font-size: 18px;
	font-weight: 600;
	color: #ffffff;
	margin-bottom: 15px;
}
.template-button {
	width: 100%;
	padding: 10px 20px;
	border: none;
	border-radius: 6px;
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.3s ease;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}
.template-button.btn-select {
	background: #4CAF50;
	color: white;
}
.template-button.btn-select:hover {
	background: #45a049;
	box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
}
.template-button.btn-active {
	background: #2196F3;
	color: white;
	cursor: default;
}
.template-button.btn-active:hover {
	background: #2196F3;
}
</style>

<div class="template-selector-container">
	<div class="template-selector-header">
		<h2>Selecciona un Template</h2>
		<p>A continuación puedes ver una lista de templates disponibles en tu sistema.</p>
	</div>
	
	<?php if(count($templates) == 0): ?>
		<div class="alert alert-warning text-center" style="max-width: 600px; margin: 40px auto;">
			<i class="fa fa-exclamation-triangle fa-2x"></i>
			<p style="margin-top: 15px;">No templates found in the templates directory.</p>
		</div>
	<?php else: ?>
		<div class="template-grid">
			<?php foreach($templates as $template): ?>
				<div class="template-card <?php echo $template['active'] ? 'active' : ''; ?>">
					<?php if($template['image']): ?>
						<img src="<?php echo $template['image']; ?>" alt="<?php echo htmlspecialchars($template['name']); ?>" class="template-image">
					<?php else: ?>
						<div class="template-image no-image">
							<i class="fa fa-image"></i>
						</div>
					<?php endif; ?>
					
					<div class="template-info">
						<div class="template-name"><?php echo htmlspecialchars($template['name']); ?></div>
						
						<?php if($template['active']): ?>
							<button class="template-button btn-active" disabled>
								Template actual
							</button>
						<?php else: ?>
							<form method="post" style="margin: 0;">
								<input type="hidden" name="template_name" value="<?php echo htmlspecialchars($template['name']); ?>">
								<button type="submit" name="select_template" class="template-button btn-select">
									Elegir
								</button>
							</form>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
