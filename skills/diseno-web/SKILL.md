---
name: diseno-web
description: Método paso a paso para diseñar o rediseñar un sitio web (por ejemplo, el de una academia) con Claude, apoyándose en skills públicas de diseño, accesibilidad y calidad web. Úsala cuando el usuario quiera definir el estilo visual, crear un sistema de diseño, prototipar páginas, revisar un diseño, mejorar la accesibilidad o pulir la interfaz, y para saber qué skill pública instalar en cada paso.
---

# Diseño web paso a paso

Esta skill no reemplaza a las skills de diseño que ya existen: las **ordena**. Dice qué hacer en cada etapa,
qué skill pública ayuda en esa etapa y qué cuidar. El catálogo completo, con enlaces, licencias, comandos de
instalación y advertencias, está en `references/catalogo.md`.

## Antes de instalar nada

1. **Instala solo lo que vas a usar en la etapa en la que estás.** Muchas skills juntas confunden a Claude y gastan contexto.
2. **Instala desde el repositorio original** que aparece en el catálogo, no desde copias.
3. **Lee qué trae:** algunas añaden *hooks* (programas que se ejecutan solos al editar o al iniciar sesión)
   o comandos `npx ...@latest` (descargan la versión del día). El catálogo marca cuáles.
4. **Revisa la licencia de cada una en el catálogo.** Copiar su contenido a otro proyecto depende de ella.
5. Si el sitio está en un CMS (WordPress, Moodle), aplica además las reglas de la skill `academia-web`: respaldo, validación, caché y verificación en vivo.

## El método

### 1. Entender la marca y lo que ya existe
- Reúne logo, colores, tipografías, fotos y el tono de la institución. Si ya hay un sitio, extrae su estilo actual antes de cambiarlo.
- Define para quién es el sitio (aspirantes, familias, estudiantes) y qué debe lograr cada página.
- Apoyo: `map-design` y `creative` (design-superpowers), `brand-discovery` (ECC).

### 2. Elegir una dirección visual
- Propón 2 o 3 direcciones distintas, cada una con paleta, tipografía, densidad y estilo de componentes, y deja elegir al dueño.
- Evita el aspecto genérico "hecho por IA": degradados morados, tarjetas idénticas, iconos de relleno.
- Apoyo: `frontend-design` (Anthropic), `impeccable`, `ui-ux-pro-max`, `arn-spark-style-explore`.

### 3. Convertirla en un sistema de diseño
- Escribe los **tokens**: colores con su uso (texto, fondo, acento, éxito, error), escala tipográfica, espacios, radios y sombras.
- Comprueba el contraste de cada par texto/fondo (mínimo 4,5:1 para texto normal).
- Define los componentes base: botón, tarjeta, formulario, aviso, navegación, pie.
- Apoyo: `ds-producer` (design-superpowers), `design-system` (ECC), `design-system-architecture` (agentic-council), `ui-ux-pro-max`.

### 4. Prototipar antes de construir
- Haz primero una o dos páginas clave (portada y una página de programa) con contenido real, no "lorem ipsum".
- Revísalas en computador y celular antes de aplicar el estilo a todo el sitio.
- Apoyo: `arn-spark-visual-sketch` y `arn-spark-clickable-prototype` (arness).

### 5. Movimiento, con moderación
- Anima solo lo que ayuda a entender (aparición de un menú, cambio de estado). Duraciones cortas.
- Respeta `prefers-reduced-motion`: quien pidió menos movimiento no debe ver animaciones grandes.
- Apoyo: `motion-foundations` y `motion-patterns` (ECC), `motion-design` (agentic-council).

### 6. Revisión de diseño
- Revisa jerarquía, consistencia, espacios, estados (vacío, error, cargando) y que cada página tenga una acción principal clara.
- Apoyo: `design-review` (design-superpowers), `visual-audit` (agentic-council), `impeccable`, `web-design-guidelines` (Vercel).

### 7. Accesibilidad
- Teclado, foco visible, textos alternativos, etiquetas en formularios, contraste, tamaño de los objetivos táctiles.
- Apoyo: `accessibility` (web-quality-skills), `a11y-audit` (agentic-council), `accessibility` (ECC).
- Para probar en un navegador real: Playwright MCP.

### 8. Velocidad y calidad
- Mide con PageSpeed: LCP ≤ 2,5 s, INP ≤ 200 ms, CLS ≤ 0,1. Optimiza imágenes y fuentes primero.
- Apoyo: `core-web-vitals`, `performance`, `seo` y `web-quality-audit` (web-quality-skills).

### 9. Textos naturales
- Que los textos suenen a una persona de la institución, no a una plantilla.
- Apoyo: `humanizer`.

### 10. Pulido final
- Alineaciones, espaciados consistentes, estados *hover* y foco, transiciones suaves, detalles en celular.
- Apoyo: `make-interfaces-feel-better` (ECC), `impeccable`.

## Entrega

Al terminar cada etapa, muestra al dueño el resultado (captura o página de prueba) y espera su aprobación antes de seguir.
Guarda las decisiones (tokens, componentes, reglas de tono) en un archivo del proyecto para que la próxima sesión las respete.
