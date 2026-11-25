# Seguridad (endurecimiento defensivo)

Esta guía es solo defensiva: cómo cerrar puertas en el propio sitio. No describe ataques ni cómo probarlos contra terceros.
Complementa `privacidad.md` (qué datos se guardan y qué nunca se publica); aquí está el **cómo** proteger el sitio.

Antes de cualquier ajuste de esta lista: respaldo, cambio mínimo, verificación en vivo y, si toca algo de seguridad, **avisar al dueño
qué se cambia y qué efecto tiene** (por ejemplo, que alguien deberá activar 2FA). Desactivar una protección siempre requiere su permiso explícito.

## 1. HTTPS y HSTS

- Todo el sitio, los subdominios y el campus por `https://`. En WordPress y en Moodle (`$CFG->wwwroot`) la dirección base empieza por `https://`.
- Una sola redirección de `http` a `https`, hecha en el servidor (`.htaccess` o la opción de cPanel), no en JavaScript.
- Revisa que no haya "contenido mixto": imágenes o scripts que aún se cargan por `http`.
- **HSTS** le dice al navegador que use siempre HTTPS. Empieza con un `max-age` corto (un día), comprueba que todo funciona, y luego súbelo a un año.
  Añade `includeSubDomains` solo cuando **todos** los subdominios tengan certificado válido; si uno falla, quedará inaccesible hasta que venza el tiempo.
  No pidas la lista de precarga (`preload`) sin acuerdo del dueño: es difícil de revertir.

## 2. Cabeceras de seguridad

Se ponen en el `.htaccess` de la raíz (Apache, lo habitual en cPanel) o con un mu-plugin. Valores de partida razonables:

| Cabecera | Valor sugerido | Para qué |
|---|---|---|
| `Strict-Transport-Security` | `max-age=86400` al inicio; luego `max-age=31536000` y, si todos los subdominios tienen certificado, `includeSubDomains` | Forzar HTTPS (ver arriba) |
| `X-Content-Type-Options` | `nosniff` | Que el navegador no adivine tipos de archivo |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | No filtrar rutas completas a otros sitios |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=()` | Apagar funciones que el sitio no usa |
| `Content-Security-Policy` | `frame-ancestors 'self'` para empezar | Impedir que otros sitios muestren el nuestro en un marco |

- Una CSP completa (qué scripts pueden ejecutarse) es lo más protector y lo más fácil de romper: la SPA, el tema y los plugins usan scripts en línea.
  Pruébala primero en modo `Content-Security-Policy-Report-Only` y revisa la consola antes de aplicarla.
- Quita o neutraliza cabeceras que anuncian versiones (`X-Powered-By`). No uses `X-XSS-Protection`: está obsoleta.
- Comprueba el resultado mirando las cabeceras de la respuesta en las herramientas del navegador, en la web, en un subdominio y en el campus.

## 3. Cuentas y privilegios mínimos

- Cada persona con **su propia cuenta**; nada de cuentas compartidas ni de "admin" genérico.
- El rol más bajo que sirva: quien solo publica noticias es Editor o Autor, no Administrador. En Moodle, el profesor tiene rol de profesor en su curso, no de gestor.
- Pocos administradores (dos es buen número: el dueño y un suplente). Revisa la lista cada trimestre y da de baja a quien ya no trabaja con la academia.
- La cuenta de la base de datos de WordPress y la de Moodle, separadas, cada una con acceso solo a su base.
- Las cuentas de cPanel, FTP y correo, con contraseñas distintas entre sí y respecto a WordPress y Moodle.
- Las contraseñas las crea y guarda el usuario en un gestor de contraseñas. Claude no las escribe ni las pide (regla 5 de la skill).

## 4. Verificación en dos pasos (2FA)

- Obligatoria para administradores de WordPress, administradores de Moodle, cPanel, el registrador del dominio y el correo del dueño.
- En WordPress se añade con un plugin de 2FA de buena reputación; en Moodle (desde la 4.3) viene integrada: Administración del sitio > Plugins > Herramientas de administración > Autenticación multifactor.
- Pide al dueño que guarde los **códigos de recuperación** fuera del servidor y del correo del propio sitio.
- La activación la hace cada persona en su cuenta; Claude puede guiar los pasos pero no escanea códigos ni escribe códigos de verificación.

## 5. Actualizaciones

- Núcleo, tema y plugins de WordPress, y Moodle, en versiones con soporte. Una versión sin soporte ya no recibe parches.
- Actualizaciones menores de WordPress automáticas; mayores y de Moodle, **con respaldo previo** y revisión en vivo después.
- Solo plugins del directorio oficial o de autores conocidos. Nada de copias "premium gratis".
- Borra (no solo desactives) plugins y temas que no se usan. Deja un tema por defecto de reserva.
- Revisa en Moodle el **informe de seguridad** (Administración del sitio > Informes > Resumen de seguridad) y atiende lo marcado como crítico.

## 6. Secretos fuera de lo público

- `wp-config.php` y `config.php` de Moodle contienen las claves de base de datos: nunca se copian a chats, repositorios ni documentos.
- La carpeta de datos de Moodle (`moodledata`) **fuera** de `public_html`.
- Claves de API (correo, pagos, formularios) en `wp-config.php` como constantes o en un archivo fuera de `public_html`, nunca en el bloque de la SPA:
  todo lo que está en el bloque lo puede leer cualquier visitante, aunque vaya en base64.
- Nada de archivos `.zip`, `.sql`, `.bak` ni copias de configuración dentro de `public_html`.
- Si una clave se expuso, se **cambia** (no basta con borrar el archivo).

## 7. Permisos de archivos

- Carpetas `755`, archivos `644`. Nunca `777`.
- `wp-config.php` en `440` o `400` si el hosting lo permite (pruébalo: algunos hostings necesitan `640`).
- En `wp-config.php`, `define('DISALLOW_FILE_EDIT', true);` quita el editor de código del panel. Los cambios de código se hacen con respaldo y por el método de la skill.
- En la carpeta de subidas (`wp-content/uploads`) no deben ejecutarse archivos PHP; bloquéalo con un `.htaccess` dentro de esa carpeta.

## 8. Proteger las zonas de administración

- Limita los intentos de inicio de sesión (plugin de seguridad o función del hosting) en WordPress, Moodle y cPanel.
- Si el dueño y su equipo entran siempre desde las mismas redes, se puede restringir `wp-admin` por IP; si no, basta con 2FA y límite de intentos.
- Desactiva XML-RPC si nada lo usa (aplicación móvil de WordPress, Jetpack); pregunta antes.
- No publiques en la web enlaces al panel ni la dirección de cPanel.
- Las zonas restringidas (biblioteca, campus) deben comprobar la sesión **en el servidor**. Ocultar un botón no es proteger.

## 9. Formularios y anti-spam

- **Valida en el servidor**, siempre. La validación del navegador ayuda al usuario, pero se puede saltar.
- Para cada campo: tipo esperado, largo máximo y formato (correo, teléfono). Rechaza lo que no encaja con un mensaje claro.
- Limpia la entrada y escapa la salida con las funciones del sistema: en WordPress `sanitize_text_field`, `sanitize_email`, `esc_html`, `esc_attr`, `esc_url`;
  consultas con `$wpdb->prepare`; en Moodle, `required_param`/`optional_param` con su tipo `PARAM_*` y `s()`/`format_string()` al mostrar.
- Protege cada envío con un nonce (`wp_nonce_field` / `wp_verify_nonce`) para que solo cuente el formulario del propio sitio.
- Anti-spam por capas: campo trampa invisible, tiempo mínimo de llenado, límite de envíos por IP y, si sigue llegando spam, un CAPTCHA respetuoso con la privacidad.
- Adjuntos: solo los tipos necesarios (PDF, JPG), tamaño máximo y guardado fuera de `public_html` o con acceso controlado.
- El correo de aviso al equipo no debe repetir datos sensibles; mejor un enlace al panel.

## 10. Registros (logs)

- Registra: inicios de sesión correctos y fallidos, cambios de roles, altas y bajas de usuarios, cambios de plugins y temas, y exportaciones de datos.
- **Nunca** registres contraseñas, tokens, claves ni el contenido completo de formularios con datos personales.
- Guarda los registros fuera de `public_html`, con acceso solo para administradores, y bórralos al cumplir el plazo que diga la política.
- En Moodle, los registros del sitio ya existen (Informes > Registros); configura su tiempo de conservación.
- Revisa los registros del servidor en cPanel (Errores, Visitantes) cuando algo falle o una vez al mes.

## 11. Si se sospecha una intrusión

Señales: páginas o usuarios que nadie creó, redirecciones a otros sitios, avisos del hosting o de Google, correos devueltos en masa, archivos PHP nuevos en `uploads`.

1. **Mantén la calma y no borres nada todavía.** Las pruebas sirven para entender qué pasó.
2. Avisa al dueño en lenguaje sencillo y, con su permiso, contacta al soporte del hosting.
3. Pon el sitio en mantenimiento si está mostrando contenido dañino.
4. Desde un equipo limpio, **cambia** contraseñas de cPanel, FTP, base de datos, administradores, correo y claves de API (lo hace el usuario). Cierra todas las sesiones y renueva las "salts" de `wp-config.php`.
5. Revisa la lista de administradores y quita los desconocidos.
6. Restaura desde un respaldo **anterior** a la intrusión (ver `hosting-respaldos.md`) o reinstala núcleo y plugins desde fuentes oficiales. Luego actualiza todo.
7. Busca la causa probable (plugin vulnerable, contraseña reutilizada) y ciérrala; si no, volverá a pasar.
8. Si hubo acceso a datos personales, el dueño debe revisar sus obligaciones legales de aviso a la autoridad y a los afectados.
9. Si Google marcó el sitio, pide la revisión en Search Console cuando esté limpio.
10. Escribe un resumen corto: qué pasó, qué se hizo y qué se cambia para que no se repita.

## Fuentes

Consultadas como referencia; el texto y los ejemplos son propios.

- https://top10.owasp.org/2025
- https://owasp.org/www-project-top-ten/
- https://cheatsheetseries.owasp.org/cheatsheets/HTTP_Headers_Cheat_Sheet.html
- https://cheatsheetseries.owasp.org/cheatsheets/Logging_Cheat_Sheet.html
- https://developer.wordpress.org/advanced-administration/security/hardening/
- https://moodledev.io/general/development/policies/security
- https://github.com/addyosmani/web-quality-skills (MIT)
