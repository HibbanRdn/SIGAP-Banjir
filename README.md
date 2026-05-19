# Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

Aplikasi web Sistem Informasi Geografis (SIG) untuk membantu pemetaan titik rawan banjir, kejadian banjir, titik evakuasi, dan pos alat berat di Kota Bandar Lampung. Project ini menargetkan analisis lokasi berbasis PostgreSQL + PostGIS, termasuk rekomendasi titik evakuasi terdekat, rekomendasi pos alat berat terdekat, dan rute evakuasi sederhana menggunakan OSRM/OpenRouteService.

Project ini dirancang sebagai MVP akademik untuk mata kuliah Sistem Informasi Geografis. Fokus utamanya adalah data spasial, visualisasi peta, analisis titik terdekat, GeoJSON, dan alur respons banjir yang realistis untuk project kuliah.

## Project Overview

Ketika terjadi banjir, pihak terkait perlu mengetahui lokasi kejadian, tingkat keparahan, titik evakuasi terdekat, pos alat berat yang paling efisien untuk dikirim, dan rute evakuasi yang dapat digunakan sebagai referensi. Project ini dirancang untuk menjawab kebutuhan tersebut dalam bentuk aplikasi web SIG yang terukur dan tetap realistis untuk dikerjakan sebagai MVP.

Project ini bukan sekadar aplikasi CRUD karena inti sistemnya berada pada:

- penyimpanan data lokasi menggunakan PostGIS;
- visualisasi layer spasial di Leaflet;
- pengiriman data spasial dalam format GeoJSON;
- perhitungan jarak antar titik menggunakan query spasial;
- rekomendasi lokasi terdekat berbasis posisi kejadian banjir;
- routing referensi dari lokasi banjir ke titik evakuasi.

Sistem ini bukan sistem BPBD produksi dan tidak dimaksudkan sebagai sumber keputusan resmi. MVP ini adalah project akademik SIG yang menekankan pemanfaatan data spasial untuk mitigasi dan respons awal banjir.

## Key Features

Fitur berikut adalah fitur yang dirancang untuk MVP. Status implementasi mengikuti roadmap pada [docs/TASKS.md](docs/TASKS.md).

### Public Features

- Melihat peta banjir Kota Bandar Lampung.
- Melihat layer titik rawan banjir.
- Melihat layer kejadian banjir.
- Melihat layer titik evakuasi.
- Melihat layer pos alat berat.
- Melihat detail marker atau popup peta.
- Menggunakan filter dan layer control peta jika tersedia.

### Admin Features

- Login admin.
- Manajemen titik rawan banjir.
- Manajemen kejadian banjir.
- Manajemen titik evakuasi.
- Manajemen pos alat berat.
- Manajemen jenis dan unit alat berat.
- Dashboard ringkasan data.
- Rekomendasi titik evakuasi terdekat.
- Rekomendasi pos alat berat terdekat.
- Rute evakuasi sederhana.

### GIS Features

- Penyimpanan data spasial menggunakan PostgreSQL + PostGIS.
- Endpoint GeoJSON untuk konsumsi Leaflet.
- Perhitungan jarak menggunakan PostGIS.
- Nearest location analysis untuk evakuasi dan alat berat.
- Visualisasi layer peta interaktif.
- Routing referensi menggunakan OSRM/OpenRouteService.

## Tech Stack

| Layer | Technology | Purpose |
|---|---|---|
| Backend | Laravel | Struktur aplikasi, routing backend, validasi, auth, controller, service |
| View | Blade | Rendering UI server-side yang cocok untuk Laravel MVP |
| Styling | Tailwind CSS | Styling modern, konsisten, dan mudah dipoles |
| Database | PostgreSQL | Database relasional utama |
| Spatial Extension | PostGIS | Penyimpanan dan analisis data spasial |
| Map | Leaflet | Peta interaktif di frontend |
| Basemap | OpenStreetMap | Basemap peta |
| Routing | OSRM/OpenRouteService | Rute evakuasi referensi |
| Data Format | GeoJSON | Format data spasial untuk Leaflet |
| Font utama | Plus Jakarta Sans | Tipografi utama UI |
| Font teknis | JetBrains Mono | Angka, koordinat, jarak, durasi, ID, kode, dan metadata teknis |

Stack ini dipilih karena realistis untuk project akademik, cepat dikembangkan, dan tetap menunjukkan nilai SIG yang kuat melalui PostGIS, GeoJSON, Leaflet, dan analisis spasial.

## System Scope

### Termasuk MVP

- CRUD data utama.
- Peta publik berbasis Leaflet.
- Dashboard admin.
- GeoJSON API.
- Analisis titik evakuasi terdekat.
- Analisis pos alat berat terdekat.
- Routing sederhana menggunakan OSRM/OpenRouteService.
- Seed data demo yang membedakan data nyata, dummy, dan simulasi.

### Tidak Termasuk MVP

- Laporan publik.
- Upload foto.
- Multi-role kompleks.
- Tracking alat berat real-time.
- Prediksi banjir.
- Integrasi BMKG/IoT.
- pgRouting.
- Aplikasi mobile.
- Simulasi jalan tertutup.
- Dashboard prioritas wilayah yang terlalu kompleks.

## Architecture Overview

Alur umum aplikasi:

```text
User/Admin
  -> Laravel Blade UI
  -> Laravel Controller/API
  -> Service Layer
  -> PostgreSQL + PostGIS
  -> GeoJSON Response
  -> Leaflet Map
```

Alur routing evakuasi:

```text
Laravel Backend
  -> Ambil koordinat flood_event dan evacuation_point dari database
  -> Panggil OSRM/OpenRouteService
  -> Terima geometry rute
  -> Format sebagai GeoJSON LineString
  -> Tampilkan sebagai route layer di Leaflet
```

Analisis spasial diproses di backend. Frontend tidak mengakses database secara langsung dan tidak memanggil routing provider secara langsung jika provider membutuhkan API key.

## Main Modules

| Module | Description |
|---|---|
| Public Map Explorer | Halaman peta publik untuk melihat layer banjir, evakuasi, dan pos alat berat |
| Admin Dashboard | Ringkasan data, status dataset, dan akses cepat ke fitur admin |
| Flood Risk Management | Pengelolaan titik rawan banjir |
| Flood Event Management | Pengelolaan kejadian banjir aktif, historis, atau simulasi |
| Evacuation Point Management | Pengelolaan titik evakuasi, kapasitas, fasilitas, dan status |
| Heavy Equipment Management | Pengelolaan pos alat berat, jenis alat, dan unit alat berat |
| GeoJSON API | Endpoint data spasial untuk layer Leaflet |
| Spatial Analysis | Query PostGIS untuk mencari lokasi terdekat dan menghitung jarak |
| Routing Service | Integrasi OSRM/OpenRouteService untuk rute evakuasi referensi |
| Dataset/Seeder | Data demo yang jelas membedakan data nyata, dummy, dan simulasi |

## Database Overview

Database menggunakan PostgreSQL dengan extension PostGIS. Kolom spasial utama bernama `geom`.

### Tabel Inti

- `users`
- `flood_risk_points`
- `flood_events`
- `evacuation_points`
- `heavy_equipment_posts`
- `equipment_types`
- `heavy_equipment_units`

### Tabel Opsional

- `districts`
- `data_sources`
- `route_histories`
- `equipment_dispatch_logs`

### Aturan Spasial Utama

- Titik disimpan sebagai `geometry(Point, 4326)`.
- SRID yang digunakan adalah `4326`.
- Input form boleh memakai `longitude` dan `latitude`.
- Analisis spasial utama tetap memakai `geom`.
- Urutan koordinat PostGIS adalah `longitude, latitude`.
- Pembuatan titik menggunakan:

```sql
ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)
```

- Perhitungan jarak dalam meter menggunakan:

```sql
ST_Distance(geom::geography, geom::geography)
```

- Response peta menggunakan:

```sql
ST_AsGeoJSON(geom)
```

- Koordinat GeoJSON memakai format `[longitude, latitude]`.

## Dataset Notes

Dataset project harus membedakan data nyata, dummy, dan simulasi secara eksplisit.

Aturan dataset:

- Data nyata harus memiliki sumber.
- Data dummy tidak boleh diklaim sebagai data resmi.
- Data simulasi digunakan untuk skenario demo.
- Data alat berat boleh dummy realistis.
- Data banjir sebaiknya menggunakan data nyata jika tersedia.
- Kapasitas titik evakuasi boleh berupa estimasi akademik jika data resmi tidak tersedia.
- Koordinat harus divalidasi melalui peta atau geocoding.
- Setiap data perlu metadata `source_type`, `source_reference`, `data_status`, dan `is_verified`.

Nilai `data_status`:

- `nyata`
- `dummy`
- `simulasi`

## API Overview

Base API:

```text
/api/v1
```

Endpoint utama yang dirancang:

| Endpoint | Purpose |
|---|---|
| `/api/v1/geojson/flood-risks` | Layer titik rawan banjir |
| `/api/v1/geojson/flood-events` | Layer kejadian banjir |
| `/api/v1/geojson/evacuation-points` | Layer titik evakuasi |
| `/api/v1/geojson/heavy-equipment-posts` | Layer pos alat berat |
| `/api/v1/analysis/flood-events/{id}/nearest-evacuation` | Rekomendasi titik evakuasi terdekat |
| `/api/v1/analysis/flood-events/{id}/nearest-equipment` | Rekomendasi pos alat berat terdekat |
| `/api/v1/routing/flood-events/{id}/to-nearest-evacuation` | Rute ke titik evakuasi terdekat |

Catatan:

- Endpoint GeoJSON digunakan oleh Leaflet.
- Query spasial diproses di backend.
- Routing provider dipanggil dari backend.
- API key OpenRouteService tidak boleh diekspos ke frontend.
- Endpoint GeoJSON mengembalikan `FeatureCollection`.

## UI/UX Direction

Arah UI project:

```text
Civic Flood Response Map Explorer with Modern Component System
```

Karakter UI:

- civic;
- calm;
- modern;
- map-first;
- data-oriented;
- academic but not outdated;
- polished;
- tidak AI slop;
- tidak seperti admin panel default yang kaku.

Frontend tetap menggunakan Laravel Blade + Tailwind CSS. Project ini tidak menggunakan React, Next.js, atau shadcn/ui asli. Prinsip modern component system diambil sebagai referensi visual: spacing rapi, border halus, radius konsisten, state hover/focus jelas, komponen reusable, dan hierarchy tipografi yang baik.

Font:

- Plus Jakarta Sans untuk heading, body text, navigasi, tombol, form, tabel, card, badge, popup peta, dan microcopy.
- JetBrains Mono hanya untuk angka statistik, koordinat, jarak, durasi, ID, kode, SRID, metadata teknis, dan nilai teknis lain.

### Brand Assets

Asset brand ditempatkan di folder publik Laravel:

| Asset | Path | Penggunaan |
|---|---|---|
| Logo utama | `public/assets/brand/logo-utama.png` | Header, login page, sidebar/topbar, dan tampilan yang membutuhkan logo lengkap |
| Logo icon | `public/assets/brand/logo-icon.png` | Favicon, app icon, sidebar compact, dan logo kecil |
| Favicon PNG | `public/favicon.png` | Favicon utama berbasis PNG |
| Favicon ICO | `public/favicon.ico` | Fallback favicon untuk browser yang membutuhkan `.ico` |

## Requirements

Kebutuhan umum:

- PHP 8.x sesuai versi Laravel yang digunakan.
- Laravel 10/11/12 sesuai project aktual.
- Composer.
- Node.js LTS dan npm.
- PostgreSQL 14+ atau sesuai environment.
- Extension PostGIS.
- Git.
- Internet connection untuk basemap OpenStreetMap dan routing provider eksternal.

Versi pasti mengikuti kondisi project aktual dan environment lokal.

## Installation

Langkah umum setup project Laravel:

```bash
git clone <repository-url>
cd SIG_FIX
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Konfigurasi database PostgreSQL di `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nama_database
DB_USERNAME=postgres
DB_PASSWORD=password
```

Aktifkan PostGIS pada database:

```sql
CREATE EXTENSION IF NOT EXISTS postgis;
```

Jalankan migration dan seeder setelah implementasi database tersedia:

```bash
php artisan migrate
php artisan db:seed
```

Jalankan asset build dan development server:

```bash
npm run dev
php artisan serve
```

Catatan: jika migration, seeder, atau struktur Laravel belum tersedia di branch saat ini, langkah tersebut dilakukan setelah fase implementasi terkait selesai.

## Environment Variables

Variabel `.env` penting:

```env
APP_NAME="SIG Banjir Bandar Lampung"
APP_ENV=local
APP_KEY=
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nama_database
DB_USERNAME=postgres
DB_PASSWORD=password

ROUTING_PROVIDER=osrm
OSRM_BASE_URL=https://router.project-osrm.org
OPENROUTESERVICE_API_KEY=
```

Catatan keamanan:

- Jangan commit file `.env`.
- Simpan API key hanya di backend.
- Jangan expose API key OpenRouteService ke frontend.
- Gunakan `.env.example` untuk contoh konfigurasi tanpa secret.

## Running the Application

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite/Tailwind development server:

```bash
npm run dev
```

URL lokal umum:

- Aplikasi Laravel: `http://127.0.0.1:8000`
- Vite dev server mengikuti output dari `npm run dev`

## Development Workflow

Workflow contributor:

1. Baca [AGENTS.md](AGENTS.md).
2. Baca dokumen di folder [docs](docs) sesuai task.
3. Pilih task dari [docs/TASKS.md](docs/TASKS.md).
4. Kerjakan satu phase kecil.
5. Jalankan test atau validasi manual.
6. Review perubahan.
7. Laporkan file yang diubah, alasan perubahan, dan cara test.

Aturan penting:

- Jangan mengerjakan fitur backlog sebelum MVP selesai.
- Jangan mengganti stack.
- Jangan mencampur data dummy dan nyata tanpa label.
- Jangan menaruh API key di frontend.
- Jangan membuat fitur di luar scope tanpa diskusi.
- Pastikan fitur SIG benar-benar memakai PostGIS.

## Project Documentation

Dokumen perencanaan project:

| Document | Purpose |
|---|---|
| [AGENTS.md](AGENTS.md) | Instruksi kerja permanen untuk agent/Codex dan contributor |
| [docs/REQUIREMENTS.md](docs/REQUIREMENTS.md) | Scope, tujuan, batasan, aktor, fitur MVP, dan kriteria keberhasilan |
| [docs/DATABASE.md](docs/DATABASE.md) | Desain database, tabel, relasi, PostGIS, index, dan query spasial |
| [docs/DATASET.md](docs/DATASET.md) | Dataset, sumber data, dummy/simulasi, validasi koordinat, dan seed data |
| [docs/API.md](docs/API.md) | Endpoint, request, response, GeoJSON, validasi, error handling, dan routing |
| [docs/UI.md](docs/UI.md) | Arah UI/UX, design system, layout, font, komponen, dan interaction state |
| [docs/TASKS.md](docs/TASKS.md) | Roadmap pengerjaan MVP dari setup sampai demo |

Folder `docs` adalah sumber utama perencanaan project. Jika ada konflik implementasi, ikuti prioritas dokumen yang dijelaskan di `AGENTS.md`.

## Demo Scenario

Skenario demo minimal:

1. Buka peta publik.
2. Lihat layer titik rawan banjir.
3. Lihat kejadian banjir aktif.
4. Login admin.
5. Tambahkan kejadian banjir.
6. Cari titik evakuasi terdekat.
7. Cari pos alat berat terdekat.
8. Tampilkan rute evakuasi.
9. Jelaskan perbedaan data nyata, dummy, dan simulasi.

## Testing and Validation

Checklist validasi MVP:

- [ ] Login admin berjalan.
- [ ] CRUD data utama berjalan.
- [ ] Validasi koordinat berjalan.
- [ ] PostGIS aktif.
- [ ] GeoJSON valid.
- [ ] Leaflet dapat membaca GeoJSON.
- [ ] Rekomendasi titik evakuasi terdekat berjalan.
- [ ] Rekomendasi pos alat berat terdekat berjalan.
- [ ] Routing berjalan atau error provider tertangani.
- [ ] Data dummy/simulasi terlihat jelas.
- [ ] UI responsif dasar tidak rusak.
- [ ] Endpoint admin terlindungi.

MVP akademik tidak harus langsung memiliki automated test penuh, tetapi validasi manual harus jelas dan dapat diulang.

## Known Limitations

Keterbatasan MVP:

- Bukan sistem BPBD resmi.
- Data demo dapat mengandung data dummy atau simulasi.
- Rute bersifat referensi, bukan rute resmi.
- Rute belum mempertimbangkan jalan tertutup.
- Belum ada prediksi banjir.
- Belum ada tracking alat berat real-time.
- Belum ada laporan publik.
- Akurasi koordinat bergantung pada validasi dataset.
- Dataset awal dapat berkembang setelah verifikasi sumber data.

## Roadmap

### MVP

MVP mengikuti urutan pada [docs/TASKS.md](docs/TASKS.md):

- Project preparation.
- Laravel project setup.
- Database dan PostGIS setup.
- Dataset dan seeder.
- Model dan relationship.
- Admin authentication.
- CRUD data inti.
- GeoJSON API.
- Spatial analysis API.
- Routing API.
- Admin dashboard UI.
- Public map explorer.
- Detail kejadian banjir.
- UI polish.
- Testing dan final demo.

### Future Improvements

- Laporan publik.
- Upload foto.
- Role petugas lapangan.
- Validasi laporan.
- pgRouting.
- Integrasi cuaca/BMKG.
- Integrasi IoT/sensor tinggi air.
- Dashboard prioritas wilayah yang lebih kompleks.
- Mobile support lebih lanjut.
- Tracking alat berat real-time.

Fitur future improvements tidak boleh dikerjakan sebelum MVP stabil kecuali scope project diubah secara eksplisit.

## Contributing

Panduan contributor:

- Baca [AGENTS.md](AGENTS.md) sebelum mulai.
- Ikuti roadmap pada [docs/TASKS.md](docs/TASKS.md).
- Buat perubahan kecil, terarah, dan mudah direview.
- Jaga konsistensi UI, API, database, dan dataset.
- Jangan menambah fitur tanpa diskusi.
- Sertakan cara test atau validasi manual.
- Jika memakai data dummy, beri label dengan jelas.
- Jika menambah endpoint spasial, pastikan format GeoJSON dan urutan koordinat benar.

Template laporan perubahan:

```text
Summary:
Changed files:
How to test:
Notes:
```

## Commit Message Guideline

Format commit:

```text
type(scope): short description
```

Contoh:

```text
docs(readme): add project documentation
feat(map): add flood event geojson layer
fix(database): correct coordinate order
style(ui): polish dashboard cards
```

Jenis commit yang disarankan:

- `docs`
- `feat`
- `fix`
- `refactor`
- `style`
- `test`
- `chore`

## License

License belum ditentukan.

## Academic Context

Project ini dibuat untuk mata kuliah Sistem Informasi Geografis. Fokus akademiknya adalah pemanfaatan data spasial untuk mendukung mitigasi dan respons banjir melalui PostGIS, Leaflet, GeoJSON, analisis titik terdekat, dan visualisasi rute.

## Final Notes

Project ini mengutamakan implementasi SIG yang realistis, rapi, dan bisa dipresentasikan. Nilai utama project ada pada PostGIS, Leaflet, GeoJSON, analisis titik terdekat, dan visualisasi rute evakuasi. Data dummy dan simulasi harus selalu diberi label agar tidak tercampur dengan data nyata.
