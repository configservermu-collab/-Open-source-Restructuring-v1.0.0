<?php
/**
 * MU Server Info Manager
 * M¨®dulo desarrollado por ConfigServerMU.net para WebEngine CMS
 * 
 * @author ConfigServerMU
 * @link https://configservermu.net
 * @version 1.0.2
 * @license MIT
 */

echo '<div class="page-title"><span>Server Info</span></div>';

$dataFile = __PATH_INCLUDES__ . 'config/info_config.json';
$jsonRaw  = file_exists($dataFile) ? file_get_contents($dataFile) : '';

$data = json_decode($jsonRaw, true, 512, JSON_UNESCAPED_UNICODE);
if (!is_array($data)) {
    $data = [];
}

foreach ($data as $section) {
    echo '<h2>' . htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') . '</h2>';

    if ($section['type'] == 'table') {
        echo '<table class="table table-condensed table-hover table-striped table-bordered info-table" style="margin-bottom:5px;">';

        foreach ($section['rows'] as $row) {
            // si las 2 columnas vienen vac¨ªas, no muestro la fila
            if (
                (!isset($row[0]) || trim($row[0]) === '') &&
                (!isset($row[1]) || trim($row[1]) === '')
            ) {
                continue;
            }

            echo '<tr>';
            echo '<td style="width:50%; padding:6px 10px;">' .
                    htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8') .
                 '</td>';
            echo '<td style="width:50%; padding:6px 10px;">' .
                    htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8') .
                 '</td>';
            echo '</tr>';
        }

        echo '</table>';
    }

    if ($section['type'] == 'video') {
        echo '<iframe width="636" height="357" src="' .
             htmlspecialchars($section['url'], ENT_QUOTES, 'UTF-8') .
             '" frameborder="0" allowfullscreen style="margin:10px 0;"></iframe>';
    }
}

echo '<hr>';
echo '<p class="text-center text-muted">';
echo '<small>';
echo 'Modulo desarrollado por <a href="https://configservermu.net" target="_blank">ConfigServerMU.net</a><br>';
echo '&copy; ' . date('Y') . ' - WebEngine CMS | MU Server Info Manager v1.0.2';
echo '</small>';
echo '</p>';
