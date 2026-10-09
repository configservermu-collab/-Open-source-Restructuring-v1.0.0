# Guía 10: configuración general de la CMS

La configuración general se administra desde **Website Settings**, implementado en `admincp/modules/website_settings.php`.

## Ajustes principales

Desde este módulo puedes revisar:

- Estado activo o mantenimiento del sitio.
- Template predeterminado.
- Nombre y título de la web.
- Descripción y palabras clave SEO.
- Enlace del foro.
- Tipo de archivos del servidor.
- Idioma predeterminado y selector de idioma.
- Perfiles de jugadores y guilds.
- Longitud mínima y máxima de usuario y contraseña.
- Sistema de plugins.
- Bloqueo de IP.
- Configuración del cron.
- Enlaces de redes sociales.
- Información pública del servidor.

## Redes sociales

Puedes agregar Facebook, Instagram, Discord, YouTube, Twitter/X, TikTok y WhatsApp. Si dejas un campo vacío, el template no muestra esa red social.

1. Escribe una URL válida.
2. Guarda la configuración.
3. Abre el footer del sitio.
4. Prueba cada enlace en una pestaña nueva.

## Cron y claves

Si activas el cron, utiliza una clave larga y privada. No la escribas en una guía, captura, issue o commit. La CMS valida que la clave tenga la longitud mínima cuando el cron está habilitado.

## Lista final de comprobación

1. Guarda una copia de la configuración anterior.
2. Cambia una sección por vez.
3. Abre la página pública después de cada guardado.
4. Revisa el log del servidor solo en tu entorno autorizado.
5. No ejecutes cron, instaladores ni callbacks de pago como prueba manual en producción.
