<?php
require_once('../../includes/config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) die('ID inválido');
$guideId = intval($_GET['id']);
$guidePath = __DIR__ . '/../../guides/' . $guideId . '.json';
if (!file_exists($guidePath)) die('Guía no encontrada');

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Traducción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { padding: 30px; background: #f0f0f0; }
    .box { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-width: 900px; margin: auto; }
    textarea { min-height: 200px; }
  </style>
</head>
<body>
<div class="box">
  <h4>Añadir Traducción - Guía #<?php echo $guideId; ?></h4>

  <form id="translationForm">
    <input type="hidden" name="guides_id" value="<?php echo $guideId; ?>">

    <div class="mb-3">
      <label for="language" class="form-label">Idioma (ej: en, es, pt)</label>
      <input type="text" class="form-control" name="language" id="language" required>
    </div>

    <div class="mb-3">
      <label for="guides_title" class="form-label">Título</label>
      <input type="text" class="form-control" name="guides_title" id="guides_title" required>
    </div>

    <div class="mb-3">
      <label for="guides_content" class="form-label">Contenido</label>
      <textarea class="form-control" name="guides_content" id="guides_content" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Traducción</button>
    <a href="manageguides.php" class="btn btn-secondary">Volver</a>
  </form>
</div>

<script>
document.getElementById('translationForm').addEventListener('submit', function(e) {
  e.preventDefault();
  let formData = new FormData(this);

  fetch('../ajax/save_translation.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if(data.success) {
      alert('Traducción guardada.');
      window.location.href = 'manageguides.php';
    } else {
      alert('Error: ' + data.message);
    }
  });
});
</script>
</body>
</html>
