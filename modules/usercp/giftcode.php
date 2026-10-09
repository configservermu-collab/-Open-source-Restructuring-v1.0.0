<?php
/**
 * WebEngine CMS – Módulo GiftCode
 * https://webenginecms.org/
 * 
 * @version 1.2.1
 * Adaptado por ConfigServerMU.net · 2025
 * Licencia MIT: http://opensource.org/licenses/MIT
 */

/* ─── DEBUG ───────────────────────────────────────────── */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ─── INCLUIR CLASE GIFTCODE ──────────────────────────── */
/* Ajusta la ruta si tu árbol de carpetas difiere */
require_once __DIR__.'/../../includes/classes/class.giftcode.php';

/* ─── SEGURIDAD ───────────────────────────────────────── */
if(!isLoggedIn()) redirect(1,'login');

// CSS integrado básico para UserCP
echo '<style>
.page-title, .page-title span { color: #FFFFFF !important; }
article div, article span, article p, section div, section span, section p { color: #FFFFFF !important; }
.form-control { color: #333 !important; }
.alert { color: #FFFFFF !important; }
.btn-primary { color: #ff0000; background-color: transparent; border-color: #ff0000; }
.btn-primary:hover { color: #fff !important; background-color: #140c17 !important; border-color: #140c17 !important; }
table.table, .table td, .table th { color: #FFFFFF !important; }
.col-xs-8, .col-xs-12 { background: rgba(0,0,0,0.3); border-radius: 8px; padding: 20px; margin-top: 20px; }
</style>';

/* ─── TÍTULO ──────────────────────────────────────────── */
echo '<h2 class="character-header text-center"><i class="fas fa-gift"></i> Código de Regalo</h2><hr>';

try {

    /* ─ Obtener personajes de la cuenta ─ */
    $Character = new Character();
    $AccountCharacters = $Character->AccountCharacter($_SESSION['username']);
    if(!is_array($AccountCharacters)) throw new Exception(lang('error_46',true));

    /* ─ Procesar formulario ─ */
    if(isset($_POST['submit']) && check_value($_POST['submit'])) {
        try {
            $GiftCode = new GiftCode();
            $GiftCode->Verificaciones(
                $_SESSION['username'],
                $_POST['character'],
                $_POST['gift_code']
            );
            message('success', '🎁 ¡Código canjeado correctamente!');
        } catch(Exception $ex) {
            message('error', $ex->getMessage());
        }
    }

    /* ─ Alerta informativa ─ */
    echo '<div class="alert alert-primary text-center" role="alert">';
    echo 'Ingresa el código de regalo y <b>OBTENÉ</b> tus recompensas.';
    echo '</div>';

    /* ─ Formulario ─ */
    echo '<form action="" method="post">';
        echo '<table class="table table-bordered">';
            echo '<thead class="thead-dark">';
                echo '<tr>';
                    echo '<th scope="col"><i class="fas fa-ticket-alt"></i> Ingresá el Código</th>';
                    echo '<th scope="col"><i class="fas fa-user"></i> Seleccioná Personaje</th>';
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
                echo '<tr>';
                    /* Campo código */
                    echo '<td>';
                        echo '<input type="text" class="form-control" name="gift_code" minlength="15" maxlength="15" required>';
                    echo '</td>';
                    /* Select personaje */
                    echo '<td>';
                        echo '<select class="form-control optionfix" name="character" required>';
                        foreach($AccountCharacters as $thisCharacter) {
                            $characterData = $Character->CharacterData($thisCharacter);
                            echo '<option value="'.$characterData[_CLMN_CHR_NAME_].'">'.$characterData[_CLMN_CHR_NAME_].'</option>';
                        }
                        echo '</select>';
                    echo '</td>';
                echo '</tr>';
                /* Botón enviar */
                echo '<tr>';
                    echo '<td colspan="2" class="text-center">';
                        echo '<button name="submit" value="submit" class="btn btn-primary">';
                        echo '<i class="fas fa-gift"></i> Obtener recompensa</button>';
                    echo '</td>';
                echo '</tr>';
            echo '</tbody>';
        echo '</table>';
    echo '</form>';

} catch(Exception $ex) {
    message('error', $ex->getMessage());
}
?>
