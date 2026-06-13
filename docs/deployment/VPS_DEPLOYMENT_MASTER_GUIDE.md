# VPS Deployment Master Guide SIGAP Banjir

## A. Ringkasan

SIGAP Banjir adalah aplikasi Laravel + Blade + Tailwind CSS + Vite dengan database PostgreSQL/PostGIS dan layer peta Leaflet/GeoJSON. Karena project membutuhkan PostgreSQL + extension PostGIS, VPS adalah pilihan deployment yang paling tepat dibanding shared hosting biasa.

Target domain:

```text
pindahtangan.my.id
```

Target root aplikasi di server:

```text
/var/www/sigap-banjir
```

Target database production:

```text
Database: sigap_banjir
User: sigap_user
```

Panduan ini tidak menjalankan deployment sungguhan dan tidak menyimpan password asli. Semua credential harus dibuat langsung di server, bukan ditulis di chat atau commit repository.

## B. Hasil Audit Project Lokal

Audit lokal terakhir:

| Item | Hasil |
|---|---|
| PHP lokal | 8.5.5 |
| Composer | 2.9.5 |
| Node lokal | 24.10.0 |
| npm lokal | 11.6.0 |
| Laravel | 12.59.0 |
| Constraint PHP project | `^8.2` |
| Database driver | `pgsql` |
| Route total | 64 route |
| `npm run build` | Sukses |
| `php artisan test` | Sukses, 2 test lulus |
| `php artisan route:cache` | Sukses, lalu route cache sudah di-clear kembali |
| `storage/app/public` | Belum linked di lokal menurut `php artisan about` |
| GeoJSON kecamatan | Ada dan tracked: `storage/app/public/geojson/bandar-lampung-districts.geojson` |

Catatan audit:

1. `composer.json` meminta PHP `^8.2`. Untuk VPS, gunakan PHP 8.3 sebagai rekomendasi utama, atau PHP 8.2 jika environment lebih stabil di provider.
2. Frontend public map memakai endpoint relatif seperti `/api/v1/geojson/flood-events`, bukan hardcoded `localhost`.
3. Hasil pencarian `localhost` hanya ditemukan pada default config/env example dan build vendor, bukan hardcoded API production.
4. `.env` sudah di-ignore oleh Git.
5. `AdminUserSeeder` masih memiliki password demo hardcoded. Untuk production, buat/ganti admin credential langsung di server setelah restore database.
6. `APP_DEBUG` lokal masih `true`; production wajib `false`.

## C. Spesifikasi VPS yang Disarankan

Minimum:

| Komponen | Minimum |
|---|---|
| CPU | 1 vCPU |
| RAM | 1 GB |
| Storage | 20 GB SSD |
| OS | Ubuntu 22.04 LTS atau Ubuntu 24.04 LTS |

Disarankan:

| Komponen | Rekomendasi |
|---|---|
| CPU | 1-2 vCPU |
| RAM | 2 GB |
| Storage | 40 GB SSD |
| OS | Ubuntu 24.04 LTS, atau Ubuntu 22.04 LTS dengan PHP 8.2/8.3 dari PPA |

RAM 2 GB lebih aman karena server menjalankan Laravel, PHP-FPM, Nginx, PostgreSQL/PostGIS, dan build asset Vite. Jika RAM hanya 1 GB, build asset sebaiknya dilakukan di lokal lalu upload hasil `public/build`, atau tambahkan swap.

## D. Alur Deployment

1. Beli/aktifkan VPS.
2. Catat IP public VPS.
3. Login SSH sebagai root atau user sudo.
4. Setup firewall.
5. Install Nginx, PHP-FPM, Composer, Node.js/npm.
6. Install PostgreSQL + PostGIS.
7. Upload project ke `/var/www/sigap-banjir`.
8. Buat database `sigap_banjir` dan user `sigap_user`.
9. Setup `.env` production.
10. Install dependency Composer.
11. Install dependency npm dan build asset.
12. Setup permission Laravel.
13. Import database lokal dengan `pg_dump/pg_restore`, atau jalankan migrate/seed jika ingin dataset dari seeder.
14. Setup Nginx server block.
15. Arahkan DNS Rumahweb ke IP VPS.
16. Aktifkan SSL Let’s Encrypt.
17. Jalankan post-deployment test.

## E. Metode Deploy Project

### Opsi 1: Git Clone

Direkomendasikan jika repository sudah siap dan tidak menyimpan credential.

```bash
cd /var/www
git clone URL_REPOSITORY sigap-banjir
cd /var/www/sigap-banjir
```

Kelebihan:

1. Deploy dan update lebih rapi.
2. Perubahan bisa dilacak.
3. Tidak perlu upload ZIP manual setiap revisi.

Catatan:

1. Jangan commit `.env`.
2. Pastikan file `storage/app/public/geojson/bandar-lampung-districts.geojson` ikut di repository.
3. Pastikan branch yang dipakai sudah berisi dataset final dan layer intensitas kecamatan.

### Opsi 2: Upload ZIP/SCP/SFTP

Gunakan jika repo belum online.

Di lokal:

```bash
cd /Users/muhamadhibbanramadhan/Downloads
zip -r sigap-banjir.zip SIG_FIX \
  -x "SIG_FIX/vendor/*" \
  -x "SIG_FIX/node_modules/*" \
  -x "SIG_FIX/.git/*" \
  -x "SIG_FIX/.env"
scp sigap-banjir.zip root@IP_VPS:/tmp/sigap-banjir.zip
```

Di VPS:

```bash
mkdir -p /var/www/sigap-banjir
unzip /tmp/sigap-banjir.zip -d /tmp
rsync -av /tmp/SIG_FIX/ /var/www/sigap-banjir/
```

## F. Metode Database

### Opsi 1: Migrate + Seed

```bash
php artisan migrate
php artisan db:seed
```

Gunakan hanya jika ingin membangun database dari migration dan seeder. Jangan gunakan `migrate:fresh` untuk production.

### Opsi 2: Dump/Restore dari Lokal

Direkomendasikan karena database lokal sudah berisi dataset final berbasis berita/jurnal dan data demo resource.

Alur ringkas:

1. Jalankan `pg_dump` di lokal.
2. Upload file dump ke VPS.
3. Buat database dan aktifkan PostGIS di VPS.
4. Jalankan `pg_restore` ke database production.
5. Validasi jumlah data dan endpoint API.

Detail ada di `DATABASE_DEPLOYMENT_GUIDE.md`.

## G. Laravel Production Commands

Jalankan setelah project ada di server:

```bash
cd /var/www/sigap-banjir

composer install --no-dev --optimize-autoloader
npm install
npm run build

cp .env.example .env
php artisan key:generate
```

Edit `.env` production sebelum cache config:

```bash
nano .env
```

Setelah `.env` benar:

```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika `route:cache` gagal, jangan dipaksa. Baca pesan error, jalankan:

```bash
php artisan route:clear
```

Pada audit lokal saat ini, `route:cache` berhasil.

## H. Permission Laravel

```bash
chown -R www-data:www-data /var/www/sigap-banjir
find /var/www/sigap-banjir -type f -exec chmod 644 {} \;
find /var/www/sigap-banjir -type d -exec chmod 755 {} \;
chmod -R ug+rwx /var/www/sigap-banjir/storage /var/www/sigap-banjir/bootstrap/cache
```

## I. Nginx Server Block

Contoh untuk PHP 8.3:

```nginx
server {
    listen 80;
    server_name pindahtangan.my.id www.pindahtangan.my.id;

    root /var/www/sigap-banjir/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Simpan sebagai:

```bash
/etc/nginx/sites-available/sigap-banjir
```

Aktifkan:

```bash
ln -s /etc/nginx/sites-available/sigap-banjir /etc/nginx/sites-enabled/sigap-banjir
nginx -t
systemctl reload nginx
```

Catatan: path socket PHP-FPM harus disesuaikan. Cek dengan:

```bash
ls /run/php/
```

## J. DNS dan SSL

DNS Rumahweb diarahkan dengan A Record ke IP VPS. Detail ada di `DNS_RUMAHWEB_GUIDE.md`.

Setelah DNS mengarah ke VPS:

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d pindahtangan.my.id -d www.pindahtangan.my.id
certbot renew --dry-run
```

## K. File GeoJSON Kecamatan

Endpoint intensitas kecamatan membaca file:

```text
storage/app/public/geojson/bandar-lampung-districts.geojson
```

Audit lokal:

```text
File tersedia, tracked Git, ukuran sekitar 676 KB.
```

Saat deploy:

1. Pastikan file ikut terupload.
2. Pastikan permission terbaca oleh PHP-FPM.
3. Jalankan `php artisan storage:link`.
4. Test endpoint `/api/v1/geojson/district-flood-intensity`.

Jangan pindahkan file GeoJSON tanpa audit kode service yang membacanya.

## L. Referensi Resmi

Rujukan teknis:

1. Laravel Deployment Documentation: https://laravel.com/docs/12.x/deployment
2. Laravel Installation / Server Requirements: https://laravel.com/docs/12.x/installation
3. PostGIS Documentation: https://postgis.net/documentation/
4. Certbot Documentation: https://eff-certbot.readthedocs.io/
