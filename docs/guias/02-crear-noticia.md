# Guía 2: cómo crear y publicar una noticia

Las noticias se crean desde el módulo `admincp/modules/addnews.php`. El formulario permite guardar título, contenido y autor mediante el editor visual.

## Pasos

1. Entra al AdminCP.
2. Abre **News** o **Agregar noticia**.
3. Escribe un título claro.
4. Redacta el contenido en el campo **Contenido**.
5. Indica el autor. Si no lo cambias, el formulario utiliza `Administrator`.
6. Pulsa **Publicar**.

Al publicar, la CMS guarda la noticia y actualiza la caché de noticias automáticamente.

## Recomendaciones de contenido

- Usa un título corto y descriptivo.
- Coloca la información importante en los primeros párrafos.
- No incluyas contraseñas, claves API, direcciones privadas ni datos personales.
- Para imágenes, utiliza URLs o archivos que estén autorizados para tu sitio.
- Revisa los enlaces antes de publicar.

## Si la noticia no aparece

1. Abre **Administración > Noticias**.
2. Confirma que la noticia esté listada.
3. Comprueba que el módulo de noticias esté habilitado en la configuración de noticias.
4. Usa la opción **Actualizar caché de noticias** desde `managenews`.
5. Revisa que la carpeta de caché sea escribible por PHP.
