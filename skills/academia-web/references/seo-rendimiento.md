# SEO y rendimiento

El objetivo es sencillo: que quien busca "curso de X en [ciudad]" encuentre la academia, y que la página cargue rápido en un celular
con datos móviles. Todo cambio de esta guía sigue el flujo normal de la skill (respaldo, cambio mínimo, caché, verificación en vivo).

## 1. Títulos y descripciones

- **Un `<title>` distinto por página**, que diga qué hay en ella: "Técnico en Contabilidad – Academia Ejemplo".
  Lo importante va al principio; el nombre de la academia, al final y corto.
- Nada de títulos genéricos ("Inicio", "Página 2") ni de repetir la misma palabra clave tres veces.
- **Meta descripción** de una o dos frases que invite a entrar: qué se aprende, duración, modalidad. Google puede no usarla, pero si es buena suele hacerlo.
- El eslogan completo solo en la portada; en las demás páginas sobra.
- En una SPA el título lo cambia el JavaScript. Asegúrate de que **la página de WordPress ya trae un título y una descripción útiles en el HTML**
  (desde el editor o un plugin de SEO), por si el buscador no ejecuta el código o tarda en hacerlo.

## 2. Encabezados y contenido

- Un solo `<h1>` por página con el nombre del programa o de la sección. Debajo, `<h2>` para cada bloque (malla, tutores, matrícula, preguntas).
- Los encabezados describen el contenido; no se eligen por el tamaño de letra.
- Texto real y útil: duración, horario, requisitos, costo o "consúltanos", certificación. Lo que un padre o un estudiante preguntaría por teléfono.
- Imágenes con `alt` que describa la foto ("Estudiantes en el laboratorio de cómputo"), no una lista de palabras clave.
- Enlaces internos con texto claro ("Ver malla de Contabilidad"), no "clic aquí".

## 3. Datos estructurados (JSON-LD)

Van en un `<script type="application/ld+json">` y no se ven en la página. Reglas:

- **Organización:** en la portada o en "Quiénes somos", un objeto `EducationalOrganization` (subtipo de `Organization`)
  con `name`, `url`, `logo`, `telephone`, `email`, `address` y `sameAs` (redes sociales). Basta en una página.
- **Si hay sede física que recibe público:** considera además un subtipo de `LocalBusiness` con dirección y horario, y que coincida con el perfil de empresa en Google.
- **Cursos:** cada página de programa puede llevar un `Course` con `name`, `description` corta y `provider` (la academia).
  Una página que lista todos los programas usa un `ItemList` con un `ListItem` por curso (`position` y `url` canónica).
- Los resultados enriquecidos de cursos en Google cambian con frecuencia: revisa la documentación vigente de Google antes de contar con ellos. El marcado sigue sirviendo para describir bien cada programa.
  No pongas precios, descuentos ni frases de venta en el nombre del curso.
- **Lo marcado debe verse en la página.** Si el dato no está visible, no lo pongas en el JSON-LD.
- Genera el JSON-LD desde los mismos datos de la SPA, así un cambio de nombre o teléfono queda igual en ambos sitios.
- Valida con la Prueba de resultados enriquecidos y revisa el informe de mejoras en Search Console.

Ejemplo mínimo (adáptalo; todo es ficticio):

```json
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "Técnico en Contabilidad",
  "description": "Programa de 18 meses con prácticas en empresas.",
  "provider": { "@type": "EducationalOrganization", "name": "Academia Ejemplo", "url": "https://ejemplo.com" }
}
```

## 4. Sitemap y robots.txt

- WordPress genera un sitemap en `/wp-sitemap.xml` (o el plugin de SEO genera el suyo). Usa **uno**, no dos.
- Solo direcciones completas (`https://…`) y canónicas: las que quieres ver en Google. Fuera borradores, páginas de prueba y zonas con inicio de sesión.
- `lastmod` solo si es fiel: cambia cuando cambia el contenido, no por el año del pie.
- Declara el sitemap en `robots.txt` con una línea `Sitemap:` y envíalo en Search Console.
- Cada subdominio es un sitio distinto para el buscador: su `robots.txt` y su sitemap hablan solo de ese subdominio,
  salvo que envíes los sitemaps desde Search Console con todos los subdominios verificados.
- El campus Moodle y la biblioteca no necesitan aparecer en buscadores, salvo la página de inicio de sesión del campus.

## 5. Subdominios o rutas

La arquitectura usa subdominios como secciones (`inscripciones.ejemplo.com`). Para el buscador eso tiene ventajas y costos:

| | Subdominios | Rutas (`ejemplo.com/inscripciones`) |
|---|---|---|
| Enlaces cortos para compartir | Sí | Casi igual |
| Autoridad del dominio | Se reparte entre hosts | Se concentra |
| Configuración en Search Console | Una propiedad por subdominio o una de dominio | Una sola |
| Riesgo de contenido duplicado | Alto si todos sirven la misma página | Bajo |

Recomendación práctica: si todos los subdominios cargan la misma SPA, **cada uno debe declarar su URL canónica** (`<link rel="canonical">`)
y mostrar un contenido distinto, o bien elegir el dominio principal como canónico y usar los subdominios solo como atajos que redirigen (301).
No cambies de esquema sin un plan de redirecciones: cada dirección vieja debe llevar a su nueva.

## 6. SEO local

- Crea y **verifica** el perfil de empresa en Google con categoría correcta, dirección (si se atiende en persona), teléfono, horario y fotos reales.
- Nombre, dirección y teléfono **idénticos** en la web, el perfil, las redes y los directorios. Un dígito distinto confunde.
- Pide reseñas a estudiantes y egresados, y respóndelas todas con calma, también las malas. Nunca pagues ni inventes reseñas.
- Una página por sede si hay varias, con su dirección, mapa y horario.
- Menciona la ciudad o el barrio de forma natural en títulos y textos de las páginas de programa.

## 7. Core Web Vitals

Google mide la experiencia real de los usuarios (percentil 75, en celular y en computador):

| Métrica | Qué mide | Bueno |
|---|---|---|
| LCP | Cuánto tarda en verse el elemento principal | ≤ 2,5 s |
| INP | Cuánto tarda la página en responder a un toque o clic | ≤ 200 ms |
| CLS | Cuánto "salta" el contenido mientras carga | ≤ 0,1 |

### Cómo mejorarlas en hosting compartido

**LCP (carga):**
- Plugin de caché de página activo y con la caché limpia tras cada cambio. Es lo que más baja el tiempo del primer byte en un hosting compartido.
- Versión de PHP soportada más reciente que acepten WordPress y los plugins (se cambia en cPanel).
- La imagen principal (portada o encabezado del programa): en WebP o AVIF, del tamaño en que se muestra, **sin carga diferida** y con `fetchpriority="high"`.
  Si la dibuja el JavaScript, precárgala con `<link rel="preload" as="image">`.
- Pocos redireccionamientos encadenados (http → https → www → …): uno como máximo.
- Si el hosting es lento lejos del servidor, una CDN delante del sitio ayuda; consúltalo con el dueño porque cambia el DNS.

**INP (respuesta):**
- Menos scripts de terceros (chats, píxeles, widgets). Cada uno que se quita se nota.
- En la SPA, evita reconstruir toda la página en cada clic; dibuja solo la sección que cambia.
- Carga los scripts con `defer` y divide tareas largas.

**CLS (estabilidad):**
- `width` y `height` (o `aspect-ratio`) en todas las imágenes y videos.
- Reserva el espacio de avisos, banners de cookies y tarjetas que llegan tarde.
- Fuentes web con `font-display: swap` y pocas variantes.

**General:**
- Revisa los plugins: desactiva y borra los que no se usan (con respaldo y permiso, regla 6 de la skill).
- Imágenes: comprime antes de subir; no subas fotos de 5 MB para mostrarlas a 400 px.

## 8. Medir

- **PageSpeed Insights:** combina datos reales de usuarios (si el sitio tiene tráfico suficiente) con una prueba de laboratorio.
  Mira primero los datos reales; el puntaje de laboratorio es orientativo.
- **Search Console:** informes de páginas indexadas, Core Web Vitals, sitemaps y mejoras (datos estructurados). Verifica la propiedad de dominio
  para cubrir todos los subdominios a la vez. La verificación por DNS la hace el usuario o Claude con su sesión, nunca con claves pegadas en el chat.
- Mide **antes y después** de cada cambio de rendimiento y anota el resultado en el informe al usuario.
- Revisa una vez al mes: errores de indexación, páginas que bajaron y consultas por las que la academia ya aparece.

## Fuentes

Consultadas como referencia; el texto es propio.

- https://web.dev/articles/vitals
- https://web.dev/articles/optimize-lcp
- https://developers.google.com/search/docs/appearance/title-link
- https://developers.google.com/search/docs/appearance/structured-data/course
- https://developers.google.com/search/docs/appearance/structured-data/organization
- https://developers.google.com/search/docs/crawling-indexing/sitemaps/build-sitemap
- https://support.google.com/business/answer/7091
- https://github.com/addyosmani/web-quality-skills (MIT)
- https://github.com/AgriciDaniel/claude-seo (MIT)
- https://github.com/aevans-eng/seo-skill (MIT)
