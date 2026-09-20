# Cómo reemplazar los fondos por fotografías generadas con IA

1. Genere la imagen (Midjourney, DALL·E, Firefly, Imagen, etc.) en formato horizontal 16:9, mínimo 2400×1350 px.
2. Expórtela como .jpg o .webp (≤ 350 KB, calidad ~75) y cópiela en `/assets`.
3. Cambie la ruta en `js/site-data.js` (secciones `images`, `pages` y `bands`). No hay que tocar nada más:
   el sistema ya aplica degradados oscuros para que el texto siga siendo legible.

Estilo común para todos los prompts:
"photorealistic, cinematic architectural photography, deep midnight navy and warm champagne tones, muted colors,
low-key lighting, soft haze, no people, no text, no logos, no gavels or scales of justice, premium, editorial"

| Clave en site-data.js | Prompt sugerido |
|---|---|
| images.hero | Long colonnade corridor of a modern institutional building at dusk, warm light at the far end, polished stone floor reflections |
| pages.nosotros / bands.nosotros | Private law library, floor-to-ceiling dark wood shelves, leather-bound books, warm brass reading lamp, quiet atmosphere |
| pages.areas / images.areasBg | Neoclassical courthouse facade with stone columns at blue hour, subtle uplighting |
| pages.equipo / bands.equipo | Empty executive boardroom, long dark table, floor-to-ceiling windows overlooking Mexico City skyline at dusk |
| pages.experiencia / bands.home | Mexico City skyline at twilight seen from a high floor, layered towers, warm horizon glow |
| pages.insights | Minimalist architectural interior, nested concrete arches leading to soft warm light |
| pages.contacto | Glass office tower facade seen from below, converging lines, night sky in navy |
| pages.legal / bands.areas | Aerial view of Mexico City avenues at night, sparse warm lights, dark tones |

Nota: use únicamente imágenes cuyos derechos de uso comercial estén claros y evite representar personas, logotipos o edificios reales identificables sin autorización.
