# Guía 6: modificar la información del servidor

El módulo **Editor de Información del Servidor** permite organizar tablas y videos que se muestran en la información del servidor. Su archivo de datos es `includes/config/info_config.json`.

## Editar una sección

1. Entra al AdminCP.
2. Abre **Info Config** o **Información del servidor**.
3. Cambia el título de la sección.
4. Edita las celdas de las tablas o la URL del video.
5. Haz clic fuera del campo para activar el guardado automático.
6. Espera el aviso **Guardado correctamente**.

## Agregar contenido

- **Agregar Tabla** crea una tabla nueva.
- **Agregar Fila** añade una fila a una tabla.
- **Agregar Video** crea una sección para una URL de video.

## Buenas prácticas

- Utiliza URLs HTTPS.
- No coloques credenciales, IPs administrativas o información privada.
- Comprueba que el video permita inserción.
- Después de guardar, revisa la página pública en escritorio y móvil.

El guardado se realiza mediante `admincp/ajax/save_info_ajax.php`. No ejecutes ese endpoint manualmente fuera de una sesión autorizada.
