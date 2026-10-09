# Guía 3: editar una noticia y configurar su listado

## Editar una noticia

1. Entra al AdminCP.
2. Abre **Administración de noticias** (`admincp/modules/managenews.php`).
3. Selecciona la noticia y pulsa **Editar**.
4. Modifica título, contenido, autor o fecha.
5. Pulsa **Actualizar noticia**.

El módulo `admincp/modules/editnews.php` vuelve a generar la caché después de guardar.

## Configurar cómo se muestran

En el módulo de configuración de noticias (`admincp/modules/mconfig/news.php`) puedes ajustar:

- Activar o desactivar el módulo.
- Cantidad de noticias expandidas.
- Límite de noticias en la página.
- Modo de noticia corta.
- Cantidad máxima de caracteres de la versión corta.

Guarda los cambios y comprueba tanto la página principal como la página completa de noticias.

## Fecha de publicación

La fecha se edita desde el formulario de edición. Utiliza el formato que muestra la instalación y verifica el resultado en la página pública antes de continuar.
