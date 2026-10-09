# Guía 4: cómo crear una guía para los jugadores

Las guías se gestionan desde **Manage Guides** y se almacenan como archivos JSON dentro de la carpeta `guides/`. No edites esos archivos manualmente mientras el panel pueda hacerlo.

## Crear la guía

1. Entra al AdminCP.
2. Abre **Manage Guides**.
3. Usa el formulario de alta disponible en el módulo.
4. Completa el título.
5. Escribe el contenido de la guía.
6. Indica el autor.
7. Guarda la guía.

El listado de administración se implementa en `admincp/modules/manageguides.php` y las operaciones AJAX relacionadas están en `admincp/ajax/guide_add.php` y `admincp/ajax/guide_edit.php`.

## Estructura recomendada

1. Objetivo de la guía.
2. Requisitos.
3. Pasos numerados.
4. Resultado esperado.
5. Problemas frecuentes.

Usa capturas sin información privada y evita publicar datos de conexión del servidor.

## Verificación

Abre la sección pública de guías y confirma que el título, el autor y el contenido se muestran correctamente.
