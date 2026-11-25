# Accesibilidad (WCAG 2.2, nivel AA)

Una web accesible la usan mejor todos: quien tiene baja visión, quien navega con teclado, quien lee desde un celular al sol
o un padre que no es experto en tecnología. La meta práctica es el nivel **AA** de WCAG 2.2.
Úsalo como lista de verificación en cada cambio visible (junto a `templates/checklist-cambio.md`).

> Las herramientas automáticas detectan solo una parte de los problemas. Un buen puntaje no reemplaza la prueba manual.

## 1. Lista de verificación

### Contraste y color
- [ ] Texto normal contra su fondo: **4,5:1** o más.
- [ ] Texto grande (≈24 px, o ≈19 px en negrita): **3:1** o más.
- [ ] Bordes de campos, iconos que informan y el anillo de foco: **3:1** contra lo que los rodea.
- [ ] Texto sobre fotos: con capa oscura o fondo sólido detrás; mide el peor punto de la imagen.
- [ ] El color no es la única señal (errores, campos obligatorios, enlaces dentro de un párrafo).

### Teclado y foco
- [ ] Todo lo que se hace con el ratón se puede hacer con `Tab`, `Shift+Tab`, `Enter`, `Espacio` y flechas.
- [ ] El orden del foco sigue el orden visual.
- [ ] El foco **siempre se ve** (no quites `outline` sin poner algo igual de visible, por ejemplo `outline: 3px solid var(--aw-acento); outline-offset: 2px`).
- [ ] El elemento enfocado no queda tapado por un encabezado fijo, un banner de cookies o un botón flotante de WhatsApp (usa `scroll-padding-top`).
- [ ] No hay trampas: se puede salir de menús, ventanas y videos con teclado; `Esc` cierra ventanas emergentes.
- [ ] Hay un enlace "Saltar al contenido" que aparece al primer `Tab`.

### Estructura y títulos
- [ ] Cada página tiene un `<title>` único y descriptivo ("Inscripciones · Academia Ejemplo").
- [ ] Un solo `h1` por vista; luego `h2`, `h3` sin saltar niveles. El título se elige por jerarquía, no por tamaño.
- [ ] Zonas marcadas: `header`, `nav`, `main`, `footer`.
- [ ] Listas como listas (`ul`/`ol`), tablas de datos como tablas con `th` (la malla curricular, por ejemplo).
- [ ] En la SPA, al cambiar de sección: actualiza el `<title>` y mueve el foco al nuevo `h1`.

### Imágenes y texto alternativo
- [ ] Imagen que informa: `alt` corto que diga lo que aporta ("Estudiantes en el laboratorio de química").
- [ ] Imagen decorativa: `alt=""`.
- [ ] Imagen que es enlace o botón: `alt` describe el destino o la acción, no la foto.
- [ ] Logo: el nombre de la academia.
- [ ] No hay texto importante dentro de imágenes (precios, fechas). Si lo hay, también va en texto.
- [ ] Videos con subtítulos; audios con transcripción.

### Formularios
- [ ] Cada campo tiene una etiqueta visible unida con `<label for>`. El `placeholder` no sustituye a la etiqueta.
- [ ] Campos obligatorios marcados en texto ("obligatorio"), no solo con un asterisco rojo.
- [ ] `autocomplete` correcto (`name`, `email`, `tel`) para que el navegador ayude a llenar.
- [ ] Los errores dicen **qué** pasó y **cómo** arreglarlo, junto al campo, y se anuncian (`aria-describedby`, `aria-live="polite"` o foco al resumen de errores).
- [ ] No se pide dos veces el mismo dato en el mismo proceso.
- [ ] Antes de enviar algo con efecto (pago, inscripción), se puede revisar y corregir.
- [ ] El inicio de sesión no exige resolver acertijos ni copiar códigos a mano sin alternativa; se permite pegar la contraseña.
- [ ] El mensaje de "enviado" se anuncia sin mover al usuario de lugar.

### Movimiento y tiempo
- [ ] Nada se mueve solo más de 5 segundos sin botón de pausa (carruseles, textos animados).
- [ ] Nada destella más de tres veces por segundo.
- [ ] Se respeta `prefers-reduced-motion`.
- [ ] Si hay tiempo límite (por ejemplo, en un formulario), se avisa y se puede ampliar.

### Tacto y puntero
- [ ] Botones y enlaces de al menos **24 × 24 px** o con espacio suficiente alrededor; para menús y botones principales, apunta a 44 × 44 px.
- [ ] Nada exige arrastrar: si hay un control deslizante, también se puede usar con clics o teclado.
- [ ] El texto visible del botón forma parte de su nombre accesible.

### Idioma, zoom y consistencia
- [ ] `<html lang="es">` (o la variante local, como `es-CO`). Fragmentos en otro idioma con su propio `lang`.
- [ ] Con zoom al 200 % y a 320 px de ancho no se pierde contenido ni aparece desplazamiento horizontal.
- [ ] El texto no se rompe si el usuario aumenta el espaciado de letras o líneas.
- [ ] Menú, pie y forma de contacto están en el mismo sitio en todas las páginas (también en el campus).
- [ ] Los enlaces dicen adónde llevan; nada de "clic aquí". Si abren un PDF o una pestaña nueva, se avisa.

## 2. Componentes frecuentes

- **Preguntas frecuentes:** usa `<details>` y `<summary>`; ya funcionan con teclado y lector de pantalla.
- **Menú de celular:** un `<button>` con `aria-expanded` y `aria-controls`; no un `div` clicable.
- **Pestañas o filtros:** si no puedes implementar bien el patrón de pestañas, usa botones o enlaces simples.
- **Botón flotante de WhatsApp:** con nombre accesible ("Escribir por WhatsApp") y sin tapar contenido ni el foco.
- **Ventanas emergentes:** usa `<dialog>` con foco dentro mientras está abierta, y devuélvelo al cerrar.
- Primero HTML nativo; ARIA solo cuando no hay elemento nativo. Un ARIA mal puesto es peor que ninguno.

## 3. Cómo probar rápido (10 minutos)

1. **Teclado:** desconecta el ratón y recorre la página con `Tab`. ¿Siempre ves dónde estás? ¿Llegas a todo?
2. **Zoom:** 200 % en el navegador y luego vista de celular a 320 px.
3. **Contraste:** revisa los colores nuevos con el selector de color de las herramientas del navegador (muestra la relación) o un verificador de contraste.
4. **Títulos:** con una extensión de esquema de títulos o el panel de accesibilidad, comprueba que el índice tenga sentido leído solo.
5. **Automático:** Lighthouse (pestaña de accesibilidad) o axe en las herramientas del navegador. Corrige primero lo crítico: etiquetas faltantes, `alt` faltante, contraste, trampas de teclado, foco invisible.
6. **Lector de pantalla** (si hay tiempo): NVDA en Windows, VoiceOver en Mac o iPhone, TalkBack en Android. Escucha una página completa y un formulario.
7. **Movimiento reducido:** activa la preferencia del sistema y recarga.

Informa al dueño en lenguaje sencillo qué se revisó, qué quedó pendiente y por qué importa.

## Fuentes

Consultadas como referencia; nada se copió literalmente.

- W3C WAI, WCAG 2.2 Quick Reference — https://www.w3.org/WAI/WCAG22/quickref/
- W3C WAI, Easy Checks — https://www.w3.org/WAI/test-evaluate/easy-checks/
- W3C WAI, Writing for Web Accessibility — https://www.w3.org/WAI/tips/writing/
- addyosmani/web-quality-skills, skill `accessibility` — https://github.com/addyosmani/web-quality-skills — MIT
- ghaida/intent, skill `include` — https://github.com/ghaida/intent — CC0-1.0
- anthropics/skills, skill `frontend-design` — https://github.com/anthropics/skills/tree/main/skills/frontend-design — Apache-2.0
