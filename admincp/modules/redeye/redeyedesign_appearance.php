<?php
header('Content-Type: text/html; charset=utf-8');
/**
 * WebEngine CMS
 * AdminCP - RedEye Design - Apariencia Visual
 * 
 * Configuración de colores, logo y fondos para RedEye
 * 
 * @version 1.0
 */

if(!defined('access') or !access or access != 'admincp') die();

// Procesar formulario
if(isset($_POST['submit_appearance']) || isset($_POST['autosave'])) {
    try {
        $appearance = array(
            'logo_url' => trim($_POST['logo_url']),
            'favicon_url' => trim($_POST['favicon_url']),
            'background_image' => trim($_POST['background_image']),
            'background_image_2k' => trim($_POST['background_image_2k']),
            'primary_color' => trim($_POST['primary_color']),
            'secondary_color' => trim($_POST['secondary_color']),
            'accent_color' => trim($_POST['accent_color']),
            'background_color' => trim($_POST['background_color']),
            'container_bg' => trim($_POST['container_bg']),
            'footer_bg' => trim($_POST['footer_bg']),
            'text_primary' => trim($_POST['text_primary']),
            'text_secondary' => trim($_POST['text_secondary']),
            'link_color' => trim($_POST['link_color']),
            'link_hover' => trim($_POST['link_hover']),
            'navbar_bg' => trim($_POST['navbar_bg']),
            'navbar_link' => trim($_POST['navbar_link']),
            'navbar_link_hover' => trim($_POST['navbar_link_hover']),
            'topbar_bg' => trim($_POST['topbar_bg']),
            'topbar_text' => trim($_POST['topbar_text']),
            'header_info_bg' => trim($_POST['header_info_bg']),
            'header_info_text' => trim($_POST['header_info_text']),
            'online_bar_bg' => trim($_POST['online_bar_bg']),
            'online_bar_progress' => trim($_POST['online_bar_progress'])
        );
        
        $cacheFile = __PATH_CACHE__ . 'redeyedesign_appearance.cache';
        $serialized = serialize($appearance);
        
        if(file_put_contents($cacheFile, $serialized)) {
            if(!isset($_POST['autosave'])) {
                message('success', 'La configuración de apariencia ha sido guardada exitosamente.');
            }
        } else {
            throw new Exception('No se pudo escribir el archivo de configuración.');
        }
        
    } catch(Exception $ex) {
        if(!isset($_POST['autosave'])) {
            message('error', $ex->getMessage());
        }
    }
}

// Cargar configuración actual
$cacheFile = __PATH_CACHE__ . 'redeyedesign_appearance.cache';
$config = array();

if(file_exists($cacheFile)) {
    $content = file_get_contents($cacheFile);
    $config = @unserialize($content);
    if(!is_array($config)) $config = array();
}

// Valores por defecto (tema RedEye)
$defaults = array(
    'logo_url' => '/templates/RedEye/img/logo.png',
    'favicon_url' => '/templates/RedEye/favicon.ico',
    'background_image' => '/templates/RedEye/img/background.jpg',
    'background_image_2k' => '/templates/RedEye/img/background-2600.jpg',
    'primary_color'        => '#1a1a1a',
    'secondary_color'      => '#2d2d2d',
    'accent_color'         => '#cc0000',
    'background_color'     => '#0f0f0f',
    'container_bg'         => '#1a1a1a',
    'footer_bg'            => '#0a0a0a',
    'text_primary'         => '#ffffff',
    'text_secondary'       => '#cccccc',
    'link_color'           => '#cc0000',
    'link_hover'           => '#ff3333',
    'navbar_bg'            => '#121212',
    'navbar_link'          => '#cc0000',
    'navbar_link_hover'    => '#ff3333',
    'topbar_bg'            => '#0d0d0d',
    'topbar_text'          => '#cc0000',
    'header_info_bg'       => 'rgba(15, 15, 15, 0.95)',
    'header_info_text'     => '#ffffff',
    'online_bar_bg'        => '#1a1a1a',
    'online_bar_progress'  => '#cc0000'
);

foreach($defaults as $key => $value) {
    if(!isset($config[$key])) {
        $config[$key] = $value;
    }
}

?>
<style>
.appearance-section {
    background: #f9f9f9;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    border-left: 4px solid #e74c3c;
}
.appearance-section h4 {
    margin-top: 0;
    color: #c0392b;
    font-weight: 700;
}
.color-picker-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}
.color-picker-item {
    display: flex;
    align-items: center;
    gap: 10px;
}
.color-picker-item input[type="color"] {
    width: 60px;
    height: 40px;
    border: 2px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
}
.color-picker-item input[type="text"] {
    flex: 1;
    font-family: monospace;
}
.preview-image {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    border: 2px solid #ddd;
}
.logo-preview {
    max-width: 400px;
    max-height: 150px;
    background: #1a1a1a;
    padding: 20px;
    border-radius: 8px;
}
</style>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-palette"></i> Configuración de Apariencia Visual - RedEye
        </h3>
    </div>
    <div class="panel-body">
        <div class="alert alert-info">
            <strong><i class="fa fa-info-circle"></i> Información:</strong><br>
            Personaliza la apariencia del template RedEye: logo, fondos, colores y más.
        </div>
        
        <form action="" method="post">
            <!-- Logo y Favicon -->
            <div class="appearance-section">
                <h4><i class="fa fa-image"></i> Logo y Favicon</h4>
                
                <div class="form-group">
                    <label>URL del Logo Principal</label>
                    <input type="text" name="logo_url" class="form-control" 
                           value="<?php echo htmlspecialchars($config['logo_url']); ?>"
                           placeholder="/templates/RedEye/img/logo.png">
                </div>
                
                <?php if(!empty($config['logo_url'])): ?>
                <div class="form-group">
                    <label>Preview del Logo</label><br>
                    <img src="<?php echo $config['logo_url']; ?>" class="logo-preview" alt="Logo" 
                         onerror="this.style.display='none';">
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>URL del Favicon</label>
                    <input type="text" name="favicon_url" class="form-control" 
                           value="<?php echo htmlspecialchars($config['favicon_url']); ?>"
                           placeholder="/templates/RedEye/favicon.ico">
                </div>
            </div>
            
            <!-- Fondos -->
            <div class="appearance-section">
                <h4><i class="fa fa-image"></i> Imágenes de Fondo</h4>
                
                <div class="form-group">
                    <label>Imagen de Fondo Principal</label>
                    <input type="text" name="background_image" class="form-control" 
                           value="<?php echo htmlspecialchars($config['background_image']); ?>"
                           placeholder="/templates/RedEye/img/background.jpg">
                </div>
                
                <?php if(!empty($config['background_image'])): ?>
                <div class="form-group">
                    <img src="<?php echo $config['background_image']; ?>" class="preview-image" alt="Background" 
                         onerror="this.style.display='none';">
                </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Imagen de Fondo 2K+</label>
                    <input type="text" name="background_image_2k" class="form-control" 
                           value="<?php echo htmlspecialchars($config['background_image_2k']); ?>"
                           placeholder="/templates/RedEye/img/background-2600.jpg">
                </div>
            </div>
            
            <!-- Paleta de Colores -->
            <div class="appearance-section">
                <h4><i class="fa fa-palette"></i> Paleta de Colores del Theme</h4>
                
                <div class="color-picker-group">
                    <div class="color-picker-item">
                        <input type="color" name="primary_color" value="<?php echo $config['primary_color']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Color Primario</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['primary_color']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="secondary_color" value="<?php echo $config['secondary_color']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Color Secundario</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['secondary_color']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="accent_color" value="<?php echo $config['accent_color']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Color de Acento</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['accent_color']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="background_color" value="<?php echo $config['background_color']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Principal</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['background_color']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="container_bg" value="<?php echo $config['container_bg']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Contenedor</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['container_bg']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="footer_bg" value="<?php echo $config['footer_bg']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Footer</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['footer_bg']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="text_primary" value="<?php echo $config['text_primary']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Texto Principal</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['text_primary']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="text_secondary" value="<?php echo $config['text_secondary']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Texto Secundario</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['text_secondary']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="link_color" value="<?php echo $config['link_color']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Color de Links</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['link_color']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="link_hover" value="<?php echo $config['link_hover']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Hover de Links</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['link_hover']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="navbar_bg" value="<?php echo $config['navbar_bg']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Navbar</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['navbar_bg']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="navbar_link" value="<?php echo $config['navbar_link']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Links Navbar</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['navbar_link']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="navbar_link_hover" value="<?php echo $config['navbar_link_hover']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Hover Links Navbar</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['navbar_link_hover']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="topbar_bg" value="<?php echo $config['topbar_bg']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Top Bar</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['topbar_bg']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="topbar_text" value="<?php echo $config['topbar_text']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Texto Top Bar</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['topbar_text']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="header_info_bg" value="<?php echo $config['header_info_bg']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Info Header</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['header_info_bg']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="header_info_text" value="<?php echo $config['header_info_text']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Texto Info Header</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['header_info_text']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="online_bar_bg" value="<?php echo $config['online_bar_bg']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Fondo Barra Online</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['online_bar_bg']; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="color-picker-item">
                        <input type="color" name="online_bar_progress" value="<?php echo $config['online_bar_progress']; ?>">
                        <div style="flex: 1;">
                            <label style="margin: 0; font-weight: 600;">Progreso Barra Online</label>
                            <input type="text" class="form-control input-sm" value="<?php echo $config['online_bar_progress']; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <button type="submit" name="submit_appearance" class="btn btn-primary btn-lg">
                    <i class="fa fa-save"></i> Guardar Configuración de Apariencia
                </button>
            </div>
        </form>
    </div>
</div>
