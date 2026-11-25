# academia-web

Skill para Claude Code que enseña a diseñar y mantener el sitio web y el campus virtual de una academia
o institución educativa pequeña: WordPress para la web pública, Moodle para el campus y un hosting compartido con cPanel.

No trae datos de ninguna institución real: todo son ejemplos genéricos.

## Instalar

En Claude Code:

```
/plugin marketplace add Fabian380/academia-web
/plugin install academia-web@academia-web
```

O copia la carpeta `skills/academia-web` dentro de `~/.claude/skills/`.

## Cómo usarla, paso a paso

1. **Instálala** con los dos comandos de arriba y reinicia Claude Code.
2. **Cuéntale a Claude qué tienes:** tu sitio en WordPress, tu campus en Moodle (si lo tienes) y tu hosting.
   No hace falta nombrar la skill: Claude la usa sola cuando hablas de tu sitio, tus programas o tu campus.
3. **Abre tú las sesiones** de WordPress, cPanel y Moodle en el navegador. Claude nunca escribe tus contraseñas.
4. **Pide un cambio a la vez**, con palabras sencillas. Por ejemplo: "pon la sección de preguntas frecuentes en todos los programas".
5. **Claude hace un respaldo antes de tocar nada**, aplica el cambio, limpia la caché y lo revisa en la página publicada.
6. **Revisa tú el resultado** en el computador y en el celular. Si algo no te gusta, pídele que vuelva al respaldo.
7. **Para borrar algo, Claude te pedirá permiso** y te dirá si se puede deshacer.

### Recomendaciones

- Empieza por algo pequeño (un texto o un precio) para ver cómo trabaja.
- Ten un sitio de pruebas (staging) para cambios grandes.
- Descarga los respaldos a un lugar seguro y bórralos del servidor cuando ya no hagan falta.
- No compartas en el chat datos de estudiantes, contraseñas ni claves.
- Revisa `references/skills-recomendadas.md` si quieres sumar otras skills públicas.

## Qué incluye

- `SKILL.md`: reglas de trabajo y flujo para cada cambio.
- `references/`: guías de arquitectura, contenido, privacidad, WordPress, Moodle, diseño, accesibilidad, textos, SEO y rendimiento, seguridad, hosting y respaldos, y skills recomendadas.
- `templates/`: datos de ejemplo, un mu-plugin de retención de datos y una checklist.

## Licencia

[MIT](LICENSE). En palabras sencillas: cualquiera puede usar, copiar, modificar y compartir esta skill, incluso con fines comerciales,
siempre que conserve el aviso de licencia. Se entrega "tal cual", sin garantías: revisa siempre los cambios en tu propio sitio.
