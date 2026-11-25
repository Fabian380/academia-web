# Campus Moodle: guía práctica

Complementa `arquitectura.md` (encabezado y pie compartidos) y `privacidad.md`. Aquí está lo que hay que saber
para montar y cuidar el campus sin romperlo. Las reglas de oro de `SKILL.md` se aplican igual: respaldo antes de tocar,
cambio mínimo, verificación en vivo y nada de contraseñas escritas por Claude.

Antes de cualquier cambio, mira la versión exacta en *Administración del sitio > Notificaciones*:
los menús y nombres cambian entre versiones, y la documentación oficial está separada por versión.

## 1. Estructura del sitio

- **Categorías** = cómo se organiza la oferta. Pocas y estables, por ejemplo: `Programas > Programa A`, `Programas > Programa B`, `Cursos cortos`, `Plantillas` (oculta), `Archivo` (oculta, para cursos terminados).
- **Cursos**: un curso por asignatura y por periodo. Nombre corto con patrón fijo (`PROGA-MAT1-2026A`) para que se pueda buscar, respaldar y sincronizar sin ambigüedad.
- **Curso plantilla**: un curso modelo con la estructura acordada (bienvenida, foro de avisos, unidades, evaluación final). Los cursos nuevos se crean importando la plantilla, no desde cero.
- **Cohortes** = grupos de personas a nivel de sitio o de categoría ("Programa A – 2026"). Sirven para matricular en bloque: se añade la persona a la cohorte una sola vez y entra en todos los cursos ligados.
- **Grupos** = divisiones dentro de un curso (sección mañana / tarde). No confundir con cohortes.
- Al cerrar un periodo: ocultar el curso, moverlo a `Archivo` y, si hace falta, reiniciarlo para reusarlo. Reiniciar borra participación: respaldo primero.

## 2. Roles y permisos (mínimo privilegio)

- Usa los **roles estándar** antes de inventar uno: Profesor, Profesor sin permiso de edición, Estudiante, Gestor.
- **Administrador del sitio**: una o dos personas como máximo. El dueño con su propia cuenta; nadie comparte cuentas.
- Para el personal administrativo, asigna **Gestor en la categoría** que le toca, no en todo el sitio.
- Un tutor que solo califica y responde foros: *Profesor sin permiso de edición*.
- Si hay que ajustar un permiso, crea un rol nuevo copiando uno existente y cambia solo esa capacidad. No edites los roles de fábrica: las actualizaciones asumen sus valores.
- Antes de dar un permiso, revísalo con *Comprobar permisos* sobre un usuario de prueba.
- Desactiva el autorregistro por correo si las matrículas vienen de la web (ver sección 3); evita cuentas basura.
- Revisa cada periodo la lista de administradores y gestores. Quien se fue, se suspende.

## 3. Matrícula e integración con la web de inscripciones

Métodos de matriculación más útiles para una academia:

| Método | Cuándo usarlo |
|---|---|
| Manual | Pocos casos, correcciones puntuales. |
| Sincronización de cohortes | Lo normal: cohorte por programa y periodo, ligada a cada curso. |
| Auto-matriculación con clave | Cursos abiertos o talleres; la clave se entrega por correo, no se publica. |
| Base de datos externa / archivo plano | Cuando otro sistema es la fuente de verdad de quién está inscrito. |
| Servicios web | Cuando la web de inscripciones debe crear cuentas y matricular sola. |

Flujo recomendado desde la web pública (`inscripciones.ejemplo.com`):

1. La persona se inscribe en la web. El pago y la aprobación se confirman **fuera** de Moodle.
2. Una persona del equipo aprueba la inscripción. Solo entonces se crea la cuenta en Moodle.
3. La cuenta entra a la cohorte del programa y periodo. La cohorte la matricula en los cursos.
4. Moodle envía el correo de bienvenida con el enlace para definir la contraseña. Nadie envía contraseñas por correo ni chat.

Si se automatiza con servicios web:

- Activa los servicios web solo con el protocolo REST y crea un **servicio propio** con únicamente las funciones necesarias (crear usuario, añadir a cohorte; nada de borrar ni de leer calificaciones si no se usa).
- Crea un **usuario técnico** con un rol que tenga solo esas capacidades. Nunca uses una cuenta de administrador para el token.
- El token lo genera y lo guarda el dueño, fuera del código público y del repositorio (por ejemplo, en la configuración del servidor de la web). Claude no lo escribe ni lo copia.
- Restringe el token a la IP del servidor de la web si el hosting lo permite, y ponle fecha de caducidad.
- Haz que la integración sea idempotente: si el usuario ya existe (mismo correo o nombre de usuario), se añade a la cohorte en vez de crear un duplicado.
- Registra cada alta en un log sin datos sensibles (id y fecha, no documentos ni teléfonos).

Al retirar a alguien: **suspender la matrícula** o sacarlo de la cohorte, no borrar la cuenta. Borrar destruye calificaciones y evidencias.

## 4. Tema, marca y coherencia con la web

Orden de preferencia, del más simple al más costoso de mantener:

1. **Ajustes del tema Boost** (*Apariencia > Temas*): logo, logo compacto, favicon, color de marca, imagen de fondo del login.
2. **SCSS en bruto** del mismo tema: dos casillas, una antes (variables) y otra después (reglas). Ejemplo corto:
   ```scss
   // Antes: variables de marca
   $primary: #1f5fa8;
   $font-family-sans-serif: "Inter", system-ui, sans-serif;
   ```
   ```scss
   // Después: reglas puntuales, siempre con un prefijo propio
   .ac-pie { font-size: .9rem; }
   ```
3. **HTML adicional** (*Apariencia > HTML adicional*): dentro de HEAD, al abrir BODY y antes de cerrar BODY. Aquí va el encabezado y pie común con la web (ver `arquitectura.md`).
4. **Tema hijo de Boost**: solo si hace falta cambiar plantillas o renderizadores. Es un plugin más que hay que actualizar en cada versión. Debe declarar a Boost como padre y no tocar los archivos de Boost.

Reglas para mantener el campus igual a la web:

- Una sola fuente de colores y tipografías: copia los mismos valores de la web en las variables SCSS, y anota en un comentario de dónde salen.
- Prefija todas las clases propias (`.ac-…`) para que no choquen con Bootstrap ni con el tema.
- El encabezado y pie inyectados deben ser ligeros, sin librerías externas y accesibles (enlaces con texto, contraste suficiente, que funcionen en celular).
- No ocultes con CSS elementos del menú de usuario o de navegación que el estudiante necesita (notificaciones, mensajes, cerrar sesión).
- Tras cambiar el tema o el SCSS, **purga las cachés** (*Desarrollo > Purgar cachés*) y revisa: portada, login, un curso, el calificador y la vista de celular.

## 5. Plugins: cómo evaluarlos

Antes de instalar cualquier plugin, comprueba:

- Que esté en el **directorio oficial de plugins** de Moodle y declare compatibilidad con la versión exacta del sitio.
- Fecha de la última versión, mantenedor activo, incidencias abiertas y si tiene revisión de seguridad o aprobación del directorio.
- Que declare cómo trata datos personales (aparece en el *Registro de privacidad de plugins*).
- Si lo que pide ya lo hace Moodle de serie. Cada plugin es una dependencia más que puede bloquear una actualización.

Reglas:

- Nunca instales plugins de fuentes desconocidas ni "versiones premium" compartidas.
- Instala primero en una copia de prueba si existe; si no, con respaldo completo hecho justo antes.
- Lleva una lista: nombre, versión, para qué se usa, quién lo pidió. Lo que no se usa, se desinstala desde el panel (no borrando carpetas).

## 6. Cron (tareas programadas)

Sin cron, Moodle no envía correos, no sincroniza cohortes, no hace respaldos automáticos ni borra datos vencidos.

- Debe ejecutarse **cada minuto**, por línea de comandos (CLI), no por la URL web.
- En cPanel, sección *Cron Jobs*: el comando llama a PHP con `admin/cli/cron.php` de la instalación y descarta la salida para no recibir un correo por minuto. Ejemplo genérico:
  `* * * * * /usr/bin/php /ruta/a/moodle/admin/cli/cron.php >/dev/null 2>&1`
  La ruta del PHP correcto (versión) la indica el hosting.
- Comprueba que funciona en *Administración del sitio > Notificaciones* (avisa si el cron no corre) y en *Servidor > Tareas > Tareas programadas* (última y próxima ejecución).
- Si un hosting no permite cada minuto, pide al menos cada 5 minutos y anota que hay funciones que irán con retraso.

## 7. Actualizaciones y soporte de versiones

- Moodle saca versión mayor cada 6 meses (abril y octubre) y versiones menores aproximadamente cada 2 meses. Hay versiones **LTS** con soporte de seguridad de unos 3 años.
- Para una academia pequeña: quedarse en una **LTS** y aplicar todas sus versiones menores (seguridad). Saltar a la siguiente LTS con calma, no el primer día.
- Consulta la tabla de soporte vigente antes de decidir; si la versión del sitio ya no recibe parches de seguridad, eso es prioridad.

Checklist de actualización:

1. Verifica requisitos de la nueva versión: versión de PHP, extensiones, base de datos. Cambiar la versión de PHP en cPanel se hace **antes** si la nueva la exige.
2. Verifica que cada plugin instalado tenga versión compatible. Si uno no la tiene, decide con el dueño: esperar, reemplazar o quitar.
3. Respaldo completo (sección 8) y anota dónde quedó.
4. Activa el **modo mantenimiento**, en horario sin clases ni exámenes, y avisa a los estudiantes antes.
5. Sustituye el código manteniendo `config.php` y los plugins adicionales; nunca mezcles archivos viejos y nuevos en la misma carpeta.
6. Ejecuta la actualización desde *Notificaciones*, revisa que no queden errores, desactiva el mantenimiento.
7. Purga cachés y prueba: login, un curso, entregar una tarea de prueba, calificador, correo de prueba, cron.

## 8. Respaldos y prueba de restauración

Un respaldo del sitio completo tiene **tres piezas**, y las tres son necesarias y del mismo momento:

1. La **base de datos** (exportación desde cPanel / phpMyAdmin o la herramienta de respaldo del hosting).
2. La carpeta **moodledata** (archivos subidos, entregas). Es la que más pesa y la que más se olvida.
3. El **código** de Moodle con `config.php` y los plugins adicionales.

- Haz el respaldo con el sitio en modo mantenimiento o en un momento sin actividad, para que base de datos y archivos coincidan.
- Guárdalo fuera de `public_html` y, además, una copia fuera del servidor. Nunca un respaldo descargable desde una URL pública.
- **Respaldos de curso** (`.mbz`): útiles para copiar o archivar un curso, no sustituyen al respaldo del sitio. Los automáticos se configuran en *Cursos > Copias de seguridad*; prográmalos de madrugada, guarda pocos por curso y excluye cursos sin cambios para no llenar el disco.
- Un respaldo no probado no cuenta: al menos una vez por periodo, **restaura un curso** en una categoría oculta y comprueba contenido, archivos y calificaciones. Si existe un entorno de prueba, restaura también el sitio completo allí.
- Antes de reiniciar o borrar cursos, descarga su `.mbz` con datos de usuario y anótalo.

## 9. Privacidad

Moodle trae herramientas de privacidad (Privacy API en el núcleo y en cada plugin bien hecho). Úsalas en lugar de procesos manuales:

- **Responsable de privacidad**: crea un rol de "Oficial de privacidad" y asígnalo a la persona encargada, para que no todo dependa del administrador.
- **Políticas** (*Usuarios > Privacidad y políticas*): publica la misma política que la web y exige aceptarla al primer acceso. Que digan lo mismo en los dos sitios.
- **Registro de datos**: define propósitos y plazos de retención por categoría o curso (por ejemplo, cursos archivados: N años tras la fecha de fin). El plazo se cuenta desde la fecha de fin del curso: ponle fecha de fin a todos los cursos.
- **Borrado de datos vencidos**: la página de borrado lista lo que venció; se confirma y la tarea programada lo elimina en la siguiente ejecución del cron. Revisa antes que nada pendiente (reclamos, certificados) dependa de esos datos.
- **Solicitudes de datos**: el usuario pide desde su perfil una copia o el borrado; el oficial de privacidad las revisa y aprueba. Responde dentro del plazo legal aplicable y deja constancia.
- **Plugins**: revisa el *Registro de privacidad de plugins*; un plugin sin proveedor de privacidad es una alerta.
- Recoge en el perfil solo los campos necesarios. Campos personalizados sensibles: visibles solo para el personal.

## 10. Rendimiento en hosting compartido

- **OPcache activado** y versión de PHP soportada (se revisa en cPanel, *Seleccionar versión de PHP*).
- No hay Redis ni Memcached en la mayoría de hostings compartidos: deja la caché por archivos por defecto, pero en disco local, no en red.
- **Archivos pesados** (vídeos): no se suben a Moodle. Se alojan en una plataforma de vídeo y se insertan. Ajusta el tamaño máximo de subida a lo que de verdad se necesita.
- Limpia periódicamente: papelera de reciclaje con caducidad corta, respaldos automáticos antiguos, cursos archivados que ya pueden borrarse según la retención.
- Vigila en cPanel el uso de disco, de inodos y de procesos; los límites del plan son los que tumban el sitio.
- Desactiva lo que no se usa: filtros, bloques en la portada, informes pesados, registros de depuración.
- La **depuración** (*Desarrollo > Depuración*) solo se activa un rato para diagnosticar y se apaga después: en producción muestra rutas y datos internos.

## 11. Calificaciones y finalización (lo básico)

- Define en la plantilla la **estructura del calificador**: categorías con peso (por ejemplo, tareas 40 %, cuestionarios 30 %, final 30 %) y la escala de la academia.
- Cada actividad calificable debe tener su categoría asignada antes de abrirse; cambiar pesos con notas ya puestas confunde a los estudiantes.
- Usa **letras o escalas** propias solo si el reglamento lo pide; si no, nota numérica.
- **Finalización de actividad**: cada actividad con condición clara (ver, entregar, aprobar con nota mínima).
- **Finalización de curso**: actívala y define qué la marca (por ejemplo, aprobar la evaluación final y completar las unidades). De ahí salen certificados e informes.
- **Restricciones de acceso** para abrir unidades en orden, sin abusar: demasiadas cadenas dejan a estudiantes bloqueados.
- Antes de publicar notas finales, revisa el informe del calificador con el docente y exporta una copia.

## 12. Errores comunes

- Respaldar la base de datos y olvidar `moodledata` (o al revés).
- Actualizar sin revisar plugins y quedar con el sitio caído por uno incompatible.
- Cron sin configurar o por URL web: correos y respaldos que nunca salen.
- Dar rol de administrador "para que pueda hacer de todo" a docentes o al personal.
- Usar la cuenta de administrador para el token de servicios web.
- Borrar usuarios o cursos en vez de suspender y archivar.
- Editar archivos del núcleo o de Boost directamente: se pierde en la siguiente actualización.
- CSS sin prefijo que rompe el calificador o el editor.
- Cursos sin fecha de fin: la retención de datos nunca se aplica.
- Dejar la depuración activa o el modo mantenimiento encendido tras un cambio.
- Instalar un plugin para algo que Moodle ya trae.

## Fuentes

Consultadas solo como referencia; el texto de esta guía es propio.

- https://moodledev.io/general/releases (calendario y soporte de versiones)
- https://moodledev.io/docs/apis/subsystems/privacy (Privacy API)
- https://moodledev.io/docs/apis/plugintypes/theme (temas y temas hijo de Boost)
- https://docs.moodle.org/en/Cron
- https://docs.moodle.org/503/en/Cron
- https://docs.moodle.org/en/Site_backup
- https://docs.moodle.org/en/Upgrading
- https://docs.moodle.org/503/en/Automated_course_backup
- https://docs.moodle.org/503/en/Enrolments
- https://docs.moodle.org/503/en/External_database_enrolment
- https://docs.moodle.org/503/en/performance_recommendations
- https://docs.moodle.org/503/en/Data_privacy
- https://github.com/SaadRahman01/claude-moodle-dev (skill pública sobre desarrollo en Moodle; MIT)
