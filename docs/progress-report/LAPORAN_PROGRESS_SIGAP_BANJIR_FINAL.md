# LAPORAN PROGRES PENGEMBANGAN APLIKASI SISTEM INFORMASI GEOGRAFIS

SIGAP BANJIR BANDAR LAMPUNG

Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung

Mata Kuliah Sistem Informasi Geografis / GIS

Dosen Pengampu: Mona Batubara

Disusun oleh: [Isi Nama Anggota dan NPM]

Universitas Lampung, 2026

# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Kota Bandar Lampung memiliki karakter wilayah perkotaan, pesisir, permukiman padat, dan koridor transportasi yang membutuhkan informasi spasial ketika terjadi genangan atau banjir. Dalam konteks mata kuliah Sistem Informasi Geografis, kebutuhan tersebut dapat dikaji melalui aplikasi yang memetakan lokasi kejadian, titik rawan, titik evakuasi, dan sumber daya respons seperti pos alat berat.

SIGAP Banjir dikembangkan sebagai MVP akademik untuk menunjukkan bagaimana data spasial dapat disimpan, divisualisasikan, dan dianalisis. Revisi progres ini memperkuat dataset banjir dengan mengganti data awal demo pada kejadian dan titik rawan menjadi data nyata berbasis berita dan jurnal.

## 1.2 Rumusan Masalah

Rumusan masalah progres ini adalah bagaimana merancang dataset spasial berbasis sumber, bagaimana merancang layer peta yang mendukung informasi kejadian, risiko, intensitas kecamatan, evakuasi, dan resource, bagaimana merancang UI aplikasi GIS yang mudah digunakan publik dan admin, serta bagaimana mengintegrasikan analisis jarak dan rute referensi dalam aplikasi.

## 1.3 Tujuan Pengembangan

Tujuan pengembangan SIGAP Banjir adalah membangun aplikasi GIS akademik yang mampu menyimpan data titik spasial di PostgreSQL/PostGIS, menampilkan layer peta melalui Leaflet, menyediakan GeoJSON API, menjalankan analisis resource terdekat menggunakan PostGIS, menampilkan rute referensi OSRM, dan menyediakan UI admin untuk pengelolaan data.

## 1.4 Batasan Progres

Dataset kejadian banjir menggunakan data nyata berbasis pemberitaan media tentang banjir Bandar Lampung 14 April 2026. Dataset titik rawan banjir menggunakan data nyata berbasis jurnal akademik. Koordinat berita dan jurnal tetap bersifat representatif: titik berita adalah hasil geocoding/plotting lokasi jalan, landmark, atau area yang disebut dalam sumber; titik jurnal adalah centroid/representasi area kelurahan. Dataset ini belum diklaim sebagai data operasional resmi pemerintah.

# BAB II GAMBARAN SISTEM DAN PROGRES IMPLEMENTASI

## 2.1 Deskripsi Aplikasi SIGAP Banjir

SIGAP Banjir adalah Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung. Aplikasi ini menyediakan peta publik untuk eksplorasi layer banjir dan panel admin untuk pengelolaan data spasial serta decision support sederhana pada detail kejadian banjir.

## 2.2 Arsitektur Teknologi

Arsitektur alur data aplikasi adalah PostgreSQL + PostGIS -> Laravel Backend dan API -> GeoJSON / Spatial Analysis / Routing Response -> Leaflet Public Map dan Admin Decision Support.

Teknologi yang digunakan meliputi Laravel, Blade, Tailwind CSS, Vite, PostgreSQL, PostGIS, Leaflet, basemap OpenStreetMap/Humanitarian/Satelit tanpa token sesuai implementasi aktual, serta OSRM demo server untuk rute referensi.

## 2.3 Fitur yang Sudah Berjalan

Fitur yang sudah berjalan meliputi Public Map Explorer pada `/peta`, layer intensitas kecamatan, login admin, dashboard admin berbasis database, CRUD kejadian banjir, titik rawan banjir, titik evakuasi, pos alat berat, jenis dan unit alat, detail kejadian sebagai decision support, halaman Sumber Data dan Validasi, GeoJSON API, Spatial Analysis API PostGIS, serta Routing API OSRM.

# BAB III RANCANGAN DATASET

## 3.1 Konsep Dataset

Dataset SIGAP Banjir dirancang sebagai kombinasi data spasial dan data relasional. Data spasial utama mencakup kejadian banjir, titik rawan banjir, titik evakuasi, dan pos alat berat. Data relasional mencakup jenis alat berat dan unit alat berat per pos.

## 3.2 Sistem Koordinat dan Penyimpanan Spasial

Penyimpanan spasial menggunakan kolom `geom geometry(Point, 4326)` pada PostGIS. Format koordinat memakai WGS 84 / SRID 4326. Urutan koordinat PostGIS dan GeoJSON adalah longitude, latitude. Perhitungan jarak memakai `ST_Distance(geom::geography, geom::geography)` agar hasil jarak dihitung dalam meter.

## 3.3 Ringkasan Dataset Final

| Dataset | Jenis Geometri | Jumlah Record | Fungsi dalam Sistem | Status Data |
|---|---|---:|---|---|
| Kejadian Banjir | Point | 12 | Menunjukkan lokasi kejadian dan severity | Nyata berbasis berita |
| Titik Rawan Banjir | Point | 12 | Menunjukkan potensi/rawan banjir | Nyata berbasis jurnal |
| Titik Evakuasi | Point | 10 | Tujuan rekomendasi evakuasi | Simulasi pengembangan |
| Pos Alat Berat | Point | 6 | Lokasi resource pemulihan | Dummy realistis |
| Unit Alat Berat | Non-spasial/relasional | 15 | Ketersediaan resource per pos | Dummy realistis |
| Jenis Alat | Master data | 6 | Kategori alat berat | Dummy realistis |

Status data spasial final adalah 24 data nyata, 10 data simulasi, 6 data dummy, dan 16 data yang masih perlu validasi operasional. Data nyata terdiri dari 12 kejadian banjir berbasis berita dan 12 titik rawan banjir berbasis jurnal.

## 3.4 Sumber Dataset Banjir

Layer kejadian banjir menggunakan data nyata dari berita lokal/nasional terkait banjir Bandar Lampung, sedangkan koordinat ditentukan berdasarkan lokasi jalan, kelurahan, kecamatan, atau landmark yang disebutkan dalam berita.

Sumber berita yang digunakan adalah Detik/Antara, Rilis ID, dan Bongkar Post. Seluruh kejadian banjir diberi `source_type = berita`, `data_status = nyata`, `is_verified = true`, dan `status = surut`.

Layer titik rawan banjir menggunakan jurnal Agustri & Asbi (2020), "Tingkat Risiko Bencana Banjir di Kota Bandar Lampung dan Upaya Pengurangannya Berbasis Penataan Ruang". Seluruh titik rawan diberi `source_type = jurnal`, `data_status = nyata`, dan `is_verified = true`.

## 3.5 Contoh Record Dataset

| Dataset | ID | Nama | Kecamatan | Koordinat | Status Data | Sumber |
|---|---:|---|---|---|---|---|
| Kejadian Banjir | 18 | Banjir Depan RSUD Abdul Moeloek | Enggal | 105.2601, -5.4160 | nyata | berita |
| Kejadian Banjir | 19 | Banjir Jalan Dokter Sutomo Penengahan | Kedaton | 105.2638, -5.3948 | nyata | berita |
| Kejadian Banjir | 20 | Genangan Jalan Pangeran Antasari - Transmart | Way Halim | 105.2878, -5.3987 | nyata | berita |
| Titik Rawan | 26 | Risiko Banjir Way Kandis | Tanjung Senang | 105.2920, -5.3608 | nyata | jurnal |
| Titik Rawan | 27 | Risiko Banjir Sukabumi | Sukabumi | 105.3110, -5.4105 | nyata | jurnal |
| Titik Rawan | 28 | Risiko Banjir Bumi Kedamaian | Kedamaian | 105.2860, -5.3940 | nyata | jurnal |
| Titik Evakuasi | 11 | Masjid Al-Furqon Lungsir | Tanjung Karang Pusat | 105.2615707, -5.4291549 | simulasi | kandidat pengembangan |
| Pos Alat Berat | 7 | Pos Alat Berat Panjang | Panjang | 105.3262000, -5.4669000 | dummy | dummy realistis |

## 3.6 Validasi Lokasi dan Provenance Data

Backup sebelum revisi disimpan pada `docs/progress-report/dataset/backups/before_real_source_update/`. Validasi koordinat dilakukan menggunakan Nominatim/OSM terhadap 24 target data banjir dan risiko. Sebanyak 14 query memperoleh kandidat lokasi OSM, sedangkan 10 lokasi dipertahankan sebagai titik plotting manual/representatif karena Nominatim tidak menemukan lokasi spesifik.

Validasi ini membuktikan bahwa koordinat berada pada konteks wilayah studi, tetapi tidak menggantikan survei GPS lapangan. Hal ini penting agar aplikasi tidak mengklaim presisi operasional yang belum dimiliki.

# BAB IV RANCANGAN LAYERS

## 4.1 Konsep Layer

Layer aplikasi dirancang agar pengguna dapat membedakan kejadian banjir, titik rawan, intensitas kecamatan, titik evakuasi, pos alat berat, dan rute evakuasi referensi. Leaflet mengonsumsi response GeoJSON dari API, sedangkan analisis nearest resource tetap dilakukan di backend menggunakan PostGIS.

| Layer | Sumber Data | Geometry | Simbol Aktual | Fungsi |
|---|---|---|---|---|
| Kejadian Banjir | `flood_events` | Point | Pin merah/coral dengan ikon kejadian | Memilih event, melihat severity, memulai analisis resource |
| Titik Rawan Banjir | `flood_risk_points` | Point | Pin amber/orange dengan ikon warning | Memberi konteks risiko wilayah |
| Intensitas Kecamatan | GeoJSON batas kecamatan + agregasi `flood_events` | Polygon | Choropleth transparan hijau/amber/coral | Menunjukkan jumlah kejadian per kecamatan |
| Titik Evakuasi | `evacuation_points` | Point | Pin teal/green dengan ikon shelter/lokasi aman | Tujuan rekomendasi evakuasi |
| Pos Alat Berat | `heavy_equipment_posts` | Point | Pin gold/amber dengan ikon resource | Resource pemulihan dan respons |
| Rute Evakuasi Referensi | Routing API OSRM | LineString | Garis civic blue dengan outline/route style | Menunjukkan rute referensi menuju titik evakuasi |

## 4.2 Layer Kejadian Banjir

Layer kejadian banjir menampilkan nama kejadian, status, severity, kecamatan, tinggi air, jenis sumber, dan status data pada popup. Layer ini menjadi titik asal untuk pencarian evakuasi dan alat berat terdekat.

## 4.3 Layer Titik Rawan Banjir

Layer titik rawan banjir menampilkan lokasi representatif risiko banjir berbasis jurnal. Risk level digunakan untuk membedakan prioritas visual, sedangkan popup menampilkan bahwa sumber data berasal dari jurnal.

## 4.4 Layer Intensitas Kecamatan

Layer intensitas kecamatan menampilkan polygon kecamatan Bandar Lampung dengan warna transparan berdasarkan jumlah kejadian banjir pada tabel `flood_events`. Klasifikasi warna yang digunakan adalah 0 kejadian, 1-4 kejadian, 5-7 kejadian, dan 8+ kejadian. Karena distribusi data saat ini masih rendah, kecamatan dengan kejadian berada pada kategori 1-4 kejadian.

## 4.5 Layer Titik Evakuasi dan Pos Alat Berat

Layer titik evakuasi menampilkan kandidat evakuasi simulasi dengan atribut jenis tempat, kapasitas, status, dan kecamatan. Layer pos alat berat menampilkan pos dummy realistis, status pos, kecamatan, dan ketersediaan alat yang dihitung melalui relasi unit alat.

## 4.6 Layer Rute Evakuasi Referensi

Layer rute berasal dari response Routing API yang memanggil OSRM. Rute bersifat referensi dan belum mempertimbangkan jalan tertutup akibat banjir.

# BAB V RANCANGAN UI DAN IMPLEMENTASI PROGRES

## 5.1 Konsep Desain UI

Konsep UI SIGAP Banjir adalah civic modern GIS interface: map-first, tenang, terstruktur, dan data-oriented. Aplikasi menggunakan Plus Jakarta Sans pada UI, JetBrains Mono untuk angka/metadata teknis, warna civic navy/civic blue, merah/coral untuk kejadian, teal/green untuk evakuasi, dan amber/gold untuk risiko serta resource.

## 5.2 Public Map Explorer

Public Map Explorer pada `/peta` menampilkan panel kiri dan peta Leaflet. Panel kiri berisi pencarian, filter kejadian, layer toggle, dan daftar layer. Peta menampilkan marker, polygon intensitas kecamatan, popup, legenda, basemap selector, rekomendasi resource, dan rute referensi.

Gambar 5.1 Tampilan Public Map Explorer SIGAP Banjir: `screenshots/01_public_map_explorer.png`

## 5.3 Dashboard Admin

Dashboard admin menampilkan statistik data aktual database, kejadian terbaru, ketersediaan alat, status data, dan quick action untuk pengelolaan data.

Gambar 5.2 Tampilan Dashboard Admin Berbasis Data Aktual Database: `screenshots/03_admin_dashboard.png`

## 5.4 Sumber Data dan Validasi

Halaman Sumber Data dan Validasi menampilkan statistik transparansi dataset: 40 total data spasial, 10 simulasi, 6 dummy, 24 nyata, dan 16 perlu validasi operasional. Record kejadian banjir tampil sebagai sumber `berita`, status `nyata`, dan terverifikasi. Record titik rawan tampil sebagai sumber `jurnal`, status `nyata`, dan terverifikasi.

Gambar 5.3 Tampilan Monitoring Sumber Data dan Status Validasi: `screenshots/05_data_sources_validation.png`

## 5.5 Detail Kejadian sebagai Decision Support

Detail kejadian banjir menampilkan metadata kejadian, mini map, rekomendasi evakuasi, rekomendasi alat berat, dan rute referensi. Halaman ini menjadi bukti bahwa data spasial tidak hanya divisualisasikan, tetapi juga dianalisis.

# BAB VI HASIL PROGRES DAN VALIDASI FUNGSIONAL

## 6.1 Progres Fitur yang Telah Diimplementasikan

Fitur inti GIS sudah berjalan, meliputi penyimpanan data spasial PostGIS, GeoJSON API, peta publik Leaflet, layer toggle, popup marker, layer polygon intensitas kecamatan, analisis nearest evacuation, analisis nearest equipment, routing referensi OSRM, dashboard admin, CRUD data spasial, dan halaman sumber data/validasi.

## 6.2 Validasi GeoJSON API

Endpoint GeoJSON mengembalikan FeatureCollection untuk layer kejadian banjir, titik rawan, titik evakuasi, pos alat berat, dan intensitas kecamatan. Koordinat GeoJSON tetap memakai urutan longitude, latitude sehingga dapat dibaca Leaflet sesuai standar GeoJSON.

## 6.3 Validasi Analisis Spasial PostGIS

Analisis resource terdekat dilakukan di backend menggunakan `ST_Distance` dengan cast geography. Dengan pendekatan ini, rekomendasi titik evakuasi dan pos alat berat tidak dihitung manual di frontend.

## 6.4 Validasi Routing Referensi OSRM

Routing referensi menggunakan OSRM demo server. Rute ditampilkan sebagai LineString pada peta, tetapi belum memperhitungkan kondisi jalan tertutup, tinggi banjir aktual, atau keputusan petugas lapangan.

## 6.5 Alur Demo Sistem

Alur demo dimulai dari membuka `/peta`, melihat marker kejadian berbasis berita, titik rawan berbasis jurnal, dan layer intensitas kecamatan, memilih kejadian banjir, menampilkan rekomendasi resource terdekat, menampilkan rute referensi, login admin, melihat dashboard, membuka detail kejadian, mengelola data melalui CRUD, dan memeriksa halaman Sumber Data dan Validasi.

# BAB VII KETERBATASAN DAN RENCANA PENGEMBANGAN

Keterbatasan utama adalah koordinat berita dan jurnal masih bersifat representatif, titik evakuasi masih kandidat simulasi, pos alat berat masih dummy realistis, rute OSRM hanya referensi, jalan tertutup akibat banjir belum dipertimbangkan, route history belum tersedia, dan workflow verifikasi massal belum dikembangkan.

Rencana pengembangan berikutnya adalah meningkatkan presisi koordinat melalui survei atau validasi lapangan, memperoleh sumber resmi untuk titik evakuasi dan resource alat berat, memperbaiki model routing jika data jalan terdampak tersedia, dan memperkuat dokumentasi sumber data.

# BAB VIII PENUTUP

## 8.1 Kesimpulan

Progres SIGAP Banjir telah memenuhi komponen rancangan dataset, rancangan layer, dan rancangan UI. Fungsi inti GIS sudah berjalan melalui penyimpanan spasial PostGIS, visualisasi layer Leaflet, GeoJSON API, analisis jarak, routing referensi, dan pengelolaan data admin. Revisi dataset juga membuat layer kejadian dan titik rawan lebih kuat karena sudah berbasis berita dan jurnal.

## 8.2 Saran Pengembangan

Saran pengembangan adalah melanjutkan validasi koordinat, menjaga transparansi status data, dan mengembangkan fitur lanjutan secara bertahap tanpa mengubah karakter project sebagai MVP akademik SIG.

# DAFTAR PUSTAKA / SUMBER REFERENSI

- Detik/Antara. Banjir Terjang 16 Kecamatan di Bandar Lampung, 1 Orang Meninggal. https://news.detik.com/berita/d-8445003/banjir-terjang-16-kecamatan-di-bandar-lampung-1-orang-meninggal
- Rilis ID. Banjir Rendam 34 Titik di Bandar Lampung. https://lampung.rilis.id/Breaking%20News/Berita/banjir-rendam-34-titik-di-bandar-lampung-1-warga-36kR?page=1
- Rilis ID. Bandar Lampung Dikepung Banjir di Kedaton. https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1
- Bongkar Post. Banjir Rendam Bandar Lampung Akibat Hujan Deras. https://bacabongkarpost.com/banjir-rendam-bandar-lampung-akibat-hujan-deras-aktivitas-warga-terganggu/
- Agustri & Asbi (2020). Tingkat Risiko Bencana Banjir di Kota Bandar Lampung dan Upaya Pengurangannya Berbasis Penataan Ruang.
- OpenStreetMap. Copyright and License. https://www.openstreetmap.org/copyright
- Nominatim. Search API Documentation. https://nominatim.org/release-docs/latest/api/Search/
- PostGIS. ST_Distance Documentation. https://postgis.net/docs/ST_Distance.html
- Leaflet. Leaflet API Reference dan GeoJSON Documentation. https://leafletjs.com/reference.html
- OSRM Project. OSRM API Documentation. https://project-osrm.org/docs/

# LAMPIRAN

Daftar file dataset CSV: `flood_events.csv`, `flood_risk_points.csv`, `real_source_flood_dataset.csv`, `evacuation_points.csv`, `heavy_equipment_posts.csv`, `heavy_equipment_units.csv`, dan `equipment_types.csv`.

File audit: `REAL_SOURCE_DATA_AUDIT.md`, `DATA_PROVENANCE_AND_VALIDATION.md`, `nominatim_real_source_validation.json`, `spatial_validation_audit.csv`, dan `SPATIAL_VALIDATION_AUDIT.md`.

Endpoint API utama: `/api/v1/geojson/flood-events`, `/api/v1/geojson/flood-risks`, `/api/v1/geojson/district-flood-intensity`, `/api/v1/geojson/evacuation-points`, `/api/v1/geojson/heavy-equipment-posts`, `/api/v1/analysis/flood-events/{id}/nearest-resources`, dan `/api/v1/routing/flood-events/{id}/to-nearest-evacuation`.

Link Google Drive Laporan dan Video: [Tempelkan Link Google Drive Setelah Upload]
