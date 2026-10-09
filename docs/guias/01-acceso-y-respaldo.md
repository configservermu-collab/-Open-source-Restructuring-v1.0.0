# Guía 1: acceso seguro y respaldo antes de configurar la CMS

Antes de cambiar noticias, guías, donaciones o plantillas, realiza un respaldo. Los cambios del AdminCP pueden escribir archivos de configuración, caché o datos de la base.

## 1. Prepara el entorno

1. Usa una cuenta con permisos de administrador.
2. Trabaja primero en una copia de pruebas.
3. Confirma que el servidor utiliza PHP y los drivers SQL Server compatibles con esta versión.
4. Verifica que la carpeta de caché y las configuraciones que vas a editar tengan permisos de escritura para el proceso de PHP.

## 2. Respaldos recomendados

- Base de datos de la CMS y de MU Online.
- Carpeta `includes/config/`.
- Carpeta `includes/cache/`.
- Carpeta `news/`, si existe en tu instalación.
- Carpeta `guides/`, si existe en tu instalación.
- Carpeta `templates/`.

No publiques credenciales, claves de cron, dumps de base de datos ni archivos de configuración privados en un repositorio.

## 3. Acceso al panel

Abre el AdminCP de tu instalación, normalmente:

```text
https://tu-dominio.example/admincp/
```

Los nombres de los módulos utilizados en estas guías son los que existen en `admincp/modules/`. Si tu instalación usa un menú personalizado, busca el módulo por su nombre equivalente.

## 4. Verificación posterior

Después de cada cambio:

1. Abre la página pública afectada.
2. Comprueba que no aparezca un error de PHP.
3. Revisa la vista móvil.
4. Si algo falla, restaura el respaldo y documenta el cambio que lo provocó.
