# DATABASE.md

# Desain Database Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

## A. Ringkasan Desain Database

Database project ini menggunakan PostgreSQL dengan extension PostGIS. PostgreSQL digunakan sebagai relational database utama, sedangkan PostGIS digunakan untuk menyimpan, mengelola, dan menganalisis data spasial.

PostGIS dibutuhkan karena sistem tidak hanya menyimpan data lokasi, tetapi juga melakukan analisis spasial, seperti:

1. Menentukan titik evakuasi terdekat dari lokasi banjir.
2. Menentukan pos alat berat terdekat dari lokasi banjir.
3. Menghitung jarak antar titik dalam meter.
4. Mengirim data spasial ke frontend dalam format GeoJSON.
5. Menyediakan dasar untuk visualisasi peta menggunakan Leaflet.

Data lokasi tidak cukup hanya disimpan sebagai latitude dan longitude biasa. Latitude dan longitude hanya berupa angka koordinat, sedangkan kolom spasial PostGIS memungkinkan sistem melakukan query geografis seperti `ST_Distance`, `ST_DWithin`, `ST_AsGeoJSON`, dan spatial indexing.

Sumber utama analisis spasial dalam database ini adalah kolom `geom`.

## B. Prinsip Desain Database

Prinsip desain database yang digunakan:

1. Data spasial disimpan menggunakan kolom PostGIS.
2. Kolom `geom` menjadi sumber utama untuk analisis spasial.
3. Latitude dan longitude boleh disimpan sebagai kolom bantu jika diperlukan, tetapi tidak menjadi sumber utama analisis.
4. Struktur tabel dibuat cukup normal, tetapi tidak terlalu kompleks.
5. Desain database disesuaikan dengan kebutuhan MVP.
6. Data alat berat boleh berupa dummy, tetapi harus realistis secara geografis.
7. Rute evakuasi tidak wajib disimpan permanen jika hanya ditampilkan dari API routing.
8. Riwayat rute dan dispatch alat berat bersifat opsional.
9. Tabel batas kecamatan atau kelurahan bersifat opsional jika data spasial wilayah tersedia.
10. Database harus mendukung kebutuhan akademik SIG, bukan hanya kebutuhan CRUD biasa.

## C. Extension dan Sistem Koordinat

### Extension yang Dibutuhkan

Extension utama:

```sql
postgis
```

Extension ini diperlukan agar PostgreSQL dapat menyimpan dan memproses tipe data spasial seperti `POINT`, `LINESTRING`, dan `POLYGON`.

### SRID yang Digunakan

SRID yang direkomendasikan:

```text
SRID 4326
```

SRID 4326 adalah sistem koordinat WGS 84 yang umum digunakan oleh GPS, OpenStreetMap, Leaflet, OSRM, dan OpenRouteService.

### Alasan Menggunakan SRID 4326

1. Kompatibel dengan Leaflet.
2. Kompatibel dengan OpenStreetMap.
3. Kompatibel dengan API routing seperti OSRM dan OpenRouteService.
4. Mudah digunakan untuk input koordinat longitude dan latitude.
5. Cocok untuk project web GIS skala akademik.

### Geometry vs Geography

`geometry` cocok digunakan untuk:

1. Penyimpanan data spasial utama.
2. Visualisasi peta.
3. Export GeoJSON.
4. Query spasial umum.
5. Spatial index dengan GiST.

`geography` cocok digunakan untuk:

1. Perhitungan jarak dalam meter secara langsung pada koordinat bumi.
2. Query jarak yang membutuhkan hasil lebih mudah dibaca dalam satuan meter.

### Rekomendasi Final

Gunakan kolom utama:

```text
geom geometry(Point, 4326)
```

Untuk perhitungan jarak dalam meter, gunakan cast ke `geography` pada query:

```sql
ST_Distance(a.geom::geography, b.geom::geography)
```

Dengan pendekatan ini, database tetap mudah divisualisasikan di Leaflet dan tetap mampu menghitung jarak dalam meter secara akurat untuk kebutuhan MVP.

## D. Daftar Tabel Utama

### 1. users

Fungsi: menyimpan akun admin.  
Alasan: dibutuhkan untuk autentikasi dan pencatatan siapa yang membuat data.  
Status MVP: wajib.  
Relasi: satu user dapat membuat banyak data banjir.

### 2. flood_risk_points

Fungsi: menyimpan titik rawan banjir.  
Alasan: titik rawan banjir berbeda dari kejadian banjir aktif atau historis.  
Status MVP: wajib.  
Relasi: dapat dibuat oleh user/admin.

### 3. flood_events

Fungsi: menyimpan titik kejadian banjir aktif atau historis.  
Alasan: kejadian banjir memiliki atribut waktu, severity, status, dan tinggi air.  
Status MVP: wajib.  
Relasi: dapat dibuat oleh user/admin, dapat digunakan untuk rekomendasi evakuasi dan alat berat.

### 4. evacuation_points

Fungsi: menyimpan titik evakuasi.  
Alasan: digunakan sebagai tujuan rekomendasi evakuasi.  
Status MVP: wajib.  
Relasi: digunakan dalam query titik evakuasi terdekat dari flood_events.

### 5. heavy_equipment_posts

Fungsi: menyimpan lokasi pos atau tempat alat berat berada.  
Alasan: sistem perlu menentukan pos alat berat terdekat dari lokasi banjir.  
Status MVP: wajib.  
Relasi: satu post dapat memiliki banyak unit alat berat.

### 6. equipment_types

Fungsi: menyimpan master jenis alat berat.  
Alasan: agar jenis alat berat tidak ditulis berulang secara bebas.  
Status MVP: wajib ringan.  
Relasi: satu equipment type dapat digunakan oleh banyak heavy equipment units.

### 7. heavy_equipment_units

Fungsi: menyimpan jumlah dan status alat berat di setiap pos.  
Alasan: lebih rapi dibanding menyimpan semua informasi alat berat langsung di tabel post.  
Status MVP: direkomendasikan.  
Relasi: terhubung ke heavy_equipment_posts dan equipment_types.

### 8. equipment_dispatch_logs

Fungsi: menyimpan riwayat rekomendasi atau pengiriman alat berat.  
Alasan: berguna untuk riwayat keputusan, tetapi belum wajib untuk MVP.  
Status MVP: opsional.

### 9. route_histories

Fungsi: menyimpan riwayat rute evakuasi.  
Alasan: hanya diperlukan jika rute ingin disimpan sebagai histori.  
Status MVP: opsional.

### 10. districts

Fungsi: menyimpan batas wilayah kecamatan.  
Alasan: berguna untuk filter dan analisis per wilayah.  
Status MVP: opsional.

### 11. data_sources

Fungsi: menyimpan sumber data nyata atau dummy.  
Alasan: membantu membedakan data dari berita, pemerintah, observasi, atau dummy akademik.  
Status MVP: opsional, tetapi direkomendasikan jika data dari banyak sumber.

## E. Detail Struktur Tabel

## 1. Tabel users

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama admin | Admin SIG |
| email | varchar | tidak | Email login | admin@example.com |
| password | varchar | tidak | Password terenkripsi | hashed_password |
| role | varchar | tidak | Role pengguna | admin |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 2. Tabel flood_risk_points

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama titik rawan | Rawan Banjir Teluk Betung |
| address | text | ya | Alamat atau deskripsi lokasi | Jl. Ikan Tenggiri |
| district | varchar | ya | Kecamatan | Teluk Betung Selatan |
| subdistrict | varchar | ya | Kelurahan | Kangkung |
| risk_level | enum/varchar | tidak | Tingkat risiko | tinggi |
| description | text | ya | Keterangan tambahan | Sering tergenang saat hujan deras |
| source_type | varchar | tidak | Jenis sumber data | berita |
| source_reference | text | ya | URL atau nama sumber | https://... |
| is_verified | boolean | tidak | Status verifikasi data | true |
| data_status | varchar | tidak | Status data nyata/dummy/simulasi | nyata |
| geom | geometry(Point, 4326) | tidak | Titik spasial lokasi rawan banjir | POINT(105.25 -5.45) |
| created_by | bigint | ya | Foreign key ke users | 1 |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 3. Tabel flood_events

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama kejadian banjir | Banjir Teluk Betung |
| address | text | ya | Alamat kejadian | Jl. Yos Sudarso |
| district | varchar | ya | Kecamatan | Teluk Betung Selatan |
| subdistrict | varchar | ya | Kelurahan | Pesawahan |
| severity_level | enum/varchar | tidak | Tingkat keparahan | tinggi |
| water_depth_cm | integer | ya | Estimasi tinggi air | 60 |
| status | enum/varchar | tidak | Status kejadian | aktif |
| description | text | ya | Keterangan kejadian | Genangan menutup sebagian jalan |
| source_type | varchar | tidak | Jenis sumber data | admin_input |
| source_reference | text | ya | URL berita atau catatan sumber | Laporan simulasi |
| occurred_at | datetime | ya | Waktu kejadian | 2026-05-19 08:30:00 |
| reported_at | datetime | tidak | Waktu dilaporkan ke sistem | 2026-05-19 09:00:00 |
| is_verified | boolean | tidak | Status verifikasi data | true |
| data_status | varchar | tidak | Status data nyata/dummy/simulasi | simulasi |
| geom | geometry(Point, 4326) | tidak | Titik spasial kejadian banjir | POINT(105.26 -5.44) |
| created_by | bigint | ya | Foreign key ke users | 1 |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 09:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 09:10:00 |

## 4. Tabel evacuation_points

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama titik evakuasi | SDN 1 Teluk Betung |
| type | varchar | tidak | Jenis lokasi evakuasi | sekolah |
| address | text | ya | Alamat | Jl. Wolter Monginsidi |
| district | varchar | ya | Kecamatan | Teluk Betung Utara |
| subdistrict | varchar | ya | Kelurahan | Kupang Kota |
| capacity | integer | ya | Kapasitas orang | 250 |
| facilities | json/text | ya | Fasilitas tersedia | aula, toilet, dapur umum |
| contact_person | varchar | ya | Nama kontak | Petugas Kelurahan |
| contact_phone | varchar | ya | Nomor kontak | 08xxxxxxxxxx |
| status | enum/varchar | tidak | Status titik evakuasi | aktif |
| description | text | ya | Keterangan tambahan | Cocok untuk evakuasi sementara |
| source_type | varchar | tidak | Jenis sumber data | observasi |
| source_reference | text | ya | URL atau catatan sumber | Validasi peta |
| is_verified | boolean | tidak | Status verifikasi data | false |
| data_status | varchar | tidak | Status data nyata/dummy/simulasi | simulasi |
| geom | geometry(Point, 4326) | tidak | Titik spasial lokasi evakuasi | POINT(105.25 -5.43) |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

Contoh jenis fasilitas:

1. aula
2. sekolah
3. masjid
4. gedung pemerintah
5. lapangan
6. puskesmas

## 5. Tabel heavy_equipment_posts

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama pos alat berat | Pos Alat Berat Panjang |
| address | text | ya | Alamat pos | Sekitar Pelabuhan Panjang |
| district | varchar | ya | Kecamatan | Panjang |
| subdistrict | varchar | ya | Kelurahan | Panjang Utara |
| contact_person | varchar | ya | Nama kontak | Koordinator Pos |
| contact_phone | varchar | ya | Nomor kontak | 08xxxxxxxxxx |
| status | enum/varchar | tidak | Status pos | aktif |
| description | text | ya | Keterangan | Pos dummy realistis untuk demo |
| source_type | varchar | tidak | Jenis sumber data | dummy |
| source_reference | text | ya | URL atau catatan sumber | Data demo akademik |
| is_verified | boolean | tidak | Status verifikasi data | false |
| data_status | varchar | tidak | Status data nyata/dummy/simulasi | dummy |
| geom | geometry(Point, 4326) | tidak | Titik spasial lokasi pos | POINT(105.32 -5.47) |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 6. Tabel equipment_types

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama jenis alat | excavator |
| description | text | ya | Keterangan jenis alat | Untuk pengerukan material dan lumpur |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 7. Tabel heavy_equipment_units

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| post_id | bigint | tidak | Foreign key ke heavy_equipment_posts | 1 |
| equipment_type_id | bigint | tidak | Foreign key ke equipment_types | 1 |
| quantity | integer | tidak | Total unit di pos | 2 |
| available_quantity | integer | tidak | Unit yang tersedia | 1 |
| status | enum/varchar | tidak | Status unit | tersedia |
| notes | text | ya | Catatan tambahan | Satu unit sedang digunakan |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 8. Tabel equipment_dispatch_logs

Status MVP: opsional.

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| flood_event_id | bigint | tidak | Foreign key ke flood_events | 1 |
| equipment_unit_id | bigint | ya | Foreign key ke heavy_equipment_units | 1 |
| post_id | bigint | tidak | Foreign key ke heavy_equipment_posts | 1 |
| distance_meters | numeric | ya | Jarak dari banjir ke pos | 2450.5 |
| status | varchar | tidak | Status dispatch | direkomendasikan |
| notes | text | ya | Catatan | Dipilih karena paling dekat |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 9. Tabel route_histories

Status MVP: opsional.

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| flood_event_id | bigint | tidak | Foreign key ke flood_events | 1 |
| evacuation_point_id | bigint | tidak | Foreign key ke evacuation_points | 2 |
| provider | varchar | tidak | Provider routing | osrm |
| distance_meters | numeric | ya | Jarak rute | 3200 |
| duration_seconds | numeric | ya | Estimasi durasi | 540 |
| route_geometry | geometry(LineString, 4326) | ya | Geometri rute jika disimpan | LINESTRING(...) |
| raw_response | json | ya | Response mentah dari API routing | {...} |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |

## 10. Tabel districts

Status MVP: opsional.

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama kecamatan | Teluk Betung Selatan |
| code | varchar | ya | Kode wilayah jika ada | 187101 |
| geom | geometry(Polygon/MultiPolygon, 4326) | tidak | Batas wilayah kecamatan | MULTIPOLYGON(...) |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

## 11. Tabel data_sources

Status MVP: opsional tetapi direkomendasikan untuk dokumentasi akademik.

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama sumber | Berita lokal |
| type | varchar | tidak | Jenis sumber | berita |
| reference_url | text | ya | Link sumber | https://... |
| description | text | ya | Keterangan | Data kejadian banjir historis |
| accessed_at | date | ya | Tanggal akses sumber | 2026-05-19 |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |

## F. Rancangan Tabel Flood

Ada dua opsi desain untuk data banjir.

### Opsi 1: Satu tabel gabungan banjir

Semua data rawan banjir dan kejadian banjir disimpan dalam satu tabel gabungan, lalu dibedakan dengan kolom `category`. Opsi ini tidak dipilih pada desain final.

Contoh category:

1. `rawan`
2. `kejadian`

Kelebihan:

1. Struktur tabel lebih sedikit.
2. Query peta banjir bisa lebih sederhana.
3. Cocok untuk prototype sangat kecil.

Kekurangan:

1. Banyak kolom akan kosong.
2. Titik rawan dan kejadian banjir memiliki makna data yang berbeda.
3. Data kejadian banjir membutuhkan waktu kejadian, status, severity, dan tinggi air.
4. Data titik rawan lebih cocok memakai risk level, bukan event status.
5. Kurang rapi untuk laporan akademik database.

### Opsi 2: Tabel terpisah flood_risk_points dan flood_events

Titik rawan banjir disimpan di `flood_risk_points`, sedangkan kejadian banjir disimpan di `flood_events`.

Kelebihan:

1. Struktur lebih jelas.
2. Lebih sesuai dengan konsep akademik.
3. Mengurangi kolom kosong.
4. Memisahkan data potensi risiko dan data kejadian aktual.
5. Lebih mudah dijelaskan dalam ERD dan laporan.

Kekurangan:

1. Jumlah tabel bertambah.
2. Endpoint GeoJSON perlu dipisahkan.

### Rekomendasi Final

Gunakan tabel terpisah:

1. `flood_risk_points`
2. `flood_events`

Alasannya, project ini perlu membedakan antara titik rawan banjir dan kejadian banjir. Keduanya terlihat mirip di peta karena sama-sama titik, tetapi berbeda secara konseptual dan atribut.

Untuk MVP akademik SIG, pemisahan ini lebih rapi dan lebih mudah dipertanggungjawabkan.

## G. Rancangan Tabel evacuation_points

Tabel `evacuation_points` menyimpan lokasi yang dapat digunakan sebagai tujuan evakuasi.

Kolom penting:

| Kolom | Tipe Data Konseptual | Nullable | Keterangan | Contoh |
|---|---|---:|---|---|
| id | bigint | tidak | Primary key | 1 |
| name | varchar | tidak | Nama titik evakuasi | Masjid Agung Al-Furqon |
| type | varchar | tidak | Jenis tempat | masjid |
| address | text | ya | Alamat | Jl. Diponegoro |
| district | varchar | ya | Kecamatan | Teluk Betung Utara |
| subdistrict | varchar | ya | Kelurahan | Gulak Galik |
| capacity | integer | ya | Kapasitas orang | 500 |
| facilities | json/text | ya | Fasilitas | aula, toilet, parkir |
| contact_person | varchar | ya | Kontak pengelola | Pengurus |
| contact_phone | varchar | ya | Nomor kontak | 08xxxxxxxxxx |
| status | varchar | tidak | Status | aktif |
| description | text | ya | Keterangan | Dapat dipakai untuk evakuasi sementara |
| source_type | varchar | tidak | Jenis sumber data | observasi |
| source_reference | text | ya | URL atau catatan sumber | Validasi peta |
| is_verified | boolean | tidak | Status verifikasi data | false |
| data_status | varchar | tidak | Status data nyata/dummy/simulasi | simulasi |
| geom | geometry(Point, 4326) | tidak | Lokasi spasial | POINT(105.26 -5.42) |
| created_at | timestamp | tidak | Waktu dibuat | 2026-05-19 10:00:00 |
| updated_at | timestamp | tidak | Waktu diperbarui | 2026-05-19 10:00:00 |

Jenis titik evakuasi yang disarankan:

1. `aula`
2. `sekolah`
3. `masjid`
4. `gedung_pemerintah`
5. `lapangan`
6. `puskesmas`

## H. Rancangan Tabel Heavy Equipment

### Pendekatan 1: Data alat berat langsung di heavy_equipment_posts

Semua informasi pos, jenis alat, jumlah, dan status disimpan dalam satu tabel.

Kelebihan:

1. Lebih sederhana.
2. Cocok jika setiap pos hanya memiliki satu jenis alat.
3. Cepat dibuat.

Kekurangan:

1. Tidak fleksibel jika satu pos memiliki beberapa jenis alat.
2. Data jenis alat bisa berulang.
3. Sulit mencatat quantity dan available quantity per jenis alat.
4. Kurang rapi untuk analisis sumber daya respons.

### Pendekatan 2: Pos alat berat dipisah dari jenis dan unit alat

Gunakan tiga tabel:

1. `heavy_equipment_posts`
2. `equipment_types`
3. `heavy_equipment_units`

Kelebihan:

1. Lebih rapi.
2. Satu pos dapat memiliki beberapa jenis alat.
3. Quantity dan available quantity dapat dihitung.
4. Cocok untuk rekomendasi alat berat terdekat.
5. Tetap tidak terlalu kompleks.

Kekurangan:

1. Jumlah tabel bertambah.
2. CRUD sedikit lebih banyak.

### Rekomendasi Final

Gunakan pendekatan kedua:

1. `heavy_equipment_posts` untuk lokasi pos.
2. `equipment_types` untuk master jenis alat.
3. `heavy_equipment_units` untuk jumlah alat di setiap pos.

Ini masih realistis untuk MVP, tetapi cukup rapi untuk project akademik.

## I. Rancangan Relasi Database

Relasi utama:

1. `users` memiliki banyak `flood_risk_points`.
2. `users` memiliki banyak `flood_events`.
3. `heavy_equipment_posts` memiliki banyak `heavy_equipment_units`.
4. `equipment_types` memiliki banyak `heavy_equipment_units`.
5. `flood_events` dapat memiliki banyak `equipment_dispatch_logs`.
6. `flood_events` dapat memiliki banyak `route_histories`.
7. `evacuation_points` dapat memiliki banyak `route_histories`.
8. `data_sources` dapat direferensikan oleh data banjir jika fitur sumber data dibuat lebih formal.

Untuk MVP, relasi yang wajib:

1. `users` ke `flood_events`.
2. `users` ke `flood_risk_points`.
3. `heavy_equipment_posts` ke `heavy_equipment_units`.
4. `equipment_types` ke `heavy_equipment_units`.

Relasi dispatch dan route history dapat ditunda.

## J. ERD Tekstual

Desain final yang direkomendasikan:

```text
users
  ├── flood_risk_points
  └── flood_events

flood_events
  ├── equipment_dispatch_logs      [opsional]
  └── route_histories              [opsional]

evacuation_points
  └── route_histories              [opsional]

heavy_equipment_posts
  └── heavy_equipment_units
        └── equipment_types

districts                            [opsional]
  ├── flood_risk_points              [relasi konseptual via district]
  ├── flood_events                   [relasi konseptual via district]
  ├── evacuation_points              [relasi konseptual via district]
  └── heavy_equipment_posts          [relasi konseptual via district]

data_sources                         [opsional]
  ├── flood_risk_points
  └── flood_events
```

Catatan: untuk MVP, `district` dan `subdistrict` dapat disimpan sebagai teks terlebih dahulu. Relasi formal ke tabel `districts` dapat dibuat jika data batas wilayah sudah tersedia.

## K. Index Database

Index yang dibutuhkan:

### Primary Key

Setiap tabel utama menggunakan primary key pada kolom `id`.

### Foreign Key

Foreign key digunakan pada:

1. `flood_risk_points.created_by` ke `users.id`
2. `flood_events.created_by` ke `users.id`
3. `heavy_equipment_units.post_id` ke `heavy_equipment_posts.id`
4. `heavy_equipment_units.equipment_type_id` ke `equipment_types.id`
5. `equipment_dispatch_logs.flood_event_id` ke `flood_events.id`
6. `route_histories.flood_event_id` ke `flood_events.id`
7. `route_histories.evacuation_point_id` ke `evacuation_points.id`

### Index Status

Index disarankan pada:

1. `flood_events.status`
2. `flood_events.severity_level`
3. `evacuation_points.status`
4. `heavy_equipment_posts.status`
5. `heavy_equipment_units.status`

Index ini mempercepat filter data aktif, tersedia, atau berdasarkan severity.

### Index District dan Subdistrict

Index disarankan pada:

1. `flood_risk_points.district`
2. `flood_events.district`
3. `evacuation_points.district`
4. `heavy_equipment_posts.district`

Index ini mempercepat filter berdasarkan kecamatan.

### Spatial Index

Spatial index wajib dibuat pada kolom `geom` menggunakan GiST.

Kolom yang membutuhkan spatial index:

1. `flood_risk_points.geom`
2. `flood_events.geom`
3. `evacuation_points.geom`
4. `heavy_equipment_posts.geom`
5. `districts.geom` jika digunakan
6. `route_histories.route_geometry` jika digunakan

Spatial index penting karena query titik terdekat dan filter radius dapat menjadi lambat jika database harus menghitung jarak terhadap semua titik tanpa bantuan index.

## L. Query Spasial Utama yang Dibutuhkan

Bagian ini berisi contoh query konseptual, bukan SQL final untuk migration.

### 1. Mengambil Semua Kejadian Banjir sebagai GeoJSON

```sql
SELECT
  id,
  name,
  severity_level,
  status,
  ST_AsGeoJSON(geom) AS geometry
FROM flood_events;
```

### 2. Mengambil Semua Titik Evakuasi sebagai GeoJSON

```sql
SELECT
  id,
  name,
  type,
  capacity,
  status,
  ST_AsGeoJSON(geom) AS geometry
FROM evacuation_points
WHERE status = 'aktif';
```

### 3. Mengambil Semua Pos Alat Berat sebagai GeoJSON

```sql
SELECT
  p.id,
  p.name,
  p.status,
  ST_AsGeoJSON(p.geom) AS geometry
FROM heavy_equipment_posts p
WHERE p.status = 'aktif';
```

### 4. Mencari Titik Evakuasi Terdekat dari Kejadian Banjir

```sql
SELECT
  e.id,
  e.name,
  e.capacity,
  e.status,
  ST_Distance(f.geom::geography, e.geom::geography) AS distance_meters
FROM flood_events f
JOIN evacuation_points e ON e.status = 'aktif'
WHERE f.id = :flood_event_id
ORDER BY distance_meters ASC
LIMIT 1;
```

### 5. Mencari Pos Alat Berat Terdekat dari Kejadian Banjir

```sql
SELECT
  p.id,
  p.name,
  p.status,
  ST_Distance(f.geom::geography, p.geom::geography) AS distance_meters
FROM flood_events f
JOIN heavy_equipment_posts p ON p.status = 'aktif'
JOIN heavy_equipment_units u ON u.post_id = p.id
WHERE f.id = :flood_event_id
AND u.status = 'tersedia'
AND u.available_quantity > 0
GROUP BY f.geom, p.id, p.name, p.status, p.geom
ORDER BY distance_meters ASC
LIMIT 3;
```

### 6. Menghitung Jarak dalam Meter

```sql
SELECT
  ST_Distance(
    geom_a::geography,
    geom_b::geography
  ) AS distance_meters;
```

### 7. Filter Titik Berdasarkan Kecamatan

```sql
SELECT *
FROM flood_events
WHERE district = :district_name;
```

### 8. Filter Titik Berdasarkan Status atau Severity

```sql
SELECT *
FROM flood_events
WHERE status = 'aktif'
AND severity_level IN ('tinggi', 'kritis');
```

### 9. Membuat Radius Terdampak Sederhana

Untuk kebutuhan visualisasi radius, gunakan buffer berbasis geography lalu kembalikan ke geometry.

```sql
SELECT
  id,
  name,
  ST_AsGeoJSON(
    ST_Buffer(geom::geography, 500)::geometry
  ) AS impact_radius
FROM flood_events
WHERE id = :flood_event_id;
```

Contoh radius:

1. severity rendah: 100 meter
2. severity sedang: 250 meter
3. severity tinggi: 500 meter
4. severity kritis: 1000 meter

Fitur ini opsional setelah MVP utama selesai.

### 10. Mengambil Koordinat untuk Routing OSRM/OpenRouteService

```sql
SELECT
  ST_X(geom) AS longitude,
  ST_Y(geom) AS latitude
FROM flood_events
WHERE id = :flood_event_id;
```

Untuk titik tujuan evakuasi:

```sql
SELECT
  ST_X(geom) AS longitude,
  ST_Y(geom) AS latitude
FROM evacuation_points
WHERE id = :evacuation_point_id;
```

### Catatan ST_MakePoint dan ST_SetSRID

Saat menyimpan titik dari input peta:

```sql
ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326)
```

Urutan koordinat penting:

```text
ST_MakePoint(longitude, latitude)
```

Bukan:

```text
ST_MakePoint(latitude, longitude)
```

## M. Format GeoJSON untuk Frontend

Data dari database dikirim ke Leaflet dalam format GeoJSON `FeatureCollection`.

### 1. GeoJSON Kejadian Banjir

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.2601, -5.4452]
      },
      "properties": {
        "id": 1,
        "name": "Banjir Teluk Betung",
        "severity_level": "tinggi",
        "status": "aktif",
        "water_depth_cm": 60,
        "district": "Teluk Betung Selatan",
        "occurred_at": "2026-05-19 08:30:00"
      }
    }
  ]
}
```

### 2. GeoJSON Titik Evakuasi

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.2520, -5.4300]
      },
      "properties": {
        "id": 1,
        "name": "Masjid Agung Al-Furqon",
        "type": "masjid",
        "capacity": 500,
        "status": "aktif",
        "district": "Teluk Betung Utara"
      }
    }
  ]
}
```

### 3. GeoJSON Pos Alat Berat

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.3200, -5.4700]
      },
      "properties": {
        "id": 1,
        "name": "Pos Alat Berat Panjang",
        "status": "aktif",
        "district": "Panjang",
        "available_equipment": [
          {
            "type": "excavator",
            "available_quantity": 1
          },
          {
            "type": "dump_truck",
            "available_quantity": 2
          }
        ]
      }
    }
  ]
}
```

### 4. GeoJSON Rute Evakuasi

Jika rute berasal dari OSRM atau OpenRouteService, frontend dapat langsung memakai geometry dari response provider.

Contoh struktur yang disederhanakan:

```json
{
  "type": "Feature",
  "geometry": {
    "type": "LineString",
    "coordinates": [
      [105.2601, -5.4452],
      [105.2580, -5.4401],
      [105.2520, -5.4300]
    ]
  },
  "properties": {
    "from": "Banjir Teluk Betung",
    "to": "Masjid Agung Al-Furqon",
    "distance_meters": 3200,
    "duration_seconds": 540,
    "provider": "osrm"
  }
}
```

Properti yang sebaiknya dikirim ke frontend:

1. `id`
2. `name`
3. `status`
4. `district`
5. `severity_level` untuk banjir
6. `risk_level` untuk titik rawan
7. `capacity` untuk evakuasi
8. `available_quantity` untuk alat berat
9. `distance_meters` untuk hasil rekomendasi

## N. Strategi Seed Data

Seed data diperlukan agar sistem dapat didemonstrasikan tanpa menunggu input manual yang terlalu banyak.

### 1. Seed Titik Rawan Banjir

Sumber dapat berasal dari:

1. berita lokal;
2. dokumen pemerintah;
3. laporan BPBD jika tersedia;
4. observasi manual dari peta dan referensi publik.

Data harus memuat nama lokasi, kecamatan, tingkat risiko, sumber, dan koordinat.

### 2. Seed Titik Kejadian Banjir Historis

Data historis dapat berasal dari:

1. berita kejadian banjir;
2. dokumentasi pemerintah;
3. simulasi akademik berbasis lokasi rawan.

Jika data dibuat dummy, tandai dengan `data_status = dummy`.

### 3. Seed Titik Evakuasi

Titik evakuasi dapat berupa:

1. sekolah;
2. masjid besar;
3. gedung pemerintah;
4. puskesmas;
5. aula;
6. lapangan.

Lokasi harus masuk akal dan tidak terlalu jauh dari kawasan rawan banjir.

### 4. Seed Alat Berat Dummy Realistis

Data alat berat dapat ditempatkan pada lokasi strategis seperti:

1. sekitar Panjang;
2. sekitar Teluk Betung;
3. sekitar Tanjung Karang;
4. sekitar Rajabasa;
5. sekitar Kemiling.

Aturan penting:

1. Koordinat harus divalidasi manual melalui peta atau geocoding.
2. Jangan mengklaim data dummy sebagai data resmi.
3. Jika data berasal dari berita atau pemerintah, simpan sumber referensinya.
4. Data dummy harus tetap masuk akal secara geografis.

## O. Data Nyata vs Data Dummy

Untuk membedakan data nyata dan dummy, gunakan kolom berikut:

| Kolom | Fungsi | Contoh |
|---|---|---|
| source_type | Menjelaskan asal data | pemerintah, berita, jurnal, observasi, admin_input, dummy |
| source_reference | Menyimpan URL atau keterangan sumber | https://... |
| is_verified | Menandai apakah data sudah diverifikasi | true |
| data_status | Menandai status data | nyata, dummy, simulasi |

Pembedaan ini penting untuk laporan akademik karena:

1. menjaga transparansi sumber data;
2. menghindari klaim data palsu sebagai data resmi;
3. memudahkan dosen menilai metode pengumpulan data;
4. membedakan data observasi, berita, dan simulasi;
5. membuat dokumentasi dataset lebih bertanggung jawab.

## P. Validasi Data

Validasi dilakukan pada level aplikasi dan database.

### Validasi Umum

1. Nama lokasi wajib diisi.
2. Kolom `geom` wajib diisi untuk semua data spasial.
3. Status harus sesuai daftar nilai yang ditentukan.
4. Severity harus sesuai daftar nilai.
5. Risk level harus sesuai daftar nilai.
6. Capacity tidak boleh negatif.
7. Quantity tidak boleh negatif.
8. Available quantity tidak boleh melebihi quantity.
9. Nomor kontak boleh kosong, tetapi jika diisi harus dalam format wajar.
10. Koordinat harus berada di wilayah Bandar Lampung atau sekitar wilayah studi.

### Validasi Spasial

1. Longitude dan latitude tidak boleh tertukar.
2. Kejadian banjir harus berada di sekitar Kota Bandar Lampung.
3. Titik evakuasi tidak boleh terlalu jauh dari wilayah studi.
4. Pos alat berat dummy harus ditempatkan di lokasi yang masuk akal.
5. Geometry harus memiliki SRID 4326.

### Validasi Khusus

Untuk `flood_events`:

1. `severity_level` wajib.
2. `status` wajib.
3. `reported_at` wajib.
4. `water_depth_cm` jika diisi tidak boleh negatif.

Untuk `evacuation_points`:

1. `capacity` jika diisi tidak boleh negatif.
2. `status` wajib.

Untuk `heavy_equipment_units`:

1. `quantity` wajib.
2. `available_quantity` wajib.
3. `available_quantity <= quantity`.

## Q. Enum atau Status yang Digunakan

### Status flood_events

| Nilai | Keterangan |
|---|---|
| aktif | Banjir sedang terjadi |
| surut | Air sudah surut |
| ditangani | Sedang dalam proses penanganan |
| arsip | Data historis atau tidak aktif |

### Severity flood_events

| Nilai | Keterangan |
|---|---|
| rendah | Genangan kecil, dampak terbatas |
| sedang | Genangan mengganggu aktivitas |
| tinggi | Banjir cukup serius dan perlu penanganan |
| kritis | Banjir parah dan prioritas tinggi |

### Risk Level flood_risk_points

| Nilai | Keterangan |
|---|---|
| rendah | Risiko banjir rendah |
| sedang | Risiko banjir sedang |
| tinggi | Risiko banjir tinggi |

### Status evacuation_points

| Nilai | Keterangan |
|---|---|
| aktif | Dapat digunakan |
| penuh | Kapasitas penuh |
| tidak_aktif | Tidak dapat digunakan |

### Status heavy_equipment_posts

| Nilai | Keterangan |
|---|---|
| aktif | Pos dapat digunakan |
| tidak_aktif | Pos tidak digunakan |

### Status heavy_equipment_units

| Nilai | Keterangan |
|---|---|
| tersedia | Dapat digunakan |
| digunakan | Sedang digunakan |
| perawatan | Dalam perawatan |
| tidak_aktif | Tidak dapat digunakan |

### Status equipment_dispatch_logs

| Nilai | Keterangan |
|---|---|
| direkomendasikan | Baru direkomendasikan oleh sistem |
| dikirim | Alat berat dikirim |
| selesai | Penanganan selesai |
| dibatalkan | Dispatch dibatalkan |

### Data Status

| Nilai | Keterangan |
|---|---|
| nyata | Data berasal dari sumber nyata |
| dummy | Data dibuat untuk demo |
| simulasi | Data dibuat untuk skenario akademik |

## R. Keputusan Desain Penting

### 1. Flood Risk dan Flood Event

Keputusan final: dipisah menjadi `flood_risk_points` dan `flood_events`.

Alasan: titik rawan dan kejadian banjir memiliki karakter data berbeda.

### 2. Data Alat Berat

Keputusan final: dipisah menjadi `heavy_equipment_posts`, `equipment_types`, dan `heavy_equipment_units`.

Alasan: satu pos dapat memiliki beberapa jenis alat berat dan jumlah ketersediaan berbeda.

### 3. Route History

Keputusan final: tidak wajib untuk MVP.

Alasan: rute evakuasi dapat diambil langsung dari OSRM atau OpenRouteService saat dibutuhkan. Simpan route history hanya jika ingin menambahkan fitur riwayat.

### 4. Geometry atau Geography

Keputusan final: simpan data utama sebagai `geometry(Point, 4326)`, lalu cast ke `geography` saat menghitung jarak dalam meter.

Alasan: geometry cocok untuk Leaflet dan GeoJSON, geography membantu perhitungan jarak.

### 5. Latitude dan Longitude Terpisah

Keputusan final: tidak wajib disimpan sebagai kolom permanen.

Alasan: longitude dan latitude dapat diambil dari `geom` menggunakan `ST_X` dan `ST_Y`.

Jika dibutuhkan untuk debugging atau kemudahan form, latitude dan longitude boleh dibuat sebagai virtual accessor di Laravel, bukan sumber utama database.

### 6. Batas Kecamatan

Keputusan final: opsional untuk MVP.

Alasan: sistem tetap layak sebagai SIG tanpa polygon kecamatan. Namun jika data batas kecamatan tersedia, tabel `districts` akan meningkatkan nilai akademik.

### 7. Data Sources

Keputusan final: opsional tetapi direkomendasikan.

Alasan: membantu laporan akademik membedakan data nyata, dummy, dan simulasi.

## S. Rekomendasi Implementasi Laravel

Desain database ini nanti dapat diterjemahkan ke Laravel melalui beberapa komponen.

### 1. Migration

Migration digunakan untuk membuat tabel, kolom, foreign key, index, dan kolom spasial.

Catatan implementasi:

1. Pastikan extension PostGIS aktif.
2. Gunakan kolom spasial untuk `geom`.
3. Buat spatial index pada kolom `geom`.
4. Buat index untuk `status`, `district`, dan `severity_level`.
5. Jangan hanya menyimpan latitude dan longitude sebagai sumber utama lokasi.

### 2. Model

Model Laravel yang disarankan:

1. `User`
2. `FloodRiskPoint`
3. `FloodEvent`
4. `EvacuationPoint`
5. `HeavyEquipmentPost`
6. `EquipmentType`
7. `HeavyEquipmentUnit`
8. `EquipmentDispatchLog` opsional
9. `RouteHistory` opsional
10. `District` opsional
11. `DataSource` opsional

### 3. Seeder

Seeder digunakan untuk:

1. membuat akun admin awal;
2. mengisi data titik rawan banjir;
3. mengisi data kejadian banjir historis atau simulasi;
4. mengisi titik evakuasi;
5. mengisi pos dan unit alat berat dummy realistis;
6. mengisi master jenis alat berat.

### 4. Controller

Controller yang disarankan:

1. `PublicMapController` untuk halaman peta publik dan ringkasan peta.
2. `FloodRiskPointController` untuk titik rawan banjir.
3. `FloodEventController` untuk kejadian banjir.
4. `EvacuationPointController` untuk titik evakuasi.
5. `HeavyEquipmentPostController` untuk pos alat berat.
6. `EquipmentTypeController` untuk jenis alat berat.
7. `HeavyEquipmentUnitController` untuk unit alat berat.
8. `SpatialAnalysisController` untuk rekomendasi titik terdekat.
9. `GeoJsonController` untuk response GeoJSON.

### 5. Service untuk Query PostGIS

Agar controller tidak terlalu penuh, query spasial sebaiknya diletakkan di service, misalnya:

1. `NearestEvacuationService`
2. `NearestEquipmentService`
3. `GeoJsonService`
4. `RoutingService`

Service ini bertanggung jawab untuk:

1. mencari titik evakuasi terdekat;
2. mencari alat berat terdekat;
3. mengubah data menjadi GeoJSON;
4. mengambil koordinat untuk OSRM/OpenRouteService.

### 6. Endpoint GeoJSON

Endpoint GeoJSON yang disarankan:

1. `/api/v1/geojson/flood-risks`
2. `/api/v1/geojson/flood-events`
3. `/api/v1/geojson/evacuation-points`
4. `/api/v1/geojson/heavy-equipment-posts`
5. `/api/v1/analysis/flood-events/{id}/nearest-evacuation`
6. `/api/v1/analysis/flood-events/{id}/nearest-equipment`
7. `/api/v1/routing/flood-events/{id}/to-nearest-evacuation`

Endpoint ini akan dipakai oleh Leaflet untuk menampilkan layer peta.

## T. Checklist DATABASE.md

Checklist kesiapan desain database:

- [ ] Tabel utama sudah jelas.
- [ ] Pemisahan titik rawan banjir dan kejadian banjir sudah ditentukan.
- [ ] Kolom spasial `geom` sudah ditentukan.
- [ ] SRID 4326 sudah dipilih.
- [ ] Relasi utama sudah jelas.
- [ ] Tabel alat berat sudah dipisah antara pos, jenis, dan unit.
- [ ] Spatial index GiST sudah dirancang.
- [ ] Index status dan wilayah sudah dirancang.
- [ ] Query PostGIS utama sudah dirancang.
- [ ] Format GeoJSON untuk frontend sudah dirancang.
- [ ] Strategi seed data sudah direncanakan.
- [ ] Data nyata dan dummy sudah dibedakan.
- [ ] Validasi data sudah dijelaskan.
- [ ] Fitur opsional sudah dipisahkan dari MVP.
- [ ] Siap dilanjutkan ke `DATASET.md` atau `API.md`.

## Keputusan Final Database MVP

Database MVP menggunakan PostgreSQL + PostGIS dengan tabel inti:

1. `users`
2. `flood_risk_points`
3. `flood_events`
4. `evacuation_points`
5. `heavy_equipment_posts`
6. `equipment_types`
7. `heavy_equipment_units`

Tabel opsional:

1. `equipment_dispatch_logs`
2. `route_histories`
3. `districts`
4. `data_sources`

Kolom spasial utama menggunakan:

```text
geom geometry(Point, 4326)
```

Perhitungan jarak menggunakan:

```text
ST_Distance(geom::geography, geom::geography)
```

Desain ini sudah cukup kuat untuk MVP akademik SIG karena mendukung pemetaan, data spasial, GeoJSON, analisis titik terdekat, dan integrasi routing sederhana.
