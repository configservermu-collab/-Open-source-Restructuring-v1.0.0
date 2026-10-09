<?php
/**
 * WebEngine CMS - AdminCP Edit Guide (JSON-Based) + Portada & Miniatura
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$guideId   = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$isNew     = ($guideId === 0);
$guidesDir = __DIR__ . '/../../guides/';
@mkdir($guidesDir, 0775, true);
$guideFile = $guidesDir . $guideId . '.json';

$guideData = [
  'guides_id'      => $guideId,
  'guides_title'   => '',
  'guides_author'  => '',
  'guides_content' => '',
  'guides_date'    => time(),
];

if(!$isNew && file_exists($guideFile)){
  $loaded=json_decode(file_get_contents($guideFile),true);
  if(is_array($loaded)) $guideData=array_merge($guideData,$loaded);
}

function eg_parse_meta($html){
  $meta=['cover'=>'','thumb'=>''];
  if(preg_match('/<!--\s*GUIDE_META(.*?)-->/is',$html,$m)){
    $attrs=$m[1];
    if(preg_match('/cover="([^"]+)"/i',$attrs,$c)) $meta['cover']=$c[1];
    if(preg_match('/thumb="([^"]+)"/i',$attrs,$t)) $meta['thumb']=$t[1];
  }
  return $meta;
}
$meta=eg_parse_meta($guideData['guides_content']);
$coverPreview=$meta['cover']?__BASE_URL__.$meta['cover']:'';
$thumbPreview=$meta['thumb']?__BASE_URL__.$meta['thumb']:'';
?>

<div class="csmu-wrap">
  <div class="csmu-head">
    <h1 class="csmu-title"><?php echo $isNew?'Nueva Guía':'Editar Guía #'.$guideId; ?></h1>
    <p class="csmu-sub">Editor de guías con portada y miniatura.</p>
  </div>

  <div class="csmu-card">
    <div class="csmu-card-h">Formulario de Guía</div>
    <div class="csmu-card-b">
      <form role="form" method="post" id="editGuideForm" enctype="multipart/form-data" class="csmu-form">
        <input type="hidden" name="guides_id" value="<?php echo (int)$guideId; ?>">

        <div class="form-group mb-3">
          <label for="guides_title">Título</label>
          <input type="text" class="csmu-input" name="guides_title" id="guides_title" value="<?php echo htmlspecialchars($guideData['guides_title'],ENT_QUOTES,'UTF-8'); ?>" required>
        </div>

        <div class="form-group mb-3">
          <label for="guides_author">Autor</label>
          <input type="text" class="csmu-input" name="guides_author" id="guides_author" value="<?php echo htmlspecialchars($guideData['guides_author'],ENT_QUOTES,'UTF-8'); ?>">
        </div>

        <div class="row" style="display:flex;gap:12px;">
          <div class="col-sm-6" style="flex:1;">
            <label>Imagen de Portada (1200×500)</label>
            <input type="file" name="guide_cover" accept=".jpg,.jpeg,.png,.webp,.gif" class="csmu-input">
            <div style="margin-top:8px">
              <?php if($coverPreview): ?>
                <img src="<?php echo htmlspecialchars($coverPreview); ?>" style="max-width:100%;border-radius:8px;border:1px solid #333">
              <?php else: ?>
                <small style="opacity:.8">Sin portada</small>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-sm-6" style="flex:1;">
            <label>Miniatura (250×250)</label>
            <input type="file" name="guide_thumb" accept=".jpg,.jpeg,.png,.webp,.gif" class="csmu-input">
            <div style="margin-top:8px">
              <?php if($thumbPreview): ?>
                <img src="<?php echo htmlspecialchars($thumbPreview); ?>" style="width:250px;height:250px;object-fit:cover;border-radius:8px;border:1px solid #333">
              <?php else: ?>
                <div style="width:250px;height:250px;border:1px dashed #444;color:#999;display:flex;align-items:center;justify-content:center;border-radius:8px">
                  250×250
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="form-group mb-3" style="margin-top:12px;">
          <label for="guides_content">Contenido</label>
          <textarea name="guides_content" id="guides_content"><?php echo htmlspecialchars($guideData['guides_content'],ENT_QUOTES,'UTF-8'); ?></textarea>
        </div>

        <button type="submit" class="csmu-btn csmu-btn-primary"><?php echo $isNew?'Crear Guía':'Guardar Cambios'; ?></button>
        <a href="?module=manageguides" class="csmu-btn" style="margin-left:8px">Volver</a>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('editGuideForm').addEventListener('submit',function(e){
  e.preventDefault();
  const fd=new FormData(this);
  fetch('<?php echo __BASE_URL__; ?>admincp/ajax/save_guides.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(j=>{
      if(j.success){
        alert(j.message||'Guardado correctamente.');
        window.location.href='?module=manageguides';
      }else{
        alert('Error: '+(j.message||'Desconocido'));
      }
    })
    .catch(err=>alert('Error al enviar: '+err));
});
</script>

<script src="https://cdn.jsdelivr.net/npm/tinymce@7.7.0/tinymce.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded",function(){
  tinymce.init({
    selector:'#guides_content',
    plugins:'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
    menubar:'file edit view insert format tools table help',
    toolbar:"undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent | forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
    promotion:false,
    toolbar_mode:'sliding',
    contextmenu:'link image table',
    skin:window.matchMedia('(prefers-color-scheme: dark)').matches?'oxide-dark':'oxide',
    content_css:window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'default',
    height:500
  });
});
</script>

<style>
:root{
  --panel:#151517;--panel2:#1b1b1e;--text:#f0f0f0;--muted:#a6a6ad;
  --border:#26262b;--accent:#ff7a18;--accent2:#ffa047;
}
.csmu-wrap{color:var(--text);}
.csmu-head{margin-bottom:18px}
.csmu-title{margin:0;font-size:26px;color:var(--accent);}
.csmu-sub{color:var(--muted);font-size:14px}
.csmu-card{background:var(--panel);border:1px solid var(--border);border-radius:12px;margin-bottom:20px;box-shadow:0 8px 24px rgba(0,0,0,.45)}
.csmu-card-h{padding:12px 16px;background:linear-gradient(180deg,var(--panel2),var(--panel));border-bottom:1px solid var(--border);font-weight:600}
.csmu-card-b{padding:16px}
.csmu-input{background:#101013;border:1px solid var(--border);border-radius:8px;color:var(--text);padding:8px 12px;width:100%}
.csmu-input:focus{border-color:var(--accent);outline:none;box-shadow:0 0 0 2px rgba(255,122,24,.25)}
.csmu-btn{border:1px solid var(--border);background:var(--panel2);color:var(--text);border-radius:8px;padding:10px 16px;cursor:pointer;text-decoration:none;display:inline-block}
.csmu-btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#111;border-color:transparent;font-weight:700}
.csmu-btn-primary:hover{opacity:.9;color:#000}
</style>
