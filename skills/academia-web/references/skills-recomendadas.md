# Skills públicas que complementan esta

Esta skill cubre el **método** (cómo trabajar con seguridad en el sitio y el campus de una academia).
Para profundizar en temas técnicos, estas skills públicas son buenas compañeras. No copiamos su contenido:
instálalas desde su repositorio original si las necesitas y revisa su licencia y su código antes.

Revisado en noviembre de 2026.

## Recomendadas

| Tema | Skill | Por qué |
|---|---|---|
| WordPress | [WordPress/agent-skills](https://github.com/WordPress/agent-skills) | Oficial. Bloques, temas de bloques, plugins, REST API, rendimiento y WP-CLI. |
| Calidad web | [addyosmani/web-quality-skills](https://github.com/addyosmani/web-quality-skills) | Core Web Vitals, accesibilidad WCAG 2.2, SEO y auditorías, en un paquete pequeño. |
| Diseño | [anthropics/skills](https://github.com/anthropics/skills) (`frontend-design`) y [pbakaus/impeccable](https://github.com/pbakaus/impeccable) | Diseño con personalidad, sin el aspecto genérico de "hecho por IA". |
| SEO y textos | [coreyhaines31/marketingskills](https://github.com/coreyhaines31/marketingskills) | Auditoría SEO, datos estructurados, arquitectura del sitio y redacción persuasiva. |
| Textos naturales | [blader/humanizer](https://github.com/blader/humanizer) | Quita las marcas de texto escrito por IA. |
| Moodle (desarrollo) | [SaadRahman01/claude-moodle-dev](https://github.com/SaadRahman01/claude-moodle-dev) | Plugins, temas hijos de Boost, privacidad y pruebas. Ver la advertencia abajo. |
| Privacidad | [wshobson/agents](https://github.com/wshobson/agents) (`gdpr-data-handling`) | Consentimiento, solicitudes de datos y retención. |
| Seguridad del código | [trailofbits/skills](https://github.com/trailofbits/skills) | Auditorías serias. Licencia CC-BY-SA-4.0: si adaptas su contenido, debes compartirlo igual. |

## Antes de instalar cualquier skill

- Instala solo desde el repositorio original, no desde copias en directorios de terceros.
- Lee su `SKILL.md` y cualquier script que traiga.
- Desconfía de las que ejecutan código remoto sin versión fija, instalan programas con permisos elevados,
  piden tokens del hosting o bloquean tu trabajo con hooks que no entiendes.
- Si no tiene licencia, puedes leerla, pero no copiar su contenido.

## Advertencias concretas (noviembre de 2026)

- `claude-moodle-dev` configura un servidor MCP que descarga y ejecuta código de GitHub sin versión fija. Revísalo o desactívalo.
- Evita las skills de "pruebas de penetración" en un sitio en producción.
- Las herramientas que manejan el panel de hosting con un token deben usar un token limitado y revisado.

## Lo que no encontramos (y esta skill cubre)

- Administración de Moodle desde el lado del administrador: cursos, matrículas, roles, copias, actualizaciones.
- Trabajo diario en hosting compartido con cPanel: respaldos, caché, limpieza.
- El método completo de cambios pequeños, con respaldo y verificación, pensado para un dueño no técnico.
