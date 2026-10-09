<?php
/**
 * AdminCP — News Design (layout switch + color wheel + posición/tamaño)
 * Ruta: /admincp/modules/news_design.php
 */

if(!defined('access')) die('Access denied.');
if(!isLoggedIn() || !canAccessAdminCP($_SESSION['username'])) { message('error','Access denied.'); return; }

/* ------------------ CONFIG ------------------ */
$ALLOWED      = ['cascada','full','expandida'];
$LAYOUT_FILE  = __PATH_MODULE_CONFIGS__ . 'news.layout.xml';
$DEFAULT_BG   = '#0e1216';

/* ------------------ HELPERS XML ------------------ */
function cs_hex_normalize($hex, $fallback){
	$hex = trim((string)$hex);
	if($hex === '') return strtoupper($fallback);
	if($hex[0] !== '#') $hex = '#'.$hex;
	if(!preg_match('/^#[0-9A-Fa-f]{6}$/',$hex)) return strtoupper($fallback);
	return strtoupper($hex);
}

function cs_settings_load($file, $allowed, $defaultBg){
	$out = [
		'layout'=>'cascada',
		'bgcolor'=>$defaultBg,
		'top'=>0,
		'left'=>0,
		'width'=>800,
		'height'=>0
	];
	if(file_exists($file)){
		$xml = @simplexml_load_file($file);
		if($xml){
			if(isset($xml->news_layout)) {
				$v = strtolower((string)$xml->news_layout);
				if(in_array($v,$allowed)) $out['layout'] = $v;
			}
			if(isset($xml->news_bgcolor)) {
				$out['bgcolor'] = cs_hex_normalize((string)$xml->news_bgcolor, $defaultBg);
			}
			if(isset($xml->news_top))    $out['top']    = (int)$xml->news_top;
			if(isset($xml->news_left))   $out['left']   = (int)$xml->news_left;
			if(isset($xml->news_width))  $out['width']  = (int)$xml->news_width;
			if(isset($xml->news_height)) $out['height'] = (int)$xml->news_height;
		}
	}
	return $out;
}

function cs_settings_save($file, $layout, $bgcolor, $top, $left, $width, $height){
	@mkdir(dirname($file),0755,true);
	$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n".
		   "<settings>\n".
		   "\t<news_layout>{$layout}</news_layout>\n".
		   "\t<news_bgcolor>{$bgcolor}</news_bgcolor>\n".
		   "\t<news_top>{$top}</news_top>\n".
		   "\t<news_left>{$left}</news_left>\n".
		   "\t<news_width>{$width}</news_width>\n".
		   "\t<news_height>{$height}</news_height>\n".
		   "</settings>\n";
	return (bool)@file_put_contents($file,$xml);
}

/* ------------------ CARGA / SAVE ------------------ */
$cfg = cs_settings_load($LAYOUT_FILE, $ALLOWED, $DEFAULT_BG);
$currentLayout = $cfg['layout'];
$currentBg     = $cfg['bgcolor'];
$currentTop    = $cfg['top'];
$currentLeft   = $cfg['left'];
$currentWidth  = $cfg['width'];
$currentHeight = $cfg['height'];

if($_SERVER['REQUEST_METHOD']==='POST'){
	$layout  = strtolower($_POST['layout'] ?? '');
	if(!in_array($layout,$ALLOWED)) $layout = 'cascada';
	$bg      = cs_hex_normalize($_POST['bgcolor'] ?? '', $DEFAULT_BG);

	$top     = (int)($_POST['top'] ?? 0);
	$left    = (int)($_POST['left'] ?? 0);
	$width   = (int)($_POST['width'] ?? 800);
	$height  = (int)($_POST['height'] ?? 0);

	if(cs_settings_save($LAYOUT_FILE,$layout,$bg,$top,$left,$width,$height)){
		$currentLayout = $layout;
		$currentBg     = $bg;
		$currentTop    = $top;
		$currentLeft   = $left;
		$currentWidth  = $width;
		$currentHeight = $height;
		message('success','Diseño guardado correctamente.');
	} else {
		message('error','No se pudo guardar el diseño.');
	}
}
?>
<?php
/* ====== PREVIEW DATA (toma 1 noticia real) ====== */
$previewNews = [
  'title'  => 'Sin noticias',
  'author' => 'Sistema',
  'date'   => date('d-m-Y H:i'),
  'short'  => 'No hay noticias en caché. Este es un texto de ejemplo para la vista previa.',
  'teaser' => 'No hay noticias disponibles. Este texto simula el teaser en el modo expandido.',
  'full'   => '<p>Contenido completo de ejemplo. Agrega tu primera noticia para ver una vista previa real.</p>'
];

try {
  $cachedNews = function_exists('loadCache') ? loadCache('news.cache') : (function_exists('LoadCacheData') ? LoadCacheData('news.cache') : []);
  if(is_array($cachedNews) && isset($cachedNews[0])) {
    $row = $cachedNews[0];
    $title = base64_decode($row['news_title']);
    $author = $row['news_author'];
    $date = date('d-m-Y H:i', $row['news_date']);
    // carga HTML completo con la clase News si está disponible
    $fullHtml = '';
    if(class_exists('News')) {
      $N = new News(); $N->setId($row['news_id']); $fullHtml = $N->LoadCachedNews();
    }
    $plain = strip_tags(preg_replace('#<\s*br\s*/?>#i', ' ', $fullHtml));
    $short = mb_substr(trim(preg_replace('/\s+/u',' ', $plain)), 0, 300, 'UTF-8');
    $teaser= mb_substr(trim(preg_replace('/\s+/u',' ', $plain)), 0, 220, 'UTF-8');
    $previewNews = [
      'title'  => $title ?: 'Noticia',
      'author' => $author ?: 'Admin',
      'date'   => $date,
      'short'  => $short ?: 'Vista previa de la noticia en modo cascada/full.',
      'teaser' => $teaser ?: 'Vista previa de teaser (expandida).',
      'full'   => $fullHtml ?: '<p>Vista previa del contenido completo.</p>',
    ];
  }
} catch(Exception $e) { /* ignora y usa dummy */ }
?>

<style>
  /* estilos de tarjeta para preview (ligeros) */
#csPreviewContent .cs-card{
  background: <?php echo htmlspecialchars($currentBg); ?>;
  border:1px solid #2a333d;
  border-radius:12px;
  padding:16px;
  color:#dfe7ef;
}
#csPreviewContent h3,
#csPreviewContent .csx-title{ color:#fff; }
#csPreviewContent .meta{ color:#aeb7c0; }
#csPreviewContent .content,
#csPreviewContent .csx-teaser{ color:#dfe7ef; }
  #csPreviewContent h3{margin:0 0 6px;color:#fff;font-size:16px}
  #csPreviewContent .meta{color:#aeb7c0;font-size:12px;margin-right:10px}
  #csPreviewContent .content{color:#dfe7ef;font-size:14px;line-height:1.45;margin-top:8px}
  #csPreviewContent .csx-title{color:#e9edf1;font-weight:700;text-transform:uppercase;font-size:15px}
  #csPreviewContent .csx-divider{border-top:1px dashed #2a333d;margin:6px 0 10px}
  #csPreviewContent .csx-teaser{color:#e9edf1;font-size:14px}
	.csmu-wrap{background:#0e1216;padding:16px;border:1px solid #1f2730;border-radius:12px;color:#e7eef7}
	.csmu-title{margin:0 0 12px}
	.cs-pills{display:flex;gap:10px;flex-wrap:wrap}
	.cs-pill{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border:1px solid #2a333d;border-radius:999px;
		background:#11161b;color:#b8c4d1;cursor:pointer;text-decoration:none}
	.cs-pill .dot{width:8px;height:8px;border-radius:50%;background:#2a333d}
	.cs-pill.active{background:#20d37b;border-color:#20d37b;color:#072b18}
	.cs-pill.active .dot{background:#0d3b26}
	.cs-actions{margin-top:14px}
	.cs-btn{padding:8px 14px;border-radius:10px;border:1px solid #2a333d;background:#121821;color:#e7eef7;cursor:pointer}
	.cs-btn-primary{background:#20d37b;border-color:#20d37b;color:#072b18}
	.cs-note{margin-top:10px;color:#8fa5bb;font-size:13px}
	/* ----- COLOR WHEEL ----- */
	.cs-color{display:flex;gap:18px;align-items:center;margin-top:16px;flex-wrap:wrap}
	.cs-wheel{position:relative;width:250px;height:250px}
	.cs-wheel canvas{width:250px;height:250px;border-radius:50%;display:block;box-shadow:0 0 0 1px #293240, 0 8px 24px rgba(0,0,0,.35)}
	.cs-color-controls{display:flex;flex-direction:column;gap:10px;min-width:220px}
	.cs-swatch{width:42px;height:42px;border-radius:50%;border:2px solid #2a333d;box-shadow:inset 0 0 0 1px rgba(255,255,255,.05)}
	.cs-row{display:flex;align-items:center;gap:10px}
	.cs-input{background:#11161b;border:1px solid #2a333d;color:#e7eef7;border-radius:8px;padding:8px 10px}
	.cs-input[readonly]{opacity:.95}
	.cs-hint{font-size:12px;color:#8fa5bb}
	.cs-preview{padding:10px;border:1px dashed #2a333d;border-radius:12px}
	/* Posición/Tamaño */
	.cs-geom{margin-top:18px;display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px}
	.cs-geom label{display:flex;flex-direction:column;font-size:13px;color:#cfd3db}
	.cs-geom input{margin-top:4px;padding:6px 8px;border-radius:8px;border:1px solid #2a333d;background:#11161b;color:#e7eef7}
</style>

<div class="csmu-wrap">
	<h1 class="csmu-title">Noticias — Diseño</h1>

	<form method="post" id="f_layout" onsubmit="return true;">
		<input type="hidden" name="layout" id="layout" value="<?php echo htmlspecialchars($currentLayout);?>">
		<input type="hidden" name="bgcolor" id="bgcolor" value="<?php echo htmlspecialchars($currentBg);?>">

		<!-- Selector de layout -->
		<div class="cs-pills">
			<a href="#" class="cs-pill <?php echo $currentLayout==='cascada'?'active':'';?>" data-layout="cascada"><span class="dot"></span> Cascada</a>
			<a href="#" class="cs-pill <?php echo $currentLayout==='full'?'active':'';?>" data-layout="full"><span class="dot"></span> Full</a>
			<a href="#" class="cs-pill <?php echo $currentLayout==='expandida'?'active':'';?>" data-layout="expandida"><span class="dot"></span> Expandida</a>
		</div>

		<!-- Rueda de color -->
		<div class="cs-color">
			<div class="cs-wheel">
				<canvas id="colorWheel" width="250" height="250"></canvas>
			</div>
			<div class="cs-color-controls">
				<div class="cs-row">
					<div class="cs-swatch" id="swatch" style="background: <?php echo htmlspecialchars($currentBg);?>;"></div>
					<input class="cs-input" type="text" id="hexInput" maxlength="7" value="<?php echo htmlspecialchars($currentBg);?>" placeholder="#RRGGBB">
					<input class="cs-input" type="color" id="nativePicker" value="<?php echo htmlspecialchars($currentBg);?>">
				</div>
				<div class="cs-hint">Elegí un color tocando la rueda o usando el selector. Se guardará en el XML como <code>&lt;news_bgcolor&gt;</code>.</div>
				<div class="cs-preview" id="bgPreview" style="background: <?php echo htmlspecialchars($currentBg);?>;">
					<small>Preview fondo</small>
				</div>
			</div>
		</div>

		<!-- Posición y tamaño -->
		<div class="cs-geom">
			<label>Top (px)
				<input type="number" name="top" value="<?php echo htmlspecialchars($currentTop);?>">
			</label>
			<label>Left (px)
				<input type="number" name="left" value="<?php echo htmlspecialchars($currentLeft);?>">
			</label>
			<label>Width (px)
				<input type="number" name="width" value="<?php echo htmlspecialchars($currentWidth);?>">
			</label>
			<label>Height (px)
				<input type="number" name="height" value="<?php echo htmlspecialchars($currentHeight);?>">
			</label>
		</div>
<!-- ===== PREVIEW EN TIEMPO REAL ===== -->
<div style="margin-top:18px">
  <div style="margin-bottom:6px;color:#8fa5bb;font-size:13px">
    Vista previa (usa la primera noticia disponible). Arrastra los valores y verás el resultado al instante.
  </div>

  <div id="csPreviewStage" style="position:relative;height:520px;border:1px dashed #2a333d;border-radius:12px;background:#10151b;overflow:hidden">
    <!-- caja posicionable -->
    <div id="csPreviewBox" style="
      position:absolute;
      top:<?php echo (int)$currentTop;?>px;
      left:<?php echo (int)$currentLeft;?>px;
      width:<?php echo (int)$currentWidth;?>px;
      <?php if((int)$currentHeight>0) echo 'height:'.(int)$currentHeight.'px;'; ?>
      background:<?php echo htmlspecialchars($currentBg);?>; 
      border:2px solid #20d37b;border-radius:12px;padding:12px; overflow:auto;">
      <div id="csPreviewContent"></div>
    </div>
  </div>
</div>

		<div class="cs-actions">
			<button class="cs-btn cs-btn-primary" type="submit">Guardar diseño</button>
		</div>
		<div class="cs-note">Se guarda en: <code><?php echo htmlspecialchars($LAYOUT_FILE);?></code></div>
	</form>
</div>

<script>
(function(){
	/* Layout pills */
	const pills = document.querySelectorAll('.cs-pill');
	const inputLayout = document.getElementById('layout');
	pills.forEach(p=>{
		p.addEventListener('click', function(e){
			e.preventDefault();
			pills.forEach(x=>x.classList.remove('active'));
			this.classList.add('active');
			inputLayout.value = this.dataset.layout;
		});
	});

	/* Color wheel */
	const wheel  = document.getElementById('colorWheel');
	const ctx    = wheel.getContext('2d');
	const size   = 250;
	const radius = size/2;
	const center = {x: radius, y: radius};

	function HSVtoRGB(h, s, v){
		let c = v * s, x = c * (1 - Math.abs((h / 60) % 2 - 1)), m = v - c;
		let r=0,g=0,b=0;
		if (0<=h && h<60){ r=c; g=x; b=0; }
		else if (60<=h && h<120){ r=x; g=c; b=0; }
		else if (120<=h && h<180){ r=0; g=c; b=x; }
		else if (180<=h && h<240){ r=0; g=x; b=c; }
		else if (240<=h && h<300){ r=x; g=0; b=c; }
		else { r=c; g=0; b=x; }
		return { r: Math.round((r+m)*255), g: Math.round((g+m)*255), b: Math.round((b+m)*255) };
	}
	function rgbToHex(r,g,b){
		return "#"+[r,g,b].map(v=>v.toString(16).padStart(2,"0")).join("").toUpperCase();
	}
	(function drawWheel(){
		const img = ctx.createImageData(size, size);
		for(let y=0; y<size; y++){
			for(let x=0; x<size; x++){
				const dx = x - center.x;
				const dy = y - center.y;
				const dist = Math.sqrt(dx*dx + dy*dy);
				const idx = (y*size + x)*4;
				if(dist <= radius){
					let angle = Math.atan2(dy, dx) * (180/Math.PI);
					if(angle < 0) angle += 360;
					const sat = dist / radius;
					const val = 1.0;
					const rgb = HSVtoRGB(angle, sat, val);
					img.data[idx+0] = rgb.r;
					img.data[idx+1] = rgb.g;
					img.data[idx+2] = rgb.b;
					img.data[idx+3] = 255;
				} else {
					img.data[idx+3] = 0;
				}
			}
		}
		ctx.putImageData(img, 0, 0);
	})();
	const swatch  = document.getElementById('swatch');
	const hexIn   = document.getElementById('hexInput');
	const nativeP = document.getElementById('nativePicker');
	const hidden  = document.getElementById('bgcolor');
	const preview = document.getElementById('bgPreview');
	function setColor(hex){
		if(!/^#[0-9A-Fa-f]{6}$/.test(hex)) return;
		hex = hex.toUpperCase();
		hexIn.value  = hex;
		nativeP.value= hex;
		hidden.value = hex;
		swatch.style.background = hex;
		preview.style.background = hex;
	}
	setColor(hexIn.value);
	function pickFromCanvas(evt){
		const rect = wheel.getBoundingClientRect();
		const x = Math.round((evt.clientX - rect.left) * (wheel.width / rect.width));
		const y = Math.round((evt.clientY - rect.top)  * (wheel.height/ rect.height));
		const dx = x - center.x, dy = y - center.y;
		if(Math.sqrt(dx*dx + dy*dy) > radius) return;
		const data = ctx.getImageData(x, y, 1, 1).data;
		setColor(rgbToHex(data[0], data[1], data[2]));
	}
	let dragging = false;
	wheel.addEventListener('mousedown', e=>{ dragging = true; pickFromCanvas(e); });
	window.addEventListener('mousemove', e=>{ if(dragging) pickFromCanvas(e); });
	window.addEventListener('mouseup', ()=> dragging = false);
	wheel.addEventListener('click', pickFromCanvas);
	hexIn.addEventListener('input', ()=>{
		const v = hexIn.value.trim().toUpperCase();
		if(/^#[0-9A-F]{6}$/.test(v)) setColor(v);
	});
	nativeP.addEventListener('input', ()=> setColor(nativeP.value.toUpperCase()));
})();
</script>
<script>
(function(){
  // datos PHP -> JS
  const PREVIEW_DATA = <?php echo json_encode($previewNews, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;

  const box   = document.getElementById('csPreviewBox');
  const cont  = document.getElementById('csPreviewContent');
  const fTop  = document.querySelector('input[name="top"]');
  const fLeft = document.querySelector('input[name="left"]');
  const fW    = document.querySelector('input[name="width"]');
  const fH    = document.querySelector('input[name="height"]');
  const pills = document.querySelectorAll('.cs-pill');
  const inLayout = document.getElementById('layout');
  const inBg   = document.getElementById('bgcolor');
  const hexIn  = document.getElementById('hexInput');
  const nativeP= document.getElementById('nativePicker');

  function tpl(layout){
    const d = PREVIEW_DATA;
    if(layout==='expandida'){
      return `
        <div class="cs-card" style="background:transparent;border:none;padding:0">
          <div class="csx-title">${escapeHtml(d.title)}</div>
          <div class="csx-divider"></div>
          <div class="csx-teaser">${escapeHtml(d.teaser)}</div>
        </div>`;
    }
    if(layout==='cascada'){
      return `
        <div class="cs-card">
          <h3>${escapeHtml(d.title)}</h3>
          <span class="meta"><i class="fa fa-user"></i> ${escapeHtml(d.author)}</span>
          <span class="meta"><i class="fa fa-calendar"></i> ${escapeHtml(d.date)}</span>
          <div class="content">${escapeHtml(d.short)}</div>
        </div>`;
    }
    // full (con una "expanded" de ejemplo)
    return `
      <div class="cs-card">
        <h3>${escapeHtml(d.title)} <small style="color:#8fa5bb">(expanded)</small></h3>
        <span class="meta"><i class="fa fa-user"></i> ${escapeHtml(d.author)}</span>
        <span class="meta"><i class="fa fa-calendar"></i> ${escapeHtml(d.date)}</span>
        <div class="content">${d.full || escapeHtml(d.short)}</div>
      </div>`;
  }

  function escapeHtml(s){
    return (s||'').toString()
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }

  function render(){
    const layout = inLayout.value;
    cont.innerHTML = tpl(layout);
    // aplicar posición/tamaño
    box.style.top  = (parseInt(fTop.value)||0) + 'px';
    box.style.left = (parseInt(fLeft.value)||0) + 'px';
    const w = parseInt(fW.value)||0, h = parseInt(fH.value)||0;
    if(w>0) box.style.width  = w + 'px';
    if(h>0){ box.style.height = h + 'px'; box.style.overflow='auto'; }
    else   { box.style.height = ''; box.style.overflow='visible'; }
    // color de fondo
    box.style.background = inBg.value;
  }

  // inputs -> live
  [fTop,fLeft,fW,fH].forEach(el=> el.addEventListener('input', render));
  pills.forEach(p=> p.addEventListener('click', ()=> setTimeout(render,0)));

  // color: enganchar a los pickers que ya tenés
  hexIn.addEventListener('input', ()=> { inBg.value = hexIn.value.toUpperCase(); render(); });
  nativeP.addEventListener('input', ()=> { inBg.value = nativeP.value.toUpperCase(); render(); });

  // primer render
  render();
})();
</script>
