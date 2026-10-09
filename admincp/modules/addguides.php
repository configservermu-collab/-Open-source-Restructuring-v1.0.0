<?php
/**
 * AdminCP - Publicar Guía con Portada y Miniatura
 * - Sube 2 imágenes (cover y thumb)
 * - Inyecta metadatos en el contenido: <!--GUIDE_META cover="..." thumb="..." -->
 * - Guarda la guía usando la clase Guides (sin romper compatibilidad)
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../../includes/classes/class.guides.php');
$Guides = new Guides();
loadModuleConfigs('guides');

$uploadDir = realpath(__DIR__ . '/../../includes/uploads');
if(!$uploadDir) {
  @mkdir(__DIR__ . '/../../includes/uploads', 0775, true);
  $uploadDir = realpath(__DIR__ . '/../../includes/uploads');
}
$guidesDir = $uploadDir . '/guides';
@mkdir($guidesDir, 0775, true);

function _g($key,$default=''){ return isset($_POST[$key])?trim($_POST[$key]):$default; }
function _extOk($f){ $e=strtolower(pathinfo($f,PATHINFO_EXTENSION)); return in_array($e,['jpg','jpeg','png','webp','gif']); }
function _safeName($n){ return preg_replace('/[^a-zA-Z0-9_\-.]/','_',$n); }

function _guides_create($Guides,$title,$content,$author='Administrator'){
  if(method_exists($Guides,'addGuides')) return $Guides->addGuides($title,$content,$author,0);
  if(method_exists($Guides,'addGuide')) return $Guides->addGuide($title,$content,$author,0);
  if(method_exists($Guides,'saveGuide')){
    $rm=new ReflectionMethod($Guides,'saveGuide');
    if($rm->getNumberOfParameters()>=3) return $Guides->saveGuide($title,$content,$author);
    return $Guides->saveGuide(['title'=>$title,'content'=>$content,'author'=>$author,'status'=>0]);
  }
  if(method_exists($Guides,'create')) return $Guides->create(['title'=>$title,'content'=>$content,'author'=>$author,'status'=>0]);
  if(method_exists($Guides,'createGuide')) return $Guides->createGuide(['title'=>$title,'content'=>$content,'author'=>$author,'status'=>0]);
  throw new Exception('No existe método para crear guías en la clase Guides.');
}
function _rebuild_guides_cache($Guides){
  foreach(['cacheGuides','rebuildGuidesCache','buildGuidesCache','updateGuidesCache','updateGuidesCacheIndex','rebuildIndex','rebuildCache'] as $m){
    if(method_exists($Guides,$m)){ try{$Guides->{$m}();}catch(Throwable $e){} }
  }
}
?>

<div class="csmu-wrap">
  <div class="csmu-head">
    <h1 class="csmu-title">Publicar Guía</h1>
    <p class="csmu-sub">Crear nueva guía con portada y miniatura.</p>
  </div>

  <?php
  if(!$Guides->isGuidesDirWritable()){
    message('error','La carpeta de guías no es escribible. Verificá permisos en <code>/includes/cache/guides/</code>.');
    return;
  }

  if(isset($_POST['guides_submit']) && $_POST['guides_submit']==='ok'){
    try{
      $title=_g('guides_title');
      $content=_g('guides_content');
      $author=_g('guides_author','Administrator');
      if(!check_value($title)||!check_value($content)) throw new Exception('Título y contenido son obligatorios.');

      $coverRel=''; $thumbRel='';
      if(isset($_FILES['guide_cover']) && is_uploaded_file($_FILES['guide_cover']['tmp_name'])){
        if(!_extOk($_FILES['guide_cover']['name'])) throw new Exception('Portada: formato inválido.');
        $fn=date('Ymd_His').'_cover_'._safeName($_FILES['guide_cover']['name']);
        $dest=$guidesDir.'/'.$fn;
        if(!move_uploaded_file($_FILES['guide_cover']['tmp_name'],$dest)) throw new Exception('No se pudo subir la portada.');
        $coverRel='includes/uploads/guides/'.$fn;
      }
      if(isset($_FILES['guide_thumb']) && is_uploaded_file($_FILES['guide_thumb']['tmp_name'])){
        if(!_extOk($_FILES['guide_thumb']['name'])) throw new Exception('Miniatura: formato inválido.');
        $fn=date('Ymd_His').'_thumb_'._safeName($_FILES['guide_thumb']['name']);
        $dest=$guidesDir.'/'.$fn;
        if(!move_uploaded_file($_FILES['guide_thumb']['tmp_name'],$dest)) throw new Exception('No se pudo subir la miniatura.');
        $thumbRel='includes/uploads/guides/'.$fn;
      }

      $meta='<!--GUIDE_META';
      if($coverRel) $meta.=' cover="'.htmlspecialchars($coverRel,ENT_QUOTES,'UTF-8').'"';
      if($thumbRel) $meta.=' thumb="'.htmlspecialchars($thumbRel,ENT_QUOTES,'UTF-8').'"';
      $meta.=' -->';
      if(preg_match('/<!--\s*GUIDE_META.*?-->/i',$content)){
        $content=preg_replace('/<!--\s*GUIDE_META.*?-->/i',$meta,$content,1);
      }else{
        $content=$meta."\n".$content;
      }

      _guides_create($Guides,$title,$content,$author);
      _rebuild_guides_cache($Guides);
      message('success','Guía publicada correctamente.');
      redirect(1,'admincp/?module=manageguides');
    }catch(Throwable $e){ message('error','Error al guardar: '.$e->getMessage()); }
  }
  ?>

  <div class="csmu-card">
    <div class="csmu-card-h">Formulario de Guía</div>
    <div class="csmu-card-b">
      <form role="form" method="post" enctype="multipart/form-data" class="csmu-form">

        <div class="form-group mb-3">
          <label for="g_title">Título</label>
          <input type="text" class="csmu-input" id="g_title" name="guides_title" placeholder="Título de la guía" required>
        </div>

        <div class="row mb-3" style="display:flex;gap:12px;">
          <div class="col-sm-6" style="flex:1;">
            <label>Imagen de Portada (1200×500)</label>
            <input type="file" name="guide_cover" accept=".jpg,.jpeg,.png,.webp,.gif" class="csmu-input">
          </div>
          <div class="col-sm-6" style="flex:1;">
            <label>Miniatura (250×250)</label>
            <input type="file" name="guide_thumb" accept=".jpg,.jpeg,.png,.webp,.gif" class="csmu-input">
          </div>
        </div>

        <div class="form-group mb-3">
          <label for="guides_content">Contenido</label>
          <textarea name="guides_content" id="guides_content"></textarea>
        </div>

        <div class="form-group mb-3">
          <label for="g_author">Autor</label>
          <input type="text" class="csmu-input" id="g_author" name="guides_author" value="Administrator">
        </div>

        <button type="submit" class="csmu-btn csmu-btn-primary" name="guides_submit" value="ok">Publicar</button>
      </form>
    </div>
  </div>
</div>

<script src="//cdn.ckeditor.com/4.7.3/full/ckeditor.js"></script>
<script> CKEDITOR.replace('guides_content',{language:'es'}); </script>

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
