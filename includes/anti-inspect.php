<?php
// Disuasión de inspección casual; no sustituye autenticación ni controles de servidor.
?>
<script>
(function () {
	if (/Mobi|Android|iPhone|iPad|iPod|Tablet/i.test(navigator.userAgent)) return;

	function block(event) {
		event.preventDefault();
		event.stopPropagation();
		return false;
	}

	document.addEventListener('contextmenu', block, true);
	document.addEventListener('dragstart', function (event) {
		if (event.target && event.target.tagName === 'IMG') block(event);
	}, true);

	window.addEventListener('keydown', function (event) {
		var key = (event.key || '').toUpperCase();
		var code = event.code || '';
		var modifier = event.ctrlKey || event.metaKey;
		var blocked =
			key === 'F12' ||
			(event.shiftKey && (key === 'F7' || key === 'F9')) ||
			(modifier && event.shiftKey && ['I', 'J', 'C', 'K', 'E', 'M'].indexOf(key) > -1) ||
			(event.metaKey && event.altKey && ['KeyI', 'KeyJ', 'KeyC', 'KeyU', 'KeyK'].indexOf(code) > -1) ||
			(modifier && (key === 'U' || key === 'S'));

		if (blocked) block(event);
	}, true);

	window.addEventListener('keyup', function (event) {
		if ((event.key || '') === 'F12') block(event);
	}, true);
}());
</script>
