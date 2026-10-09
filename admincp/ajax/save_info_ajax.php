<?php
/**
 * Guardado AJAX para MU Server Info Manager
 * Desarrollado por ConfigServerMU.net
 */

if(!defined('__PATH_INCLUDES__')) {
    define('__PATH_INCLUDES__', dirname(__DIR__, 2) . '/includes/');
}

$infoFile = __PATH_INCLUDES__ . 'config/info_config.json';
if(!file_exists($infoFile)) file_put_contents($infoFile, '[]');

$data = json_decode(file_get_contents($infoFile), true);
if(!is_array($data)) $data = [];

// Acción (si es crear/eliminar)
$action = $_POST['action'] ?? null;
$section = isset($_POST['section']) ? (int) $_POST['section'] : null;
$field   = $_POST['field'] ?? null;
$type    = $_POST['type'] ?? null;
$value   = $_POST['value'] ?? null;

switch($action) {
    case 'create_section':
        if(!isset($data[$section])) {
            $newSection = [
                'title' => ($type === 'video' ? 'Nuevo Video' : 'Nueva Tabla'),
                'type'  => $type
            ];
            if($type === 'table') $newSection['rows'] = [];
            if($type === 'video') $newSection['url'] = '';
            $data[$section] = $newSection;
        }
        break;

    case 'add_row':
        if(isset($data[$section]) && $data[$section]['type'] === 'table') {
            $data[$section]['rows'][] = ["", ""];
        }
        break;

    case 'delete_row':
        $row = isset($_POST['row']) ? (int) $_POST['row'] : null;
        if(isset($data[$section]['rows'][$row])) {
            unset($data[$section]['rows'][$row]);
            $data[$section]['rows'] = array_values($data[$section]['rows']); // reindexar
        }
        break;

    case 'delete_section':
        if(isset($data[$section])) {
            unset($data[$section]);
            $data = array_values($data); // reindexar
        }
        break;

    default:
        // Autosave estándar
        if(isset($data[$section])) {
            if($type === 'table') {
                $row = isset($_POST['row']) ? (int) $_POST['row'] : null;
                $col = isset($_POST['col']) ? (int) $_POST['col'] : null;
                if(!isset($data[$section]['rows'][$row])) $data[$section]['rows'][$row] = ["", ""];
                $data[$section]['rows'][$row][$col] = $value;
            } else {
                if($field) $data[$section][$field] = $value;
            }
        }
        break;
}

file_put_contents($infoFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo 'OK';
