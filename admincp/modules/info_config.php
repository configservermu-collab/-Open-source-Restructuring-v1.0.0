<?php
if (!defined('access') || !access) die();

$infoFile = __PATH_INCLUDES__ . 'config/info_config.json';
$data = file_exists($infoFile) ? json_decode(file_get_contents($infoFile), true) : [];
?>

<h2>Editor de Información del Servidor <small>(auto guardado)</small></h2>
<div id="editorContainer">
    <?php foreach ($data as $i => $section): ?>
        <div class="panel panel-default section-block" data-index="<?= $i ?>">
            <div class="panel-heading">
                <strong><input type="text" class="form-control autosave" data-section="<?= $i ?>" data-field="title" value="<?= htmlspecialchars($section['title']) ?>"></strong>
                <button type="button" class="btn btn-xs btn-danger pull-right delete-section" data-section="<?= $i ?>">Eliminar</button>
            </div>
            <div class="panel-body">
                <?php if ($section['type'] == 'table'): ?>
                    <table class="table table-bordered row-table">
                        <thead><tr><th>Columna 1</th><th>Columna 2</th><th>Acción</th></tr></thead>
                        <tbody>
                        <?php foreach ($section['rows'] as $j => $row): ?>
                            <tr>
                                <td><input type="text" class="form-control autosave" data-section="<?= $i ?>" data-type="table" data-row="<?= $j ?>" data-col="0" value="<?= htmlspecialchars($row[0]) ?>"></td>
                                <td><input type="text" class="form-control autosave" data-section="<?= $i ?>" data-type="table" data-row="<?= $j ?>" data-col="1" value="<?= htmlspecialchars($row[1]) ?>"></td>
                                <td><button type="button" class="btn btn-danger btn-sm delete-row" data-section="<?= $i ?>" data-row="<?= $j ?>">X</button></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm add-row" data-section="<?= $i ?>">Agregar Fila</button>
                <?php elseif ($section['type'] == 'video'): ?>
                    <label>Video URL</label>
                    <input type="text" class="form-control autosave" data-section="<?= $i ?>" data-field="url" value="<?= htmlspecialchars($section['url']) ?>">
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<hr>
<button type="button" id="addTable" class="btn btn-info">Agregar Tabla</button>
<button type="button" id="addVideo" class="btn btn-warning">Agregar Video</button>
<hr>
<p class="text-center text-muted">
    <small>
        Módulo desarrollado por <a href="https://configservermu.net" target="_blank">ConfigServerMU.net</a><br>
        &copy; <?= date('Y') ?> - WebEngine CMS | MU Server Info Manager
    </small>
</p>

<script>
let sectionIndex = <?= count($data) ?>;

function showSavedNotice() {
    const notice = document.createElement('div');
    notice.className = 'alert alert-success';
    notice.textContent = 'Guardado correctamente';
    notice.style.position = 'fixed';
    notice.style.bottom = '20px';
    notice.style.right = '20px';
    notice.style.zIndex = '9999';
    document.body.appendChild(notice);
    setTimeout(() => notice.remove(), 2500);
}

function saveChange(formData) {
    fetch('ajax/save_info_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(() => showSavedNotice());
}

function bindAutosaveInputs(context = document) {
    context.querySelectorAll('.autosave').forEach(input => {
        input.addEventListener('blur', function() {
            const formData = new FormData();
            formData.append('section', this.dataset.section);
            if (this.dataset.type === 'table') {
                formData.append('type', 'table');
                formData.append('row', this.dataset.row);
                formData.append('col', this.dataset.col);
            }
            formData.append('field', this.dataset.field || '');
            formData.append('value', this.value);
            saveChange(formData);
        });
    });
}

bindAutosaveInputs();

document.getElementById('addTable').addEventListener('click', function() {
    const container = document.getElementById('editorContainer');
    const div = document.createElement('div');
    div.className = 'panel panel-default section-block';
    div.setAttribute('data-index', sectionIndex);
    div.innerHTML = `
        <div class="panel-heading">
            <strong><input type="text" class="form-control autosave" data-section="${sectionIndex}" data-field="title" value="Nueva Tabla"></strong>
            <button type="button" class="btn btn-xs btn-danger pull-right delete-section" data-section="${sectionIndex}">Eliminar</button>
        </div>
        <div class="panel-body">
            <input type="hidden" class="autosave" data-section="${sectionIndex}" data-field="type" value="table">
            <table class="table table-bordered row-table">
                <thead><tr><th>Columna 1</th><th>Columna 2</th><th>Acción</th></tr></thead>
                <tbody></tbody>
            </table>
            <button type="button" class="btn btn-primary btn-sm add-row" data-section="${sectionIndex}">Agregar Fila</button>
        </div>
    `;
    container.appendChild(div);
    bindAutosaveInputs(div);

    let formData = new FormData();
    formData.append('action', 'create_section');
    formData.append('section', sectionIndex);
    formData.append('type', 'table');
    saveChange(formData);

    sectionIndex++;
});

document.getElementById('addVideo').addEventListener('click', function() {
    const container = document.getElementById('editorContainer');
    const div = document.createElement('div');
    div.className = 'panel panel-default section-block';
    div.setAttribute('data-index', sectionIndex);
    div.innerHTML = `
        <div class="panel-heading">
            <strong><input type="text" class="form-control autosave" data-section="${sectionIndex}" data-field="title" value="Nuevo Video"></strong>
            <button type="button" class="btn btn-xs btn-danger pull-right delete-section" data-section="${sectionIndex}">Eliminar</button>
        </div>
        <div class="panel-body">
            <input type="hidden" class="autosave" data-section="${sectionIndex}" data-field="type" value="video">
            <input type="text" class="form-control autosave" data-section="${sectionIndex}" data-field="url" placeholder="URL de video (YouTube embed)">
        </div>
    `;
    container.appendChild(div);
    bindAutosaveInputs(div);

    let formData = new FormData();
    formData.append('action', 'create_section');
    formData.append('section', sectionIndex);
    formData.append('type', 'video');
    saveChange(formData);

    sectionIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.matches('.add-row')) {
        const section = e.target.dataset.section;
        const table = e.target.previousElementSibling.querySelector('tbody');
        const rowCount = table.querySelectorAll('tr').length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" class="form-control autosave" data-section="${section}" data-type="table" data-row="${rowCount}" data-col="0"></td>
            <td><input type="text" class="form-control autosave" data-section="${section}" data-type="table" data-row="${rowCount}" data-col="1"></td>
            <td><button type="button" class="btn btn-danger btn-sm delete-row" data-section="${section}" data-row="${rowCount}">X</button></td>
        `;
        table.appendChild(tr);
        bindAutosaveInputs(tr);

        let formData = new FormData();
        formData.append('action', 'add_row');
        formData.append('section', section);
        saveChange(formData);
    }

    if (e.target.matches('.delete-row')) {
        const section = e.target.dataset.section;
        const row = e.target.dataset.row;
        e.target.closest('tr').remove();
        let formData = new FormData();
        formData.append('action', 'delete_row');
        formData.append('section', section);
        formData.append('row', row);
        saveChange(formData);
    }

    if (e.target.matches('.delete-section')) {
        const section = e.target.dataset.section;
        e.target.closest('.section-block').remove();
        let formData = new FormData();
        formData.append('action', 'delete_section');
        formData.append('section', section);
        saveChange(formData);
    }
});
</script>
