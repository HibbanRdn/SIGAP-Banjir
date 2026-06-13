# Database Deployment Guide PostgreSQL + PostGIS

Panduan ini menjelaskan setup database production untuk SIGAP Banjir.

Target:

```text
Database: sigap_banjir
User: sigap_user
Extension: postgis
```

Jangan jalankan `migrate:fresh` di production. Jika database production sudah berisi data, backup dulu sebelum restore.

## A. Buat User dan Database

Masuk ke PostgreSQL sebagai user postgres:

```bash
sudo -u postgres psql
```

Jalankan SQL:

```sql
CREATE DATABASE sigap_banjir;
CREATE USER sigap_user WITH ENCRYPTED PASSWORD 'GANTI_DENGAN_PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON DATABASE sigap_banjir TO sigap_user;
\c sigap_banjir
CREATE EXTENSION IF NOT EXISTS postgis;
SELECT PostGIS_Version();
GRANT ALL ON SCHEMA public TO sigap_user;
ALTER SCHEMA public OWNER TO sigap_user;
\q
```

Catatan:

1. Ganti password langsung di terminal VPS.
2. Jangan tulis password asli di repo atau chat.
3. `CREATE EXTENSION postgis` wajib sebelum restore data spasial.

## B. Konfigurasi `.env`

Pastikan `.env` di VPS berisi:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sigap_banjir
DB_USERNAME=sigap_user
DB_PASSWORD=GANTI_DENGAN_PASSWORD_DATABASE
```

Setelah `.env` benar:

```bash
php artisan config:clear
php artisan config:cache
```

## C. Opsi 1: Migrate + Seed

Gunakan jika ingin membuat database dari migration dan seeder.

```bash
cd /var/www/sigap-banjir
php artisan migrate
php artisan db:seed
```

Catatan:

1. Jangan jalankan `php artisan migrate:fresh`.
2. Jangan jalankan seeder destructive tanpa backup.
3. Opsi ini dapat menghasilkan data dari seeder, tetapi belum tentu identik dengan database lokal jika ada input manual lokal.

## D. Opsi 2: Dump/Restore dari Database Lokal

Direkomendasikan untuk SIGAP Banjir karena database lokal sudah berisi dataset final.

### 1. Buat dump di lokal

Di komputer lokal:

```bash
cd /Users/muhamadhibbanramadhan/Downloads/SIG_FIX
pg_dump -Fc -d "sigap-banjir" -f sigap_banjir.dump
```

Jika PostgreSQL lokal membutuhkan user tertentu:

```bash
pg_dump -Fc -U postgres -h 127.0.0.1 -d "sigap-banjir" -f sigap_banjir.dump
```

### 2. Upload dump ke VPS

```bash
scp sigap_banjir.dump root@IP_VPS:/tmp/sigap_banjir.dump
```

Jika login bukan root:

```bash
scp sigap_banjir.dump USERNAME@IP_VPS:/tmp/sigap_banjir.dump
```

### 3. Restore di VPS

Pastikan database target sudah dibuat dan PostGIS sudah aktif.

```bash
sudo -u postgres pg_restore -d sigap_banjir --clean --if-exists --no-owner /tmp/sigap_banjir.dump
```

Setelah restore, grant ulang:

```bash
sudo -u postgres psql -d sigap_banjir -c "GRANT ALL ON SCHEMA public TO sigap_user;"
sudo -u postgres psql -d sigap_banjir -c "GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO sigap_user;"
sudo -u postgres psql -d sigap_banjir -c "GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO sigap_user;"
```

Jika ingin semua object dimiliki user aplikasi:

```bash
sudo -u postgres psql -d sigap_banjir -c "ALTER SCHEMA public OWNER TO sigap_user;"
```

Catatan:

1. `--clean --if-exists` akan membersihkan object yang ada di database target sebelum restore. Pastikan targetnya benar.
2. Jangan restore ke database yang salah.
3. Jika restore gagal karena ownership, gunakan `--no-owner` seperti contoh di atas.

## E. Validasi PostGIS

```bash
sudo -u postgres psql -d sigap_banjir -c "SELECT PostGIS_Version();"
```

Validasi kolom geometry:

```bash
sudo -u postgres psql -d sigap_banjir -c "\\d flood_events"
sudo -u postgres psql -d sigap_banjir -c "\\d flood_risk_points"
```

## F. Validasi dari Laravel

Di folder project:

```bash
cd /var/www/sigap-banjir

php artisan migrate:status
php artisan tinker --execute="echo App\\Models\\FloodEvent::count();"
php artisan tinker --execute="echo App\\Models\\FloodRiskPoint::count();"
php artisan tinker --execute="echo App\\Models\\EvacuationPoint::count();"
php artisan tinker --execute="echo App\\Models\\HeavyEquipmentPost::count();"
```

Target dari database lokal saat ini:

| Model | Target Count |
|---|---:|
| `FloodEvent` | 12 |
| `FloodRiskPoint` | 12 |
| `EvacuationPoint` | 10 |
| `HeavyEquipmentPost` | 6 |

Validasi status data:

```bash
php artisan tinker --execute="echo App\\Models\\FloodEvent::where('data_status','nyata')->count();"
php artisan tinker --execute="echo App\\Models\\FloodRiskPoint::where('data_status','nyata')->count();"
```

Target:

```text
12
12
```

## G. Validasi API GeoJSON

Setelah Nginx dan domain aktif:

```bash
curl -s https://pindahtangan.my.id/api/v1/geojson/flood-events | head
curl -s https://pindahtangan.my.id/api/v1/geojson/flood-risks | head
curl -s https://pindahtangan.my.id/api/v1/geojson/district-flood-intensity | head
```

Response harus berupa:

```json
{
  "type": "FeatureCollection",
  "features": []
}
```

`features` tidak boleh kosong untuk `flood-events`, `flood-risks`, dan `district-flood-intensity`.
