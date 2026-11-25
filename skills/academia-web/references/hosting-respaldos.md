# Hosting, DNS, correo y respaldos

Guía de operación para un hosting compartido con cPanel. El dueño debe conocer **dónde** está cada cosa (registrador del dominio,
hosting, correo) y tener acceso propio a cada una. Claude trabaja con la sesión que el usuario abre; las contraseñas y códigos los escribe él.

## 1. Buenas prácticas en cPanel

- **Inventario** en un documento privado del dueño: proveedor del dominio y del hosting, fechas de renovación, qué subdominios existen y para qué,
  qué bases de datos hay y a qué aplicación pertenecen. Sin contraseñas en ese documento.
- Renovación automática del dominio y del hosting, con una tarjeta vigente y un correo de aviso que el dueño lea. Un dominio vencido tumba web, campus y correo a la vez.
- Una carpeta por aplicación: WordPress en `public_html`, Moodle en su subdominio, `moodledata` y respaldos **fuera** de `public_html`.
- Versión de PHP por carpeta o subdominio (Selector de PHP / MultiPHP): la más reciente que soporten WordPress, Moodle y sus plugins. Pruébala primero en un subdominio de pruebas si existe.
- Vigila el uso de disco, inodos (número de archivos) y memoria en la barra de estadísticas. Llegar al límite rompe subidas, correo y respaldos.
- Usa SFTP o el Administrador de archivos; evita FTP sin cifrar. Borra cuentas FTP que ya no se usan.
- Un **sitio de pruebas** (subdominio con contraseña de directorio y `noindex`) es el lugar para ensayar actualizaciones grandes.

## 2. Certificados SSL

- La mayoría de hostings con cPanel emiten certificados gratuitos automáticos (AutoSSL). Comprueba en "SSL/TLS Status" que **cada** dominio y subdominio tenga uno válido.
- La renovación es automática, pero falla si el DNS de un subdominio apunta a otro servidor o si un `.htaccess` bloquea la ruta de validación (`/.well-known/`). No bloquees esa ruta.
- Revisa la fecha de vencimiento una vez al mes o con un monitor (sección 9). Un subdominio sin certificado rompe HSTS con `includeSubDomains` (ver `seguridad.md`).
- Si se agrega un subdominio nuevo, ejecuta AutoSSL o espera su ciclo y verifica el candado antes de anunciarlo.

## 3. DNS y subdominios

- El dueño debe saber **dónde se administra el DNS**: en el registrador, en el hosting o en una CDN. Solo uno manda; cambiar en el lugar equivocado no hace nada.
- Registros habituales: `A` (dominio → IP del servidor), `CNAME` (subdominio → otro nombre), `MX` (correo), `TXT` (SPF, DKIM, DMARC, verificaciones).
- Para crear un subdominio de sección: créalo en cPanel (Dominios), apunta su carpeta a la misma raíz de la SPA si así lo pide la arquitectura,
  confirma que el DNS resuelve y que tiene certificado.
- Antes de editar el DNS, **copia la zona completa** (exportar o captura de texto) a un respaldo con fecha. Un registro borrado por error puede dejar sin correo.
- Los cambios tardan en propagarse (minutos a horas, según el TTL). Si se planea una migración, baja el TTL un día antes.
- Activa el bloqueo de transferencia del dominio y la privacidad de datos del titular en el registrador.

## 4. Correo: que llegue y que no lo suplanten

Tres registros TXT en el DNS del dominio:

- **SPF:** lista qué servidores pueden enviar correo en nombre del dominio. Debe haber **un solo** registro SPF; si se usan varios servicios (hosting, correo corporativo, envíos masivos), se combinan en uno.
- **DKIM:** firma cada correo con una clave; el servidor de correo o el proveedor da el registro público a publicar. En cPanel está en "Entregabilidad de correo".
- **DMARC:** dice qué hacer con el correo que falla SPF/DKIM y a dónde enviar informes. Empieza con `p=none` y un buzón de informes, revisa unas semanas, y luego sube a `quarantine` y después a `reject`.

Además:

- WordPress y Moodle deben enviar por **SMTP autenticado** con una cuenta del propio dominio, no con la función de correo genérica del servidor. Mejora mucho la entrega.
- El remitente (`From`) siempre del propio dominio (`no-responder@ejemplo.com`), alineado con SPF o DKIM.
- Los grandes proveedores exigen además DNS inverso válido, cifrado TLS y una tasa de quejas por spam muy baja. Para boletines masivos, enlace para darse de baja en un clic.
- Revisa en cPanel "Entregabilidad de correo": marca en rojo lo que falta.
- Prueba enviando un correo de formulario a una cuenta externa y mira los encabezados: SPF, DKIM y DMARC deben decir `pass`.

## 5. Respaldos 3-2-1

- **3** copias de los datos (la del servidor y dos más), en **2** soportes distintos, con **1** fuera del hosting.
- Qué respaldar: archivos de WordPress (sobre todo `wp-content`), base de datos de WordPress, código de Moodle, `moodledata`, base de datos de Moodle,
  la zona DNS exportada y la configuración del correo. Un respaldo sin base de datos no sirve.
- Frecuencia según cuánto se puede perder: base de datos diaria (en época de matrículas, más), archivos semanal, copia completa antes de cada actualización grande.
- Conserva varias generaciones (por ejemplo, 7 diarias, 4 semanales, 6 mensuales) para poder volver a un punto **anterior** a un problema que tardó en notarse.
- Copias fuera del hosting: el dueño las descarga a un almacenamiento suyo o se envían a un servicio externo con cuenta del dueño. Cifradas si contienen datos de estudiantes.
- El respaldo propio del hosting es útil, pero **no cuenta** como copia externa: si se pierde la cuenta, se pierde con ella.
- Antes de un cambio puntual, el respaldo de la skill (regla 2) sigue aplicando; los de esta sección son además, no en lugar de.

## 6. Simulacros de restauración

Un respaldo que nunca se ha restaurado es una esperanza, no un respaldo.

1. Cada trimestre, elige un respaldo al azar.
2. Restáuralo en el sitio de pruebas, nunca encima del sitio real.
3. Comprueba: la portada carga, se puede iniciar sesión, un curso de Moodle abre con sus archivos y el último formulario recibido está.
4. Anota cuánto tardó y qué faltó. Corrige el procedimiento.
5. Borra la restauración de prueba cuando termines (contiene datos reales).

Ten escrito el **procedimiento de restauración** en lenguaje sencillo, para que otra persona pueda hacerlo si el responsable no está.

## 7. Limpieza de archivos viejos

- Una vez al trimestre, lista lo que hay en la cuenta y pregunta por lo que no se reconozca. Candidatos: respaldos vencidos, instalaciones de prueba,
  `.zip` y `.sql` olvidados, carpetas `old`, `copia`, `backup`, plugins borrados que dejaron carpetas, registros de errores enormes.
- Nunca borres sin permiso del dueño (regla 6 de la skill). Primero mueve a una carpeta de cuarentena fuera de `public_html`, espera unos días y luego borra.
- Bases de datos huérfanas (de una instalación que ya no existe): exporta una copia y bórrala con permiso.
- Vacía la caché de Moodle y de WordPress desde sus paneles, no borrando carpetas a mano, salvo que la guía del plugin lo indique.

## 8. Tareas programadas (cron)

- **Moodle necesita su cron** para enviar correos, calificar, limpiar y más. Debe ejecutarse cada minuto (o lo más frecuente que permita el hosting)
  con el PHP de línea de comandos: `php /ruta/a/moodle/admin/cli/cron.php`. Revisa en Administración del sitio > Notificaciones que no avise de cron atrasado.
- **WordPress** ejecuta sus tareas solo cuando alguien visita el sitio. En sitios con pocas visitas conviene desactivar eso
  (`define('DISABLE_WP_CRON', true);`) y crear en cPanel un cron real cada 5–15 minutos que llame a `wp-cron.php`.
- La tarea de retención de datos (`templates/mu-plugin-retencion.php`) depende de que este cron funcione.
- Añade al final de cada línea de cron una redirección de salida a un archivo de registro fuera de `public_html`, o un correo de aviso, para enterarte si falla. El cron de Moodle, que corre cada minuto, es la excepción: descarta su salida y vigila *Notificaciones* (ver `moodle.md`).
- Nunca pongas contraseñas en la línea del cron.

## 9. Monitoreo de disponibilidad

- Un servicio externo de monitoreo (hay planes gratuitos) que visite cada pocos minutos la portada, un subdominio de sección y el inicio de sesión del campus.
- Que compruebe una palabra que deba aparecer en la página, no solo que responda: una página de error del hosting también responde.
- Alertas al correo y al celular del dueño (y de un suplente). Un correo de alerta del propio dominio no llega si el dominio está caído: usa otra cuenta.
- Monitorea también el vencimiento del certificado y del dominio.
- Cuando llegue una alerta: revisa si es solo tu red, mira el estado del hosting, revisa el registro de errores en cPanel y, si hubo un cambio reciente, considera volver al respaldo.

## 10. Rutina sugerida

| Frecuencia | Tarea |
|---|---|
| Semanal | Revisar alertas, actualizaciones pendientes y formularios recibidos |
| Mensual | Certificados, entregabilidad de correo, espacio en disco, Search Console, registros de errores |
| Trimestral | Simulacro de restauración, limpieza de archivos, revisión de cuentas y administradores |
| Anual | Renovaciones, revisión de la política de privacidad y del inventario |

## Fuentes

Consultadas como referencia; el texto es propio.

- https://support.google.com/a/answer/81126
- https://developer.wordpress.org/advanced-administration/security/hardening/
- https://github.com/addyosmani/web-quality-skills (MIT)
