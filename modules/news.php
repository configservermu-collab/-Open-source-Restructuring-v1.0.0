<?php
/**
 * Front – Noticias (Cascada / Full / Expandida) + Posición/Tamaño desde AdminCP
 * FIX: Sidebar a la derecha; noticias pegadas a la izquierda sin mover el sidebar.
 * Ruta: /modules/news.php
 */

try {
    if(function_exists('loadModuleConfigs')) loadModuleConfigs('news');
    if(!mconfig('active')) throw new Exception(lang('error_47',true));

    /* ============================== Helpers ============================== */
    function cs_fix_utf8($s){
        $s = (string)$s;
        if(function_exists('mb_detect_encoding') && mb_detect_encoding($s,'UTF-8',true)){
            $t = @iconv('UTF-8','UTF-8//IGNORE',$s);
            return $t!==false ? $t : $s;
        }
        $t = @mb_convert_encoding($s,'UTF-8','Windows-1252,ISO-8859-1,UTF-8');
        if($t!==false){
            $t2 = @iconv('UTF-8','UTF-8//IGNORE',$t);
            return $t2!==false ? $t2 : $t;
        }
        return $s;
    }
    function cs_glen($s){ return function_exists('grapheme_strlen') ? grapheme_strlen($s) : mb_strlen($s,'UTF-8'); }
    function cs_gsub($s,$len){ return function_exists('grapheme_substr') ? grapheme_substr($s,0,$len) : mb_substr($s,0,$len,'UTF-8'); }

    /* ====== LECTURA DE SETTINGS GUARDADOS EN ADMINCP ====== */
    function cs_get_news_settings(){
        $allowed = ['cascada','full','expandida'];
        $file = __PATH_MODULE_CONFIGS__ . 'news.layout.xml';
        $out = ['layout'=>'cascada','bgcolor'=>'#151A20','top'=>0,'left'=>0,'width'=>800,'height'=>0];
        if(file_exists($file)){
            $xml = @simplexml_load_file($file);
            if($xml){
                if(isset($xml->news_layout)){
                    $v = strtolower((string)$xml->news_layout);
                    if(in_array($v,$allowed)) $out['layout'] = $v;
                }
                if(isset($xml->news_bgcolor)){
                    $hex = strtoupper((string)$xml->news_bgcolor);
                    if($hex && $hex[0]!=='#') $hex = '#'.$hex;
                    if(preg_match('/^#[0-9A-F]{6}$/',$hex)) $out['bgcolor'] = $hex;
                }
                if(isset($xml->news_top))    $out['top']    = (int)$xml->news_top;
                if(isset($xml->news_left))   $out['left']   = (int)$xml->news_left;
                if(isset($xml->news_width))  $out['width']  = (int)$xml->news_width;
                if(isset($xml->news_height)) $out['height'] = (int)$xml->news_height;
            }
        }
        return $out;
    }

    function cs_html_excerpt($html, $limit = 220) {
        $html = cs_fix_utf8($html);
        $txt  = trim(preg_replace('/\s+/u',' ', strip_tags($html)));
        if(mb_strlen($txt,'UTF-8') <= $limit) return $txt;
        return rtrim(mb_substr($txt, 0, $limit, 'UTF-8'), " \t\n\r\0\x0B.,;:!-") . '#';
    }

    function cs_plain_text($html){
        $html = cs_fix_utf8($html);
        $html = preg_replace('#<\s*br\s*/?>#i', ' ', $html);
        $html = preg_replace_callback('#<code\b[^>]*>(.*?)</code>#is', function($m){
            $inner = html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8');
            return $inner;
        }, $html);
        $txt = strip_tags($html);
        $txt = html_entity_decode($txt, ENT_QUOTES, 'UTF-8');
        $txt = preg_replace('/\s+/u',' ', $txt);
        return trim($txt);
    }

    function cs_news_meta($author, $timestamp) {
        $d = date("d-m-Y H:i", $timestamp);
        return '<div class="cs-news-meta"><span class="meta"><i class="fa fa-user"></i> '.htmlspecialchars($author,ENT_QUOTES,'UTF-8').'</span><span class="meta"><i class="fa fa-calendar"></i> '.$d.'</span></div>';
    }

    /* ============================== Data ============================== */
    $News = new News();
    $cachedNews = function_exists('loadCache') ? loadCache('news.cache') : LoadCacheData('news.cache');
    if(!is_array($cachedNews)) $cachedNews = array();

    $langSwitch   = config('language_switch_active', true);
    $langDisplay  = $_SESSION['language_display'] ?? null;
    if($langSwitch && check_value($langDisplay)) $News->setLanguage($langDisplay);

    $showSingleNews = false;
    $newsID = null;
    if(isset($_GET['subpage']) && check_value($_GET['subpage']) && $News->newsIdExists($_GET['subpage'])) {
        $showSingleNews = true;
        $newsID = $_GET['subpage'];
    }

    $cfg    = cs_get_news_settings();          // <- LEE XML DEL ADMINCP
    $layout = $cfg['layout'];
    $bg     = $cfg['bgcolor'];

    $items = [];
    $i = 0;
    $listLimit = (int)mconfig('news_list_limit');
    foreach($cachedNews as $row) {
        if($showSingleNews && $row['news_id'] != $newsID) continue;
        if($i >= $listLimit) break;

        $News->setId($row['news_id']);

        $id     = $row['news_id'];
        $author = cs_fix_utf8($row['news_author']);
        $date   = $row['news_date'];
        $url    = __BASE_URL__.'news/'.$id.'/';

        $title = cs_fix_utf8(base64_decode($row['news_title']));
        if($langSwitch && check_value($langDisplay) && is_array($row['translations'])) {
            $lang = $langDisplay;
            if(isset($row['translations'][$lang])) {
                $title = cs_fix_utf8(base64_decode($row['translations'][$lang]));
            }
        }

        $fullRaw   = cs_fix_utf8($News->LoadCachedNews());
        $plain     = cs_plain_text($fullRaw);
        $shortText = cs_html_excerpt($fullRaw, 300);
        $teaserLen = 220;
        $teaser    = cs_gsub($plain, $teaserLen);
        if(cs_glen($plain) > $teaserLen) $teaser .= '#';

        $items[] = [
            'id'=>$id,
            'title'=>$title,
            'author'=>$author,
            'date'=>$date,
            'url'=>$url,
            'full'=>$fullRaw,
            'short'=>$shortText,
            'teaser'=>$teaser
        ];
        $i++;
    }

    /* ============================== CSS + WRAPPER (SIDEBAR A LA DERECHA) ============================== */

    // Posición/Tamaño desde Admin (NO toca el sidebar)
    $left = (int)$cfg['left'];
    $top  = (int)$cfg['top'];
    $styleParts = [];
    if($left !== 0) $styleParts[] = "--news-offset-x: {$left}px;";
    if($top !== 0) $styleParts[] = "--news-offset-y: {$top}px;";
    // Width del AdminCP se ignora porque debe ocupar el 100% del contenedor padre
    if((int)$cfg['height'] > 0) {
        $heightValue = (int)$cfg['height'];
        $styleParts[] = "max-height: {$heightValue}px;";
        $styleParts[] = "overflow: auto;";
    }
    $style = implode(' ', $styleParts);

    ?>
<style>
:root{
  --surface:#14171b; --border:#232a31; --muted:#aeb7c0; --accent:#20d37b; --accent2:#2ee59d; --radius:14px;
  --cta:#2ea8ff;
}

/* El sidebar ya está en su propia columna de tabla - no necesita reserva de espacio */
.cs-news-wrapper{
    display:block !important;
    margin:0 !important;
    width: 100%;
    max-width: 100%;
    position: relative;
    z-index: 5;
    clear: none !important;
    box-sizing: border-box;
    overflow: visible;
    transform: translate(var(--news-offset-x, 0px), var(--news-offset-y, 0px));
}

/* Evitar clears del template que empujan */
h1.page-header, .page-header { clear: none !important; margin-top:0; }

/* Neutralizaciones locales */
.cs-news-wrapper a { color:#fff; text-decoration:none; }
.cs-news-wrapper h3 a:hover { color:var(--accent2)!important; text-decoration:none!important; }
.cs-news-wrapper .form-group { margin:0; }
.cs-news-wrapper table,
.cs-news-wrapper thead th,
.cs-news-wrapper tbody td { border-top:initial!important; }

/* Tarjeta/estilo (color viene del XML) */
.cs-card{background:#151a20;border:1px solid var(--border);border-radius:14px;padding:16px}
.cs-card h3{margin:0 0 8px;font-size:20px;line-height:1.25}
.cs-card h3 a{color:#fff;text-decoration:none}
.cs-card h3 a:hover{color:var(--accent2)}
.cs-card .content{margin-top:10px;color:#dfe7ef}
.cs-card .actions{margin-top:12px}

/* Nunca más ancha que su contenedor */
.cs-news-wrapper article.cs-card{
  margin-left:0!important;
  margin-right:0!important;
  max-width:100%!important;
  width:auto!important;
  box-sizing:border-box;
}

/* Layouts */
.cs-layout-full .cs-card{margin-bottom:14px}
.cs-card.expanded{border-color:#284034;box-shadow:0 0 0 1px #223a2f inset}
.cs-card.expanded .content{max-height:none;overflow:visible}
.cs-card.compact .content{max-height:220px;overflow:hidden}

.cs-layout-cascada{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:14px}
.cs-layout-cascada .cs-card .content{max-height:160px;overflow:hidden}

.cs-layout-expandida{display:grid;grid-template-columns:1fr;gap:14px}
.cs-layout-expandida .cs-card{display:flex;flex-direction:column;align-items:center;text-align:center;padding:18px 16px;}
.csx-title{color:#e9edf1;font-size:18px;font-weight:700;text-transform:uppercase;letter-spacing:.3px;margin:0 0 8px;}
.csx-divider{width:92%;max-width:720px;height:1px;border-top:1px dashed var(--border);margin:6px auto 12px;opacity:.9;}
.csx-teaser{color:#e9edf1;font-size:15.5px;line-height:1.6;margin:0 0 12px;max-width:720px;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;overflow:hidden;}

/* Mobile: ocupar ancho completo y anular reserva del sidebar */
@media (max-width:980px){
    .cs-news-wrapper { margin-right:0 !important; max-width:100% !important; transform:none !important; }
  .csx-teaser{ -webkit-line-clamp:6; }
} /* Ajustes para pantallas pequeñas */
@media (max-width:980px){
    .cs-news-wrapper { transform:none !important; }
    .csx-teaser{ -webkit-line-clamp:6; }
    .cs-layout-cascada{grid-template-columns:1fr;ms:center;gap:8px;padding:8px 14px;border-radius:10px;border:1px solid var(--border);
  background:#12161a;color:#dfe7ef;text-decoration:none}
.cs-btn:hover{background:#162028;color:#fff}
.cs-btn-cta{background:var(--accent);border-color:var(--accent);color:#082b19}
.cs-btn-cta:hover{background:#29eb96;border-color:#29eb96}
.cs-btn-blue{background:var(--cta);border-color:var(--cta);color:#021a2e}
.cs-btn-blue:hover{filter:brightness(1.1)}
.cs-news-meta{display:flex;gap:12px;align-items:center;margin-top:8px;color:var(--muted);font-size:.9em}
.cs-news-meta .meta i{opacity:.7;margin-right:6px}
</style>
<?php

    // Wrapper + color de tarjeta desde AdminCP (sidebar intacto a la derecha)
    echo "<div class='cs-news-wrapper'" . ($style ? " style=\"{$style}\"" : '') . ">";
    echo '<style>.cs-card{background: '.htmlspecialchars($bg,ENT_QUOTES,'UTF-8').' !important;}</style>';

    /* ============================== Render ============================== */
    if($showSingleNews) {
        $article = $items[0] ?? null;
        if(!$article) throw new Exception(lang('error_61'));
        echo '<h1 class="page-header" style="margin:0 0 12px">Noticias</h1>';
        echo '<div class="cs-layout-full">';
            echo '<article class="cs-card expanded">';
                echo '<h3 class="news-article"><a href="'.htmlspecialchars($article['url'],ENT_QUOTES,'UTF-8').'">'.htmlspecialchars($article['title'],ENT_QUOTES,'UTF-8').'</a></h3>';
                echo cs_news_meta($article['author'], $article['date']);
                echo '<div class="content" style="margin-top:14px">'.$article['full'].'</div>';
            echo '</article>';
        echo '</div>';
        echo "</div>";
        return;
    }

    echo '<h1 class="page-header" style="margin:0 0 12px">Noticias</h1>';

    if($layout === 'full') {
        $expandN = max(0, (int)mconfig('news_expanded'));
        echo '<div class="cs-layout-full">';
        foreach($items as $idx=>$a){
            $isExpanded = ($idx < $expandN);
            echo '<article class="cs-card '.($isExpanded?'expanded':'compact').'">';
                echo '<h3 class="news-article"><a href="'.htmlspecialchars($a['url'],ENT_QUOTES,'UTF-8').'">'.htmlspecialchars($a['title'],ENT_QUOTES,'UTF-8').'</a></h3>';
                echo cs_news_meta($a['author'],$a['date']);
                if($isExpanded){
                    echo '<div class="content">'.$a['full'].'</div>';
                }else{
                    echo '<div class="content">'.htmlspecialchars($a['short'],ENT_QUOTES,'UTF-8').'</div>';
                    echo '<div class="actions"><a class="cs-btn cs-btn-cta" href="'.htmlspecialchars($a['url'],ENT_QUOTES,'UTF-8').'">'.lang('news_txt_3').'</a></div>';
                }
            echo '</article>';
        }
        echo '</div>';

    } elseif($layout === 'expandida') {
        echo '<div class="cs-layout-expandida">';
        foreach($items as $a){
            echo '<article class="cs-card">';
                echo '<div class="csx-title">'.htmlspecialchars($a['title'],ENT_QUOTES,'UTF-8').'</div>';
                echo '<div class="csx-divider"></div>';
                $teaserHtml = htmlspecialchars($a['teaser'] !== '' ? $a['teaser'] : $a['short'], ENT_QUOTES, 'UTF-8');
                echo '<p class="csx-teaser">'.$teaserHtml.'</p>';
                echo '<div class="actions"><a class="cs-btn cs-btn-blue" href="'.htmlspecialchars($a['url'],ENT_QUOTES,'UTF-8').'">'.lang('news_txt_3').'</a></div>';
            echo '</article>';
        }
        echo '</div>';

    } else {
        echo '<div class="cs-layout-cascada">';
        foreach($items as $a){
            echo '<article class="cs-card">';
                echo '<h3 class="news-article"><a href="'.htmlspecialchars($a['url'],ENT_QUOTES,'UTF-8').'">'.htmlspecialchars($a['title'],ENT_QUOTES,'UTF-8').'</a></h3>';
                echo cs_news_meta($a['author'],$a['date']);
                echo '<div class="content" style="margin-top:10px">'.htmlspecialchars($a['short'],ENT_QUOTES,'UTF-8').'</div>';
                echo '<div class="actions"><a class="cs-btn" href="'.htmlspecialchars($a['url'],ENT_QUOTES,'UTF-8').'">'.lang('news_txt_3').'</a></div>';
            echo '</article>';
        }
        if(empty($items)) {
            echo '<article class="cs-card cs-news-empty">';
                echo '<h3>Noticias</h3>';
                echo '<p>No hay noticias publicadas todavía.</p>';
                echo '<p class="cs-news-empty-help">Puedes publicar una noticia y actualizar la caché desde el AdminCP.</p>';
            echo '</article>';
        }
        echo '</div>';
    }

    echo "</div>"; // cierre wrapper

} catch(Exception $ex) {
    message('warning', $ex->getMessage());
}
?>
