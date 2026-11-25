# Catálogo de skills públicas de diseño

Verificado en noviembre de 2026 contra cada repositorio original. Los comandos son los que documenta cada autor;
si cambian, manda lo que diga su README. Ninguna de estas skills está copiada aquí: solo se enlazan.

## Recomendadas para empezar

| Skill | Etapas | Repositorio | Licencia | Instalar en Claude Code |
|---|---|---|---|---|
| frontend-design | 2 | [anthropics/claude-plugins-official](https://github.com/anthropics/claude-plugins-official) | Apache-2.0 | `/plugin install frontend-design@claude-plugins-official` |
| web-quality-skills | 7, 8 | [addyosmani/web-quality-skills](https://github.com/addyosmani/web-quality-skills) | MIT | `/plugin marketplace add addyosmani/web-quality-skills` y `/plugin install web-quality-skills@addy-web-quality-skills` |
| humanizer | 9 | [blader/humanizer](https://github.com/blader/humanizer) | MIT | `/plugin marketplace add blader/humanizer` y `/plugin install humanizer@humanizer` (Claude Code 2.1.142 o posterior) |

Con estas tres cubres dirección visual, accesibilidad, velocidad, SEO y textos.

## Para ir más lejos

| Skill | Etapas | Repositorio | Licencia | Instalar en Claude Code | Cuidado |
|---|---|---|---|---|---|
| impeccable | 2, 6, 10 | [pbakaus/impeccable](https://github.com/pbakaus/impeccable) | Apache-2.0 | `/plugin marketplace add pbakaus/impeccable` y luego instalar desde `/plugin` | Su instalador por `npx` ofrece un *hook* de diseño que se ejecuta al editar: acéptalo solo si lo entiendes. |
| ui-ux-pro-max | 2, 3 | [nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) | MIT | `/plugin marketplace add nextlevelbuilder/ui-ux-pro-max-skill` y `/plugin install ui-ux-pro-max@ui-ux-pro-max-skill` | Base de datos de estilos y paletas. Su configuración opcional usa servidores con `@latest`. |
| design-superpowers | 1, 3, 6 | [LSDimi/design-superpowers](https://github.com/LSDimi/design-superpowers) | MIT | `/plugin marketplace add github:LSDimi/design-superpowers` y `/plugin install design-superpowers@design-superpowers` | Algunas funciones dependen de Figma y de herramientas extra opcionales. |
| arn-spark (arness) | 2, 4 | [AppsVortex/arness](https://github.com/AppsVortex/arness) | MIT | `/plugin marketplace add AppsVortex/arness` y `/plugin install arn-spark@arn-marketplace` | Instala solo `arn-spark`: `arn-code` trae *hooks*. |
| agentic-council | 3, 5, 6, 7 | [dtsong/agentic-council](https://github.com/dtsong/agentic-council) | Apache-2.0 | `/plugin marketplace add https://github.com/dtsong/agentic-council` y `/plugin install agentic-council` | Paquete grande, con muchos agentes además de las skills de diseño. |
| ECC | 1, 3, 5, 7, 10 | [affaan-m/ECC](https://github.com/affaan-m/ECC) | MIT | `/plugin marketplace add https://github.com/affaan-m/ECC` y `/plugin install ecc@ecc` | Muy grande (cientos de skills) y registra muchos *hooks* que se ejecutan solos. Si solo quieres una skill, léela en GitHub en vez de instalar todo. |
| web-design-guidelines | 6 | [vercel-labs/agent-skills](https://github.com/vercel-labs/agent-skills) | Sin licencia propia | `npx skills add vercel-labs/agent-skills` | Sus reglas vienen de [vercel-labs/web-interface-guidelines](https://github.com/vercel-labs/web-interface-guidelines) (MIT). No copies el contenido de la skill. Sus reglas vienen incluidas, no se descargan al usarla. |

## Herramientas y librerías (no son skills)

| Herramienta | Para qué | Repositorio | Licencia | Cuidado |
|---|---|---|---|---|
| Playwright MCP | Abrir el sitio en un navegador real y comprobar accesibilidad y diseño | [microsoft/playwright-mcp](https://github.com/microsoft/playwright-mcp) | Apache-2.0 | El comando oficial usa `@latest`; fija una versión si quieres estabilidad. |
| Magic UI | Componentes animados para React | [magicuidesign/magicui](https://github.com/magicuidesign/magicui) | MIT | Pensado para React y Tailwind, no para bloques de WordPress. |
| React Bits | Componentes animados para React | [DavidHDev/react-bits](https://github.com/DavidHDev/react-bits) | MIT con *Commons Clause* | Puedes usarlos en tu sitio, pero no revender ni redistribuir los componentes. |

## Qué evitar

- Copiar en tu repositorio el contenido de skills sin licencia o con licencias restrictivas (AGPL, no comercial).
- Instalar varios paquetes grandes a la vez: se pisan nombres de skills y Claude elige peor.
- Ejecutar comandos `npx`, `npm install -g` o scripts de instalación sin leer qué hacen.
- Skills de "pruebas de penetración" sobre un sitio en producción.
