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
    <h1 class="csmu-title">Publicar Noticia</h1>
    <p class="csmu-sub">Agrega nuevas noticias visibles en la web.</p>
  </div>

<?php
$News = new News();
loadModuleConfigs('news');

if($News->isNewsDirWritable()) {
	
	if(isset($_POST['news_submit'])) {
		$News->addNews($_POST['news_title'],$_POST['news_content'],$_POST['news_author'],0);
		$News->cacheNews();
		$News->updateNewsCacheIndex();
		redirect(1, 'admincp/?module=managenews');
	}
?>
  <div class="csmu-card">
    <div class="csmu-card-h">Formulario de Noticia</div>
    <div class="csmu-card-b">
      <form role="form" method="post" class="csmu-form">
        <div class="form-group mb-3">
          <label for="input_1">Título</label>
          <input type="text" class="csmu-input" id="input_1" name="news_title" required>
        </div>

        <div class="form-group mb-3">
          <label for="news_content">Contenido</label>
          <textarea name="news_content" id="news_content"></textarea>
        </div>

        <div class="form-group mb-3">
          <label for="input_2">Autor</label>
          <input type="text" class="csmu-input" id="input_2" name="news_author" value="Administrator">
        </div>

        <button type="submit" class="csmu-btn csmu-btn-primary" name="news_submit" value="ok">Publicar</button>
      </form>
    </div>
  </div>

  <script>
  const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  document.addEventListener("DOMContentLoaded", function(){
    tinymce.init({
      selector: '#news_content',
      plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
      menubar: 'file edit view insert format tools table help',
      toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent | forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
      promotion: false,
      license_key: 'gpl',
      toolbar_mode: 'sliding',
      contextmenu: 'link image table',
      skin: useDarkMode ? 'oxide-dark' : 'oxide',
      content_css: useDarkMode ? 'dark' : 'default',
      height: 500
    });
  });
  </script>
<?php	
} else {
	message('error','La carpeta de caché de noticias no es escribible.');
}
?>
</div>

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
