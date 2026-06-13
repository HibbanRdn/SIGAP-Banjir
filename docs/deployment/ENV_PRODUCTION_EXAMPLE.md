# ENV Production Example SIGAP Banjir

File ini adalah contoh isi `.env` production. Jangan isi password asli di dokumen ini dan jangan commit file `.env` production.

Lokasi file di VPS:

```text
/var/www/sigap-banjir/.env
```

## Contoh `.env.production`

```env
APP_NAME="SIGAP Banjir"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://pindahtangan.my.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sigap_banjir
DB_USERNAME=sigap_user
DB_PASSWORD=GANTI_DENGAN_PASSWORD_DATABASE

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@pindahtangan.my.id"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"

ROUTING_PROVIDER=osrm
OSRM_BASE_URL=https://router.project-osrm.org
OPENROUTESERVICE_API_KEY=
```

## Catatan Penting

1. `APP_DEBUG=false` wajib untuk production.
2. `APP_URL` wajib memakai domain HTTPS: `https://pindahtangan.my.id`.
3. `APP_KEY` dibuat di server dengan:

   ```bash
   php artisan key:generate
   ```

4. Jangan commit `.env`.
5. Jangan menulis password database asli di repo, chat, screenshot, atau laporan.
6. Jika memakai `SESSION_DRIVER=file`, pastikan folder `storage/framework/sessions` writable oleh `www-data`.
7. Jika ingin session database, buat migration/session table dulu:

   ```bash
   php artisan session:table
   php artisan migrate
   ```

   Untuk MVP saat ini, `SESSION_DRIVER=file` sudah cukup.

8. `OSRM_BASE_URL=https://router.project-osrm.org` memakai OSRM demo server. Rute tetap bersifat referensi, bukan rute resmi kebencanaan.

## Setelah Edit `.env`

Jalankan:

```bash
php artisan config:clear
php artisan config:cache
php artisan about
```

Pastikan output:

```text
Environment: production
Debug Mode: OFF
URL: https://pindahtangan.my.id
Database: pgsql
```
