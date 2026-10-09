<?php
/**
 * Frontend Guías — Cascada + Detalle (usa SOLO el META)
 * Lee metadatos: <!--GUIDE_META cover="..." thumb="..." -->
 */

if(!class_exists('Guides')) include_once(__DIR__ . '/../../includes/classes/class.guides.php');
$Guides = new Guides();

/* ========================= Helpers ========================= */
function g_parse_meta($html){
    $meta = ['cover'=>'','thumb'=>''];
    if(preg_match('/<!--\s*GUIDE_META(.*?)-->/is', $html, $m)){
        $attrs = $m[1];
        if(preg_match('/cover="([^"]+)"/i', $attrs, $c)) $meta['cover'] = trim($c[1]);
        if(preg_match('/thumb="([^"]+)"/i', $attrs, $t)) $meta['thumb'] = trim($t[1]);
    }
    return $meta;
}
function g_strip_meta($html){ return preg_replace('/<!--\s*GUIDE_META.*?-->\s*/is','',$html,1); }

function g_url($path){
    if(!$path) return '';
    if(preg_match('#^https?://#i', $path)) return $path;
    $base = rtrim(__BASE_URL__, '/');
    $p = ltrim($path, '/');
    return $base.'/'.$p;
}
function g_excerpt($html, $len=220){
    $txt = trim(preg_replace('/\s+/', ' ', strip_tags($html)));
    if(mb_strlen($txt,'UTF-8') <= $len) return $txt;
    return rtrim(mb_substr($txt, 0, $len, 'UTF-8'), " \t\n\r\0\x0B.,;:!-").'…';
}

/* Campos tolerantes a esquemas distintos */
function g_id($row){ return isset($row['guides_id']) ? (int)$row['guides_id'] : (int)($row['id'] ?? 0); }
function g_date($row){
    foreach(['guides_date','date','created_at','timestamp'] as $k){
        if(isset($row[$k]) && is_numeric($row[$k])) return (int)$row[$k];
    }
    return time();
}
function g_str($row, $key, $default=''){
    return isset($row[$key]) ? (string)$row[$key] : $default;
}

$DEBUG = !empty($_GET['debug']);

try{
    // ¿Vista individual?
    $isSingle = false; $gdata = null;
    if(isset($_GET['subpage']) && is_numeric($_GET['subpage'])){
        $Guides->setId((int)$_GET['subpage']);
        $gdata = $Guides->getGuideData();
        if(!$gdata) throw new Exception('La guía solicitada no existe.');
        $isSingle = true;
    }

    echo '<div class="guides-wrap">';
    echo '<h1 class="guides-title">📘 Guías del Servidor</h1>';

    if($isSingle){
        $title   = htmlspecialchars(g_str($gdata,'guides_title','(Sin título)'), ENT_QUOTES, 'UTF-8');
        $author  = htmlspecialchars(g_str($gdata,'guides_author','Desconocido'), ENT_QUOTES, 'UTF-8');
        $date    = date('d/m/Y', g_date($gdata));
        $raw     = g_str($gdata,'guides_content','');
        $meta    = g_parse_meta($raw);
        $content = g_strip_meta($raw);

        // cover: SOLO meta (si no hay, no se muestra portada)
        if(!empty($meta['cover'])){
            $coverURL = g_url($meta['cover']);
            if($DEBUG) echo '<div class="alert alert-info">DEBUG cover: '.htmlspecialchars($coverURL).'</div>';
            echo '<div class="guide-cover"><img src="'.htmlspecialchars($coverURL,ENT_QUOTES,'UTF-8').'" alt="Portada"></div>';
        }

        echo '<div class="guide-single-body cs-card">';
            echo '<h2 class="g-title-single">'.$title.'</h2>';
            echo '<div class="guide-meta">Autor: '.$author.' · '.$date.'</div>';
            echo '<div class="guide-content">'.$content.'</div>';
        echo '</div>';

    } else {
        // Listado en grid (cascada)
        $list = $Guides->getAllGuides();
        if(!is_array($list) || !count($list)) throw new Exception('No hay guías disponibles.');

        echo '<div class="guides-grid">';
        foreach($list as $g){
            $id     = g_id($g);
            $url    = __BASE_URL__.'guides/'.$id.'/';
            $title  = htmlspecialchars(g_str($g,'guides_title','(Sin título)'), ENT_QUOTES, 'UTF-8');
            $author = htmlspecialchars(g_str($g,'guides_author','Desconocido'), ENT_QUOTES, 'UTF-8');
            $date   = date('d/m/Y', g_date($g));
            $raw    = g_str($g,'guides_content','');

            $meta    = g_parse_meta($raw);
            $content = g_strip_meta($raw);
            $preview = g_excerpt($content, 220);

            // thumb: SOLO meta (si no hay, placeholder 250×250)
            $thumbTag = '<div class="no-thumb">250×250</div>';
            if(!empty($meta['thumb'])){
                $thumbURL = g_url($meta['thumb']);
                if($DEBUG) $preview = '<div class="alert alert-info">DEBUG thumb: '.htmlspecialchars($thumbURL).'</div>'.$preview;
                $thumbTag = '<img src="'.htmlspecialchars($thumbURL,ENT_QUOTES,'UTF-8').'" alt="Thumb">';
            }

            echo '<a class="guide-card" href="'.$url.'">';
                echo '<div class="thumb">'.$thumbTag.'</div>';
                echo '<div class="info">';
                    echo '<h3 class="g-title">'.$title.'</h3>';
                    echo '<div class="meta">'.$author.' · '.$date.'</div>';
                    echo '<p>'.$preview.'</p>';
                echo '</div>';
            echo '</a>';
        }
        echo '</div>';
    }

    echo '</div>'; // .guides-wrap

} catch(Exception $ex){
    message('warning',$ex->getMessage());
}
?>

<style>
/* Layout general */
.guides-wrap{max-width:1100px;margin:18px auto;padding:0 10px}
.guides-title{color:#ffa726;font-weight:800;margin:0 0 14px;text-align:center}

/* Tarjeta base */
.cs-card{background:#151a20;border:1px solid #232a31;border-radius:14px;padding:16px}

/* === LISTADO EN CASCADA === */
.guides-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
  gap:14px;
}
.guide-card{
  display:block;
  background:#151a20;
  border:1px solid #232a31;
  border-radius:14px;
  text-decoration:none;
  color:#e9edf1;
  overflow:hidden;
  transition:transform .15s ease, border-color .15s ease;
}
.guide-card:hover{transform:translateY(-2px);border-color:#2ee59d}
.guide-card .thumb{
  width:100%;
  height:250px;
  background:#0f1317;
  display:flex;align-items:center;justify-content:center;
}
.guide-card .thumb img{
  width:100%;height:100%;object-fit:cover;
}
.guide-card .thumb .no-thumb{
  width:250px;height:250px;border:1px dashed #334;display:flex;align-items:center;justify-content:center;color:#778;
  font-weight:700;border-radius:10px;background:#0f1317;
}
.guide-card .info{padding:12px}
.guide-card h3{margin:0 0 6px;font-size:18px;color:#fff}
.guide-card .meta{opacity:.75;font-size:.9em;margin-bottom:6px}
.guide-card p{margin:0;color:#cfd7df;font-size:.95em}
/* Títulos naranja */
.g-title,
.g-title a{ color:#ffa726 !important; }

.g-title-single,
.g-title-single a{ color:#ffa726 !important; }

/* Opcional: efecto hover */
.g-title a:hover,
.g-title-single a:hover{ filter:brightness(1.1); }

/* === DETALLE === */
.guide-cover{margin:0 0 12px;border-radius:14px;overflow:hidden;border:1px solid #232a31}
.guide-cover img{width:100%;height:auto;display:block}
.guide-single-body h2{margin:0 0 6px}
.guide-single-body .guide-meta{opacity:.75;margin-bottom:10px}
.guide-content{color:#e9edf1}
.guide-content img{max-width:100%;height:auto;border-radius:10px}
</style>
