# QA Final SIGAP Banjir

Tanggal pemeriksaan: 1 Juni 2026

## Tujuan QA

QA final dilakukan untuk memastikan MVP SIGAP Banjir siap didemokan sebagai project akademik Sistem Informasi Geografis. Pemeriksaan berfokus pada alur utama, konsistensi dokumentasi, validasi endpoint, transparansi data, dan kestabilan UI tanpa menambah fitur baru.

## Scope Fitur yang Diperiksa

- Auth admin.
- Dashboard admin data real.
- CRUD core data.
- GeoJSON API.
- Spatial Analysis API.
- Routing API OSRM.
- Public Map Explorer `/peta`.
- Detail kejadian banjir admin sebagai decision support.
- Halaman Sumber Data & Validasi.
- Build asset dan view Blade.
- Dokumentasi akhir dan panduan demo.

## Environment Validasi

| Item | Nilai |
|---|---|
| Laravel | 12.59.0 |
| PHP | 8.5.5 |
| Composer | 2.9.5 |
| Node.js | v24.10.0 |
| npm | 11.6.0 |
| Database driver | pgsql |
| Database demo | `sigap-banjir` |
| Spatial extension | PostGIS aktif |
| Browser check | Codex in-app browser dan HTTP session check |
| Server validasi | `http://127.0.0.1:8001` |

## Status Data Validasi

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

## Command yang Dijalankan

```bash
git status --short
git branch --show-current
php artisan about --no-ansi
php artisan route:list
php artisan route:list --path=api/v1
php artisan migrate:status
composer dump-autoload --no-interaction
npm run build
php artisan view:cache && php artisan view:clear
php artisan test
```

Syntax check:

```bash
php -l app/Http/Controllers/Admin/DashboardController.php
php -l app/Http/Controllers/Admin/DataSourceController.php
php -l app/Http/Controllers/Api/V1/GeoJsonController.php
php -l app/Http/Controllers/Api/V1/SpatialAnalysisController.php
php -l app/Http/Controllers/Api/V1/RoutingController.php
php -l app/Services/AdminDashboardService.php
php -l app/Services/DataSourceMonitoringService.php
php -l app/Services/GeoJsonService.php
php -l app/Services/SpatialAnalysisService.php
php -l app/Services/RoutingService.php
```

Validasi database:

```bash
php artisan tinker --execute="echo App\\Models\\FloodEvent::count();"
php artisan tinker --execute="echo App\\Models\\FloodRiskPoint::count();"
php artisan tinker --execute="echo App\\Models\\EvacuationPoint::count();"
php artisan tinker --execute="echo App\\Models\\HeavyEquipmentPost::count();"
php artisan tinker --execute="echo App\\Models\\HeavyEquipmentUnit::count();"
php artisan tinker --execute="echo App\\Models\\EquipmentType::count();"
```

Validasi HTTP dan API dilakukan dengan `curl` terhadap server lokal `127.0.0.1:8001`.

## Hasil Validasi

### Auth Admin

| Check | Hasil |
|---|---|
| `/admin/dashboard` tanpa login | Redirect ke `/admin/login` |
| `/admin/data-sources` tanpa login | Redirect ke `/admin/login` |
| Login admin demo | Berhasil |
| Dashboard setelah login | HTTP 200 |
| Logout | HTTP 302 dan route admin kembali redirect ke login |

### Dashboard Admin

| Check | Hasil |
|---|---|
| Dashboard dapat dibuka | Lulus |
| Statistik memakai data database | Lulus |
| Link `Buka Peta Publik` tersedia | Lulus |
| Link validasi data tersedia | Lulus |
| Placeholder database tidak terlihat | Lulus |

### CRUD Core Data

Halaman berikut dapat dibuka sebagai admin dengan HTTP 200:

- `/admin/flood-events`
- `/admin/flood-risks`
- `/admin/evacuation-points`
- `/admin/heavy-equipment-posts`
- `/admin/equipment`
- `/admin/equipment-types`
- `/admin/heavy-equipment-units`
- `/admin/flood-events/9`

QA final ini tidak menjalankan mutation test destruktif. CRUD mutation sudah diuji pada fase implementasi, sedangkan QA final menjaga data seed tetap utuh.

### GeoJSON API

| Endpoint | Hasil |
|---|---|
| `/api/v1/geojson/flood-events` | `FeatureCollection`, 8 features |
| `/api/v1/geojson/flood-risks` | `FeatureCollection`, 12 features |
| `/api/v1/geojson/evacuation-points` | `FeatureCollection`, 10 features |
| `/api/v1/geojson/heavy-equipment-posts` | `FeatureCollection`, 6 features |

Filter yang diuji:

- `flood-events?status=aktif` -> 3 features.
- `flood-risks?risk_level=tinggi` -> 5 features.
- `evacuation-points?status=aktif` -> 9 features.
- `heavy-equipment-posts?status=aktif` -> 5 features.

### Spatial Analysis API

Event uji:

```text
ID 9 - Banjir Teluk Betung Selatan
```

| Endpoint | Hasil |
|---|---|
| nearest evacuation | Sukses, 3 rekomendasi |
| nearest equipment | Sukses, 3 rekomendasi |
| nearest resources | Sukses, 3 evakuasi dan 3 pos alat |
| filter `type=masjid` | Sukses, 2 rekomendasi |
| filter radius 5000 meter | Sukses, hasil dibatasi radius |
| invalid `limit=99` | 422 JSON error |
| event tidak ditemukan | 404 JSON error |

### Routing API OSRM

| Endpoint | Hasil |
|---|---|
| `/api/v1/routing/flood-events/9/to-nearest-evacuation` | Sukses, provider `osrm`, geometry `LineString`, jarak `500 m` |
| `/api/v1/routing/flood-events/9/to-evacuation/11` | Sukses, provider `osrm`, geometry `LineString`, jarak `4.49 km` |
| routing ke event tidak ada | 404 JSON error |
| routing ke titik evakuasi tidak aktif | 422 JSON error |

Catatan: OSRM demo server adalah dependency eksternal gratis. Jika provider tidak merespons, aplikasi sudah menyiapkan error handling provider.

### Public Map Explorer `/peta`

Browser check menunjukkan:

- Leaflet container tampil.
- Attribution tampil.
- Basemap selector memuat Standar, Humanitarian, dan Satelit.
- Marker peta tampil.
- Legend tampil.
- Tombol/teks rute tersedia.
- Tidak ada horizontal overflow pada viewport uji.

### Detail Kejadian Decision Support

Halaman `/admin/flood-events/9`:

- Mini map Leaflet tampil.
- Rekomendasi spasial termuat.
- Tombol rute tampil.
- Rute referensi dapat digambar.
- Panel rute menampilkan provider OSRM dan notice bahwa rute belum mempertimbangkan jalan tertutup.
- Polyline route tampil pada mini map.

### Sumber Data & Validasi

Halaman `/admin/data-sources`:

- Notice transparansi dataset tampil.
- Statistik data real tampil.
- Filter `module`, `data_status`, `verification`, `source_type`, dan `search` dapat membuka hasil.
- Tabel monitoring menampilkan link detail/edit atau empty state yang rapi.

### Build dan Test

| Command | Hasil |
|---|---|
| `composer dump-autoload --no-interaction` | Berhasil |
| `npm run build` | Berhasil |
| `php artisan view:cache && php artisan view:clear` | Berhasil |
| `php artisan test` | 2 test lulus |
| `php -l` file controller/service utama | Tidak ada syntax error |

## Bug yang Ditemukan dan Diperbaiki

Ditemukan satu sisa navigasi placeholder dari fase UI awal:

- menu `Analisis Spasial` di sidebar masih mengarah ke halaman pratinjau yang belum menjadi fitur aktif;
- route preview lama `/admin/spatial-analysis`, `/admin/routes/preview`, dan `/admin/ui-states` masih terdaftar.

Perbaikan:

- menu placeholder dihapus dari sidebar admin;
- route preview lama dihapus dari `routes/web.php`;
- alur analisis spasial tetap tersedia melalui detail kejadian banjir admin dan public map explorer.

Temuan QA lanjutan setelah pemeriksaan visual:

- detail titik rawan banjir masih memakai mini map placeholder statis;
- detail titik evakuasi masih memakai mini map placeholder statis;
- detail pos alat berat masih memakai mini map placeholder statis.

Perbaikan:

- tiga detail modul spasial tersebut sekarang memakai Leaflet mini map read-only;
- koordinat dikirim dari Blade melalui `data-*` attribute berdasarkan data real yang sudah dimuat dengan `withCoordinates()`;
- marker memakai visual pin kategori yang selaras dengan `/peta`;
- popup marker menampilkan ringkasan data per kategori;
- teks `Mini map masih placeholder. Integrasi Leaflet dan GeoJSON dikerjakan pada fase peta final.` sudah dihapus dari detail modul spasial inti.

Perbaikan dokumentasi yang dilakukan:

- README diperbarui agar sesuai fitur aktual.
- Roadmap di `docs/TASKS.md` disesuaikan dengan status implementasi.
- `docs/API.md` dibatasi pada endpoint yang benar-benar aktif.
- `docs/DATABASE.md`, `docs/DATASET.md`, `docs/UI.md`, dan `docs/REQUIREMENTS.md` diberi penyesuaian status aktual.

## Known Limitations

- Data yang tersedia adalah data simulasi dan dummy untuk demo akademik.
- Belum ada data yang diklaim sebagai data resmi.
- Seluruh data spasial utama masih berstatus perlu validasi.
- OSRM demo server adalah layanan eksternal, sehingga respons dapat bergantung pada koneksi dan ketersediaan provider.
- Rute bersifat referensi dan belum mempertimbangkan jalan tertutup, banjir aktual, atau lalu lintas.
- Belum ada route history.
- Belum ada rute ke pos alat berat.
- Belum ada verifikasi massal.
- Belum ada upload dokumen sumber.
- Belum ada multi-role kompleks.
- Belum disiapkan untuk production deployment.

## Kesimpulan

MVP SIGAP Banjir siap digunakan untuk demo akademik lokal. Alur utama yang membedakan project ini dari CRUD biasa sudah berjalan: data spasial PostGIS, GeoJSON, peta Leaflet, rekomendasi resource berbasis jarak, routing referensi OSRM, dashboard data real, detail kejadian decision support, dan transparansi status dataset.
