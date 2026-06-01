# SIGAP Banjir

**Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung**

SIGAP Banjir adalah aplikasi web SIG akademik untuk memetakan titik rawan banjir, kejadian banjir, titik evakuasi, dan pos alat berat di Bandar Lampung. Aplikasi ini menekankan penggunaan PostgreSQL + PostGIS, GeoJSON, Leaflet, analisis jarak spasial, dan rute evakuasi referensi.

Project ini bukan sistem resmi BPBD atau pemerintah, bukan sistem kebencanaan produksi, dan tidak dimaksudkan sebagai sumber keputusan darurat resmi. Data simulasi dan dummy digunakan secara transparan untuk kebutuhan demonstrasi akademik.

## Fitur Utama

- Peta publik interaktif di `/peta` berbasis Leaflet.
- Layer GeoJSON untuk kejadian banjir, titik rawan banjir, titik evakuasi, dan pos alat berat.
- Basemap gratis tanpa token: OpenStreetMap Standard, Humanitarian OSM, dan Esri World Imagery.
- Marker pin kategori, popup ringkas, filter, layer toggle, legend, dan route LineString.
- Analisis resource terdekat berbasis PostGIS:
  - titik evakuasi terdekat;
  - pos alat berat terdekat;
  - ringkasan resource terdekat.
- Routing evakuasi referensi memakai OSRM demo server tanpa token.
- Admin login session-based.
- CRUD data inti:
  - kejadian banjir;
  - titik rawan banjir;
  - titik evakuasi;
  - pos alat berat;
  - jenis alat;
  - unit alat.
- Dashboard admin berbasis data database.
- Detail kejadian banjir admin sebagai decision support dengan mini map, rekomendasi resource, dan rute referensi.
- Halaman Sumber Data & Validasi untuk memantau status data nyata, simulasi, dummy, dan verifikasi.

## Teknologi

| Layer | Teknologi |
|---|---|
| Backend | Laravel |
| View | Blade |
| Styling | Tailwind CSS |
| Asset build | Vite |
| Database | PostgreSQL |
| Spatial extension | PostGIS |
| Map | Leaflet |
| Basemap | OpenStreetMap, Humanitarian OSM, Esri World Imagery |
| Routing | OSRM demo server |
| Format spasial | GeoJSON |
| Font UI | Plus Jakarta Sans |
| Font teknis | JetBrains Mono |

## Arsitektur Ringkas

```text
PostgreSQL + PostGIS
        |
        v
Laravel Backend dan Service Layer
        |
        v
GeoJSON / Spatial Analysis / Routing Response
        |
        v
Leaflet Public Map dan Admin Decision Support
```

Analisis spasial dilakukan di backend menggunakan PostGIS. Frontend tidak menghitung jarak sendiri dan tidak memanggil OSRM secara langsung.

## Setup Lokal

Prasyarat:

- PHP dan Composer.
- Node.js dan npm.
- PostgreSQL melalui Postgres.app atau instalasi lokal lain.
- Extension PostGIS.

Langkah setup:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Konfigurasi database pada `.env`:

```env
DB_CONNECTION=pgsql
DB_DATABASE=sigap-banjir
```

Aktifkan PostGIS pada database:

```sql
CREATE EXTENSION IF NOT EXISTS postgis;
```

Jalankan migration dan seeder:

```bash
php artisan migrate
php artisan db:seed
```

Build asset dan jalankan aplikasi:

```bash
npm run build
php artisan serve --host=127.0.0.1 --port=8001
```

Untuk mode pengembangan frontend, gunakan:

```bash
npm run dev
```

Jangan menjalankan `migrate:fresh` pada database demo jika data sudah disiapkan.

## Akun Demo Lokal

```text
Email: hibbanrdn@gmail.com
Password: admin123
```

Akun ini hanya untuk demo lokal dan kebutuhan akademik. Jangan gunakan credential demo ini untuk lingkungan produksi.

## Endpoint API Utama

Base path:

```text
/api/v1
```

GeoJSON:

```text
GET /api/v1/geojson/flood-events
GET /api/v1/geojson/flood-risks
GET /api/v1/geojson/evacuation-points
GET /api/v1/geojson/heavy-equipment-posts
```

Analisis spasial:

```text
GET /api/v1/analysis/flood-events/{floodEvent}/nearest-evacuation
GET /api/v1/analysis/flood-events/{floodEvent}/nearest-equipment
GET /api/v1/analysis/flood-events/{floodEvent}/nearest-resources
```

Routing referensi:

```text
GET /api/v1/routing/flood-events/{floodEvent}/to-nearest-evacuation
GET /api/v1/routing/flood-events/{floodEvent}/to-evacuation/{evacuationPoint}
```

Endpoint GeoJSON mengembalikan `FeatureCollection` langsung untuk Leaflet. Endpoint analisis dan routing mengembalikan JSON dengan struktur `success`, `message`, dan `data`.

## Data Demo dan Transparansi

Status dataset demo saat QA akhir:

| Data | Jumlah |
|---|---:|
| Kejadian banjir | 8 |
| Titik rawan banjir | 12 |
| Titik evakuasi | 10 |
| Pos alat berat | 6 |
| Unit alat berat | 15 |
| Jenis alat | 6 |

Ringkasan status data spasial utama:

| Status | Jumlah |
|---|---:|
| Simulasi | 30 |
| Dummy | 6 |
| Nyata | 0 |
| Perlu validasi | 36 |

Data simulasi dan dummy digunakan untuk kebutuhan demonstrasi akademik dan tidak diklaim sebagai data resmi. Status `source_type`, `source_reference`, `data_status`, dan `is_verified` ditampilkan di dashboard dan halaman Sumber Data & Validasi.

## Provider Gratis dan Attribution

- Leaflet digunakan sebagai library peta frontend.
- OpenStreetMap Standard dan Humanitarian OSM digunakan sebagai basemap gratis tanpa token.
- Esri World Imagery digunakan sebagai mode satelit gratis tanpa token untuk kebutuhan demo.
- Attribution tile tetap ditampilkan pada peta.
- OSRM demo server digunakan untuk rute evakuasi referensi tanpa akun dan tanpa API key.

Rute OSRM bersifat referensi. Rute belum mempertimbangkan jalan tertutup, kondisi banjir aktual, lalu lintas, atau keputusan resmi petugas lapangan.

## Alur Demo

Route utama:

```text
/peta
/admin/login
/admin/dashboard
/admin/flood-events
/admin/flood-events/{id}
/admin/data-sources
```

Alur yang disarankan:

1. Buka `/peta` untuk menunjukkan layer, marker, basemap, popup, rekomendasi, dan rute.
2. Login admin.
3. Buka dashboard untuk menunjukkan statistik data real.
4. Buka CRUD data inti secara singkat.
5. Buka detail kejadian banjir untuk decision support.
6. Buka Sumber Data & Validasi untuk menjelaskan transparansi data simulasi/dummy.

Panduan demo lengkap tersedia di [docs/DEMO_GUIDE.md](docs/DEMO_GUIDE.md).

## Batasan Sistem

- Data demo belum diklaim sebagai data resmi pemerintah.
- Semua data spasial utama saat ini masih perlu validasi.
- Rute evakuasi hanya referensi dan belum mempertimbangkan jalan tertutup atau kondisi banjir aktual.
- Belum ada route history.
- Belum ada rute ke pos alat berat.
- Belum ada workflow verifikasi massal.
- Belum ada upload bukti/dokumen sumber.
- Belum ada multi-role kompleks.
- Belum disiapkan sebagai deployment produksi.

## Dokumentasi

- [docs/REQUIREMENTS.md](docs/REQUIREMENTS.md)
- [docs/DATABASE.md](docs/DATABASE.md)
- [docs/DATASET.md](docs/DATASET.md)
- [docs/API.md](docs/API.md)
- [docs/UI.md](docs/UI.md)
- [docs/TASKS.md](docs/TASKS.md)
- [docs/FINAL_QA.md](docs/FINAL_QA.md)
- [docs/DEMO_GUIDE.md](docs/DEMO_GUIDE.md)

## Kontribusi

Panduan kontribusi:

- Baca [AGENTS.md](AGENTS.md) sebelum mulai.
- Ikuti roadmap pada [docs/TASKS.md](docs/TASKS.md).
- Jaga scope MVP akademik.
- Jangan menambah fitur di luar scope tanpa keputusan eksplisit.
- Jangan menghapus label data dummy/simulasi.
- Pastikan koordinat tetap memakai urutan longitude, latitude untuk PostGIS/GeoJSON/OSRM.
- Sertakan cara validasi setelah perubahan.

## Lisensi

Lisensi belum ditentukan.

## Konteks Akademik

Project ini dibuat untuk kebutuhan akademik mata kuliah Sistem Informasi Geografis. Nilai utama project berada pada pemanfaatan PostGIS, Leaflet, GeoJSON, analisis titik terdekat, rekomendasi resource, dan visualisasi rute evakuasi referensi.
