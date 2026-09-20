# Despliegue (Hostinger u otro hosting con PHP 8.3+)

## Requisitos
PHP 8.3+ con las extensiones `intl`, `mbstring`, `pdo_mysql` (o `pdo_sqlite`), `fileinfo`, `gd` (recomendada para imágenes) y `zip`.

## Pasos
1. Compile los assets en su equipo: `npm ci && npm run build` y suba también `public/build`.
2. Suba el proyecto. El **document root** del dominio debe apuntar a la carpeta `public/`.
   Si el hosting no lo permite, mueva el contenido de `public/` a `public_html/` y ajuste las rutas de `require` en `index.php` para que apunten a `vendor/autoload.php` y `bootstrap/app.php` del proyecto.
3. En el servidor: `composer install --no-dev --optimize-autoloader`.
4. Copie `.env.example` a `.env` y configure como mínimo:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://sudominio.com
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=...
   DB_USERNAME=...
   DB_PASSWORD=...
   MAIL_MAILER=smtp
   MAIL_HOST=...   MAIL_PORT=587   MAIL_USERNAME=...   MAIL_PASSWORD=...
   MAIL_FROM_ADDRESS="contacto@sudominio.com"
   ADMIN_EMAIL=usted@sudominio.com
   ADMIN_PASSWORD=<contraseña larga>
   LEADS_NOTIFY_EMAIL=usted@sudominio.com
   ```
5. `php artisan key:generate --force`, `php artisan migrate --force --seed` y `php artisan storage:link`.
6. Optimice: `php artisan optimize`.
7. Entre a `/admin` y complete la lista **«Antes de publicar»** del escritorio (nombre, contacto, abogados, textos legales…).

## Notas
- Las solicitudes del formulario se guardan siempre en la base de datos (`/admin` → Solicitudes y citas) aunque falle el correo.
- Limitación de envíos, honeypot y CSP estricta vienen activados. Configure además HTTPS (HSTS se envía automáticamente bajo HTTPS).
- Para actualizar contenido no hace falta tocar código: todo se edita desde el panel.
