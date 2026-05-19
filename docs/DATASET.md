# DATASET.md

# Dataset Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

## A. Ringkasan Dataset

Project ini membutuhkan beberapa dataset untuk mendukung pemetaan, analisis spasial, rekomendasi titik terdekat, dan demo sistem.

Dataset dibagi menjadi:

1. **Data nyata**
   - Digunakan untuk titik rawan banjir, kejadian banjir historis, fasilitas publik, dan batas wilayah jika tersedia.
   - Data nyata harus memiliki sumber yang jelas.

2. **Data dummy**
   - Digunakan terutama untuk pos alat berat, unit alat berat, dan sebagian kapasitas titik evakuasi jika tidak tersedia data resmi.
   - Data dummy harus ditandai jelas dan tidak boleh diklaim sebagai data resmi.

3. **Data simulasi**
   - Digunakan untuk skenario demo, misalnya kejadian banjir aktif yang sengaja dibuat untuk menguji rekomendasi titik evakuasi dan alat berat.
   - Data simulasi harus ditandai sebagai `simulasi`.

4. **Data opsional**
   - Batas kecamatan/kelurahan.
   - Riwayat rute evakuasi.
   - Riwayat dispatch alat berat.
   - Tabel sumber data formal.

Untuk MVP, dataset wajib adalah titik rawan banjir, kejadian banjir, titik evakuasi, pos alat berat, jenis alat berat, dan unit alat berat.

## B. Prinsip Penggunaan Data

Prinsip penggunaan data:

1. Data nyata tidak boleh dicampur dengan data dummy tanpa penanda.
2. Data dummy tidak boleh diklaim sebagai data resmi.
3. Setiap data harus memiliki informasi sumber melalui `source_type`, `source_reference`, `data_status`, dan `is_verified`.
4. Koordinat harus divalidasi melalui peta atau geocoding.
5. Lokasi harus berada di Kota Bandar Lampung atau area sekitar yang relevan.
6. Data harus cukup realistis untuk kebutuhan demo akademik.
7. Data harus mendukung analisis spasial PostGIS, terutama pencarian titik terdekat.
8. Data yang tidak jelas sumber atau lokasinya harus ditandai sebagai belum terverifikasi.
9. Dataset tidak perlu terlalu besar pada MVP, tetapi harus cukup untuk memperlihatkan fungsi SIG.

## C. Kategori Dataset

| Kategori Dataset | Fungsi | Status | Jenis Data | Tabel Tujuan | Spasial | Atribut Penting |
|---|---|---|---|---|---|---|
| Titik rawan banjir | Menampilkan lokasi rawan banjir | Wajib | Nyata/dummy terbatas | `flood_risk_points` | Ya | nama, risiko, sumber, geom |
| Titik kejadian banjir | Menampilkan kejadian banjir aktif/historis | Wajib | Nyata/simulasi | `flood_events` | Ya | severity, status, waktu, geom |
| Titik evakuasi | Tujuan evakuasi dan rekomendasi terdekat | Wajib | Nyata/dummy atribut | `evacuation_points` | Ya | kapasitas, fasilitas, status, geom |
| Pos alat berat | Lokasi sumber alat berat | Wajib | Dummy realistis | `heavy_equipment_posts` | Ya | nama pos, status, geom |
| Jenis alat berat | Master jenis alat berat | Wajib | Dummy | `equipment_types` | Tidak | nama jenis, fungsi |
| Unit alat berat | Jumlah alat per pos | Wajib | Dummy realistis | `heavy_equipment_units` | Tidak langsung | post, jenis, quantity |
| Batas kecamatan/kelurahan | Filter dan visualisasi wilayah | Opsional | Nyata jika tersedia | `districts` | Ya | nama, kode, polygon |
| Rute evakuasi | Riwayat rute jika disimpan | Opsional | API/simulasi | `route_histories` | Ya | linestring, jarak, durasi |
| Sumber data | Dokumentasi sumber dataset | Opsional | Nyata | `data_sources` | Tidak | nama sumber, URL, tanggal akses |

## D. Dataset Titik Rawan Banjir

Dataset ini menyimpan lokasi yang berpotensi atau sering mengalami banjir. Data ini sebaiknya menggunakan sumber nyata jika tersedia.

Atribut yang dibutuhkan:

- `name`
- `address`
- `district`
- `subdistrict`
- `risk_level`
- `source_type`
- `source_reference`
- `data_status`
- `is_verified`
- `longitude`
- `latitude`
- `geom`
- `notes`

Sumber data yang dapat digunakan:

1. Berita lokal.
2. Website BPBD atau pemerintah daerah.
3. Dokumen pemerintah.
4. Jurnal atau penelitian banjir Bandar Lampung.
5. Observasi manual berbasis peta untuk keperluan akademik.

Contoh format dataset:

| name | address | district | subdistrict | risk_level | source_type | source_reference | data_status | is_verified | longitude | latitude | notes |
|---|---|---|---|---|---|---|---|---|---:|---:|---|
| Contoh Rawan Teluk Betung | Placeholder lokasi rawan | Teluk Betung | Perlu validasi | tinggi | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh, bukan data resmi |
| Contoh Rawan Panjang | Placeholder lokasi rawan | Panjang | Perlu validasi | tinggi | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Koordinat harus divalidasi |
| Contoh Rawan Rajabasa | Placeholder lokasi rawan | Rajabasa | Perlu validasi | sedang | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh demo |
| Contoh Rawan Kemiling | Placeholder lokasi rawan | Kemiling | Perlu validasi | sedang | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh demo |
| Contoh Rawan Sukarame | Placeholder lokasi rawan | Sukarame | Perlu validasi | rendah | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh demo |

Catatan: kolom `geom` tidak perlu ditulis manual dalam CSV. Nilainya dibuat dari `longitude` dan `latitude` saat import ke database.

## E. Dataset Titik Kejadian Banjir

Dataset ini menyimpan kejadian banjir aktif, historis, simulasi, atau arsip.

Atribut yang dibutuhkan:

- `name`
- `address`
- `district`
- `subdistrict`
- `severity_level`
- `water_depth_cm`
- `status`
- `occurred_at`
- `reported_at`
- `source_type`
- `source_reference`
- `data_status`
- `is_verified`
- `longitude`
- `latitude`
- `geom`

Perbedaan status data kejadian:

| Jenis | Penjelasan |
|---|---|
| Aktif | Banjir sedang terjadi dalam skenario sistem |
| Historis | Kejadian banjir masa lalu dari sumber nyata |
| Simulasi | Kejadian dibuat untuk demo akademik |
| Arsip | Data lama yang tidak aktif |

Contoh format dataset:

| name | address | district | subdistrict | severity_level | water_depth_cm | status | occurred_at | reported_at | source_type | source_reference | data_status | is_verified | longitude | latitude |
|---|---|---|---|---|---:|---|---|---|---|---|---|---|---:|---:|
| Simulasi Banjir Teluk Betung | Placeholder lokasi | Teluk Betung | Perlu validasi | tinggi | 60 | aktif | 2026-05-19 08:00 | 2026-05-19 08:30 | admin_input | skenario demo | simulasi | false | 105.xxxx | -5.xxxx |
| Simulasi Banjir Rajabasa | Placeholder lokasi | Rajabasa | Perlu validasi | sedang | 35 | aktif | 2026-05-19 09:00 | 2026-05-19 09:20 | admin_input | skenario demo | simulasi | false | 105.xxxx | -5.xxxx |
| Simulasi Banjir Panjang | Placeholder lokasi | Panjang | Perlu validasi | tinggi | 55 | aktif | 2026-05-19 10:00 | 2026-05-19 10:15 | admin_input | skenario demo | simulasi | false | 105.xxxx | -5.xxxx |

## F. Dataset Titik Evakuasi

Dataset ini menyimpan lokasi yang dapat digunakan sebagai tempat evakuasi sementara.

Atribut yang dibutuhkan:

- `name`
- `type`
- `address`
- `district`
- `subdistrict`
- `capacity`
- `facilities`
- `contact_person`
- `contact_phone`
- `status`
- `source_type`
- `source_reference`
- `data_status`
- `is_verified`
- `longitude`
- `latitude`
- `geom`

Jenis tempat evakuasi:

1. `sekolah`
2. `masjid`
3. `gedung_pemerintah`
4. `aula`
5. `lapangan`
6. `puskesmas`

Data titik evakuasi sebaiknya berasal dari fasilitas publik yang lokasinya dapat diverifikasi di peta. Jika kapasitas resmi tidak tersedia, kapasitas boleh berupa estimasi akademik dan harus diberi catatan.

Contoh format dataset:

| name | type | address | district | subdistrict | capacity | facilities | contact_person | contact_phone | status | source_type | source_reference | data_status | is_verified | longitude | latitude |
|---|---|---|---|---|---:|---|---|---|---|---|---|---|---|---:|---:|
| Contoh Sekolah Evakuasi Teluk Betung | sekolah | Placeholder alamat | Teluk Betung | Perlu validasi | 250 | aula,toilet,parkir | Petugas | - | aktif | observasi | contoh akademik | simulasi | false | 105.xxxx | -5.xxxx |
| Contoh Masjid Evakuasi Panjang | masjid | Placeholder alamat | Panjang | Perlu validasi | 300 | aula,toilet | Pengurus | - | aktif | observasi | contoh akademik | simulasi | false | 105.xxxx | -5.xxxx |
| Contoh Gedung Evakuasi Rajabasa | gedung_pemerintah | Placeholder alamat | Rajabasa | Perlu validasi | 200 | aula,parkir | Petugas | - | aktif | observasi | contoh akademik | simulasi | false | 105.xxxx | -5.xxxx |

## G. Dataset Pos Alat Berat

Dataset ini menyimpan lokasi pos alat berat. Pada MVP, data pos alat berat boleh dummy, tetapi harus realistis secara geografis.

Aturan dummy realistis:

1. Pos diletakkan di lokasi strategis.
2. Tidak semua pos memiliki semua jenis alat.
3. Jumlah unit tidak terlalu berlebihan.
4. Lokasi pos harus masuk akal secara geografis.
5. Status ketersediaan harus realistis.
6. Pos tidak boleh ditempatkan sembarangan hanya untuk memenangkan skenario demo.

Wilayah pos yang dapat dipertimbangkan:

1. Teluk Betung
2. Panjang
3. Tanjung Karang
4. Rajabasa
5. Kemiling
6. Sukarame
7. Way Halim

Atribut yang dibutuhkan:

- `name`
- `address`
- `district`
- `subdistrict`
- `contact_person`
- `contact_phone`
- `status`
- `source_type`
- `source_reference`
- `data_status`
- `is_verified`
- `longitude`
- `latitude`
- `geom`

Contoh format dataset:

| name | address | district | subdistrict | contact_person | contact_phone | status | source_type | source_reference | data_status | is_verified | longitude | latitude | notes |
|---|---|---|---|---|---|---|---|---|---|---|---:|---:|---|
| Pos Dummy Teluk Betung | Sekitar Teluk Betung, perlu validasi | Teluk Betung | Perlu validasi | Koordinator Pos | - | aktif | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh akademik |
| Pos Dummy Panjang | Sekitar Panjang, perlu validasi | Panjang | Perlu validasi | Koordinator Pos | - | aktif | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh akademik |
| Pos Dummy Rajabasa | Sekitar Rajabasa, perlu validasi | Rajabasa | Perlu validasi | Koordinator Pos | - | aktif | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh akademik |
| Pos Dummy Kemiling | Sekitar Kemiling, perlu validasi | Kemiling | Perlu validasi | Koordinator Pos | - | aktif | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh akademik |
| Pos Dummy Sukarame | Sekitar Sukarame, perlu validasi | Sukarame | Perlu validasi | Koordinator Pos | - | aktif | dummy | contoh akademik | dummy | false | 105.xxxx | -5.xxxx | Contoh akademik |

## H. Dataset Jenis dan Unit Alat Berat

### Dataset equipment_types

Jenis alat yang disarankan:

| name | Fungsi dalam konteks banjir |
|---|---|
| excavator | Membersihkan material, lumpur, atau membuka akses |
| dump_truck | Mengangkut material, sampah, atau lumpur |
| wheel_loader | Memindahkan material dalam jumlah besar |
| pompa_air | Membantu penyedotan genangan |
| crane_kecil | Membantu pengangkatan material ringan |
| mobil_tangki | Distribusi air bersih atau penyemprotan |
| pickup_operasional | Mobilitas petugas dan logistik ringan |

### Dataset heavy_equipment_units

Atribut yang dibutuhkan:

- `post_id`
- `equipment_type`
- `quantity`
- `available_quantity`
- `status`
- `notes`

Contoh format dataset:

| post_name | equipment_type | quantity | available_quantity | status | notes |
|---|---|---:|---:|---|---|
| Pos Dummy Teluk Betung | excavator | 1 | 1 | tersedia | Untuk respons wilayah Teluk Betung |
| Pos Dummy Teluk Betung | dump_truck | 2 | 1 | tersedia | Satu unit dapat diasumsikan sedang standby |
| Pos Dummy Panjang | pompa_air | 2 | 2 | tersedia | Cocok untuk genangan |
| Pos Dummy Rajabasa | pickup_operasional | 2 | 2 | tersedia | Operasional ringan |
| Pos Dummy Kemiling | wheel_loader | 1 | 1 | tersedia | Untuk material berat |

## I. Dataset Batas Kecamatan/Kelurahan

Dataset batas kecamatan/kelurahan bersifat opsional untuk MVP.

Jika digunakan, dataset ini dapat dipakai untuk:

1. Filter wilayah.
2. Visualisasi batas administrasi.
3. Rekap jumlah kejadian banjir per kecamatan.
4. Analisis sebaran banjir.

Kemungkinan sumber:

1. Portal data pemerintah.
2. File GeoJSON/SHP resmi jika tersedia.
3. Sumber terbuka seperti OpenStreetMap atau portal geospasial.
4. Data manual untuk demo, dengan status `simulasi`.

Format data:

| Kolom | Keterangan |
|---|---|
| name | Nama kecamatan/kelurahan |
| code | Kode wilayah jika tersedia |
| geom | Polygon/MultiPolygon SRID 4326 |
| source_type | Sumber data |
| source_reference | Referensi sumber |
| is_verified | Status verifikasi |
| data_status | nyata/simulasi |

## J. Aturan Status Data

Kolom status sumber yang digunakan:

| Kolom | Fungsi |
|---|---|
| source_type | Jenis sumber data |
| source_reference | URL, dokumen, atau catatan sumber |
| data_status | Status data nyata, dummy, atau simulasi |
| is_verified | Apakah data sudah diverifikasi |

Nilai `source_type`:

1. `pemerintah`
2. `berita`
3. `jurnal`
4. `observasi`
5. `admin_input`
6. `dummy`

Nilai `data_status`:

1. `nyata`
2. `dummy`
3. `simulasi`

Nilai `is_verified`:

1. `true`
2. `false`

Contoh penerapan:

| Kasus | source_type | data_status | is_verified |
|---|---|---|---|
| Data dari website BPBD | pemerintah | nyata | true |
| Data dari berita lokal | berita | nyata | true/false |
| Data alat berat buatan demo | dummy | dummy | false |
| Kejadian banjir skenario demo | admin_input | simulasi | false |

## K. Aturan Koordinat

Aturan koordinat:

1. Koordinat menggunakan longitude dan latitude.
2. Format koordinat mengikuti WGS 84 / SRID 4326.
3. Urutan untuk PostGIS adalah longitude, latitude.
4. Jangan tertukar antara latitude dan longitude.
5. Koordinat harus divalidasi dengan peta.
6. Titik harus berada di wilayah Kota Bandar Lampung atau area sekitar yang relevan.
7. Jika koordinat dari berita tidak tersedia, lakukan geocoding atau manual pinpoint berdasarkan alamat.
8. Jika lokasi tidak jelas, tandai sebagai perlu validasi.

Contoh benar:

```text
POINT(105.xxxx -5.xxxx)
```

Contoh salah:

```text
POINT(-5.xxxx 105.xxxx)
```

Konversi ke `geom` dilakukan dengan pola konseptual:

```text
ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)
```

## L. Format File Dataset

Format yang direkomendasikan sebelum data dimasukkan ke database:

| Format | Penggunaan |
|---|---|
| CSV | Data titik rawan banjir, kejadian banjir, titik evakuasi, pos alat berat, jenis alat, unit alat |
| GeoJSON | Data spasial siap peta atau batas wilayah |
| SHP | Batas wilayah jika sumber resmi tersedia dalam shapefile |
| SQL seed | Digunakan setelah struktur dataset final |

Rekomendasi final:

1. Data titik rawan banjir, kejadian banjir, titik evakuasi, pos alat berat, jenis alat berat, dan unit alat berat disiapkan dalam CSV.
2. Data batas kecamatan jika ada disiapkan dalam GeoJSON atau SHP.
3. Laravel Seeder dibuat setelah dataset final dan sudah divalidasi.
4. Jangan membuat seeder sebelum nama kolom dan isi dataset stabil.

## M. Template CSV

### 1. `flood_risk_points.csv`

```csv
name,address,district,subdistrict,risk_level,description,source_type,source_reference,data_status,is_verified,longitude,latitude,notes
```

Fungsi kolom: menyimpan titik rawan banjir, tingkat risiko, sumber, status data, dan koordinat.

### 2. `flood_events.csv`

```csv
name,address,district,subdistrict,severity_level,water_depth_cm,status,description,occurred_at,reported_at,source_type,source_reference,data_status,is_verified,longitude,latitude,notes
```

Fungsi kolom: menyimpan kejadian banjir aktif, historis, atau simulasi.

### 3. `evacuation_points.csv`

```csv
name,type,address,district,subdistrict,capacity,facilities,contact_person,contact_phone,status,description,source_type,source_reference,data_status,is_verified,longitude,latitude,notes
```

Fungsi kolom: menyimpan titik evakuasi dan informasi kapasitas/fasilitas.

### 4. `heavy_equipment_posts.csv`

```csv
name,address,district,subdistrict,contact_person,contact_phone,status,description,source_type,source_reference,data_status,is_verified,longitude,latitude,notes
```

Fungsi kolom: menyimpan pos alat berat dan koordinat pos.

### 5. `equipment_types.csv`

```csv
name,description
```

Fungsi kolom: menyimpan master jenis alat berat.

### 6. `heavy_equipment_units.csv`

```csv
post_name,equipment_type,quantity,available_quantity,status,notes
```

Fungsi kolom: menyimpan jumlah alat berat per pos.

## N. Strategi Seed Data untuk Demo

Jumlah minimal data demo:

| Dataset | Jumlah Disarankan |
|---|---:|
| Titik rawan banjir | 10 sampai 20 titik |
| Kejadian banjir | 5 sampai 10 titik |
| Titik evakuasi | 8 sampai 15 titik |
| Pos alat berat | 5 sampai 8 pos |
| Jenis alat berat | 5 sampai 7 jenis |
| Unit alat berat | Menyesuaikan pos |

Jumlah ini cukup untuk MVP karena:

1. Peta terlihat hidup.
2. Query titik terdekat dapat diuji di beberapa wilayah.
3. Demo tidak terlalu kosong.
4. Input manual masih realistis.
5. Dosen dapat melihat nilai SIG dari data spasial dan analisis jarak.

## O. Contoh Skenario Data Demo

### 1. Banjir di Teluk Betung

Input:

- Kejadian banjir simulasi di wilayah Teluk Betung.
- Severity `tinggi`.

Layer yang terlihat:

- Kejadian banjir aktif.
- Titik evakuasi sekitar Teluk Betung.
- Pos alat berat Teluk Betung/Panjang.
- Rute evakuasi.

Hasil yang diharapkan:

- Sistem merekomendasikan titik evakuasi terdekat di sekitar Teluk Betung.
- Sistem merekomendasikan pos alat berat terdekat, bukan pos yang jauh.

Nilai SIG:

- Menunjukkan nearest facility analysis berbasis jarak spasial.

### 2. Banjir di Rajabasa

Input:

- Kejadian banjir simulasi di Rajabasa.
- Severity `sedang` atau `tinggi`.

Layer yang terlihat:

- Kejadian banjir aktif.
- Titik evakuasi Rajabasa.
- Pos alat berat Rajabasa/Sukarame/Kemiling.

Hasil yang diharapkan:

- Sistem tidak memilih pos Teluk Betung jika ada pos Rajabasa yang lebih dekat.
- Jarak rekomendasi terlihat secara kuantitatif.

Nilai SIG:

- Menunjukkan keputusan berbasis lokasi, bukan pilihan manual.

### 3. Banjir di Panjang

Input:

- Kejadian banjir simulasi di Panjang.
- Severity `tinggi`.

Layer yang terlihat:

- Kejadian banjir aktif.
- Titik evakuasi sekitar Panjang.
- Pos alat berat Panjang.
- Rute evakuasi.

Hasil yang diharapkan:

- Sistem merekomendasikan evakuasi dan alat berat di sekitar Panjang.
- Sistem menampilkan rute evakuasi sederhana.

Nilai SIG:

- Menunjukkan integrasi PostGIS untuk analisis jarak dan API routing untuk visualisasi rute.

## P. Sumber Data Awal yang Perlu Dicari

### 1. Sumber Kejadian Banjir

Strategi pencarian:

- Berita lokal tentang banjir Bandar Lampung.
- Laporan BPBD atau pemerintah daerah.
- Artikel jurnal atau penelitian banjir.
- Dokumentasi kejadian banjir dari media.

### 2. Sumber Titik Rawan Banjir

Strategi pencarian:

- Dokumen pemerintah terkait kawasan rawan banjir.
- Kajian akademik atau skripsi/jurnal.
- Portal kebencanaan nasional/daerah.
- Berita yang menyebut lokasi banjir berulang.

### 3. Sumber Fasilitas Publik untuk Evakuasi

Strategi pencarian:

- Peta fasilitas publik.
- OpenStreetMap.
- Website sekolah, masjid besar, puskesmas, kantor kecamatan.
- Observasi manual melalui peta.

### 4. Sumber Batas Wilayah

Strategi pencarian:

- Portal data pemerintah.
- Portal geospasial.
- OpenStreetMap.
- File GeoJSON/SHP administrasi jika tersedia.

### 5. Sumber Peta Dasar

Rekomendasi:

- OpenStreetMap sebagai basemap Leaflet.

Catatan: URL spesifik sebaiknya ditulis setelah sumber benar-benar ditemukan dan diverifikasi.

## Q. Kriteria Dataset Siap Implementasi

Dataset siap digunakan jika:

- [ ] Setiap baris memiliki nama lokasi.
- [ ] Setiap baris spasial memiliki longitude dan latitude valid.
- [ ] Setiap baris memiliki `data_status`.
- [ ] Setiap baris memiliki `source_type`.
- [ ] Data nyata memiliki `source_reference`.
- [ ] Data dummy ditandai jelas.
- [ ] Titik berada di Bandar Lampung atau area relevan.
- [ ] Tidak ada koordinat latitude dan longitude yang tertukar.
- [ ] Tidak ada quantity negatif.
- [ ] `available_quantity` tidak melebihi `quantity`.
- [ ] Status sesuai enum yang sudah ditentukan.
- [ ] Data dapat dikonversi menjadi `geom geometry(Point, 4326)`.
- [ ] Data yang belum valid diberi catatan `perlu validasi`.

## R. Hubungan Dataset dengan Database

Mapping dataset ke tabel database:

| File Dataset | Tabel Database |
|---|---|
| `flood_risk_points.csv` | `flood_risk_points` |
| `flood_events.csv` | `flood_events` |
| `evacuation_points.csv` | `evacuation_points` |
| `heavy_equipment_posts.csv` | `heavy_equipment_posts` |
| `equipment_types.csv` | `equipment_types` |
| `heavy_equipment_units.csv` | `heavy_equipment_units` |
| `districts.geojson` atau `districts.shp` | `districts` |
| `data_sources.csv` | `data_sources` |

Kolom `longitude` dan `latitude` akan dikonversi menjadi `geom` dengan pola:

```text
ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)
```

Kolom `longitude` dan `latitude` dapat tetap disimpan sementara di file CSV untuk memudahkan validasi, tetapi analisis spasial utama di database menggunakan `geom`.

## S. Hubungan Dataset dengan UI dan API

Penggunaan dataset pada UI dan API:

1. Data titik rawan banjir digunakan untuk layer marker risiko banjir.
2. Data kejadian banjir digunakan untuk layer marker banjir aktif/historis.
3. Data evakuasi digunakan untuk layer marker evakuasi dan rekomendasi titik terdekat.
4. Data pos alat berat digunakan untuk layer marker pos alat berat.
5. Data unit alat berat digunakan untuk popup informasi ketersediaan alat.
6. Data koordinat digunakan untuk routing OSRM/OpenRouteService.
7. Data status digunakan untuk filter peta.
8. Data severity digunakan untuk warna marker banjir.
9. Data district/subdistrict digunakan untuk filter wilayah.
10. Data source digunakan untuk keterangan validitas pada detail data.

Endpoint GeoJSON akan mengambil data dari database dan mengirimkannya ke Leaflet sebagai `FeatureCollection`.

## T. Risiko Dataset dan Solusi

| Risiko | Dampak | Solusi |
|---|---|---|
| Data banjir tidak memiliki koordinat | Tidak bisa ditampilkan akurat di peta | Lakukan geocoding/manual pinpoint berdasarkan alamat |
| Sumber berita tidak konsisten | Data sulit diverifikasi | Simpan source_reference dan tandai is_verified sesuai kondisi |
| Alamat tidak spesifik | Titik bisa meleset | Beri catatan perlu validasi dan gunakan lokasi perkiraan hanya untuk simulasi |
| Data evakuasi tidak memiliki kapasitas resmi | Informasi kurang akurat | Gunakan estimasi akademik dan tandai sebagai simulasi/dummy |
| Data alat berat dummy tidak realistis | Demo terlihat tidak kredibel | Letakkan pos di wilayah strategis dan batasi jumlah unit |
| Koordinat tertukar | Titik muncul di lokasi salah | Validasi semua titik di peta sebelum import |
| Data terlalu sedikit | Demo kurang menunjukkan analisis spasial | Siapkan jumlah minimal sesuai strategi seed |
| Data terlalu banyak | Input manual berat | Batasi MVP pada 10-20 titik rawan dan 5-10 kejadian |
| Data nyata bercampur dummy | Laporan akademik tidak transparan | Wajib gunakan `data_status`, `source_type`, dan `is_verified` |
| Status tidak konsisten | Filter API menjadi bermasalah | Gunakan enum/status yang sudah disepakati |

## U. Keputusan Final Dataset

### 1. Dataset Wajib

Dataset wajib untuk MVP:

1. `flood_risk_points.csv`
2. `flood_events.csv`
3. `evacuation_points.csv`
4. `heavy_equipment_posts.csv`
5. `equipment_types.csv`
6. `heavy_equipment_units.csv`

### 2. Dataset Opsional

Dataset opsional:

1. `districts.geojson` atau `districts.shp`
2. `route_histories`
3. `equipment_dispatch_logs`
4. `data_sources.csv`

### 3. Data yang Boleh Dummy

Data yang boleh dummy:

1. Pos alat berat.
2. Unit alat berat.
3. Jumlah dan status alat berat.
4. Kapasitas titik evakuasi jika tidak ada data resmi.
5. Kejadian banjir skenario demo.

### 4. Data yang Sebaiknya Nyata

Data yang sebaiknya nyata:

1. Titik rawan banjir.
2. Kejadian banjir historis.
3. Fasilitas publik untuk evakuasi.
4. Batas kecamatan/kelurahan jika digunakan.

### 5. Format Dataset yang Direkomendasikan

Format final:

1. CSV untuk data titik dan master data.
2. GeoJSON/SHP untuk batas wilayah.
3. Seeder Laravel dibuat setelah dataset final.
4. SQL seed tidak dibuat sebelum validasi dataset selesai.

### 6. Jumlah Data Minimal untuk Demo

Jumlah minimal:

1. Titik rawan banjir: 10 titik.
2. Kejadian banjir: 5 titik.
3. Titik evakuasi: 8 titik.
4. Pos alat berat: 5 pos.
5. Jenis alat berat: 5 jenis.
6. Unit alat berat: minimal 1-3 jenis per pos.

### 7. Dokumen Berikutnya

Dokumen berikutnya yang paling tepat dibuat adalah:

```text
API.md
```

Alasannya, setelah requirements, database, dan dataset jelas, rancangan endpoint API dapat dibuat lebih presisi untuk mendukung Leaflet, GeoJSON, query PostGIS, rekomendasi titik terdekat, dan routing OSRM/OpenRouteService.
