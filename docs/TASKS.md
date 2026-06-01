# TASKS.md

# Roadmap dan Status Implementasi SIGAP Banjir

Dokumen ini menyinkronkan roadmap MVP dengan implementasi aktual project **SIGAP Banjir - Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung**.

Project ini adalah MVP akademik SIG. Fokusnya adalah data spasial PostGIS, GeoJSON, Leaflet, analisis titik terdekat, rekomendasi resource, rute evakuasi referensi, dan transparansi data.

## Status Legend

```text
[ ] Belum dikerjakan
[~] Sedang dikerjakan
[x] Selesai
[!] Perlu revisi
[?] Perlu keputusan
```

## Roadmap MVP Aktual

| Status | Phase | Output Utama | Validasi Status |
|---|---|---|---|
| [x] | Phase 0 - Project Preparation | Scope MVP, dokumen acuan, stack, dan batasan project disepakati | `AGENTS.md` dan dokumen `docs/*` menjadi acuan kerja |
| [x] | Phase 1 - Laravel Project Setup | Laravel, Blade, Tailwind CSS, Vite, layout publik/admin, UI foundation | App berjalan, `npm run build` sukses |
| [x] | Phase 2 - Database and PostGIS | PostgreSQL `sigap-banjir`, PostGIS, migration tabel inti, `geom geometry(Point, 4326)` | `php artisan migrate:status` semua migration inti `Ran` |
| [x] | Phase 3 - Dataset and Seeder | Seeder demo realistis, admin demo, data simulasi/dummy transparan | Count data tersedia dan koordinat berada di area Bandar Lampung |
| [x] | Phase 4 - Model and Relationship | Model inti, relasi Eloquent, trait `HasPostgisPoint` | Query koordinat, relasi, dan scope berjalan |
| [x] | Phase 5 - Admin Authentication | Login/logout admin session-based, middleware `auth` + `admin` | Route admin redirect ke login saat belum autentikasi |
| [x] | Phase 6 - Admin CRUD Core Data | CRUD kejadian banjir, titik rawan, titik evakuasi, pos alat berat, jenis alat, unit alat | Route CRUD aktif dan halaman index/show/edit dapat dibuka |
| [x] | Phase 7 - GeoJSON API | Endpoint layer peta dalam format `FeatureCollection` | 4 endpoint GeoJSON aktif dan dipakai Leaflet |
| [x] | Phase 8 - Spatial Analysis API | Rekomendasi evakuasi, alat berat, dan resource terdekat berbasis PostGIS | `ST_Distance`/`ST_DWithin` berjalan melalui service backend |
| [x] | Phase 9 - Routing API | Routing referensi ke titik evakuasi memakai OSRM demo server | Endpoint routing mengembalikan GeoJSON `LineString` |
| [x] | Phase 10 - Public Map Explorer | `/peta` Leaflet final dengan layer real, marker pin, filter, toggle, popup, basemap, rekomendasi, dan rute | Browser check peta, marker, legend, basemap, route |
| [x] | Phase 11 - Dashboard Admin Data Real | Dashboard statistik database, kejadian terbaru, ketersediaan alat, status data, quick action | Halaman `/admin/dashboard` memakai query database |
| [x] | Phase 12 - Detail Kejadian Decision Support | Detail kejadian admin dengan mini map, rekomendasi resource, dan rute OSRM | `/admin/flood-events/{id}` memuat rekomendasi dan route |
| [x] | Phase 13 - Sumber Data & Validasi | `/admin/data-sources` menggabungkan status data empat modul spasial | Statistik transparansi, filter, detail/edit link aktif |
| [x] | Phase 14 - Final QA MVP | Validasi build, syntax, route, auth, API, map, dashboard, detail, sumber data | Dicatat pada `docs/FINAL_QA.md` |
| [x] | Phase 15 - Dokumentasi dan Demo Guide | README, dokumen teknis, QA final, dan panduan demo disinkronkan | `docs/DEMO_GUIDE.md` tersedia |

## Data Demo Saat QA Akhir

| Data | Jumlah |
|---|---:|
| Admin demo | 1 |
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

## Endpoint Aktif

GeoJSON:

```text
GET /api/v1/geojson/flood-events
GET /api/v1/geojson/flood-risks
GET /api/v1/geojson/evacuation-points
GET /api/v1/geojson/heavy-equipment-posts
```

Spatial analysis:

```text
GET /api/v1/analysis/flood-events/{floodEvent}/nearest-evacuation
GET /api/v1/analysis/flood-events/{floodEvent}/nearest-equipment
GET /api/v1/analysis/flood-events/{floodEvent}/nearest-resources
```

Routing:

```text
GET /api/v1/routing/flood-events/{floodEvent}/to-nearest-evacuation
GET /api/v1/routing/flood-events/{floodEvent}/to-evacuation/{evacuationPoint}
```

Admin web:

```text
GET /admin/dashboard
GET /admin/data-sources
GET /admin/flood-events
GET /admin/flood-risks
GET /admin/evacuation-points
GET /admin/heavy-equipment-posts
GET /admin/equipment
GET /admin/equipment-types
GET /admin/heavy-equipment-units
```

## Done Criteria MVP

MVP dianggap siap demo jika:

1. Database `sigap-banjir` aktif dan PostGIS dapat dipakai.
2. Semua migration inti berstatus `Ran`.
3. Data demo tersedia dan tidak diklaim sebagai data resmi.
4. Admin dapat login dan mengakses dashboard.
5. CRUD inti dapat dibuka.
6. GeoJSON API mengembalikan `FeatureCollection`.
7. Spatial Analysis API mengembalikan jarak berbasis PostGIS dalam meter.
8. Routing API mengembalikan rute referensi dari OSRM dalam GeoJSON `LineString`.
9. `/peta` menampilkan layer real, marker, basemap selector, rekomendasi, dan rute.
10. Detail kejadian admin menampilkan decision support.
11. Halaman Sumber Data & Validasi menampilkan transparansi dataset.
12. Build, view cache, syntax check, dan test dasar berhasil.
13. Keterbatasan sistem dicatat dengan jelas.

## Backlog Setelah MVP

Fitur berikut belum dikerjakan dan tidak boleh diklaim selesai:

- Route history database.
- Turn-by-turn navigation.
- Rute menuju pos alat berat.
- Verifikasi massal atau workflow approval.
- Upload dokumen/bukti sumber data.
- Admin global map terpisah.
- Laporan publik.
- Multi-role kompleks.
- Tracking alat berat real-time.
- Prediksi banjir.
- Integrasi BMKG/IoT.
- pgRouting.
- Simulasi jalan tertutup.
- Deployment production.

## Catatan Scope

- OSRM demo server dipakai hanya untuk rute referensi.
- Rute belum mempertimbangkan jalan tertutup, banjir aktual, lalu lintas, atau keputusan resmi petugas.
- Data dummy/simulasi wajib tetap terlihat sebagai dummy/simulasi.
- Tabel opsional seperti `data_sources`, `route_histories`, dan `equipment_dispatch_logs` belum dibuat sebagai bagian aktif MVP.
