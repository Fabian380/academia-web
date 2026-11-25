# Diseño visual

Guía para que la web de la academia se vea cuidada, coherente y propia, sin rehacer lo que ya funciona.
La estructura de cada página está en `contenido.md`; aquí va el **cómo se ve**. La accesibilidad está en `accesibilidad.md`.

## 1. Antes de diseñar

- Pregunta (o deduce del sitio actual) quién lo mira: aspirantes, familias, estudiantes activos. El público decide el tono visual.
- Revisa los colores, la letra y el logo que ya usa la academia. **No cambies la identidad sin que el dueño lo pida.**
- Escribe un mini plan antes del código: 4 a 6 colores con nombre, 1 o 2 tipos de letra, la idea de la página y qué es lo primero que se debe ver.

## 2. Jerarquía visual

- Cada pantalla tiene **una** acción principal (por ejemplo, "Inscribirme"). Solo ese botón va relleno con el color de acento; los demás, con borde o como enlace.
- Orden de lectura: título → frase que explica → acción. Si el ojo no sabe dónde ir primero, sobra algo.
- Contrasta por tamaño y peso, no por cantidad de colores. Tres niveles de título suelen bastar.
- Quita antes de agregar: al terminar una sección, elimina un adorno y comprueba si se entiende igual.

## 3. Tipografía

- Máximo dos familias, y si son dos, que se distingan claramente (por ejemplo, una con serifa para títulos y una sin serifa para el texto).
- Texto base de 16 px o más; en celular nunca menos.
- Líneas de 45 a 75 caracteres (`max-width: 65ch`). Interlineado de 1,5 para el texto y 1,1 a 1,25 para títulos.
- Escala fija de tamaños (por ejemplo 14 · 16 · 20 · 25 · 31 · 39 px). Usa `clamp()` para títulos que crecen con la pantalla.
- Evita: todo en mayúsculas, títulos con una sola palabra resaltada en otro color, etiquetas pequeñas encima de cada título.
- Carga como mucho dos pesos por familia y usa `font-display: swap`.

## 4. Color y tokens

Define los colores **una sola vez** como variables y úsalas en todo el bloque. Nómbralas por su función, no por el tono:

```css
.aw-app {
  --aw-fondo: #ffffff;
  --aw-superficie: #f4f6f8;
  --aw-texto: #1b2430;
  --aw-texto-suave: #4a5563;
  --aw-acento: #0b5cad;      /* botón principal y enlaces */
  --aw-acento-texto: #ffffff;
  --aw-borde: #d5dbe1;       /* solo separadores decorativos */
  --aw-borde-campo: #7b8794; /* bordes de campos de formulario: 3:1 o más */
  --aw-exito: #1e7a46;
  --aw-error: #b3261e;
}
```

- Comprueba cada par texto/fondo: 4,5:1 para texto normal y 3:1 para texto grande, iconos y bordes de campos (detalle en `accesibilidad.md`).
- El color nunca es la única señal: un error lleva también texto o icono.
- Un solo color de acento. Los demás tonos, neutros.
- Paletas muy vistas en sitios hechos con IA que conviene evitar salvo que sean de la marca: crema con terracota, negro con verde ácido, degradados morado-azul.

## 5. Espaciado y retícula

- Escala de espacios basada en 4 u 8 px: `--aw-esp-1: 4px` … `--aw-esp-8: 64px`. No uses valores sueltos como 13 px.
- Más espacio **entre** secciones que **dentro** de ellas; así se ve qué va junto.
- Contenedor con ancho máximo (≈1200 px) y márgenes laterales de al menos 16 px en celular.
- Cuadrículas con `display: grid; grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr)); gap: var(--aw-esp-4);` para que se acomoden solas.

## 6. Responsive, primero el celular

- Escribe el CSS para pantalla pequeña y agrega `@media (min-width: …)` para ampliar. La mayoría de aspirantes llega desde el celular.
- Puntos de quiebre según el contenido, no según modelos de teléfono (típicos: 600, 900, 1200 px).
- Nada de desplazamiento horizontal de la página. Prueba a 320 px de ancho.
- Tablas anchas (como la malla curricular) dentro de un contenedor con `overflow-x: auto` y la primera columna fija si ayuda.
- Botones y enlaces del menú con área táctil cómoda (ver `accesibilidad.md`).

## 7. Tarjetas y cuadrículas

- Usa tarjetas solo cuando cada una es un elemento independiente (un programa, un tutor, un acceso directo).
- Toda la tarjeta puede ser clicable, pero con **un único enlace** real (el título) y el resto extendido con CSS; así el lector de pantalla no repite.
- Misma altura de título e imagen en todas las tarjetas de una fila (`aspect-ratio` en la imagen, `display: grid` en la tarjeta).
- Evita el "kit genérico": muchas tarjetas idénticas con esquinas redondeadas, la misma sombra y un icono decorativo. Varía por contenido real (foto, dato, precio).
- Cada borde, número o etiqueta debe aportar un dato al lector; si solo adorna, sobra. Los pasos de la matrícula sí se numeran; una lista de ventajas, no.

## 8. Carruseles bien hechos

Prefiere una fila con desplazamiento horizontal nativo (como la de tutores) a un carrusel automático. Si hace falta un carrusel:

- **Sin avance automático**, o con botón visible de pausa y detenido al pasar el ratón o recibir foco.
- Botones "anterior" y "siguiente" con nombre accesible y manejables con teclado.
- `scroll-snap-type: x mandatory` y `scroll-snap-align: start` en los elementos; nada de librerías pesadas para esto.
- Que se note que hay más contenido: deja asomar la siguiente tarjeta.
- Nunca pongas información clave solo en la segunda o tercera diapositiva.

## 9. CSS aislado dentro del bloque del CMS

La SPA vive en un bloque de WordPress, rodeada por el tema. Para que ninguno pise al otro:

- Envuelve todo en un contenedor con clase propia (`.aw-app`) y prefija todas las clases (`aw-`).
- No uses selectores globales (`body`, `h2`, `a`) sin el prefijo del contenedor.
- Si el tema impone estilos fuertes, sube la especificidad con el contenedor, no con `!important`. Si una regla no se aplica, revisa primero la especificidad.
- Opcional: `@layer aw { … }` o `all: revert` en el contenedor para partir de cero; pruébalo antes, porque puede borrar estilos útiles del tema.
- Las variables de color y espacio se declaran en `.aw-app`, no en `:root`, para no afectar al resto del sitio ni al campus.

## 10. Imágenes

- Formatos: **WebP** o **AVIF** para fotos, **SVG** para logos e iconos, PNG solo si hace falta transparencia sin SVG.
- Tamaño: exporta al doble del ancho mostrado como máximo. Una foto de tarjeta rara vez necesita más de 800 px ni pesar más de 150 KB.
- Usa `srcset` y `sizes` (WordPress los genera si la imagen está en la biblioteca de medios).
- Siempre `width` y `height` (o `aspect-ratio`) para que la página no salte al cargar.
- `loading="lazy"` solo en imágenes que están **debajo** de la primera pantalla. La imagen principal de arriba se carga normal y puede llevar `fetchpriority="high"`.
- Fotos reales de la academia valen más que fotos de banco. Texto alternativo según `accesibilidad.md`.

## 11. Iconos

- Un solo estilo (mismo grosor de trazo, relleno o línea) en todo el sitio.
- SVG en línea o un sprite; evita cargar una fuente de iconos completa para usar cinco.
- Icono decorativo junto a texto: `aria-hidden="true"`. Icono solo (sin texto): necesita nombre accesible.
- El icono acompaña al texto; no lo reemplaza en acciones importantes.

## 12. Modo claro y oscuro

- Solo si el dueño lo quiere; el modo claro bien hecho es suficiente.
- Si se hace: redefine las mismas variables bajo `@media (prefers-color-scheme: dark)` y declara `color-scheme: light dark` en el contenedor.
- En oscuro, el fondo no es negro puro ni el texto blanco puro; baja un poco ambos para reducir el brillo.
- Revisa logos, sombras y fotos en ambos modos; vuelve a medir el contraste.

## 13. Movimiento

- Poco y con propósito: mostrar que algo cambió (se abrió una pregunta, se envió un formulario).
- Duraciones cortas (150 a 300 ms). Nada que parpadee.
- Respeta `@media (prefers-reduced-motion: reduce)` desactivando animaciones no esenciales.

## 14. Revisión final

1. Captura de pantalla en celular (≈375 px) y en computador.
2. ¿Se entiende en 5 segundos qué es y qué hacer?
3. ¿Hay un solo botón principal por pantalla?
4. ¿Colores, letras y espacios salen de las variables?
5. ¿Algo se ve "de plantilla"? Cámbialo por contenido real de la academia.

## Fuentes

Consultadas como referencia; nada se copió literalmente.

- anthropics/skills, skill `frontend-design` — https://github.com/anthropics/skills/tree/main/skills/frontend-design — Apache-2.0
- addyosmani/web-quality-skills, skill `accessibility` — https://github.com/addyosmani/web-quality-skills — MIT
- W3C WAI, WCAG 2.2 Quick Reference — https://www.w3.org/WAI/WCAG22/quickref/
- web.dev, Browser-level image lazy loading — https://web.dev/articles/browser-level-image-lazy-loading
- web.dev, prefers-color-scheme — https://web.dev/articles/prefers-color-scheme
