<?php
/**
 * WebEngine CMS
 * https://webenginecms.org/
 * 
 * @version 1.2.6
 */
?>
<div class="csmu-wrap">
  <div class="csmu-head">
    <h1 class="csmu-title">Gestionar Noticias</h1>
    <p class="csmu-sub">Editar, eliminar y traducir noticias publicadas.</p>
  </div>

<?php
$News = new News();

if($News->isNewsDirWritable()) {

	# Eliminar noticia
	if(isset($_REQUEST['delete'])) {
		$deleteNews = $News->removeNews($_REQUEST['delete']);
		$News->cacheNews();
		$News->updateNewsCacheIndex();
		if($deleteNews) {
			redirect(1, 'admincp/?module=managenews');
		} else {
			message('error','ID de noticia inválido.');
		}
	}
	
	# Eliminar traducción
	if(isset($_GET['deletetranslation']) && isset($_GET['language'])) {
		try {
			$News->setId($_GET['deletetranslation']);
			$News->setLanguage($_GET['language']);
			$News->deleteNewsTranslation();
			$News->updateNewsCacheIndex();
			redirect(1, 'admincp/?module=managenews');
		} catch(Exception $ex) {
			message('error',$ex->getMessage());
		}
	}
	
	# Actualizar caché
	if(isset($_REQUEST['cache']) && $_REQUEST['cache'] == 1) {
		$cacheNews = $News->cacheNews();
		$News->updateNewsCacheIndex();
		if($cacheNews) {
			message('success','Noticias cacheadas correctamente.');
		} else {
			message('error','No hay noticias para cachear.');
		}
	}
	
	$news_list = $News->retrieveNews();
	if(is_array($news_list)) {
		foreach($news_list as $row) {
			$News->setId($row['news_id']);
?>
  <div class="csmu-card">
    <div class="csmu-card-h">
      <a href="<?php echo __BASE_URL__.'news/'.$row['news_id'].'/'; ?>" target="_blank">
        <?php echo htmlspecialchars($row['news_title'],ENT_QUOTES,'UTF-8'); ?>
      </a>
      <div style="float:right;display:flex;gap:6px;">
        <a class="csmu-btn csmu-btn-danger csmu-btn-xs" href="<?php echo admincp_base("managenews&delete=".$row['news_id']); ?>">Eliminar</a>
        <a class="csmu-btn csmu-btn-warning csmu-btn-xs" href="<?php echo admincp_base("editnews&id=".$row['news_id']); ?>">Editar</a>
        <a class="csmu-btn csmu-btn-xs" href="<?php echo admincp_base("addnewstranslation&id=".$row['news_id']); ?>">+ Traducción</a>
      </div>
    </div>
    <div class="csmu-card-b">
      <div class="row" style="display:flex;gap:20px;">
        <div class="col-sm-6" style="flex:1;">
          <table class="table csmu-table-dark table-sm">
            <tr><th>ID</th><td><?php echo $row['news_id']; ?></td></tr>
            <tr><th>Autor</th><td><?php echo htmlspecialchars($row['news_author'],ENT_QUOTES,'UTF-8'); ?></td></tr>
            <tr><th>Fecha</th><td><?php echo date("Y-m-d H:i",$row['news_date']); ?></td></tr>
          </table>
        </div>
        <div class="col-sm-6" style="flex:1;">
          <strong>Traducciones:</strong>
          <?php
          $newsTranslations = $News->getNewsTranslationsDataList();
          if(is_array($newsTranslations)) {
            echo '<ul>';
            foreach($newsTranslations as $translation) {
              echo '<li>[<span style="color:var(--accent)">'.$translation['news_language'].'</span>] '.base64_decode($translation['news_title']).' 
              <a href="'.admincp_base('editnewstranslation&id='.$translation['news_id'].'&language='.$translation['news_language']).'" class="csmu-btn csmu-btn-xs">Editar</a> 
              <a href="'.admincp_base('managenews&deletetranslation='.$translation['news_id'].'&language='.$translation['news_language']).'" class="csmu-btn csmu-btn-xs">Eliminar</a></li>';
            }
            echo '</ul>';
          } else {
            echo '<small class="text-muted">Sin traducciones.</small>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>
<?php
		}
	}
	echo '<a class="csmu-btn csmu-btn-primary" href="'.admincp_base("managenews&cache=1").'">Actualizar Caché de Noticias</a>';
} else {
	message('error','La carpeta de caché de noticias no es escribible.');
}
?>
</div>

<style>
:root{
  --panel:#151517;--panel2:#1b1b1e;--text:#f0f0f0;--muted:#a6a6ad;
  --border:#26262b;--accent:#ff7a18;--accent2:#ffa047;
  --danger:#ff3b30;--warn:#ffc107;
}
.csmu-wrap{color:var(--text);}
.csmu-head{margin-bottom:18px}
.csmu-title{margin:0;font-size:26px;color:var(--accent);}
.csmu-sub{color:var(--muted);font-size:14px}
.csmu-card{background:var(--panel);border:1px solid var(--border);border-radius:12px;margin-bottom:20px;box-shadow:0 8px 24px rgba(0,0,0,.45)}
.csmu-card-h{padding:10px 16px;background:linear-gradient(180deg,var(--panel2),var(--panel));border-bottom:1px solid var(--border);font-weight:600;display:flex;justify-content:space-between;align-items:center}
.csmu-card-b{padding:16px}
.csmu-btn{border:1px solid var(--border);background:var(--panel2);color:var(--text);border-radius:6px;padding:6px 12px;cursor:pointer;text-decoration:none;display:inline-block;font-size:13px}
.csmu-btn-xs{padding:3px 8px;font-size:12px}
.csmu-btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#111;border:none;font-weight:700}
.csmu-btn-warning{background:linear-gradient(135deg,var(--warn),#ffe28a);color:#111;font-weight:700;border:none}
.csmu-btn-danger{background:linear-gradient(135deg,var(--danger),#ff6b5a);color:#fff;font-weight:700;border:none}
.table.csmu-table-dark th{background:#1b1b1e;color:var(--accent);width:120px}
.table.csmu-table-dark td{background:#151517;color:var(--text)}
</style>
