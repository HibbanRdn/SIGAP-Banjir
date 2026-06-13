# Troubleshooting Deployment SIGAP Banjir

Panduan ini berisi masalah umum saat deploy Laravel + Nginx + PostgreSQL/PostGIS.

## 1. 500 Internal Server Error

Gejala:

```text
Browser menampilkan 500 Internal Server Error.
```

Kemungkinan penyebab:

1. `.env` salah.
2. `APP_KEY` belum dibuat.
3. Permission `storage` atau `bootstrap/cache` salah.
4. Database tidak terhubung.
5. Config cache masih memakai env lama.

Command cek:

```bash
tail -n 100 /var/www/sigap-banjir/storage/logs/laravel.log
tail -n 100 /var/log/nginx/error.log
php artisan about
```

Solusi:

```bash
cd /var/www/sigap-banjir
php artisan key:generate
php artisan config:clear
php artisan config:cache
chmod -R ug+rwx storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 2. Permission Denied Storage/Cache

Gejala:

```text
The stream or file ".../storage/logs/laravel.log" could not be opened.
```

Command cek:

```bash
ls -lah /var/www/sigap-banjir/storage
ls -lah /var/www/sigap-banjir/bootstrap/cache
```

Solusi:

```bash
chown -R www-data:www-data /var/www/sigap-banjir/storage /var/www/sigap-banjir/bootstrap/cache
chmod -R ug+rwx /var/www/sigap-banjir/storage /var/www/sigap-banjir/bootstrap/cache
systemctl reload php8.3-fpm
```

## 3. APP_KEY Missing

Gejala:

```text
No application encryption key has been specified.
```

Solusi:

```bash
cd /var/www/sigap-banjir
php artisan key:generate
php artisan config:clear
php artisan config:cache
```

## 4. Vite Manifest Not Found

Gejala:

```text
Vite manifest not found at public/build/manifest.json
```

Penyebab:

1. `npm run build` belum dijalankan.
2. Folder `public/build` tidak ikut upload.
3. Permission file build salah.

Solusi:

```bash
cd /var/www/sigap-banjir
npm install
npm run build
ls -lah public/build/manifest.json
chown -R www-data:www-data public/build
```

Jika RAM VPS kecil, jalankan build di lokal lalu upload folder `public/build`.

## 5. Database Connection Refused

Gejala:

```text
SQLSTATE[08006] Connection refused
```

Command cek:

```bash
systemctl status postgresql
php artisan tinker --execute="DB::connection()->getPdo(); echo 'ok';"
```

Solusi:

1. Pastikan PostgreSQL aktif:

   ```bash
   systemctl start postgresql
   systemctl enable postgresql
   ```

2. Pastikan `.env` benar:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=sigap_banjir
   DB_USERNAME=sigap_user
   DB_PASSWORD=...
   ```

3. Clear config:

   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

## 6. PostGIS Extension Not Found

Gejala:

```text
type "geometry" does not exist
function st_distance does not exist
```

Command cek:

```bash
sudo -u postgres psql -d sigap_banjir -c "SELECT PostGIS_Version();"
```

Solusi:

```bash
apt install -y postgis
sudo -u postgres psql -d sigap_banjir -c "CREATE EXTENSION IF NOT EXISTS postgis;"
sudo -u postgres psql -d sigap_banjir -c "SELECT PostGIS_Version();"
```

Jika package PostGIS belum lengkap, install paket sesuai versi PostgreSQL:

```bash
apt-cache search "postgresql-.*-postgis"
```

## 7. GeoJSON Kecamatan Tidak Terbaca

Gejala:

1. Endpoint `/api/v1/geojson/district-flood-intensity` error.
2. Layer polygon kecamatan tidak tampil.
3. Log menyebut file GeoJSON tidak ditemukan.

Command cek:

```bash
ls -lah /var/www/sigap-banjir/storage/app/public/geojson/bandar-lampung-districts.geojson
php artisan storage:link
curl -s https://pindahtangan.my.id/api/v1/geojson/district-flood-intensity | head
```

Solusi:

1. Pastikan file ikut deploy.
2. Pastikan permission terbaca PHP-FPM:

   ```bash
   chown -R www-data:www-data /var/www/sigap-banjir/storage/app/public/geojson
   chmod -R 755 /var/www/sigap-banjir/storage/app/public/geojson
   ```

3. Jangan pindahkan file tanpa mengubah service backend.

## 8. Nginx 404 untuk Route Laravel

Gejala:

```text
/peta atau /admin/login 404 dari Nginx
```

Kemungkinan penyebab:

1. `root` Nginx bukan ke folder `public`.
2. `try_files` salah.
3. Server block belum aktif.

Command cek:

```bash
nginx -t
cat /etc/nginx/sites-available/sigap-banjir
ls -lah /etc/nginx/sites-enabled/
```

Solusi:

Pastikan:

```nginx
root /var/www/sigap-banjir/public;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Reload:

```bash
systemctl reload nginx
```

## 9. SSL Gagal

Gejala:

```text
Certbot gagal membuat certificate.
```

Kemungkinan penyebab:

1. DNS belum mengarah ke IP VPS.
2. Port 80/443 tertutup firewall.
3. Server block Nginx belum valid.

Command cek:

```bash
dig pindahtangan.my.id
dig www.pindahtangan.my.id
ufw status
nginx -t
curl -I http://pindahtangan.my.id
```

Solusi:

```bash
ufw allow 'Nginx Full'
systemctl reload nginx
certbot --nginx -d pindahtangan.my.id -d www.pindahtangan.my.id
```

## 10. OSRM Tidak Merespons

Gejala:

1. Rute tidak tampil.
2. API routing menampilkan error provider.

Command cek:

```bash
curl -I https://router.project-osrm.org
php artisan config:show services.routing
```

Solusi:

1. Pastikan `OSRM_BASE_URL=https://router.project-osrm.org`.
2. Pastikan VPS bisa akses internet outbound.
3. Coba ulang beberapa saat kemudian jika OSRM demo server rate limit/down.
4. Untuk production serius, pertimbangkan OSRM self-host atau provider routing dengan API key backend.

## 11. Map Tile Tidak Tampil

Gejala:

1. Peta abu-abu.
2. Marker tampil tetapi tile tidak.

Kemungkinan penyebab:

1. Koneksi browser ke tile provider terblokir.
2. Mixed content.
3. Rate limit tile provider.

Command cek:

1. Buka browser devtools console/network.
2. Cek URL tile OpenStreetMap/Esri.
3. Pastikan halaman memakai HTTPS.

Solusi:

1. Pastikan domain sudah HTTPS.
2. Jangan ubah URL tile menjadi HTTP.
3. Refresh dan cek koneksi internet client.

## 12. Mixed Content HTTP/HTTPS

Gejala:

```text
Browser console: Mixed Content
```

Penyebab:

1. `APP_URL` masih HTTP.
2. Asset/API dipanggil pakai absolute HTTP.

Command cek:

```bash
grep -Rni "http://pindahtangan\\|http://127.0.0.1\\|http://localhost" resources app routes config public .env
php artisan about
```

Solusi:

1. Set `.env`:

   ```env
   APP_URL=https://pindahtangan.my.id
   ```

2. Clear/cache config:

   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

## 13. Login Redirect Loop

Gejala:

1. Login sukses tetapi kembali ke login.
2. Session tidak tersimpan.

Kemungkinan penyebab:

1. Permission session storage salah.
2. `SESSION_DOMAIN` salah.
3. Config cache masih lama.
4. HTTPS/session cookie mismatch.

Command cek:

```bash
ls -lah storage/framework/sessions
php artisan about
tail -n 100 storage/logs/laravel.log
```

Solusi:

```bash
chown -R www-data:www-data storage/framework/sessions
chmod -R ug+rwx storage/framework/sessions
php artisan config:clear
php artisan config:cache
```

Gunakan:

```env
SESSION_DRIVER=file
SESSION_DOMAIN=null
SESSION_PATH=/
```

## 14. `route:cache` Gagal

Gejala:

```text
Unable to prepare route for serialization
```

Kemungkinan penyebab:

1. Ada route closure.
2. Ada route yang tidak serializable.

Command cek:

```bash
php artisan route:cache
```

Solusi:

1. Jika gagal, jalankan:

   ```bash
   php artisan route:clear
   ```

2. Aplikasi tetap bisa berjalan tanpa route cache.
3. Audit route closure jika ingin memperbaiki.

Catatan audit lokal saat ini: `route:cache` berhasil.
