<?php
/**
 * WebEngine CMS
 * Dream Design - Live Preview Helper
 * 
 * Genera vista previa en tiempo real del template
 * 
 * @version 1.0
 */

if(!defined('access') or !access or access != 'admincp') die();

// Obtener configuraciones actuales
$appearanceCache = __PATH_CACHE__ . 'dreamdesign_appearance.cache';
$sliderCache = __PATH_CACHE__ . 'dreamdesign_slider.cache';
$socialCache = __PATH_CACHE__ . 'dreamdesign_social.cache';
$infocardsCache = __PATH_CACHE__ . 'dreamdesign_infocards.cache';

$appearance = file_exists($appearanceCache) ? @unserialize(file_get_contents($appearanceCache)) : array();
$slider = file_exists($sliderCache) ? @unserialize(file_get_contents($sliderCache)) : array();
$social = file_exists($socialCache) ? @unserialize(file_get_contents($socialCache)) : array();
$infocards = file_exists($infocardsCache) ? @unserialize(file_get_contents($infocardsCache)) : array();

// URL del sitio para el iframe
$siteUrl = config('website_url');
if(substr($siteUrl, -1) != '/') $siteUrl .= '/';
?>

<style>
.preview-container {
    position: fixed;
    top: 60px;
    right: 20px;
    width: 400px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 9999;
    max-height: calc(100vh - 80px);
    display: flex;
    flex-direction: column;
}

.preview-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.preview-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.preview-controls {
    display: flex;
    gap: 10px;
}

.preview-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-btn:hover {
    background: rgba(255,255,255,0.3);
}

.preview-body {
    flex: 1;
    overflow: hidden;
    position: relative;
}

.preview-iframe {
    width: 100%;
    height: 100%;
    border: none;
    background: white;
}

.preview-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.preview-spinner {
    border: 3px solid #f3f3f3;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.preview-footer {
    padding: 10px 15px;
    background: #f8f9fa;
    border-top: 1px solid #ddd;
    border-radius: 0 0 8px 8px;
    font-size: 12px;
    color: #666;
}

.autosave-indicator {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 500;
}

.autosave-indicator.saving {
    background: #fff3cd;
    color: #856404;
}

.autosave-indicator.saved {
    background: #d4edda;
    color: #155724;
}

.autosave-indicator.error {
    background: #f8d7da;
    color: #721c24;
}

/* Responsivo */
@media (max-width: 1400px) {
    .preview-container {
        width: 350px;
    }
}

@media (max-width: 1200px) {
    .preview-container {
        display: none;
    }
}

/* Toggle minimizado */
.preview-container.minimized {
    height: 60px;
    overflow: hidden;
}

.preview-container.minimized .preview-body,
.preview-container.minimized .preview-footer {
    display: none;
}
</style>

<div class="preview-container" id="live-preview">
    <div class="preview-header">
        <h4><i class="fa fa-eye"></i> Vista Previa en Vivo</h4>
        <div class="preview-controls">
            <button class="preview-btn" onclick="refreshPreview()" title="Refrescar">
                <i class="fa fa-sync-alt"></i>
            </button>
            <button class="preview-btn" onclick="togglePreview()" title="Minimizar">
                <i class="fa fa-minus"></i>
            </button>
            <button class="preview-btn" onclick="closePreview()" title="Cerrar">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    
    <div class="preview-body">
        <div class="preview-loading" id="preview-loading">
            <div class="preview-spinner"></div>
            <div>Cargando vista previa...</div>
        </div>
        <iframe id="preview-iframe" class="preview-iframe" src="<?php echo $siteUrl; ?>" style="display:none;"></iframe>
    </div>
    
    <div class="preview-footer">
        <div class="autosave-indicator" id="autosave-status">
            <i class="fa fa-circle"></i>
            <span>Sin cambios</span>
        </div>
    </div>
</div>

<script>
// Sistema de Auto-Guardado
let autoSaveTimeout;
let lastFormData = '';
let isSaving = false;

function initAutoSave() {
    const form = document.querySelector('form');
    if(!form) return;
    
    // Capturar cambios en todos los campos
    form.addEventListener('input', function(e) {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            autoSaveForm();
        }, 2000); // Esperar 2 segundos después del último cambio
    });
    
    // Guardar estado inicial
    lastFormData = new FormData(form).toString();
}

function autoSaveForm() {
    if(isSaving) return;
    
    const form = document.querySelector('form');
    if(!form) return;
    
    const formData = new FormData(form);
    const currentData = formData.toString();
    
    // No guardar si no hay cambios
    if(currentData === lastFormData) return;
    
    isSaving = true;
    updateAutoSaveStatus('saving', 'Guardando...');
    
    // Agregar flag de auto-guardado
    formData.append('autosave', '1');
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        lastFormData = currentData;
        updateAutoSaveStatus('saved', 'Guardado automáticamente');
        
        // Refrescar preview
        setTimeout(refreshPreview, 500);
        
        // Limpiar mensaje después de 3 segundos
        setTimeout(function() {
            updateAutoSaveStatus('', 'Sin cambios');
        }, 3000);
    })
    .catch(error => {
        console.error('Error en auto-guardado:', error);
        updateAutoSaveStatus('error', 'Error al guardar');
        
        setTimeout(function() {
            updateAutoSaveStatus('', 'Sin cambios');
        }, 3000);
    })
    .finally(() => {
        isSaving = false;
    });
}

function updateAutoSaveStatus(status, text) {
    const indicator = document.getElementById('autosave-status');
    if(!indicator) return;
    
    indicator.className = 'autosave-indicator ' + status;
    indicator.querySelector('span').textContent = text;
    
    const icon = indicator.querySelector('i');
    switch(status) {
        case 'saving':
            icon.className = 'fa fa-spinner fa-spin';
            break;
        case 'saved':
            icon.className = 'fa fa-check-circle';
            break;
        case 'error':
            icon.className = 'fa fa-exclamation-circle';
            break;
        default:
            icon.className = 'fa fa-circle';
    }
}

// Control del Preview
function refreshPreview() {
    const iframe = document.getElementById('preview-iframe');
    const loading = document.getElementById('preview-loading');
    
    if(iframe && loading) {
        loading.style.display = 'block';
        iframe.style.display = 'none';
        
        // Forzar recarga completa
        iframe.src = iframe.src.split('?')[0] + '?t=' + Date.now();
    }
}

function togglePreview() {
    const container = document.getElementById('live-preview');
    const btn = event.target.closest('button');
    
    container.classList.toggle('minimized');
    
    if(container.classList.contains('minimized')) {
        btn.innerHTML = '<i class="fa fa-plus"></i>';
        btn.title = 'Maximizar';
    } else {
        btn.innerHTML = '<i class="fa fa-minus"></i>';
        btn.title = 'Minimizar';
    }
}

function closePreview() {
    const container = document.getElementById('live-preview');
    container.style.display = 'none';
    localStorage.setItem('dreamdesign_preview_closed', '1');
}

// Manejar carga del iframe
document.getElementById('preview-iframe').addEventListener('load', function() {
    const loading = document.getElementById('preview-loading');
    const iframe = document.getElementById('preview-iframe');
    
    loading.style.display = 'none';
    iframe.style.display = 'block';
});

// Inicializar al cargar página
document.addEventListener('DOMContentLoaded', function() {
    // Verificar si el usuario cerró el preview
    if(localStorage.getItem('dreamdesign_preview_closed') === '1') {
        document.getElementById('live-preview').style.display = 'none';
    }
    
    // Iniciar auto-guardado
    initAutoSave();
});

// Mostrar preview si se hace clic en "Ver Preview"
function showPreview() {
    const container = document.getElementById('live-preview');
    container.style.display = 'flex';
    localStorage.removeItem('dreamdesign_preview_closed');
    refreshPreview();
}
</script>
