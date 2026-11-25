---
name: academia-web
description: Guía para que Claude diseñe y mantenga el sitio web y el campus virtual de una academia o institución educativa pequeña (WordPress + Moodle en hosting compartido con cPanel). Úsala cuando el usuario quiera crear o cambiar la página principal, las páginas de programas, las mallas curriculares, el directorio de tutores, los accesos directos, el pie de página, el campus Moodle o las políticas de datos de su academia.
---

# Sitio web y campus de una academia

Esta skill describe un método probado para construir y mantener, con Claude, el sitio de una academia:
una web pública en WordPress, un campus en Moodle y una biblioteca restringida, todo en un hosting compartido.
Lo importante no es el código sino la **forma de trabajar**: cambios pequeños, siempre con respaldo, verificados en vivo
y nunca sin autorización del dueño.

## 1. Reglas de oro (no se negocian)

1. **Solo lo que se pide.** No quites, muevas ni "mejores" nada que el usuario no haya pedido. Si ves algo raro, pregúntalo.
2. **Respaldo antes de cada cambio.** Copia el archivo o el bloque a una carpeta privada (fuera de `public_html`) o a un borrador, con fecha en el nombre.
3. **Valida antes de guardar.** Revisa la sintaxis del código nuevo (por ejemplo, `new Function(codigo)` para JavaScript) antes de escribirlo en el sitio.
4. **Limpia la caché y verifica en vivo.** Un cambio no está hecho hasta que se ve en la página publicada, en computador y en celular.
5. **Las contraseñas las escribe el usuario.** Claude nunca escribe credenciales, claves ni códigos de verificación.
6. **Borrar es con permiso.** Antes de un borrado definitivo, di qué se borra y que no se puede deshacer. Prefiere mover a una carpeta de respaldo o a la papelera.
7. **Nada temporal se queda en el servidor.** Prefiere las herramientas del panel de administración. Si alguna vez hace falta un archivo temporal, que exija sesión de administrador y bórralo en cuanto termine.
8. **Nada de datos reales fuera del servidor.** No copies datos de estudiantes, contraseñas ni rutas del servidor a chats, repositorios ni documentos públicos.

## 2. Arquitectura

Lee `references/arquitectura.md` para el detalle. En resumen:

- **Web pública (WordPress):** una aplicación de una sola página (SPA) guardada en un **bloque reutilizable**.
  El bloque tiene dos partes: los **datos** (programas, precios, textos, contactos) en JSON y el **código** que los dibuja.
  Separarlos permite cambiar un precio o un texto sin tocar el código.
- **Subdominios como secciones:** `inscripciones.ejemplo.com`, `contacto.ejemplo.com`… apuntan a la misma SPA, que muestra la sección según la dirección.
- **Lógica del servidor en mu-plugins:** formularios, tareas programadas y reglas de privacidad viven en `wp-content/mu-plugins/`, un archivo por tema.
- **Campus (Moodle):** comparte encabezado y pie con la web mediante el HTML adicional de Moodle, leído desde un archivo para versionarlo fácil.
- **Biblioteca u otras zonas restringidas:** exigen inicio de sesión. El enlace puede ser público; el contenido no.

## 3. Flujo de trabajo para cada cambio

1. Entiende el pedido. Si es ambiguo, haz **una** pregunta concreta.
2. Lee el estado actual (el bloque, el archivo, la página publicada).
3. Haz el respaldo (regla 2) y anota dónde quedó.
4. Aplica el cambio mínimo. Reemplaza por posición o por texto exacto, nunca "a ojo".
5. Valida la sintaxis (regla 3) y guarda.
6. Limpia la caché del sitio.
7. Verifica en vivo y compara con lo pedido.
8. Informa en lenguaje sencillo: qué cambió, dónde está el respaldo y qué falta.

## 4. Contenido de las páginas

Ver `references/contenido.md`. Puntos clave:

- **Página de programa:** qué nos diferencia, misión y visión, malla curricular, tutores, proceso de matrícula y preguntas frecuentes.
  Todas las páginas de programa con la misma estructura.
- **Malla curricular:** columnas alineadas a la misma altura; los nombres de asignaturas, cortos y consistentes en todos los módulos.
- **Tutores:** en la página de programa, tarjetas pequeñas en una sola fila deslizable; en Contacto, el directorio completo.
  Mientras no haya persona asignada, la tarjeta lo dice con naturalidad ("por asignar").
- **Accesos directos de la portada:** solo a páginas que existen y funcionan. Nada de enlaces viejos ni de secciones restringidas.
- **Tono:** humano y directo. Quita frases robóticas, números de versión internos y jerga del equipo.

## 5. Privacidad y datos

Ver `references/privacidad.md`. Recoge solo lo necesario, explica en la política qué se guarda y por cuánto tiempo,
y haz que el sistema **cumpla lo que dice la política** (por ejemplo, una tarea diaria que borra registros vencidos).

## 6. Plantillas

En `templates/` hay ejemplos genéricos para copiar y adaptar:

- `datos-ejemplo.json`: estructura de datos de la SPA.
- `mu-plugin-retencion.php`: borrado automático de registros antiguos.
- `checklist-cambio.md`: lista de verificación para cada cambio.
