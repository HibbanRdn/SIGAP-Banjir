# TASKS.md

# Roadmap Implementasi Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

## 1. Ringkasan Roadmap

Dokumen ini adalah roadmap pengerjaan MVP untuk project Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung. Roadmap ini dibuat agar implementasi berjalan bertahap, tidak lompat-lompat, dan tetap sesuai scope yang sudah ditentukan pada dokumen project.

Project dikerjakan melalui fase berikut:

1. Persiapan project.
2. Setup Laravel.
3. Setup PostgreSQL dan PostGIS.
4. Pembuatan database dan tabel inti.
5. Persiapan dataset dan seeder.
6. Model dan relasi Laravel.
7. Autentikasi admin.
8. CRUD data utama.
9. Endpoint GeoJSON.
10. Endpoint analisis spasial.
11. Endpoint routing.
12. Dashboard admin.
13. Public Map Explorer.
14. Detail kejadian banjir dan rekomendasi.
15. Polish UI.
16. Testing dan validasi.
17. Dokumentasi akhir dan demo.

MVP tidak boleh melebar ke fitur yang sudah ditunda, seperti:

- laporan publik;
- upload foto;
- multi-role kompleks;
- tracking alat berat real-time;
- prediksi banjir;
- integrasi BMKG/IoT;
- pgRouting;
- aplikasi mobile;
- simulasi jalan tertutup;
- dashboard prioritas wilayah kompleks.

Fokus utama MVP adalah peta, data spasial, PostGIS, GeoJSON, rekomendasi titik terdekat, routing referensi, dan UI yang rapi sesuai `UI.md`.

## 2. Prinsip Pengerjaan

Prinsip pengerjaan project:

1. Kerjakan satu fase sampai selesai sebelum lanjut ke fase besar berikutnya.
2. Jangan menambah fitur di luar MVP.
3. Setiap task harus punya output yang jelas.
4. Setiap task harus bisa diuji.
5. Setelah perubahan, lakukan review diff atau review file yang berubah.
6. Jaga konsistensi dengan dokumen di folder `docs`.
7. Jangan hardcode data jika data bisa disiapkan lewat seed/database.
8. Pastikan fitur SIG benar-benar memakai PostGIS.
9. UI harus mengikuti `UI.md`.
10. API harus mengikuti `API.md`.
11. Database harus mengikuti `DATABASE.md`.
12. Dataset harus mengikuti `DATASET.md`.
13. Data dummy, simulasi, dan nyata harus selalu dibedakan.
14. Koordinat harus memakai urutan longitude, latitude.
15. Jangan mengekspos API key routing di frontend.

## 3. Status Legend

Gunakan status berikut untuk memperbarui progress task:

```text
[ ] Belum dikerjakan
[~] Sedang dikerjakan
[x] Selesai
[!] Perlu revisi
[?] Perlu keputusan
```

Status task boleh diperbarui seiring pengerjaan. Jika task berubah menjadi `[!]` atau `[?]`, tuliskan alasan dan keputusan yang dibutuhkan.

## 4. Phase 0 - Project Preparation

Tujuan fase ini adalah memastikan semua prasyarat project jelas sebelum coding dimulai.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Review semua dokumen di `docs` | Memahami scope, database, dataset, API, dan UI | Catatan ringkas scope MVP | Tidak ada konflik besar antar dokumen |
| [ ] | Pastikan scope MVP sudah jelas | Mencegah fitur melebar | Daftar fitur MVP final | Cocok dengan `REQUIREMENTS.md` |
| [ ] | Pastikan stack tidak berubah | Menjaga konsistensi teknologi | Stack final terkunci | Laravel, Blade, Tailwind CSS, PostgreSQL, PostGIS, Leaflet, OSRM/OpenRouteService |
| [ ] | Pastikan folder project Laravel sudah siap | Menentukan apakah perlu install baru atau memakai project ada | Status folder project | Struktur Laravel tersedia atau rencana setup jelas |
| [ ] | Pastikan environment lokal tersedia | Menghindari blocker awal | Versi PHP, Composer, Node, NPM, PostgreSQL diketahui | Command versi berjalan |
| [ ] | Pastikan PostgreSQL tersedia | Database utama siap dipakai | Database server bisa diakses | Bisa login ke PostgreSQL lokal |
| [ ] | Pastikan PostGIS tersedia | Extension spasial siap dipakai | PostGIS bisa diaktifkan | `CREATE EXTENSION postgis` memungkinkan |
| [ ] | Pastikan Node/NPM tersedia | Asset build Tailwind siap | Node/NPM tersedia | `npm --version` berjalan |
| [ ] | Pastikan Composer dependency siap | Laravel dependency bisa dipasang | Composer tersedia | `composer --version` berjalan |
| [ ] | Pastikan rencana branch/git jika menggunakan Git | Menjaga perubahan terkontrol | Branch kerja disiapkan | `git status` bersih atau diketahui |

Catatan fase:

- Jangan mulai migration sebelum PostGIS dan koneksi database jelas.
- Jangan mulai UI sebelum layout dan asset build siap.
- Jika folder belum menjadi Git repository, tentukan dulu apakah perlu inisialisasi Git.

## 5. Phase 1 - Laravel Project Setup

Tujuan fase ini adalah menyiapkan fondasi Laravel tanpa membuat fitur besar.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Install/setup Laravel jika belum ada | Menyiapkan aplikasi utama | Project Laravel tersedia | Halaman default Laravel bisa berjalan |
| [ ] | Konfigurasi `.env` | Menyambungkan environment lokal | `.env` terisi sesuai kebutuhan | App key, DB, dan app URL benar |
| [ ] | Konfigurasi koneksi PostgreSQL | Menghubungkan Laravel ke database | DB connection PostgreSQL aktif | Laravel bisa query database |
| [ ] | Setup Tailwind CSS | Menyiapkan styling | Tailwind terpasang | Asset build berjalan |
| [ ] | Setup Blade layout dasar | Menyiapkan fondasi UI | Layout publik dan admin awal | Halaman memakai layout konsisten |
| [ ] | Setup auth admin awal | Menyiapkan akses admin | Auth dasar tersedia | Admin bisa diarahkan ke login |
| [ ] | Setup struktur folder frontend | Menjaga file Blade, CSS, JS rapi | Folder view/layout/component tertata | Struktur mudah dipahami |
| [ ] | Setup asset build | Menjalankan CSS/JS | Vite atau build asset aktif | `npm run dev` berjalan |
| [ ] | Setup base route | Menyiapkan route awal | Route publik dan admin skeleton | Route tidak error |
| [ ] | Setup middleware admin | Proteksi halaman admin | Middleware tersedia | Halaman admin butuh login |

Validasi akhir fase:

- Laravel berjalan lokal.
- Tailwind berjalan.
- Layout awal bisa dirender.
- Koneksi PostgreSQL siap.
- Belum ada fitur besar di luar setup.

## 6. Phase 2 - Database and PostGIS Setup

Tujuan fase ini adalah membuat struktur database sesuai `DATABASE.md`.

### 6.1 Task Database Inti

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Aktifkan extension PostGIS | Mendukung tipe dan fungsi spasial | Extension `postgis` aktif | Query PostGIS sederhana berjalan |
| [ ] | Buat migration tabel `users` | Menyimpan admin | Tabel users | Auth bisa memakai users |
| [ ] | Buat migration `flood_risk_points` | Menyimpan titik rawan banjir | Tabel titik rawan | Ada `geom geometry(Point, 4326)` |
| [ ] | Buat migration `flood_events` | Menyimpan kejadian banjir | Tabel kejadian banjir | Ada `geom geometry(Point, 4326)` |
| [ ] | Buat migration `evacuation_points` | Menyimpan titik evakuasi | Tabel evakuasi | Ada `geom geometry(Point, 4326)` |
| [ ] | Buat migration `heavy_equipment_posts` | Menyimpan pos alat berat | Tabel pos alat berat | Ada `geom geometry(Point, 4326)` |
| [ ] | Buat migration `equipment_types` | Master jenis alat | Tabel jenis alat | Nama jenis unik atau tervalidasi |
| [ ] | Buat migration `heavy_equipment_units` | Jumlah alat per pos | Tabel unit alat | Relasi post dan type benar |
| [ ] | Buat foreign key | Menjaga relasi | FK sesuai DATABASE.md | Relasi tidak orphan |
| [ ] | Buat spatial index GiST | Mempercepat query spasial | Index pada semua kolom `geom` | Index terdaftar di database |
| [ ] | Buat index status/district/severity | Mempercepat filter | Index non-spasial | Query filter lebih siap |
| [ ] | Validasi enum/status | Menjaga data konsisten | Status sesuai daftar | Tidak ada nilai bebas liar |
| [ ] | Test `ST_AsGeoJSON` | Memastikan data bisa ke Leaflet | Query GeoJSON berhasil | Output GeoJSON valid |
| [ ] | Test `ST_Distance` | Memastikan analisis jarak berjalan | Query jarak berhasil | Jarak keluar dalam meter |

### 6.2 Tabel Opsional

Tabel opsional tidak wajib untuk MVP awal. Kerjakan hanya jika waktu cukup atau benar-benar dibutuhkan.

| Status | Tabel Opsional | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | `districts` | Batas kecamatan/kelurahan | Tabel polygon wilayah | GeoJSON polygon valid |
| [ ] | `data_sources` | Dokumentasi sumber data | Tabel sumber data | Data nyata punya sumber |
| [ ] | `route_histories` | Riwayat rute | Tabel histori rute | Tidak wajib untuk routing MVP |
| [ ] | `equipment_dispatch_logs` | Riwayat rekomendasi/dispatch | Tabel dispatch | Tidak wajib untuk MVP awal |

Validasi akhir fase:

- Semua tabel inti tersedia.
- Semua kolom `geom` memakai SRID 4326.
- Spatial index tersedia.
- Query GeoJSON dan distance berjalan.
- Tabel opsional tetap tidak memblokir MVP jika belum dibuat.

## 7. Phase 3 - Dataset and Seeder

Tujuan fase ini adalah menyiapkan data demo yang realistis dan transparan sesuai `DATASET.md`.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Siapkan folder dataset jika diperlukan | Menata sumber CSV/GeoJSON | Folder dataset lokal | File dataset mudah ditemukan |
| [ ] | Siapkan CSV sesuai `DATASET.md` | Menjaga struktur data stabil | CSV template final | Header sesuai dokumen |
| [ ] | Input seed admin | Membuat akun admin demo | Admin awal tersedia | Login berhasil |
| [ ] | Input seed `equipment_types` | Master jenis alat | 5-7 jenis alat | Jenis sesuai DATASET.md |
| [ ] | Input seed `heavy_equipment_posts` dummy realistis | Pos alat berat demo | 5-8 pos | Pos tersebar masuk akal |
| [ ] | Input seed `heavy_equipment_units` | Ketersediaan alat per pos | Unit alat per pos | `available_quantity <= quantity` |
| [ ] | Input seed `evacuation_points` | Titik evakuasi | 8-15 titik | Lokasi relevan dan status jelas |
| [ ] | Input seed `flood_risk_points` | Titik rawan banjir | 10-20 titik | Risk level dan sumber jelas |
| [ ] | Input seed `flood_events` simulasi/historis | Kejadian banjir demo | 5-10 kejadian | Status dan severity jelas |
| [ ] | Validasi koordinat longitude/latitude | Mencegah titik salah lokasi | Semua koordinat dicek | Titik berada di Bandar Lampung/area relevan |
| [ ] | Konversi longitude/latitude menjadi `geom` | Memakai PostGIS sebagai sumber spasial | `geom` terisi | `ST_X` dan `ST_Y` benar |
| [ ] | Tandai data nyata/dummy/simulasi | Transparansi akademik | `data_status` dan `source_type` terisi | Tidak ada dummy diklaim resmi |
| [ ] | Validasi seed setelah import | Memastikan data siap demo | Data masuk database | Peta dan query bisa membaca data |

Checklist jumlah minimal data demo:

- [ ] Minimal 10 titik rawan banjir.
- [ ] Minimal 5 kejadian banjir.
- [ ] Minimal 8 titik evakuasi.
- [ ] Minimal 5 pos alat berat.
- [ ] Minimal 5 jenis alat berat.
- [ ] Unit alat per pos secukupnya.

Validasi akhir fase:

- Data cukup untuk demo.
- Data dummy/simulasi diberi label jelas.
- Koordinat tidak tertukar.
- Data bisa digunakan oleh endpoint GeoJSON dan analisis PostGIS.

## 8. Phase 4 - Backend Model and Relationship

Tujuan fase ini adalah membuat model Laravel yang rapi dan tidak mencampur logika spasial secara sembarangan.

| Status | Model | Task | Output | Validasi |
|---|---|---|---|---|
| [ ] | `User` | Definisikan fillable/casts dan relasi | Model admin | Relasi ke data yang dibuat jelas |
| [ ] | `FloodRiskPoint` | Fillable, casts, relasi user, accessor koordinat jika perlu | Model titik rawan | Bisa membaca `geom` via query |
| [ ] | `FloodEvent` | Fillable, casts, relasi user, accessor koordinat jika perlu | Model kejadian banjir | Bisa dipakai analisis terdekat |
| [ ] | `EvacuationPoint` | Fillable, casts, accessor koordinat jika perlu | Model evakuasi | Status aktif bisa difilter |
| [ ] | `HeavyEquipmentPost` | Fillable, relasi units, accessor koordinat jika perlu | Model pos alat berat | Bisa join ke unit alat |
| [ ] | `EquipmentType` | Fillable dan relasi units | Model jenis alat | Bisa dipakai master data |
| [ ] | `HeavyEquipmentUnit` | Fillable, casts, relasi post dan type | Model unit alat | Quantity tervalidasi |
| [ ] | Model opsional | Buat hanya jika tabel opsional dipakai | Model tambahan | Tidak memblokir MVP |

Catatan handling `geom`:

- Jangan menjadikan latitude/longitude sebagai sumber utama analisis.
- Latitude/longitude boleh menjadi input form atau accessor tampilan.
- Sumber analisis spasial tetap `geom`.
- Query PostGIS yang kompleks sebaiknya diletakkan di service, bukan di model penuh logika.

Validasi akhir fase:

- Semua model inti dapat membaca data.
- Relasi post-unit-type berjalan.
- Relasi user ke flood data jelas.
- Tidak ada logika SIG penting yang dihitung di frontend.

## 9. Phase 5 - Admin Authentication

Tujuan fase ini adalah mengamankan area admin.

Rekomendasi auth:

- Gunakan pendekatan auth Laravel yang sederhana dan aman untuk MVP.
- Jika memakai Sanctum/session, ikuti arahan `API.md`.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Buat halaman login admin | Entry point admin | Form login | UI sesuai `UI.md` |
| [ ] | Implement proses login | Autentikasi admin | Login berjalan | Credential benar masuk dashboard |
| [ ] | Implement logout | Mengakhiri sesi | Logout tersedia | User kembali ke login |
| [ ] | Buat middleware admin | Proteksi halaman | Middleware aktif | Route admin butuh login |
| [ ] | Redirect setelah login | UX rapi | Redirect ke dashboard | Tidak kembali ke login |
| [ ] | Validasi error login | Menjelaskan kesalahan | Error message | Pesan dekat form |
| [ ] | Proteksi halaman admin | Menjaga akses | Admin route aman | Tanpa login diarahkan |
| [ ] | Proteksi endpoint admin | Menjaga API admin | Endpoint admin aman | Request tanpa login ditolak |

Validasi akhir fase:

- Admin bisa login dan logout.
- Endpoint admin tidak bisa diakses tanpa login.
- Error login jelas.
- Auth tidak dibuat terlalu kompleks.

## 10. Phase 6 - Admin CRUD Core Data

Tujuan fase ini adalah membuat pengelolaan data inti untuk MVP.

### 10.1 Titik Rawan Banjir

| Status | Task | Output | Validasi |
|---|---|---|---|
| [ ] | Index/list | Tabel titik rawan | Data tampil dengan badge risk |
| [ ] | Create | Form tambah | Form section sesuai UI.md |
| [ ] | Store | Data tersimpan | `geom` terbentuk dari longitude/latitude |
| [ ] | Edit | Form edit | Data lama terisi |
| [ ] | Update | Data diperbarui | `geom` ikut berubah jika koordinat berubah |
| [ ] | Delete | Hapus data | Data hilang atau soft delete jika dipakai |
| [ ] | Validation | Validasi input | Error dekat field |
| [ ] | Empty state | UI saat kosong | Pesan kosong jelas |
| [ ] | Success/error message | Feedback aksi | Alert sesuai UI.md |
| [ ] | Filter/search sederhana | Cari data | Filter status/risk/district berjalan |

### 10.2 Kejadian Banjir

| Status | Task | Output | Validasi |
|---|---|---|---|
| [ ] | Index/list | Tabel kejadian banjir | Status dan severity tampil |
| [ ] | Create | Form tambah kejadian | Section informasi, lokasi, status, sumber |
| [ ] | Store | Data tersimpan | `reported_at`, status, severity valid |
| [ ] | Edit | Form edit | Data lama terisi |
| [ ] | Update | Data diperbarui | Koordinat dapat diperbarui |
| [ ] | Delete | Hapus data | Data tidak muncul di list |
| [ ] | Validation | Validasi input | `water_depth_cm` tidak negatif |
| [ ] | Empty state | UI saat kosong | CTA tambah data tersedia |
| [ ] | Success/error message | Feedback aksi | Pesan jelas |
| [ ] | Filter/search sederhana | Cari data | Filter status/severity/district berjalan |

### 10.3 Titik Evakuasi

| Status | Task | Output | Validasi |
|---|---|---|---|
| [ ] | Index/list | Tabel evakuasi | Status dan kapasitas tampil |
| [ ] | Create | Form tambah | Type dan fasilitas tersedia |
| [ ] | Store | Data tersimpan | Capacity tidak negatif |
| [ ] | Edit | Form edit | Data lama terisi |
| [ ] | Update | Data diperbarui | Status `aktif`/`penuh`/`tidak_aktif` valid |
| [ ] | Delete | Hapus data | Data hilang dari list |
| [ ] | Validation | Validasi input | Koordinat wajib |
| [ ] | Empty state | UI saat kosong | Pesan kosong jelas |
| [ ] | Success/error message | Feedback aksi | Alert sesuai UI.md |
| [ ] | Filter/search sederhana | Cari data | Filter type/status/district berjalan |

### 10.4 Pos Alat Berat

| Status | Task | Output | Validasi |
|---|---|---|---|
| [ ] | Index/list | Tabel pos alat berat | Status dan district tampil |
| [ ] | Create | Form tambah pos | Koordinat dan kontak tersedia |
| [ ] | Store | Data tersimpan | `geom` terbentuk |
| [ ] | Edit | Form edit | Data lama terisi |
| [ ] | Update | Data diperbarui | Status `aktif`/`tidak_aktif` valid |
| [ ] | Delete | Hapus data | Relasi unit dipertimbangkan |
| [ ] | Validation | Validasi input | Koordinat wajib |
| [ ] | Empty state | UI saat kosong | Pesan kosong jelas |
| [ ] | Success/error message | Feedback aksi | Alert sesuai UI.md |
| [ ] | Filter/search sederhana | Cari data | Filter status/district berjalan |

### 10.5 Jenis Alat Berat

| Status | Task | Output | Validasi |
|---|---|---|---|
| [ ] | Index/list | Tabel jenis alat | Jenis tampil |
| [ ] | Create/store | Tambah jenis | Nama jenis valid |
| [ ] | Edit/update | Ubah jenis | Data berubah |
| [ ] | Delete | Hapus jenis | Tidak merusak unit terkait |
| [ ] | Validation | Validasi input | Nama wajib |

### 10.6 Unit Alat Berat

| Status | Task | Output | Validasi |
|---|---|---|---|
| [ ] | Index/list | Tabel unit alat | Post dan type tampil |
| [ ] | Create/store | Tambah unit | Relasi post/type valid |
| [ ] | Edit/update | Ubah unit | Quantity valid |
| [ ] | Delete | Hapus unit | Unit hilang |
| [ ] | Validation | Validasi quantity | `available_quantity <= quantity` |

Catatan UI untuk seluruh CRUD:

- Table modern.
- Badge status/severity/risk.
- Button konsisten.
- Form terbagi section.
- Helper text koordinat.
- Error dekat field.
- Ada akses “Lihat di Peta” untuk data spasial jika realistis.

## 11. Phase 7 - GeoJSON API

Tujuan fase ini adalah menyediakan data spasial untuk Leaflet.

| Status | Endpoint | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | `/api/v1/geojson/flood-risks` | Layer titik rawan | FeatureCollection | `coordinates` `[longitude, latitude]` |
| [ ] | `/api/v1/geojson/flood-events` | Layer kejadian banjir | FeatureCollection | Status/severity di properties |
| [ ] | `/api/v1/geojson/evacuation-points` | Layer evakuasi | FeatureCollection | Kapasitas/status di properties |
| [ ] | `/api/v1/geojson/heavy-equipment-posts` | Layer pos alat berat | FeatureCollection | Unit tersedia bisa tampil |
| [ ] | `/api/v1/geojson/districts` | Layer batas wilayah opsional | FeatureCollection polygon | Hanya jika dataset tersedia |
| [ ] | Impact radius opsional | Radius terdampak | GeoJSON polygon | Hanya setelah MVP inti stabil |

Ketentuan endpoint:

1. Gunakan `ST_AsGeoJSON`.
2. Kirim format `FeatureCollection`.
3. Pastikan coordinates memakai `[longitude, latitude]`.
4. Kirim `properties` yang diperlukan UI.
5. Dukung filter `status`, `severity_level`, `risk_level`, `district`, dan `data_status`.
6. Jangan bungkus GeoJSON dengan `success/message` jika ingin langsung kompatibel dengan Leaflet.

Validasi akhir fase:

- Semua layer wajib bisa dibaca Leaflet.
- GeoJSON valid.
- Filter dasar berjalan.
- Tidak ada query spasial di frontend.

## 12. Phase 8 - Spatial Analysis API

Tujuan fase ini adalah membuat fitur SIG utama: rekomendasi berdasarkan jarak spasial.

| Status | Endpoint | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | `/api/v1/analysis/flood-events/{id}/nearest-evacuation` | Mencari evakuasi terdekat | List evakuasi berjarak | Diurutkan paling dekat |
| [ ] | `/api/v1/analysis/flood-events/{id}/nearest-equipment` | Mencari pos alat berat terdekat | List pos/unit alat | Hanya yang aktif/tersedia |
| [ ] | `/api/v1/analysis/flood-events/{id}/nearest-resources` | Mengambil evakuasi dan alat berat sekaligus | Response gabungan | Konsisten dengan endpoint tunggal |
| [ ] | Impact radius opsional | Radius terdampak simulasi | GeoJSON radius | Tidak wajib MVP awal |

Ketentuan:

1. Jarak dihitung dengan `ST_Distance(geom::geography, geom::geography)`.
2. Hasil diurutkan dari paling dekat.
3. Hanya evakuasi `aktif` yang dipakai.
4. Hanya pos alat berat `aktif` dan unit tersedia yang dipakai.
5. Response menyertakan `distance_meters`.
6. Parameter `limit` boleh digunakan.
7. Parameter `equipment_type` boleh digunakan jika realistis.
8. Error harus jelas jika tidak ada data aktif.
9. Query tidak dilakukan di frontend.

Validasi akhir fase:

- Kasus Teluk Betung memilih titik evakuasi dan pos alat berat terdekat.
- Kasus Rajabasa tidak memilih pos yang jauh jika ada yang dekat.
- Kasus Panjang memilih titik evakuasi dan pos alat berat sekitar Panjang jika tersedia.

## 13. Phase 9 - Routing API

Tujuan fase ini adalah menyediakan rute referensi dari kejadian banjir ke titik evakuasi.

Provider default MVP:

```text
OSRM
```

Endpoint:

| Status | Endpoint | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | `/api/v1/routing/flood-events/{id}/to-nearest-evacuation` | Rute ke evakuasi terdekat | GeoJSON LineString | Rute tampil di Leaflet |
| [ ] | `/api/v1/routing/flood-events/{id}/to-evacuation/{evacuation_id}` | Rute ke evakuasi tertentu | GeoJSON LineString | Jarak dan durasi tampil |

Task routing:

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Siapkan `RoutingService` | Memisahkan logika provider | Service routing | Controller tetap tipis |
| [ ] | Ambil koordinat flood event | Menentukan origin | Longitude/latitude asal | Koordinat benar |
| [ ] | Ambil koordinat evacuation point | Menentukan destination | Longitude/latitude tujuan | Koordinat benar |
| [ ] | Panggil OSRM dari backend | Mengambil rute | Response provider | Provider merespons |
| [ ] | Format ke GeoJSON LineString | Kompatibel Leaflet | GeoJSON route | Coordinates benar |
| [ ] | Tampilkan `distance_meters` dan `duration_seconds` | Informasi rute | Metadata rute | Angka masuk akal |
| [ ] | Handle provider error | UX error jelas | Error response | 502 jika provider gagal |
| [ ] | Tambahkan label rute referensi | Hindari klaim resmi | Microcopy rute | UI menjelaskan batasan |

Catatan:

- Jika memakai OpenRouteService, API key harus berada di backend.
- Jangan expose API key di frontend.
- Rute belum mempertimbangkan jalan tertutup.
- Rute bukan rute resmi, melainkan referensi.

## 14. Phase 10 - Admin Dashboard UI

Tujuan fase ini adalah membuat dashboard admin yang modern, calm, dan informatif.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Layout admin | Fondasi halaman admin | Sidebar + content | Konsisten di semua halaman |
| [ ] | Sidebar modern | Navigasi utama | Menu admin | Active/hover jelas |
| [ ] | Topbar | Konteks halaman | Judul, aksi, user | Tidak terlalu tinggi |
| [ ] | Ringkasan data | Statistik umum | Summary cards | Angka sesuai database |
| [ ] | Statistik card | Visualisasi ringkas | Card polished | JetBrains Mono untuk angka |
| [ ] | Aksi cepat | Mempercepat workflow | Quick action links | Link benar |
| [ ] | Status data | Transparansi dataset | Data nyata/simulasi/dummy | Badge jelas |
| [ ] | Kejadian banjir terbaru | Monitoring admin | List kejadian | Status/severity terbaca |
| [ ] | Ketersediaan alat berat | Ringkasan sumber daya alat berat | List/card alat | Quantity masuk akal |
| [ ] | Link ke peta/detail | Menghubungkan dashboard ke SIG | CTA jelas | Navigasi bekerja |
| [ ] | Empty/loading state | UX saat data kosong/loading | Skeleton/empty | Sesuai UI.md |

Ketentuan UI:

- Plus Jakarta Sans sebagai font utama.
- JetBrains Mono untuk angka.
- Clean component system.
- Card polished.
- Icon konsisten.
- Tidak kaku.
- Tidak AI slop.

## 15. Phase 11 - Public Map Explorer

Tujuan fase ini adalah membuat pengalaman publik yang map-first.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Layout split map explorer | Peta dominan | Panel + map | Desktop nyaman |
| [ ] | Panel kiri | Eksplorasi data | Search/filter/layer/list | Tidak menutup peta |
| [ ] | Search bar | Cari lokasi/data | Input search | Hasil terfilter |
| [ ] | Filter chips | Filter cepat | Chips aktif/nonaktif | State jelas |
| [ ] | Layer toggles | Kontrol layer | Toggle layer | Layer bisa dinyalakan/matikan |
| [ ] | Result list | Alternatif akses map | List card | Klik item pan/zoom |
| [ ] | Leaflet map | Peta utama | Map OSM | Peta render |
| [ ] | Custom marker | Marker lebih polished | Marker kategori | Warna sesuai UI.md |
| [ ] | Popup marker | Info ringkas | Popup mini card | CTA detail tersedia |
| [ ] | Legend | Membaca simbol | Legend ringkas | Tidak menutupi peta |
| [ ] | Reset filter | Menghapus filter | Tombol reset | Hasil kembali |
| [ ] | Loading skeleton | UX loading | Skeleton list/card | Tidak blank |
| [ ] | Empty state | UX hasil kosong | Empty message | Ada reset filter |
| [ ] | Responsive mobile | Bisa dipakai mobile | Bottom sheet/collapsible panel | Tidak kacau di mobile |

Layer wajib:

1. Titik rawan banjir.
2. Kejadian banjir.
3. Titik evakuasi.
4. Pos alat berat.

Interaksi wajib:

1. Klik item list membuat map pan/zoom.
2. Klik marker membuka popup.
3. Filter layer bekerja.
4. Marker aktif berbeda.
5. Popup ringkas.
6. Detail link tersedia.

## 16. Phase 12 - Admin Map and Detail Flood Event

Tujuan fase ini adalah membuat halaman detail yang menjadi pusat analisis banjir.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Header detail | Identitas kejadian | Nama, status, severity | Badge jelas |
| [ ] | Metadata sumber data | Transparansi | Source/data_status | Dummy/simulasi terlihat |
| [ ] | Mini map atau map section | Konteks spasial | Peta detail | Marker banjir tampil |
| [ ] | Tombol cari evakuasi | Jalankan nearest evacuation | Action button | Loading/success/error |
| [ ] | Tombol cari alat berat | Jalankan nearest equipment | Action button | Loading/success/error |
| [ ] | Tombol tampilkan rute | Jalankan routing | Action button | Route muncul |
| [ ] | Panel rekomendasi evakuasi | Menampilkan hasil analisis | Card/list evakuasi | Jarak tampil |
| [ ] | Panel rekomendasi alat berat | Menampilkan pos dan unit alat berat | Card/list alat | Unit tersedia tampil |
| [ ] | Panel rute | Menampilkan rute | LineString + metadata | Distance/duration tampil |
| [ ] | Alert data simulasi/dummy | Transparansi | Alert info | Tidak mengganggu |
| [ ] | Error state rekomendasi kosong | UX saat tidak ada data | Empty/error message | Pesan jelas |

Ketentuan:

- Jarak memakai JetBrains Mono.
- Rekomendasi tampil sebagai card/list, bukan tabel mentah.
- Hasil terdekat diberi badge.
- Rute tampil sebagai garis di peta.
- Severity terlihat, tetapi tidak panik.

## 17. Phase 13 - UI Polish and Design System

Tujuan fase ini adalah menyatukan kualitas visual sesuai `UI.md`.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Button variants | Standar aksi | Primary/secondary/outline/ghost/destructive | State lengkap |
| [ ] | Input/select/textarea style | Form konsisten | Form component style | Focus/error jelas |
| [ ] | Badge style | Status konsisten | Badge status/severity | Warna sesuai mapping |
| [ ] | Card style | Layout polished | Card reusable | Border/radius konsisten |
| [ ] | Table style | Admin rapi | Table modern | Row hover, empty state |
| [ ] | Alert style | Feedback jelas | Alert success/warning/error/info | Pesan membantu |
| [ ] | Empty state | UX data kosong | Empty component | Ada CTA jika relevan |
| [ ] | Loading skeleton | UX loading | Skeleton | Tidak blank |
| [ ] | Focus-visible state | Aksesibilitas | Focus ring | Keyboard terlihat |
| [ ] | Hover/active state | Interaksi terasa | State visual | Tidak berlebihan |
| [ ] | Icon consistency | Visual konsisten | Satu icon library | Tidak campur ikon |
| [ ] | Spacing consistency | Layout rapi | Spacing harmonis | Tidak terlalu padat/kosong |
| [ ] | Radius consistency | Komponen konsisten | Radius 6-8px | Tidak acak |
| [ ] | Responsive adjustment | Mobile/tablet aman | Layout responsive | Tidak overlap |
| [ ] | prefers-reduced-motion | Aksesibilitas motion | Motion terkendali | Reduced motion dihormati |
| [ ] | Microcopy | Bahasa jelas | Label/helper/error | Tidak teknis berlebihan |
| [ ] | Anti AI-slop review | Hindari tampilan generik | Review visual akhir | Tidak terlalu ramai/kaku |

Font wajib:

- Plus Jakarta Sans untuk UI utama.
- JetBrains Mono untuk angka, koordinat, jarak, durasi, ID, kode, dan metadata teknis.

## 18. Phase 14 - Testing and Validation

Tujuan fase ini adalah memastikan MVP dapat didemonstrasikan dengan stabil.

### 18.1 Checklist Manual Testing

| Status | Test | Tujuan | Validasi |
|---|---|---|---|
| [ ] | Login admin | Auth berjalan | Login sukses dan gagal ditangani |
| [ ] | Logout admin | Session selesai | Kembali ke login |
| [ ] | CRUD titik rawan | Data rawan berjalan | Create/read/update/delete berhasil |
| [ ] | CRUD kejadian banjir | Data banjir berjalan | Status/severity valid |
| [ ] | CRUD titik evakuasi | Data evakuasi berjalan | Capacity valid |
| [ ] | CRUD pos alat berat | Data pos berjalan | Geom valid |
| [ ] | CRUD jenis alat | Master alat berjalan | Nama jenis valid |
| [ ] | CRUD unit alat | Unit alat berjalan | Quantity valid |
| [ ] | Validasi koordinat | Cegah lokasi salah | Longitude/latitude benar |
| [ ] | Data dummy/nyata/simulasi | Transparansi | Badge/status tampil |
| [ ] | GeoJSON valid | Peta membaca data | FeatureCollection valid |
| [ ] | Leaflet membaca GeoJSON | Layer tampil | Marker muncul |
| [ ] | Nearest evacuation | Analisis evakuasi | Hasil terurut jarak |
| [ ] | Nearest equipment | Analisis alat berat | Hasil terurut jarak |
| [ ] | Routing OSRM | Rute tampil | LineString tampil |
| [ ] | Error provider routing | Robustness | Error jelas |
| [ ] | Empty state | UX data kosong | Pesan kosong tampil |
| [ ] | Filter | Eksplorasi data | Filter bekerja |
| [ ] | Responsive layout | Mobile/tablet | Tidak overlap |
| [ ] | Endpoint admin tanpa login | Keamanan | Ditolak/redirect |
| [ ] | Data tidak ditemukan | Error handling | 404 jelas |
| [ ] | Tidak ada evakuasi aktif | Error analysis | Pesan jelas |
| [ ] | Tidak ada alat berat tersedia | Error analysis | Pesan jelas |

### 18.2 Catatan Testing

MVP akademik tidak wajib memiliki automated test penuh, tetapi validasi manual harus jelas. Jika waktu cukup, tambahkan feature test untuk:

1. Auth admin.
2. GeoJSON endpoint.
3. Nearest evacuation.
4. Nearest equipment.
5. Validasi form utama.

Validasi akhir fase:

- Semua skenario demo berjalan.
- Error utama ditangani.
- Tidak ada fitur MVP yang rusak.
- UI tidak bertentangan dengan `UI.md`.

## 19. Phase 15 - Documentation and Final Demo

Tujuan fase ini adalah menyiapkan project untuk dinilai dan dipresentasikan.

| Status | Task | Tujuan | Output | Validasi |
|---|---|---|---|---|
| [ ] | Update `README.md` | Panduan project | README lengkap | Bisa diikuti dari nol |
| [ ] | Jelaskan cara install | Setup mudah | Instruksi install | Dependency jelas |
| [ ] | Jelaskan setup PostgreSQL + PostGIS | Database siap | Instruksi DB | PostGIS aktif |
| [ ] | Jelaskan setup `.env` | Konfigurasi jelas | Contoh env | Tidak berisi secret asli |
| [ ] | Jelaskan seed data | Data demo siap | Instruksi seed | Data masuk |
| [ ] | Jelaskan cara menjalankan project | Demo lokal | Perintah run | App bisa dibuka |
| [ ] | Jelaskan akun admin demo | Login demo | Credential demo aman | Bisa login |
| [ ] | Jelaskan fitur MVP | Scope jelas | Daftar fitur | Sesuai REQUIREMENTS.md |
| [ ] | Jelaskan data nyata/dummy/simulasi | Transparansi akademik | Bagian data | Tidak ada klaim palsu |
| [ ] | Jelaskan batasan sistem | Ekspektasi jelas | Bagian batasan | Fitur ditunda disebut |
| [ ] | Jelaskan skenario demo | Presentasi lancar | Alur demo | Semua langkah bisa dilakukan |
| [ ] | Siapkan screenshot jika diperlukan | Bukti visual | Screenshot | UI representatif |
| [ ] | Siapkan alur presentasi | Demo terstruktur | Script demo | Tidak terlalu panjang |

Skenario demo minimal:

1. Buka peta publik.
2. Filter kejadian banjir.
3. Login admin.
4. Tambah kejadian banjir.
5. Cari evakuasi terdekat.
6. Cari alat berat terdekat.
7. Tampilkan rute evakuasi.

## 20. Backlog Fitur Ditunda

Fitur berikut masuk backlog setelah MVP selesai. Jangan dikerjakan sebelum semua fitur MVP selesai dan stabil.

| Status | Fitur | Alasan Ditunda |
|---|---|---|
| [ ] | Laporan publik | Membutuhkan validasi dan moderasi |
| [ ] | Upload foto | Menambah kompleksitas storage dan validasi |
| [ ] | Validasi laporan | Butuh workflow tambahan |
| [ ] | Role petugas lapangan | Multi-role belum perlu untuk MVP |
| [ ] | Role BPBD | Cukup aktor konseptual pada MVP |
| [ ] | Tracking alat berat real-time | Butuh GPS/telemetri |
| [ ] | Integrasi cuaca/BMKG | Butuh API dan interpretasi data |
| [ ] | Prediksi banjir | Di luar scope analisis MVP |
| [ ] | Sensor tinggi air | Butuh perangkat/IoT |
| [ ] | pgRouting | Setup jaringan jalan terlalu berat untuk awal |
| [ ] | Jalan tertutup | Butuh data kondisi jalan dinamis |
| [ ] | Dashboard prioritas kompleks | Dapat dibuat setelah data stabil |
| [ ] | Aplikasi mobile | Di luar stack MVP |

## 21. Definition of Done

Sebuah task dianggap selesai jika:

1. Fitur sesuai dokumen.
2. Tidak keluar scope MVP.
3. Tidak ada error utama.
4. Validasi berjalan.
5. UI mengikuti `UI.md`.
6. API mengikuti `API.md`.
7. Database mengikuti `DATABASE.md`.
8. Data mengikuti `DATASET.md`.
9. Bisa didemonstrasikan.
10. Perubahan dijelaskan di ringkasan akhir.
11. Tidak ada secret/API key yang bocor ke frontend.
12. Tidak ada data dummy yang diklaim sebagai data resmi.

Jika salah satu poin belum terpenuhi, task belum dianggap selesai.

## 22. Urutan Eksekusi Singkat

Checklist eksekusi dari awal sampai akhir:

1. [ ] Setup project.
2. [ ] Setup database PostgreSQL + PostGIS.
3. [ ] Buat migration tabel inti.
4. [ ] Buat seeder dan data demo.
5. [ ] Buat auth admin.
6. [ ] Buat CRUD data utama.
7. [ ] Buat GeoJSON endpoints.
8. [ ] Tampilkan GeoJSON di peta.
9. [ ] Buat spatial analysis endpoints.
10. [ ] Buat routing endpoints.
11. [ ] Buat dashboard admin.
12. [ ] Buat public map explorer.
13. [ ] Buat detail kejadian banjir dan rekomendasi.
14. [ ] Polish UI.
15. [ ] Testing manual.
16. [ ] Dokumentasi final.
17. [ ] Demo MVP.

Urutan ini tegas: jangan mulai routing sebelum data, GeoJSON, dan nearest evacuation stabil.

## 23. Catatan untuk Codex

Instruksi khusus untuk Codex saat mengerjakan project:

1. Sebelum mengerjakan task, baca dokumen terkait di folder `docs`.
2. Jangan mengerjakan lebih dari satu phase besar sekaligus.
3. Jangan menghapus keputusan desain tanpa alasan.
4. Jangan mengganti stack.
5. Jangan membuat fitur di luar MVP.
6. Jangan mengubah scope project tanpa persetujuan.
7. Setelah mengerjakan task, tulis:
   - file yang diubah;
   - alasan perubahan;
   - cara test;
   - risiko atau sisa pekerjaan.
8. Jika ada konflik antar dokumen, berhenti dan jelaskan konflik sebelum mengubah kode.
9. Jika ada data dummy, beri label jelas.
10. Jika ada koordinat, validasi longitude/latitude.
11. Jangan menaruh API key di frontend.
12. Jangan membuat endpoint frontend langsung mengakses database.
13. Jangan mengganti Laravel Blade menjadi React/Next.js.
14. Jangan memakai shadcn/ui secara literal.
15. Gunakan prinsip UI modern sesuai `UI.md`.

## 24. Checklist Akhir TASKS.md

- [ ] Semua phase sudah tersusun.
- [ ] Task backend sudah jelas.
- [ ] Task database sudah jelas.
- [ ] Task dataset sudah jelas.
- [ ] Task API sudah jelas.
- [ ] Task UI sudah jelas.
- [ ] Task GIS/PostGIS sudah jelas.
- [ ] Task routing sudah jelas.
- [ ] Task testing sudah jelas.
- [ ] Task dokumentasi sudah jelas.
- [ ] Fitur ditunda sudah dipisahkan.
- [ ] Siap lanjut ke `AGENTS.md`.

## Keputusan Akhir Roadmap

Prioritas pertama saat mulai coding adalah:

```text
Phase 0 - Project Preparation
```

Setelah Phase 0 selesai, lanjut ke:

```text
Phase 1 - Laravel Project Setup
```

Jangan langsung membuat fitur peta, routing, atau analisis spasial sebelum database, dataset, model, CRUD, dan GeoJSON tersedia. Urutan ini menjaga project tetap realistis, mudah diuji, dan tidak keluar dari scope MVP akademik SIG.
