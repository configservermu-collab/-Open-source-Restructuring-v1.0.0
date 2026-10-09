# Guía 9: cambiar o agregar un template

Los templates se encuentran en `templates/`. El selector del AdminCP busca carpetas que contengan un `index.php`.

## Cambiar el template activo

1. Copia el nuevo template completo dentro de `templates/`.
2. Confirma que la carpeta tenga `index.php`.
3. Entra al AdminCP.
4. Abre **Template Selector**.
5. Selecciona el template.
6. Guarda y abre la página pública.

La selección se guarda en la configuración de WebEngine.

## Agregar un template propio

Como mínimo, revisa que la carpeta incluya:

- `index.php`.
- Hojas de estilo y JavaScript usados por el template.
- Imágenes con rutas relativas correctas.
- Las vistas o archivos auxiliares esperados por la CMS.

Conserva los nombres de variables y las llamadas que utiliza el template existente. No copies configuraciones privadas de otro servidor.

## Pruebas posteriores

Comprueba inicio de sesión, registro, noticias, guías, rankings, perfil, donaciones y versión móvil. Si el template rompe una página, vuelve temporalmente al template anterior desde el selector.
