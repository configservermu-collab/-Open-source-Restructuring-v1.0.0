<?php
// Cargar config del módulo (paleta, etc.)
$configFile = __DIR__ . '/../cache/guides_module_config.json';
$configRaw  = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];

// Valores por defecto
$defaultConfig = [
    'guides_short'      => false,
    'guides_background' => '#181818',
];

$config = array_merge($defaultConfig, is_array($configRaw) ? $configRaw : []);

/* ===================== Helpers ===================== */
function mg_str($row, $key, $default='') {
    return isset($row[$key]) ? (string)$row[$key] : $default;
}
function mg_id($row) {
    return isset($row['guides_id']) ? (int)$row['guides_id'] : (int)($row['id'] ?? 0);
}
function mg_date_ts($row) {
    // 1) Campo estándar
    if (isset($row['guides_date']) && is_numeric($row['guides_date'])) return (int)$row['guides_date'];
    // 2) Otros posibles nombres
    foreach (['date','created_at','timestamp'] as $k) {
        if (isset($row[$k]) && is_numeric($row[$k])) return (int)$row[$k];
    }
    // 3) Si guardamos la ruta del archivo, usamos su mtime
    if (isset($row['__file']) && is_string($row['__file']) && file_exists($row['__file'])) {
        return (int)@filemtime($row['__file']);
    }
    // 4) Último recurso
    return time();
}

/* ===================== Listado de guías ===================== */
$guidesDir   = __DIR__ . '/../../guides/';
$guidesList  = [];

foreach (glob($guidesDir . '*.json') as $file) {
    $data = json_decode(file_get_contents($file), true);
    if (is_array($data) && isset($data['guides_id'], $data['guides_title'])) {
        // Guardamos ruta para fallback de fecha
        $data['__file'] = $file;
        $guidesList[]   = $data;
    }
}

// Ordenar por fecha desc
usort($guidesList, function($a,$b){
    return mg_date_ts($b) <=> mg_date_ts($a);
});
?>
<hr>
<h4>Listado de Guías</h4>

<div class="table-responsive">
  <table class="table table-striped table-bordered table-sm align-middle text-center">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>Fecha</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if(empty($guidesList)): ?>
        <tr><td colspan="5">No hay guías creadas aún.</td></tr>
      <?php else: ?>
        <?php foreach($guidesList as $guide): ?>
          <?php
            $id     = mg_id($guide);
            $title  = htmlspecialchars(mg_str($guide,'guides_title','(Sin título)'), ENT_QUOTES, 'UTF-8');
            $author = htmlspecialchars(mg_str($guide,'guides_author','Desconocido'), ENT_QUOTES, 'UTF-8');
            $ts     = mg_date_ts($guide);
            $date   = date('Y-m-d H:i', $ts);
          ?>
          <tr>
            <td><?php echo $id; ?></td>
            <td><?php echo $title; ?></td>
            <td><?php echo $author; ?></td>
            <td><?php echo $date; ?></td>
            <td>
              <a href="?module=editguides&id=<?php echo $id; ?>" class="btn btn-sm btn-warning">Editar</a>
              <button class="btn btn-sm btn-danger" onclick="deleteGuide(<?php echo $id; ?>)">Eliminar</button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
function deleteGuide(id) {
  if (!confirm('¿Estás seguro de eliminar la guía #' + id + '? Esta acción no se puede deshacer.')) return;

  fetch('ajax/delete_guide.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + encodeURIComponent(id)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('✅ Guía eliminada.');
      location.reload();
    } else {
      alert('❌ Error: ' + data.message);
    }
  })
  .catch(err => {
    alert('❌ Error de red.');
    console.error(err);
  });
}
</script>

<h4>Configuración del módulo</h4>
<form id="saveConfigForm">
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr><th>Clave</th><th>Valor</th></tr>
      </thead>
      <tbody>
        <?php foreach($config as $key => $value): ?>
        <tr>
          <td><?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?></td>
          <td>
            <?php if($key == 'guides_short'): ?>
              <div class="form-check form-switch" style="padding-left: 2.5rem;">
                <input class="form-check-input" type="checkbox" name="config[<?php echo $key; ?>]" id="switch_<?php echo $key; ?>" value="on" <?php echo ($value === true || $value === 'on') ? 'checked' : ''; ?> style="width: 2.5em; height: 1.25em;">
              </div>
            <?php elseif($key == 'guides_background'): ?>
              <input type="color" name="config[<?php echo $key; ?>]" class="form-control form-control-color" value="<?php echo htmlspecialchars($value,ENT_QUOTES,'UTF-8'); ?>" style="width: 60px; height: 32px;">
            <?php else: ?>
              <input type="text" name="config[<?php echo $key; ?>]" class="form-control" value="<?php echo htmlspecialchars((string)$value,ENT_QUOTES,'UTF-8'); ?>">
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <button type="submit" class="btn btn-primary">Guardar configuración</button>
</form>

<script>
document.getElementById('saveConfigForm').addEventListener('submit', function(e) {
  e.preventDefault();
  let formData = new FormData(this);

  // Asegurar envío de guides_short si queda apagado
  if (!formData.has('config[guides_short]')) {
    formData.append('config[guides_short]', 'off');
  }

  fetch(window.location.origin + '/admincp/ajax/save_guides_config.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(text => {
    try {
      const data = JSON.parse(text);
      if (data.success) {
        alert('✅ Configuración guardada correctamente.');
        location.reload();
      } else {
        alert('❌ Error: ' + data.message);
      }
    } catch (e) {
      console.error('❌ No es JSON válido:', text);
      alert('❌ Error inesperado. Revisá la consola.');
    }
  })
  .catch(err => {
    alert('❌ Error de red. Revisá conexión.');
    console.error(err);
  });
});
</script>
