# WordPress: guía práctica para mantener el sitio

Complementa a `arquitectura.md` (dónde vive cada pieza) y a las reglas de oro de `SKILL.md`.
Aquí está el **cómo** del día a día en WordPress: qué tocar, qué no y en qué orden.

## 1. Tema de bloques o tema clásico

| | Tema de bloques | Tema clásico |
|---|---|---|
| Plantillas | Archivos HTML hechos de bloques | Archivos PHP |
| Dónde se edita | Editor del sitio (Apariencia > Editor) y `theme.json` | Personalizador, opciones del tema, widgets |
| Riesgo al editar | Bajo: todo es contenido guardado en la base de datos | Medio: un error de PHP puede tumbar el sitio |

Reglas:
- Antes de cambiar nada, averigua cuál usa el sitio (Apariencia > Temas; si aparece "Editor", es de bloques).
- No cambies de tema para "arreglar" algo puntual. Cambiar de tema reordena menús, widgets y estilos.
- Si hay que tocar código del tema, hazlo en un **tema hijo**; las actualizaciones del tema padre borran lo editado en él.
- En un tema de bloques, los cambios del Editor del sitio quedan en la base de datos y **ganan** sobre los archivos del tema.
  Si un archivo no parece tener efecto, busca primero una versión personalizada en el editor.

## 2. Patrones sincronizados (antes "bloques reutilizables")

- Desde WordPress 6.3 los bloques reutilizables se llaman **patrones**. Hay dos tipos:
  - **Sincronizado:** una sola copia; cambiarla cambia todas las páginas donde aparece. Es lo que usa la SPA de la academia.
  - **No sincronizado:** una plantilla que se copia al insertarla; editar el original no afecta a lo ya insertado.
- Se gestionan en Apariencia > Editor > Patrones (o en "Gestionar patrones" desde el menú de tres puntos del editor).
- Por dentro, un patrón sincronizado es una entrada del tipo `wp_block`; por eso se edita en `/wp/v2/blocks/<id>`.
- **Borrar** un patrón sincronizado lo quita de todas las páginas, que quedan con un aviso de bloque no disponible. Nunca lo borres; si sobra, déjalo sin usar y avisa.
- **Desvincular** ("Detach") en una página rompe la sincronía solo ahí. Útil para una excepción; peligroso si se hace por error.
- Para cambios grandes: duplica el patrón, trabaja en la copia, prueba en una página de borrador y solo entonces cambia la referencia.

## 3. Editar por la API REST desde la sesión del administrador

La API acepta la cookie de sesión del panel, **pero solo si la petición lleva un nonce** de la acción `wp_rest`.
Sin nonce, WordPress trata la petición como anónima aunque el usuario haya iniciado sesión.

Lo más simple: ejecutar el código en una pestaña del editor de bloques, donde `wp.apiFetch` ya añade el nonce.

```js
// Leer el contenido "crudo" (sin procesar) de un patrón sincronizado
const p = await wp.apiFetch({ path: '/wp/v2/blocks/123?context=edit' });
const anterior = p.content.raw;

// Respaldo: copia como borrador antes de tocar nada
await wp.apiFetch({ path: '/wp/v2/blocks', method: 'POST',
  data: { title: 'respaldo-2026-01-15 ' + p.title.raw, content: anterior, status: 'draft' } });

// Guardar el cambio (reemplazo exacto, nunca "a ojo")
const nuevo = anterior.replace('Matrícula abierta', 'Matrícula abierta hasta el 30 de enero');
if (nuevo === anterior) throw new Error('No se encontró el texto a reemplazar');
await wp.apiFetch({ path: '/wp/v2/blocks/123', method: 'POST', data: { content: nuevo } });
```

Reglas:
- Usa siempre `context=edit` y el campo `raw`. El campo `rendered` es HTML ya procesado; guardarlo de vuelta daña el bloque.
- Comprueba que el reemplazo ocurrió exactamente una vez antes de guardar.
- Después de guardar, vuelve a leer y compara longitud o un fragmento clave; luego limpia la caché y mira la página publicada.
- Las **contraseñas de aplicación** (Usuarios > Perfil) permiten acceso remoto por HTTPS. No las crees ni las uses
  sin que el dueño lo pida; si se crean, que sea con nombre claro y se revoquen al terminar.
- Nunca pegues nonces, cookies ni contraseñas de aplicación en chats, documentos ni repositorios.

## 4. mu-plugins o plugins normales

| Úsalo para… | mu-plugin | Plugin normal |
|---|---|---|
| Lógica propia que no debe apagarse (formularios, retención, ajustes de privacidad) | Sí | No |
| Funciones de terceros con actualizaciones | No | Sí |
| Algo que el dueño debe poder activar y desactivar | No | Sí |

Lo que hay que saber de los mu-plugins:
- Se cargan solos, antes que los plugins normales y en orden alfabético. No aparecen en la lista de plugins con botón de desactivar.
- Solo se cargan los `.php` sueltos en `wp-content/mu-plugins/`. Un plugin en subcarpeta necesita un archivo cargador en la raíz.
- No reciben avisos de actualización ni ejecutan ganchos de activación o desinstalación. Cualquier preparación (tablas, opciones) debe comprobarse en tiempo de ejecución.
- Un error de sintaxis en un mu-plugin rompe **todo** el sitio, panel incluido. Valida el PHP antes de subirlo y ten a mano cómo retirarlo por el administrador de archivos del hosting.
- Cada archivo con cabecera: nombre, descripción, versión y fecha. Que el nombre diga qué hace (`academia-formularios.php`).
- Protege el archivo contra acceso directo y usa nonces y comprobación de permisos en todo lo que reciba datos.

## 5. Elegir plugins: pocos y cuidados

Antes de instalar, comprueba en la ficha de wordpress.org:
- Actualizado en los últimos meses y probado con la versión actual de WordPress.
- Instalaciones activas razonables y foro de soporte con respuestas.
- Hace **una** cosa que de verdad se necesita y que no resuelve ya el núcleo ni otro plugin instalado.
- Descárgalo solo desde el directorio oficial o del sitio del autor. Nunca versiones "premium gratis".

Reglas:
- Pregunta al dueño antes de instalar o desinstalar. Explica qué hace y qué riesgo tiene.
- Lista orientativa para una academia: caché, formularios, antispam, copias de seguridad, 2FA. Poco más.
- Plugin desactivado que no se va a usar: se elimina (con permiso). Desactivado sigue siendo código en el servidor.
- Revisa cada trimestre la lista: ¿sigue mantenido?, ¿se sigue usando?

## 6. Actualizar sin romper

Orden recomendado, una vez por semana o al salir un aviso de seguridad:
1. Copia de seguridad completa (archivos + base de datos) y anota dónde quedó.
2. Lee el registro de cambios de lo que se va a actualizar; atención a versiones mayores.
3. Si hay staging, actualiza allí primero (ver sección 11).
4. Actualiza de uno en uno: plugins, luego tema, luego núcleo. Así sabes qué causó un fallo.
5. Tras cada paso: abre la portada, una página de programa, envía el formulario de prueba y entra al panel.
6. Limpia la caché y repite la revisión en celular.

- Las actualizaciones menores (de seguridad) del núcleo pueden ir automáticas; las mayores, a mano y con respaldo.
- Si algo falla: desactiva el último plugin actualizado. Si el panel no abre, renombra su carpeta desde el administrador de archivos del hosting y avisa.
- Mantén una versión de PHP con soporte vigente; cambiarla en el panel del hosting es una actualización más (respaldo y revisión).

## 7. Endurecimiento (hardening)

Cuentas y roles:
- Un solo administrador por persona real; nada de cuentas compartidas ni usuario llamado "admin".
- Cada quien con el rol mínimo: Editor para contenido, Autor o Colaborador para quien solo redacta.
- Revisa la lista de usuarios cada trimestre y pide permiso para degradar o quitar cuentas que sobran.
- **2FA** para todos los administradores. El dueño configura su app de códigos; Claude no ve ni escribe los códigos.

Configuración (en `wp-config.php`, con respaldo previo y permiso):
- `define( 'DISALLOW_FILE_EDIT', true );` quita el editor de archivos del panel. Así un acceso robado no puede escribir PHP desde ahí.
- `WP_DEBUG` apagado en producción; los errores no deben mostrarse a los visitantes.
- Las claves y "salts" deben existir y ser únicas; si hubo un incidente, se regeneran (eso cierra todas las sesiones).

Acceso:
- HTTPS en todo el sitio y en el panel.
- XML-RPC: si nada lo usa (app móvil, publicación remota), desactívalo con el plugin de seguridad o una regla del servidor. Pregunta antes, porque algunos servicios dependen de él.
- Límite de intentos de inicio de sesión, por el plugin de seguridad o por el propio hosting.
- SFTP en vez de FTP; base de datos con un usuario que solo tenga permisos sobre su propia base.
- Contraseñas largas y únicas, guardadas en un gestor. Las escribe siempre el dueño.

## 8. Rendimiento y caché

- Un solo plugin de caché de páginas. Dos a la vez se pisan y causan fallos difíciles de rastrear.
- Excluye de la caché: el panel, páginas con formularios que usan nonce, y zonas con sesión iniciada.
- Imágenes: súbelas ya del tamaño en que se muestran, en WebP o JPG comprimido, con texto alternativo.
- Carga diferida de imágenes y vídeos (el núcleo ya lo hace en la mayoría de casos); vídeos alojados fuera, insertados.
- PHP actual y OPcache (casi siempre ya activo en el hosting) mejoran todo sin tocar el sitio.
- Si el sitio se vuelve lento, desactiva plugins de uno en uno en staging para encontrar el culpable.
- Mide antes y después con la misma herramienta y la misma página; un cambio sin medición no es una mejora.

## 9. Formularios y spam

- Pide solo los campos que hacen falta (ver `privacidad.md`) y enlaza la política de datos junto al botón.
- Capas antispam, de menor a mayor fricción: campo trampa oculto, tiempo mínimo de llenado, servicio antispam, desafío visual.
- Valida y limpia en el servidor todo lo que llegue (`sanitize_*` al guardar, `esc_*` al mostrar) y comprueba el nonce.
- Correo saliente por SMTP autenticado del dominio; el correo de PHP sin autenticar suele acabar en spam.
- Configura SPF, DKIM y DMARC del dominio en el panel DNS del hosting (con el dueño).
- Prueba cada formulario de punta a punta tras cualquier cambio: envío, mensaje de éxito, correo recibido y registro guardado.
- Que el formulario cumpla el plazo de conservación prometido (la tarea de retención de `templates/`).

## 10. Copias de seguridad y prueba de restauración

- Qué copiar: base de datos **y** archivos (`wp-content`, `wp-config.php`, `.htaccess`). Una sin la otra no sirve.
- Frecuencia: semanal para un sitio tranquilo, diaria en temporada de matrículas, y siempre antes de actualizar.
- Dónde: al menos tres copias recientes, en dos lugares distintos (hosting y una nube externa). Ninguna dentro de `public_html`.
- Las copias contienen datos personales: acceso restringido y, si es posible, cifradas.
- **Una copia que nunca se restauró no es una copia.** Cada trimestre, restaura la última en staging o en un subdominio de prueba:
  archivos primero, luego base de datos; ajusta la dirección del sitio; comprueba portada, panel y formularios.
- Anota fecha, copia usada, tiempo que tomó y problemas. Esa nota es el plan de emergencia.

## 11. Staging (sitio de pruebas)

- Muchos cPanel ofrecen clonado (por ejemplo, desde el instalador de aplicaciones). Si no, un subdominio como `pruebas.ejemplo.com` con una copia.
- El staging debe estar **protegido** (contraseña del servidor o acceso solo para administradores) y fuera de buscadores.
- Desactiva en staging los correos salientes y las tareas programadas que contactan a estudiantes.
- Si el staging usa datos reales, trátalo como producción en seguridad y bórralo cuando no se use.
- No copies staging sobre producción a ciegas: en producción hay inscripciones nuevas. Lleva los cambios a mano o solo la parte probada.

## 12. Errores comunes

- Editar un patrón sincronizado pensando que solo cambia una página.
- Guardar el campo `rendered` en vez de `raw` por la API.
- Instalar un plugin para cada pequeña cosa; acabar con 40 y no saber cuál rompe qué.
- Actualizar todo de golpe un viernes por la tarde sin copia de seguridad.
- Dejar plugins desactivados "por si acaso" o archivos de prueba en el servidor.
- Dos plugins de caché, o caché activa en páginas con formularios.
- Editar el tema padre y perder el cambio con la siguiente actualización.
- Dar rol de administrador a quien solo publica noticias.
- Creer que la copia funciona sin haberla restaurado nunca.
- Dar por terminado un cambio sin verlo en vivo, sin caché y en celular.

## Fuentes

Consultadas como referencia; el texto y los ejemplos de esta guía son propios.

- https://developer.wordpress.org/themes/getting-started/what-is-a-theme/
- https://wordpress.org/documentation/article/reusable-blocks/
- https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
- https://developer.wordpress.org/advanced-administration/plugins/mu-plugins/
- https://developer.wordpress.org/advanced-administration/security/hardening/
- https://developer.wordpress.org/advanced-administration/security/backup/
- https://developer.wordpress.org/advanced-administration/performance/optimization/
- https://github.com/WordPress/agent-skills (GPL-2.0-or-later): solo como referencia; no se copió ni se descargó nada.
- https://github.com/respira-press/agent-skills-wordpress (MIT): solo como referencia; no se copió ni se descargó nada.
- https://github.com/Jeffallan/claude-skills (MIT): solo como referencia; no se copió ni se descargó nada.
