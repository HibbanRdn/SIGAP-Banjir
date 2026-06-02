# DATA DICTIONARY SIGAP BANJIR

## 1. Tujuan Dataset

Dataset SIGAP Banjir digunakan sebagai dataset awal pengembangan untuk menguji fungsi utama aplikasi Sistem Informasi Geografis Antisipasi dan Penanganan Banjir Bandar Lampung. Dataset ini mendukung pemetaan titik banjir, pemetaan titik rawan, pengelolaan titik evakuasi, pengelolaan pos dan unit alat berat, analisis resource terdekat, serta visualisasi rute evakuasi referensi.

Dataset saat ini belum diposisikan sebagai dataset resmi pemerintah. Data digunakan untuk pengembangan dan demonstrasi akademik, dengan status sumber, status data, dan verifikasi tetap ditampilkan secara eksplisit.

## 2. Sistem Koordinat dan Penyimpanan Spasial

| Komponen | Keterangan |
|---|---|
| Sistem koordinat | WGS 84 |
| SRID | `4326` |
| Kolom spasial database | `geom geometry(Point, 4326)` |
| Format koordinat database | `ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)` |
| Format koordinat GeoJSON | `[longitude, latitude]` |
| Format koordinat Leaflet | Leaflet menerima `LatLng`, tetapi data GeoJSON tetap `[longitude, latitude]` |
| Perhitungan jarak | `ST_Distance(geom::geography, geom::geography)` |

Kolom `longitude` dan `latitude` pada CSV adalah hasil ekspor dari `geom` menggunakan `ST_X(geom)` dan `ST_Y(geom)`. Analisis spasial utama pada aplikasi tetap menggunakan kolom `geom`, bukan angka koordinat biasa.

## 3. Data Dictionary: `flood_events`

Fungsi: menyimpan titik kejadian banjir simulasi untuk status aktif, ditangani, surut, atau arsip.

| Kolom | Tipe Konseptual | Keterangan |
|---|---|---|
| `id` | bigint | Primary key kejadian banjir |
| `name` | string | Nama kejadian banjir |
| `address` | text | Alamat atau deskripsi lokasi kejadian |
| `district` | string | Kecamatan |
| `subdistrict` | string | Kelurahan atau area |
| `severity_level` | enum | Tingkat keparahan: `rendah`, `sedang`, `tinggi`, `kritis` |
| `water_depth_cm` | integer | Estimasi tinggi air dalam sentimeter |
| `status` | enum | Status kejadian: `aktif`, `ditangani`, `surut`, `arsip` |
| `description` | text | Catatan skenario atau deskripsi kejadian |
| `source_type` | enum | Jenis sumber data |
| `source_reference` | text | Referensi sumber atau catatan dataset |
| `occurred_at` | datetime | Waktu kejadian dalam skenario aplikasi |
| `reported_at` | datetime | Waktu kejadian dilaporkan ke sistem |
| `is_verified` | boolean | Status verifikasi operasional data |
| `data_status` | enum | Status data: `nyata`, `dummy`, `simulasi` |
| `longitude` | decimal | Longitude hasil `ST_X(geom)` |
| `latitude` | decimal | Latitude hasil `ST_Y(geom)` |

## 4. Data Dictionary: `flood_risk_points`

Fungsi: menyimpan titik rawan banjir sebagai layer potensi risiko berbasis skenario pengembangan.

| Kolom | Tipe Konseptual | Keterangan |
|---|---|---|
| `id` | bigint | Primary key titik rawan |
| `name` | string | Nama titik rawan banjir |
| `address` | text | Alamat atau deskripsi lokasi |
| `district` | string | Kecamatan |
| `subdistrict` | string | Kelurahan atau area |
| `risk_level` | enum | Tingkat risiko: `rendah`, `sedang`, `tinggi` |
| `description` | text | Deskripsi kondisi rawan |
| `source_type` | enum | Jenis sumber data |
| `source_reference` | text | Referensi sumber atau catatan dataset |
| `is_verified` | boolean | Status verifikasi operasional data |
| `data_status` | enum | Status data |
| `longitude` | decimal | Longitude hasil `ST_X(geom)` |
| `latitude` | decimal | Latitude hasil `ST_Y(geom)` |

## 5. Data Dictionary: `evacuation_points`

Fungsi: menyimpan kandidat titik evakuasi simulasi yang digunakan untuk rekomendasi evakuasi terdekat.

| Kolom | Tipe Konseptual | Keterangan |
|---|---|---|
| `id` | bigint | Primary key titik evakuasi |
| `name` | string | Nama titik evakuasi atau kandidat evakuasi |
| `type` | enum/string | Jenis tempat, misalnya `masjid`, `aula`, `sekolah`, `gedung_pemerintah` |
| `address` | text | Alamat titik evakuasi |
| `district` | string | Kecamatan |
| `subdistrict` | string | Kelurahan atau area |
| `capacity` | integer | Estimasi kapasitas orang |
| `facilities` | text | Fasilitas utama, disimpan sebagai teks daftar fasilitas |
| `contact_person` | string | Kontak pengelola simulasi |
| `contact_phone` | string | Nomor kontak demo |
| `status` | enum | Status: `aktif`, `penuh`, `tidak_aktif` |
| `description` | text | Deskripsi titik evakuasi |
| `source_type` | enum | Jenis sumber data |
| `source_reference` | text | Referensi sumber atau catatan dataset |
| `is_verified` | boolean | Status verifikasi operasional data |
| `data_status` | enum | Status data |
| `longitude` | decimal | Longitude hasil `ST_X(geom)` |
| `latitude` | decimal | Latitude hasil `ST_Y(geom)` |

## 6. Data Dictionary: `heavy_equipment_posts`

Fungsi: menyimpan lokasi pos alat berat sebagai resource respons banjir dalam skenario dummy realistis.

| Kolom | Tipe Konseptual | Keterangan |
|---|---|---|
| `id` | bigint | Primary key pos alat berat |
| `name` | string | Nama pos alat berat |
| `address` | text | Alamat atau deskripsi lokasi pos |
| `district` | string | Kecamatan |
| `subdistrict` | string | Kelurahan atau area |
| `contact_person` | string | Kontak koordinator pos simulasi |
| `contact_phone` | string | Nomor kontak demo |
| `status` | enum | Status pos: `aktif`, `tidak_aktif` |
| `description` | text | Deskripsi pos |
| `source_type` | enum | Jenis sumber data |
| `source_reference` | text | Referensi sumber atau catatan dataset |
| `is_verified` | boolean | Status verifikasi operasional data |
| `data_status` | enum | Status data |
| `longitude` | decimal | Longitude hasil `ST_X(geom)` |
| `latitude` | decimal | Latitude hasil `ST_Y(geom)` |

## 7. Data Dictionary: `equipment_types`

Fungsi: master jenis alat berat.

| Kolom | Tipe Konseptual | Keterangan |
|---|---|---|
| `id` | bigint | Primary key jenis alat |
| `name` | string | Nama jenis alat, misalnya `excavator`, `pompa_air` |
| `description` | text | Fungsi alat dalam konteks respons banjir |

## 8. Data Dictionary: `heavy_equipment_units`

Fungsi: menyimpan jumlah unit alat berat per pos dan jenis alat.

| Kolom | Tipe Konseptual | Keterangan |
|---|---|---|
| `id` | bigint | Primary key unit alat |
| `post_id` | bigint | Foreign key ke `heavy_equipment_posts.id` |
| `post_name` | string | Nama pos hasil join untuk kebutuhan CSV |
| `equipment_type_id` | bigint | Foreign key ke `equipment_types.id` |
| `equipment_type_name` | string | Nama jenis alat hasil join untuk kebutuhan CSV |
| `quantity` | integer | Jumlah total unit |
| `available_quantity` | integer | Jumlah unit tersedia |
| `status` | enum | Status: `tersedia`, `digunakan`, `perawatan`, `tidak_aktif` |
| `notes` | text | Catatan kondisi unit |

## 9. Arti Status Utama

| Status | Nilai | Arti |
|---|---|---|
| Status banjir | `aktif` | Banjir sedang aktif dalam skenario aplikasi |
| Status banjir | `ditangani` | Kejadian sedang dalam penanganan simulasi |
| Status banjir | `surut` | Air sudah surut dalam skenario |
| Status banjir | `arsip` | Data historis atau tidak aktif |
| Severity | `rendah` | Dampak terbatas |
| Severity | `sedang` | Mengganggu aktivitas |
| Severity | `tinggi` | Perlu perhatian respons |
| Severity | `kritis` | Prioritas tinggi |
| Risk level | `rendah` | Risiko rendah dalam skenario pengembangan |
| Risk level | `sedang` | Risiko sedang dalam skenario pengembangan |
| Risk level | `tinggi` | Risiko tinggi dalam skenario pengembangan |
| Status evakuasi | `aktif` | Dapat digunakan pada skenario aplikasi |
| Status evakuasi | `penuh` | Kapasitas dianggap penuh untuk variasi demo |
| Status evakuasi | `tidak_aktif` | Tidak digunakan pada skenario aplikasi |
| Status alat | `tersedia` | Dapat digunakan pada skenario aplikasi |
| Status alat | `digunakan` | Sedang digunakan pada skenario aplikasi |
| Status alat | `perawatan` | Dalam perawatan pada skenario aplikasi |
| Status alat | `tidak_aktif` | Tidak aktif pada skenario aplikasi |
| Data status | `simulasi` | Data skenario akademik |
| Data status | `dummy` | Data demo realistis non-resmi |
| Data status | `nyata` | Data yang benar-benar memiliki bukti sumber nyata; saat ini 0 record |
| Verification | `is_verified = false` | Belum terverifikasi operasional resmi |

## 10. Relasi Antar Dataset

| Relasi | Keterangan |
|---|---|
| `heavy_equipment_posts` -> `heavy_equipment_units` | Satu pos dapat memiliki banyak unit alat |
| `equipment_types` -> `heavy_equipment_units` | Satu jenis alat dapat dipakai banyak unit |
| `flood_events` -> `evacuation_points` | Tidak ada foreign key langsung; relasi analisis dihitung dengan jarak PostGIS |
| `flood_events` -> `heavy_equipment_posts` | Tidak ada foreign key langsung; relasi analisis dihitung dengan jarak PostGIS |

## 11. Validasi Penting

| Validasi | Aturan/Hasil |
|---|---|
| Koordinat | Longitude sekitar `105.xxxx`, latitude sekitar `-5.xxxx`, dan diaudit melalui OpenStreetMap/Nominatim |
| Urutan koordinat | PostGIS dan GeoJSON memakai `[longitude, latitude]` |
| Geometry | Semua tabel spasial memiliki `geom` dengan SRID 4326 |
| Quantity | `available_quantity` tidak boleh lebih besar dari `quantity` |
| Sumber data | Data nyata harus memiliki referensi sumber yang dapat diverifikasi |
| Status verifikasi | Data tanpa bukti sumber tetap perlu validasi operasional lanjutan |
| Transparansi | Data simulasi/dummy tidak boleh diklaim sebagai data resmi |
