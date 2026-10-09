# Guía 5: editar o traducir una guía existente

## Editar una guía

1. Entra al AdminCP.
2. Abre **Manage Guides**.
3. Localiza la guía por ID o título.
4. Pulsa **Editar**.
5. Cambia el título o el contenido.
6. Guarda y revisa la página pública.

No cambies el ID de una guía existente salvo que conozcas todas las referencias que lo utilizan.

## Agregar una traducción

La CMS incluye `admincp/modules/addguidestranslation.php` para agregar traducciones:

1. Abre la acción de traducción de la guía.
2. Introduce el código del idioma, por ejemplo `es`, `en` o `pt`.
3. Escribe el título traducido.
4. Escribe el contenido traducido.
5. Pulsa **Guardar traducción**.

Mantén la misma estructura de pasos en todos los idiomas. No traduzcas nombres de tablas, comandos o variables si deben copiarse literalmente.

## Si la traducción no aparece

Comprueba que el idioma esté instalado en `includes/languages/`, que el código sea correcto y que la guía original exista.
