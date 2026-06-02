# LAPORAN PROGRES PENGEMBANGAN APLIKASI SISTEM INFORMASI GEOGRAFIS

SIGAP BANJIR BANDAR LAMPUNG

Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung

Mata Kuliah Sistem Informasi Geografis / GIS

Dosen Pengampu: Mona Batubara

Disusun oleh: [Isi Nama Anggota dan NPM]

Universitas Lampung, 2026

# BAB I PENDAHULUAN

## 1.1 Latar Belakang

Kota Bandar Lampung memiliki karakter wilayah perkotaan, pesisir, permukiman padat, dan koridor transportasi yang membutuhkan informasi spasial ketika terjadi genangan atau banjir. Dalam konteks mata kuliah Sistem Informasi Geografis, kebutuhan tersebut dapat dikaji melalui rancangan aplikasi yang memetakan lokasi kejadian, titik rawan, titik evakuasi, dan sumber daya respons seperti pos alat berat.

SIGAP Banjir dikembangkan sebagai MVP akademik untuk menunjukkan bagaimana data spasial dapat disimpan, divisualisasikan, dan dianalisis. Fokus laporan progres ini adalah dokumentasi rancangan dataset, rancangan layer, rancangan UI, serta status implementasi yang sudah berjalan pada aplikasi.

## 1.2 Rumusan Masalah

Rumusan masalah progres ini adalah bagaimana merancang dataset spasial untuk kebutuhan pemetaan banjir, bagaimana merancang layer peta yang mendukung informasi kejadian, risiko, evakuasi, dan resource, bagaimana merancang UI aplikasi GIS yang mudah digunakan publik dan admin, serta bagaimana mengintegrasikan analisis jarak dan rute referensi dalam aplikasi.

## 1.3 Tujuan Pengembangan

Tujuan pengembangan SIGAP Banjir adalah membangun aplikasi GIS akademik yang mampu menyimpan data titik spasial di PostgreSQL/PostGIS, menampilkan layer peta melalui Leaflet, menyediakan GeoJSON API, menjalankan analisis resource terdekat menggunakan PostGIS, menampilkan rute referensi OSRM, dan menyediakan UI admin untuk pengelolaan data.

## 1.4 Batasan Progres

Dataset yang digunakan pada tahap progres merupakan dataset pengembangan berbasis lokasi nyata di wilayah Kota Bandar Lampung. Koordinat dan nama area diverifikasi terhadap peta publik, sedangkan status operasional banjir, titik evakuasi, dan pos alat berat digunakan sebagai data simulasi/dummy untuk pengujian fungsi spasial aplikasi. Dataset ini belum diklaim sebagai data operasional resmi pemerintah.

# BAB II GAMBARAN SISTEM DAN PROGRES IMPLEMENTASI

## 2.1 Deskripsi Aplikasi SIGAP Banjir

SIGAP Banjir adalah singkatan dari Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung. Aplikasi ini menyediakan peta publik untuk eksplorasi layer banjir dan panel admin untuk pengelolaan data spasial serta decision support sederhana pada detail kejadian banjir.

## 2.2 Arsitektur Teknologi

Arsitektur alur data aplikasi adalah PostgreSQL + PostGIS -> Laravel Backend dan API -> GeoJSON / Spatial Analysis / Routing Response -> Leaflet Public Map dan Admin Decision Support.

Teknologi yang digunakan meliputi Laravel, Blade, Tailwind CSS, Vite, PostgreSQL, PostGIS, Leaflet, basemap OpenStreetMap/Humanitarian/Satelit tanpa token sesuai implementasi aktual, serta OSRM demo server untuk rute referensi.

## 2.3 Fitur yang Sudah Berjalan

Fitur yang sudah berjalan meliputi Public Map Explorer pada /peta, login admin full-screen split layout, dashboard admin berbasis data database, CRUD kejadian banjir, titik rawan banjir, titik evakuasi, pos alat berat, jenis dan unit alat, detail kejadian banjir sebagai decision support, halaman Sumber Data dan Validasi, GeoJSON API, Spatial Analysis API PostGIS, serta Routing API OSRM.

# BAB III RANCANGAN DATASET

## 3.1 Konsep Dataset

Dataset SIGAP Banjir dirancang sebagai kombinasi data spasial dan data relasional. Data spasial utama mencakup kejadian banjir, titik rawan banjir, titik evakuasi, dan pos alat berat. Data relasional mencakup jenis alat berat dan unit alat berat per pos.

## 3.2 Sistem Koordinat dan Penyimpanan Spasial

Penyimpanan spasial menggunakan kolom geom geometry(Point, 4326) pada PostGIS. Format koordinat memakai WGS 84 / SRID 4326. Urutan koordinat PostGIS dan GeoJSON adalah longitude, latitude. Perhitungan jarak memakai ST_Distance(geom::geography, geom::geography) agar hasil jarak dihitung dalam meter.

## 3.3 Ringkasan Dataset Final

| Dataset | Jenis Geometri | Jumlah Record | Fungsi dalam Sistem | Status Data |
| --- | --- | --- | --- | --- |
| Kejadian Banjir | Point | 8 | Menunjukkan lokasi kejadian dan severity | Simulasi |
| Titik Rawan Banjir | Point | 12 | Menunjukkan potensi/rawan banjir | Simulasi |
| Titik Evakuasi | Point | 10 | Tujuan rekomendasi evakuasi | Simulasi |
| Pos Alat Berat | Point | 6 | Lokasi resource pemulihan | Dummy realistis |
| Unit Alat Berat | Non-spasial/relasional | 15 | Ketersediaan resource per pos | Dummy realistis |
| Jenis Alat | Master data | 6 | Kategori alat berat | Dummy realistis |

Status data spasial final adalah 30 simulasi, 6 dummy, 0 nyata, dan 36 perlu validasi operasional. Status tersebut diperoleh setelah audit dan koreksi inkonsistensi pelabelan Pos Alat Berat Panjang.

## 3.4 Struktur Atribut Dataset

| Dataset | Atribut Utama |
| --- | --- |
| flood_events | id, name, address, district, subdistrict, severity_level, water_depth_cm, status, source_type, source_reference, is_verified, data_status, geom |
| flood_risk_points | id, name, address, district, subdistrict, risk_level, source_type, source_reference, is_verified, data_status, geom |
| evacuation_points | id, name, type, capacity, facilities, contact, status, source_type, source_reference, is_verified, data_status, geom |
| heavy_equipment_posts | id, name, address, district, subdistrict, contact, status, source_type, source_reference, is_verified, data_status, geom |
| equipment_types | id, name, description |
| heavy_equipment_units | id, post_id, equipment_type_id, quantity, available_quantity, status, notes |

## 3.5 Contoh Record Dataset

| Dataset | ID | Nama | Kecamatan | Koordinat | Status Data | Catatan Validasi |
| --- | --- | --- | --- | --- | --- | --- |
| Kejadian Banjir | 9 | Banjir Teluk Betung Selatan | Teluk Betung Selatan | 105.2607000, -5.4478000 | simulasi | Tervalidasi area via OSM/Nominatim |
| Kejadian Banjir | 10 | Genangan Way Halim | Way Halim | 105.2746909, -5.3823404 | simulasi | Tervalidasi area via OSM/Nominatim |
| Kejadian Banjir | 11 | Banjir Sukarame | Sukarame | 105.2946540, -5.3974767 | simulasi | Tervalidasi area via OSM/Nominatim |
| Titik Rawan | 13 | Rawan Banjir Way Halim | Way Halim | 105.2746909, -5.3823404 | simulasi | Tervalidasi area via OSM/Nominatim |
| Titik Rawan | 14 | Rawan Banjir Teluk Betung Selatan | Teluk Betung Selatan | 105.2608000, -5.4469000 | simulasi | Tervalidasi area via OSM/Nominatim |
| Titik Rawan | 15 | Rawan Banjir Panjang Utara | Panjang | 105.3229645, -5.4721335 | simulasi | Tervalidasi area via OSM/Nominatim |
| Titik Evakuasi | 11 | Masjid Al-Furqon Lungsir | Tanjung Karang Pusat | 105.2615707, -5.4291549 | simulasi | Kandidat simulasi, bukan shelter resmi |
| Titik Evakuasi | 12 | GOR Saburai | Enggal | 105.2598000, -5.4218000 | simulasi | Kandidat simulasi, bukan shelter resmi |
| Titik Evakuasi | 13 | Kantor Kecamatan Teluk Betung Selatan | Teluk Betung Selatan | 105.2591000, -5.4485000 | simulasi | Kandidat simulasi, bukan shelter resmi |
| Pos Alat Berat | 7 | Pos Alat Berat Panjang | Panjang | 105.3262000, -5.4669000 | dummy | Dummy realistis, bukan fasilitas resmi |
| Pos Alat Berat | 8 | Pos Alat Berat Teluk Betung | Teluk Betung Selatan | 105.2590000, -5.4442000 | dummy | Dummy realistis, bukan fasilitas resmi |
| Pos Alat Berat | 9 | Pos Alat Berat Rajabasa | Rajabasa | 105.2297280, -5.3627526 | dummy | Dummy realistis, bukan fasilitas resmi |

## 3.6 Validasi Lokasi dan Provenance Data

Audit spasial dilakukan terhadap 36 record menggunakan OpenStreetMap/Nominatim pada 2 Juni 2026. Seluruh record final terbaca pada area Kota Bandar Lampung. Koreksi dilakukan pada 24 record, terutama untuk menyelaraskan koordinat Way Halim, Sukarame, Panjang Utara, Rajabasa, Bumi Waras, Enggal, Kemiling Permai, serta koreksi status Pos Alat Berat Panjang dari nyata menjadi dummy.

# BAB IV RANCANGAN LAYERS

## 4.1 Konsep Layer

Layer aplikasi dirancang agar pengguna dapat membedakan kejadian banjir, titik rawan, titik evakuasi, pos alat berat, dan rute evakuasi referensi. Leaflet mengonsumsi response GeoJSON dari API, sedangkan analisis nearest resource tetap dilakukan di backend menggunakan PostGIS.

| Layer | Sumber Data | Geometry | Simbol Aktual | Fungsi |
| --- | --- | --- | --- | --- |
| Kejadian Banjir | flood_events | Point | Pin merah/coral dengan ikon kejadian | Memilih event, melihat severity, memulai analisis resource |
| Titik Rawan Banjir | flood_risk_points | Point | Pin amber/orange dengan ikon warning | Memberi konteks risiko wilayah |
| Titik Evakuasi | evacuation_points | Point | Pin teal/green dengan ikon shelter/lokasi aman | Tujuan rekomendasi evakuasi |
| Pos Alat Berat | heavy_equipment_posts | Point | Pin gold/amber dengan ikon resource | Resource pemulihan dan respons |
| Rute Evakuasi Referensi | Routing API OSRM | LineString | Garis civic blue dengan outline/route style | Menunjukkan rute referensi menuju titik evakuasi |

## 4.2 Layer Kejadian Banjir

Layer kejadian banjir menampilkan nama kejadian, status, severity, kecamatan, tinggi air, dan status data pada popup. Layer ini menjadi titik asal untuk pencarian evakuasi dan alat berat terdekat.

## 4.3 Layer Titik Rawan Banjir

Layer titik rawan banjir menampilkan lokasi skenario risiko banjir. Risk level digunakan untuk membedakan prioritas visual, tetapi tidak diklaim sebagai klasifikasi resmi pemerintah.

## 4.4 Layer Titik Evakuasi

Layer titik evakuasi menampilkan kandidat evakuasi simulasi dengan atribut jenis tempat, kapasitas, status, dan kecamatan.

## 4.5 Layer Pos Alat Berat

Layer pos alat berat menampilkan pos dummy realistis, status pos, kecamatan, dan ketersediaan alat yang dihitung melalui relasi unit alat.

## 4.6 Layer Rute Evakuasi Referensi

Layer rute berasal dari response Routing API yang memanggil OSRM. Rute bersifat referensi dan belum mempertimbangkan jalan tertutup akibat banjir.

## 4.7 Basemap, Legenda, dan Interaksi Peta

Public Map Explorer menyediakan basemap OpenStreetMap standar, Humanitarian, dan Satelit tanpa token sesuai implementasi aktual. Pengguna dapat memakai layer toggle, filter kejadian, legenda, popup, dan basemap selector.

# BAB V RANCANGAN UI DAN IMPLEMENTASI PROGRES

## 5.1 Konsep Desain UI

Konsep UI SIGAP Banjir adalah civic modern GIS interface: map-first, tenang, terstruktur, dan data-oriented. Aplikasi menggunakan Plus Jakarta Sans pada UI, JetBrains Mono untuk angka/metadata teknis, warna civic navy/civic blue, merah/coral untuk kejadian, teal/green untuk evakuasi, dan amber/gold untuk risiko serta resource.

## 5.2 Public Map Explorer

Public Map Explorer pada /peta menampilkan panel kiri dan peta Leaflet. Panel kiri berisi pencarian, filter kejadian, layer toggle, dan daftar layer, sedangkan peta menampilkan marker, popup, legenda, basemap selector, rekomendasi resource, dan rute referensi.

Gambar 5.1 Tampilan Public Map Explorer SIGAP Banjir: screenshots/01_public_map_explorer.png

## 5.3 Login Admin

Halaman login admin memakai full-screen split layout. Akses admin dipisahkan dari halaman publik agar pengguna umum tetap fokus pada eksplorasi peta.

Gambar 5.2 Tampilan Halaman Login Admin SIGAP Banjir: screenshots/02_admin_login.png

## 5.4 Dashboard Admin

Dashboard admin menampilkan statistik data aktual database, kejadian terbaru, ketersediaan alat, status data, dan quick action untuk pengelolaan data.

Gambar 5.3 Tampilan Dashboard Admin Berbasis Data Aktual Database: screenshots/03_admin_dashboard.png

## 5.5 Detail Kejadian sebagai Decision Support

Detail kejadian banjir menampilkan data kejadian, mini map, rekomendasi evakuasi, rekomendasi alat berat, dan rute referensi. Halaman ini menjadi bukti bahwa data spasial tidak hanya divisualisasikan, tetapi juga dianalisis.

Gambar 5.4 Tampilan Analisis Resource dan Rute pada Detail Kejadian Banjir: screenshots/04_detail_kejadian_decision_support.png

## 5.6 Sumber Data dan Validasi

Halaman Sumber Data dan Validasi menampilkan statistik transparansi dataset: 36 total data spasial, 30 simulasi, 6 dummy, 0 nyata, dan 36 perlu validasi operasional.

Gambar 5.5 Tampilan Monitoring Sumber Data dan Status Validasi: screenshots/05_data_sources_validation.png

## 5.7 Halaman CRUD dan Detail Data Spasial

Halaman CRUD dan detail data spasial tersedia untuk kejadian banjir, titik rawan, titik evakuasi, pos alat berat, jenis alat, dan unit alat. Tampilan CRUD mendukung pengelolaan data, sedangkan detail data membantu pengecekan atribut dan lokasi.

Gambar 5.6 Ringkasan Halaman CRUD dan Detail Data Spasial: screenshots/09_crud_detail_summary.png

# BAB VI HASIL PROGRES DAN VALIDASI FUNGSIONAL

## 6.1 Progres Fitur yang Telah Diimplementasikan

Fitur inti GIS sudah berjalan, meliputi penyimpanan data spasial PostGIS, GeoJSON API, peta publik Leaflet, layer toggle, popup marker, analisis nearest evacuation, analisis nearest equipment, routing referensi OSRM, dashboard admin, CRUD data spasial, dan halaman sumber data/validasi.

## 6.2 Validasi GeoJSON API

Endpoint GeoJSON mengembalikan FeatureCollection untuk layer kejadian banjir, titik rawan, titik evakuasi, dan pos alat berat. Koordinat GeoJSON tetap memakai urutan longitude, latitude sehingga dapat dibaca Leaflet sesuai standar GeoJSON.

## 6.3 Validasi Analisis Spasial PostGIS

Analisis resource terdekat dilakukan di backend menggunakan ST_Distance dengan cast geography. Dengan pendekatan ini, rekomendasi titik evakuasi dan pos alat berat tidak dihitung manual di frontend.

## 6.4 Validasi Routing Referensi OSRM

Routing referensi menggunakan OSRM demo server. Rute ditampilkan sebagai LineString pada peta, tetapi belum memperhitungkan kondisi jalan tertutup, tinggi banjir aktual, atau keputusan petugas lapangan.

## 6.5 Alur Demo Sistem

Alur demo dimulai dari membuka /peta, melihat layer, memilih kejadian banjir, menampilkan rekomendasi resource terdekat, menampilkan rute referensi, login admin, melihat dashboard, membuka detail kejadian, mengelola data melalui CRUD, dan memeriksa halaman Sumber Data dan Validasi.

# BAB VII KETERBATASAN DAN RENCANA PENGEMBANGAN

Keterbatasan utama adalah dataset belum resmi, validasi operasional belum dilakukan, rute OSRM hanya referensi, jalan tertutup akibat banjir belum dipertimbangkan, route history belum tersedia, dan workflow verifikasi massal belum dikembangkan.

Rencana pengembangan berikutnya adalah memperoleh sumber data terverifikasi, menambah referensi sumber pada source_reference, memvalidasi koordinat secara lebih detail, menyusun workflow verifikasi data, memperbaiki model routing jika data jalan terdampak tersedia, dan memperkuat dokumentasi sumber data.

# BAB VIII PENUTUP

## 8.1 Kesimpulan

Progres SIGAP Banjir telah memenuhi tiga komponen minimum tugas dosen, yaitu rancangan dataset, rancangan layers, dan rancangan UI. Selain itu, fungsi inti GIS sudah berjalan melalui penyimpanan spasial PostGIS, visualisasi layer Leaflet, GeoJSON API, analisis jarak, routing referensi, dan pengelolaan data admin.

## 8.2 Saran Pengembangan

Saran pengembangan adalah melanjutkan validasi dataset dengan sumber resmi atau berita terverifikasi, menjaga transparansi status data, dan mengembangkan fitur lanjutan secara bertahap tanpa mengubah karakter project sebagai MVP akademik SIG.

# DAFTAR PUSTAKA / SUMBER REFERENSI

- OpenStreetMap. Copyright and License. https://www.openstreetmap.org/copyright
- Nominatim. Reverse Geocoding API Documentation. https://nominatim.org/release-docs/latest/api/Reverse/
- PostGIS. ST_Distance Documentation. https://postgis.net/docs/ST_Distance.html
- Leaflet. Leaflet API Reference dan GeoJSON Documentation. https://leafletjs.com/reference.html dan https://leafletjs.com/examples/geojson/
- OSRM Project. OSRM API Documentation. https://project-osrm.org/docs/

# LAMPIRAN

Daftar file dataset CSV: flood_events.csv, flood_risk_points.csv, evacuation_points.csv, heavy_equipment_posts.csv, heavy_equipment_units.csv, equipment_types.csv.

File audit koordinat: spatial_validation_audit.csv dan SPATIAL_VALIDATION_AUDIT.md.

Endpoint API utama: /api/v1/geojson/flood-events, /api/v1/geojson/flood-risks, /api/v1/geojson/evacuation-points, /api/v1/geojson/heavy-equipment-posts, /api/v1/analysis/flood-events/{id}/nearest-resources, /api/v1/routing/flood-events/{id}/to-nearest-evacuation.

Daftar screenshot: 01_public_map_explorer.png, 02_admin_login.png, 03_admin_dashboard.png, 04_detail_kejadian_decision_support.png, 05_data_sources_validation.png, 06_crud_flood_events.png, 07_detail_evakuasi.png, 08_detail_pos_alat_berat.png.

Link Google Drive Laporan dan Video: [Tempelkan Link Google Drive Setelah Upload]
