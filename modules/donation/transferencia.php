<?php
/**
 * Módulo de Transferencia Bancaria
 * Información para donaciones via transferencia bancaria
 */

(!isLoggedIn()) ? redirect(1,'login') : null;

echo '<div class="page-title">Donación &rarr; Transferencia Bancaria</div>';
        
        echo '<style>
.transferencia-container {
    max-width: 800px;
    margin: 20px auto;
    background: #0e1017;
    border: 1px solid #1f2330;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 12px 24px rgba(0,0,0,.35);
}

.transferencia-header {
    background: linear-gradient(135deg, #f8b400, #e09900);
    color: #ffffff;
    padding: 25px;
    text-align: center;
}

.transferencia-header h2 {
    margin: 0 0 10px 0;
    font-size: 28px;
    font-weight: bold;
}

.transferencia-header p {
    margin: 0;
    font-size: 16px;
    opacity: 0.8;
}

.transferencia-content {
    padding: 30px;
}

.banco-info {
    background: #1f2330;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
    border-left: 4px solid #f8b400;
}

.banco-logo {
    text-align: center;
    margin-bottom: 25px;
}

.banco-logo img {
    max-width: 200px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.datos-bancarios {
    display: grid;
    gap: 15px;
    margin-bottom: 25px;
}

.dato-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #2a2f3e;
    border-radius: 6px;
    border: 1px solid #3a4052;
}

.dato-label {
    font-weight: bold;
    color: #f8b400;
    font-size: 14px;
    text-transform: uppercase;
}

.dato-valor {
    color: #f5f6fb;
    font-family: monospace;
    font-size: 16px;
    font-weight: bold;
    background: #1a1e2b;
    padding: 8px 12px;
    border-radius: 4px;
    border: 1px solid #4a5568;
    position: relative;
}

.copiar-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    margin-left: 10px;
    transition: background 0.3s ease;
}

.copiar-btn:hover {
    background: #218838;
}

.instrucciones {
    background: #2d1b69;
    border: 1px solid #4c3a9b;
    border-radius: 8px;
    padding: 20px;
    margin-top: 25px;
}

.instrucciones h3 {
    color: #f8b400;
    margin: 0 0 15px 0;
    font-size: 18px;
}

.instrucciones ol {
    color: #d6def0;
    padding-left: 20px;
}

.instrucciones li {
    margin-bottom: 10px;
    line-height: 1.6;
}

.warning-box {
    background: #8b4513;
    border: 1px solid #cd853f;
    border-radius: 6px;
    padding: 15px;
    margin-top: 20px;
    color: #fff;
}

.warning-box strong {
    color: #ffd700;
}

.contact-info {
    text-align: center;
    margin-top: 25px;
    padding: 20px;
    background: #1a1e2b;
    border-radius: 8px;
}

.contact-info p {
    color: #d6def0;
    margin-bottom: 15px;
}

.contact-btn {
    display: inline-block;
    background: #f8b400;
    color: #12141d;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: bold;
    transition: transform 0.2s ease;
}

.contact-btn:hover {
    transform: translateY(-2px);
    color: #12141d;
    text-decoration: none;
}

@media (max-width: 768px) {
    .transferencia-container {
        margin: 10px;
    }
    
    .transferencia-content {
        padding: 20px;
    }
    
    .dato-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .dato-valor {
        font-size: 14px;
        width: 100%;
        word-break: break-all;
    }
}
        </style>';

        echo '<div class="transferencia-container">';
        
        // Header
        echo '<div class="transferencia-header">';
            echo '<h2>💸 Transferencia en Pesos</h2>';
            echo '<p>Realiza tu donacion desde un banco o billetera virtual</p>';
        echo '</div>';
        
        // Content
        echo '<div class="transferencia-content">';
        
            // Banco logo
            echo '<div class="banco-logo">';
                echo '<img src="https://i.imgur.com/TTKQaQq.png" alt="Banco Logo" id="bancoLogo">';
            echo '</div>';
            
            // Informacion de transferencia
            echo '<div class="banco-info">';
                echo '<div class="datos-bancarios">';
                
                    // CVU
                    echo '<div class="dato-item">';
                        echo '<div class="dato-label">CVU:</div>';
                        echo '<div>';
                            echo '<span class="dato-valor" id="cvu">0000003100020947440431</span>';
                            echo '<button class="copiar-btn" onclick="copiarTexto(\'cvu\')">📋 Copiar</button>';
                        echo '</div>';
                    echo '</div>';
                    
                    // Alias
                    echo '<div class="dato-item">';
                        echo '<div class="dato-label">Alias:</div>';
                        echo '<div>';
                            echo '<span class="dato-valor" id="alias">dementesmu.site</span>';
                            echo '<button class="copiar-btn" onclick="copiarTexto(\'alias\')">📋 Copiar</button>';
                        echo '</div>';
                    echo '</div>';
                    
                echo '</div>';
            echo '</div>';
            
// Instrucciones
echo '<div class="instrucciones">';
    echo '<h3>📋 Instrucciones para la transferencia:</h3>';
    echo '<ol>';
        echo '<li><strong>Realiza la transferencia</strong> con los datos bancarios proporcionados arriba.</li>';
        echo '<li><strong>Envía el comprobante</strong> de transferencia por mensaje privado o ticket de soporte.</li>';
        echo '<li><strong>Incluye tu usuario</strong> del juego en el mensaje para identificar tu cuenta.</li>';
        echo '<li><strong>Especifica el monto</strong> transferido y el concepto de la donación.</li>';
        echo '<li><strong>Aguarda la confirmación</strong> — procesamos las donaciones en un máximo de 24 horas.</li>';
    echo '</ol>';
echo '</div>';

            
            // Warning
            echo '<div class="warning-box">';
                echo '<strong>⚠️ Importante:</strong> Asegúrate de enviar el comprobante de transferencia para que podamos procesar tu donación correctamente. Sin el comprobante no podremos acreditar los créditos a tu cuenta.';
            echo '</div>';
            
            // Contact info
            echo '<div class="contact-info">';
                echo '<p><strong>¿Necesitas ayuda?</strong></p>';
                echo '<p>Contáctanos para cualquier consulta sobre tu donación</p>';
                echo '<a href="https://discord.gg/7D86yN8Ju" class="contact-btn">💬 Contactar Soporte</a>';
            echo '</div>';
            
        echo '</div>'; // transferencia-content
        echo '</div>'; // transferencia-container
        
        // JavaScript para copiar texto
        echo '<script>
function copiarTexto(elementId) {
    const elemento = document.getElementById(elementId);
    const texto = elemento.textContent;
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(texto).then(() => {
            mostrarMensajeCopia(elementId);
        }).catch(err => {
            console.error("Error al copiar: ", err);
            copiarFallback(texto, elementId);
        });
    } else {
        copiarFallback(texto, elementId);
    }
}

function copiarFallback(texto, elementId) {
    const textArea = document.createElement("textarea");
    textArea.value = texto;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand("copy");
        mostrarMensajeCopia(elementId);
    } catch (err) {
        console.error("Error al copiar: ", err);
    }
    
    document.body.removeChild(textArea);
}

function mostrarMensajeCopia(elementId) {
    const btn = document.querySelector(`#${elementId} + .copiar-btn`) || 
                document.querySelector(`[onclick*="${elementId}"]`);
    if (btn) {
        const textoOriginal = btn.innerHTML;
        btn.innerHTML = "✅ Copiado!";
        btn.style.background = "#28a745";
        
        setTimeout(() => {
            btn.innerHTML = textoOriginal;
            btn.style.background = "#28a745";
        }, 2000);
    }
}

// Verificar si la imagen se carga correctamente
document.getElementById("bancoLogo").onerror = function() {
    this.style.display = "none";
    const container = this.parentElement;
    container.innerHTML = "<div style=\"background: linear-gradient(135deg, #1c2230, #0d1018); padding: 40px; border-radius: 8px; text-align: center; color: #63708a; font-size: 16px;\">🏦 Logo del Banco</div>";
};
        </script>';

?>