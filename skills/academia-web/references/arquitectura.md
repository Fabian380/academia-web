# Arquitectura

## Web pública: SPA en un bloque reutilizable de WordPress

El bloque contiene HTML con tres piezas:

1. **Estilos** (`<style>`), con todas las reglas bajo una clase raíz (por ejemplo `.app`) para no chocar con el tema.
2. **Datos** en JSON, guardados en base64 dentro de un `<script type="application/json">`.
   Base64 evita que el editor de WordPress altere comillas, acentos o etiquetas.
3. **Código** JavaScript, también en base64, que lee los datos y dibuja cada sección.

Para leer y escribir base64 con acentos sin dañarlos:

```js
const dec = b => new TextDecoder().decode(Uint8Array.from(atob(b), c => c.charCodeAt(0)));
const enc = s => {
  const u = new TextEncoder().encode(s);
  let b = '';
  for (let i = 0; i < u.length; i += 0x8000) b += String.fromCharCode.apply(null, u.subarray(i, i + 0x8000));
  return btoa(b);
};
```

Edita el bloque por la API REST de WordPress (`/wp/v2/blocks/<id>`) desde una sesión de administrador ya abierta.
Antes de guardar, crea un **borrador** con el contenido anterior como respaldo.

## Secciones por subdominio

Cada subdominio (`inscripciones.`, `contacto.`, `noticias.`…) carga la misma página.
El código mira `location.hostname` (y el `#` de la dirección) y muestra la sección que corresponde.
Así hay una sola fuente de verdad y enlaces cortos y fáciles de compartir.

## Lógica del servidor: mu-plugins

Los "must-use plugins" se cargan siempre y no se pueden desactivar por accidente desde el panel.
Usa uno por tema (formularios, retención de datos, ajustes de fichas) y una cabecera clara con nombre y descripción.

## Campus Moodle

- Encabezado y pie comunes con la web usando **HTML adicional** (`additionalhtmltopofbody` / `additionalhtmlfooter`).
- Guardar ese HTML en un archivo dentro de la carpeta de datos de Moodle y cargarlo desde `config.php` facilita respaldarlo y compararlo.
  Si haces esto, recuerda que el archivo **manda** sobre lo que diga la base de datos: edita el archivo.
- Avisos o preguntas al estudiante: que no se repitan en cada página; guarda la respuesta ligada a la sesión.

## Caché

Después de cada cambio, vacía la caché del plugin de caché (o borra su carpeta) y comprueba con una carga nueva de la página.

## Archivos del servidor

Con el panel del hosting (cPanel) puedes leer, guardar, mover y listar archivos mediante su API de archivos,
siempre desde la sesión que el usuario abrió. Los respaldos van a una carpeta **fuera** de `public_html`.
