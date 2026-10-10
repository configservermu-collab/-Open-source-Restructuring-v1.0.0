# WebEngine CMS

WebEngine CMS is an open-source PHP website and account-management platform
for MU Online communities. This repository contains the public application
source, installer, templates, modules, and database scripts.

## Requirements

- PHP 8.2 or newer
- A web server with URL rewriting support
- SQL Server with the PHP PDO SQL Server drivers
- The PHP extensions checked by the installer, including OpenSSL, cURL, GD,
  XML, JSON, PDO, and the SQL Server drivers required by your environment

The installer reports the available extensions and the PHP version before
continuing. Review its checks and your web server configuration before
deploying.

## Getting started

1. Download or clone this repository into the document root of a local web
   server.
2. Create a database and load the schema files from `install/sql/` that match
   the features you intend to use.
3. Configure the application using your local deployment settings. Do not
   place credentials in the repository.
4. Open the installer through your local web server and follow its steps.
5. Restrict or remove installer access after setup, and keep configuration
   files and database backups outside publicly served directories.

The installer generates a random cron API key and protects its state-changing
steps with session-bound CSRF tokens. Keep that key in a protected scheduler
configuration; do not publish it in URLs, logs, screenshots, or documentation.

This project does not include production credentials, private deployment
paths, or payment-provider secrets. Payment and other external integrations
must be configured independently and securely.

Payment debug logs, when enabled by the integration code, are written to the
system temporary directory rather than the public `api/` tree. Review the
server's filesystem and log-retention policy before enabling diagnostics.

## Project layout

- `install/` — installer flow and SQL schema files
- `admincp/` — administration area
- `api/` — application integration endpoints
- `includes/` — shared application code and configuration structure
- `modules/` — public and account modules
- `templates/` — presentation templates

## Módulos incluidos

Esta distribución incluye los siguientes módulos funcionales para una comunidad
de MU Online:

- **Información y contenido:** página de información del servidor (`info`),
  noticias, contacto, descargas, privacidad, reembolsos y términos de servicio.
- **Guías:** consulta pública de guías y administración de guías y sus
  traducciones desde el AdminCP.
- **Cuentas y perfiles:** registro, inicio y cierre de sesión, recuperación de
  contraseña, cuentas múltiples, perfil de jugador, perfil de guild y
  configuración de la cuenta.
- **Rankings:** rankings general, level, resets, killers/PK, guilds, grand
  resets, online, gens, master, Blood Castle, Devil Square, Chaos Castle,
  duelos e Illusion Temple.
- **Donaciones y pagos:** Mercado Pago, PayPal y transferencia bancaria, con
  historial de operaciones y configuración independiente para cada proveedor.
- **Sistemas del juego:** Castle Siege, intercambio de resets, compra de Zen,
  códigos de regalo, sistema VIP, referidos, tickets y jugadores online.
- **AdminCP:** panel de administración de cuentas, personajes, bloqueos,
  registros de pagos, configuración del sitio, editor de información,
  administración de guías, selector de templates, widgets y gestión de
  módulos.
- **Diseño y presentación:** template `default`, navegación, información del
  servidor, tarjetas informativas, rankings, redes sociales y herramientas de
  personalización del diseño desde el AdminCP.

Los módulos de pago requieren configuración del proveedor y credenciales
propias del despliegue. No se incluyen credenciales ni datos privados en este
repositorio.

## Aporte de ConfigServerMU

Esta copia incluye un aporte propio de ConfigServerMU, desarrollado y mantenido
por **Bruno Neira** durante aproximadamente un año. El trabajo comenzó como un
desarrollo individual y posteriormente continuó con apoyo de los agentes de IA
generados y coordinados mediante VideCode. Los agentes se utilizaron como apoyo
técnico; las decisiones de alcance, revisión y dirección del proyecto
corresponden a Bruno Neira.

### Cambios realizados

- Restauración del flujo de rankings a partir de la copia funcional de
  referencia, manteniendo el funcionamiento esperado del CMS.
- Conservación exclusiva de los rankings definidos para el proyecto:
  General, Level, Resets, Killers / PK, Guilds, Grand Resets, Online, Gens,
  Master, Blood Castle, Devil Square, Chaos Castle, Duelos e Illusion Temple.
- Eliminación del módulo adicional `eventrankings` para evitar duplicados de
  rankings de eventos que ya están integrados en el CMS.
- Restauración del botón general para borrar caché en el Cache Manager y del
  método `clearAllCacheData()`, conservando los controles existentes del
  AdminCP.
- Retiro de inicializadores automáticos de caché que podían crear archivos
  vacíos o mostrar rankings sin jugadores. Las cachés de rankings deben ser
  actualizadas por el cron configurado para el servidor.
- Correcciones de compatibilidad para lecturas de fechas y timestamps usados
  por los rankings, sin ejecutar cron, instaladores ni conexiones reales durante
  la validación local.
- Incorporación de los siete enlaces sociales configurables:
  Facebook, Instagram, Discord, YouTube, Twitter / X, TikTok y WhatsApp.
  Cada enlace se muestra en el template default solo cuando tiene una URL
  configurada; los campos vacíos permanecen ocultos.
- Aclaración en el AdminCP sobre el comportamiento de los campos sociales y
  validación de sus URLs antes de guardar la configuración.
- Conservación de la estructura procedural existente de WebEngine, de sus
  módulos y de la compatibilidad con el entorno PHP soportado por el proyecto.

## Changelog completo del aporte

### [En desarrollo] — Aporte ConfigServerMU

#### Estabilización de rankings

- Se restauró el flujo de rankings desde la copia funcional de referencia.
- Se conservaron los rankings definidos para el proyecto: General, Level,
  Resets, Killers / PK, Guilds, Grand Resets, Online, Gens, Master, Blood
  Castle, Devil Square, Chaos Castle, Duelos e Illusion Temple.
- Se revisaron los módulos, menús y tareas cron relacionados con rankings.
- Se corrigieron lecturas de fechas y timestamps que podían provocar errores
  en versiones recientes de PHP.
- Se evitó que los rankings mostraran datos artificiales cuando la caché no
  estaba actualizada.
- Se retiraron componentes externos o duplicados que no forman parte del flujo
  integrado de rankings de esta CMS.

#### Caché y AdminCP

- Se recuperó el botón general para borrar todas las cachés desde el
  `Cache Manager`.
- Se restauró el método central `clearAllCacheData()`.
- Se conservaron las acciones de limpieza individual y general existentes.
- Se eliminaron inicializadores automáticos que podían crear archivos de caché
  vacíos y ocultar el problema real de actualización.
- Se mantuvo la actualización de rankings bajo el cron configurado por el
  servidor, sin inventar un proceso alternativo desde el panel.

#### Instalador y compatibilidad SQL

- Se confirmó la presencia de `CashShopData.sql` y `MasterSkillTree.sql`.
- Ambas tablas están incluidas en la lista del instalador y se crean cuando no
  existen en la base de datos seleccionada.
- Ambas tablas están protegidas contra borrado accidental durante la opción de
  reconstrucción forzada del instalador.
- Se mantuvieron sincronizados los scripts de instalación con la estructura
  utilizada por la CMS.
- No se ejecutaron scripts SQL ni se modificó una base de datos real durante
  este trabajo.

#### Template default y redes sociales

- Se agregaron los campos configurables para Facebook, Instagram, Discord,
  YouTube, Twitter / X, TikTok y WhatsApp.
- El footer del template default genera los enlaces de forma dinámica.
- Una red social no aparece cuando su campo está vacío.
- Las URLs se escapan antes de renderizarse y los enlaces externos usan
  `noopener noreferrer`.
- Se agregaron estilos flexibles para los iconos sociales.
- El AdminCP explica que dejar un campo vacío oculta esa red social.
- Los campos sociales aceptan URLs válidas y rechazan valores no válidos.

#### Seguridad y mantenimiento

- Se mantuvo la validación de la clave de la API de cron sin publicar ningún
  valor privado en el repositorio.
- Se preservaron las configuraciones privadas, credenciales, cachés, logs y
  datos de usuarios fuera de la documentación.
- Se mantuvo la arquitectura procedural existente, sin agregar frameworks ni
  dependencias innecesarias.
- Se documentaron los límites de las validaciones para no confundir análisis
  estático con una prueba de producción.

#### Documentación y verificación

- Se documentó el aporte de Bruno Neira y el apoyo posterior de agentes de IA
  generados y coordinados mediante VideCode.
- Se actualizaron las instrucciones visibles del AdminCP para los enlaces
  sociales.
- Se ejecutó el validador estático del repositorio.
- Se verificó la sintaxis PHP de los archivos modificados.
- Se validaron los manifiestos JSON y el formato del diff.
- Se verificó el mapa local de arquitectura sin extracción semántica externa.

## Capturas del proyecto

Las imágenes se enlazan mediante URLs directas de Imgur para que GitHub pueda
renderizarlas al publicar este README.

![Captura del proyecto 1](https://i.imgur.com/wyZg1KP.png)

![Captura del proyecto 2](https://i.imgur.com/GL2o7a6.png)

![Captura del proyecto 3](https://i.imgur.com/oMFlZJB.png)

![Captura del proyecto 4](https://i.imgur.com/81mcedE.png)

![Captura del proyecto 5](https://i.imgur.com/EvMlDDM.png)

### Validación y límites

Las comprobaciones realizadas son estáticas y no sustituyen una prueba de
integración. Se verificaron la sintaxis PHP y los manifiestos JSON modificados,
el formato del diff, el validador local del repositorio y el mapa AST local.
No se ejecutaron la aplicación, el instalador, los cron, endpoints de API,
callbacks de pagos ni conexiones a bases de datos reales.

Este aporte no incluye credenciales, claves de API, datos de usuarios,
configuraciones privadas ni secretos de despliegue. Antes de publicar o fusionar
cambios, se debe revisar el entorno de destino, actualizar las cachés mediante
el cron autorizado y comprobar los rankings en una base de pruebas.

## License

WebEngine CMS is distributed under the MIT License. See [LICENSE](LICENSE)
for the full license text.

## Contributing

Bug reports and pull requests are welcome. Please avoid including credentials,
personal data, private server addresses, database dumps, or other deployment
secrets in issues and proposed changes.
