# DATASET SUMMARY SIGAP BANJIR

## 1. Ringkasan Jumlah Record Final

| Dataset | Jenis Geometri | Jumlah Record | Fungsi dalam Sistem | Status Data |
|---|---:|---:|---|---|
| Kejadian Banjir | Point | 12 | Menunjukkan lokasi kejadian dan severity | Nyata berbasis berita |
| Titik Rawan Banjir | Point | 12 | Menunjukkan potensi/rawan banjir | Nyata berbasis jurnal |
| Titik Evakuasi | Point | 10 | Tujuan rekomendasi evakuasi | Simulasi pengembangan |
| Pos Alat Berat | Point | 6 | Lokasi resource pemulihan | Dummy realistis |
| Unit Alat Berat | Non-spasial/relasional | 15 | Ketersediaan resource per pos | Dummy realistis |
| Jenis Alat | Master data | 6 | Kategori alat berat | Dummy realistis |

Total data spasial utama: **40 record**.

Status data spasial final setelah revisi sumber berita dan jurnal:

| Status | Jumlah |
|---|---:|
| Nyata | 24 |
| Simulasi | 10 |
| Dummy | 6 |
| Perlu validasi operasional | 16 |

`flood_events` sudah berisi data nyata berbasis pemberitaan media terkait banjir Bandar Lampung 14 April 2026. `flood_risk_points` sudah berisi data nyata berbasis literatur akademik dari jurnal Agustri & Asbi (2020). Titik evakuasi tetap kandidat simulasi untuk kebutuhan rekomendasi, sedangkan pos dan unit alat berat tetap dummy realistis untuk demo respons.

## 2. Tujuan Setiap Dataset

| Dataset | Tujuan |
|---|---|
| `flood_events` | Menampilkan kejadian banjir nyata berbasis berita dan menjadi titik asal analisis nearest resource |
| `flood_risk_points` | Menampilkan titik representatif risiko banjir berbasis jurnal/literatur |
| `evacuation_points` | Menjadi kandidat tujuan evakuasi dan rekomendasi titik terdekat |
| `heavy_equipment_posts` | Menjadi kandidat resource alat berat terdekat dalam skenario respons |
| `heavy_equipment_units` | Menjelaskan ketersediaan alat per pos |
| `equipment_types` | Menstandarkan kategori alat berat |

## 3. Atribut Spasial

Semua dataset titik menyimpan lokasi utama dalam kolom PostGIS:

```text
geom geometry(Point, 4326)
```

CSV menampilkan `longitude` dan `latitude` agar mudah dibaca dan divalidasi. Nilai tersebut diekspor dari `geom` menggunakan `ST_X(geom)` dan `ST_Y(geom)`, bukan diketik ulang. Analisis jarak pada aplikasi tetap menggunakan `ST_Distance(geom::geography, geom::geography)`.

## 4. Contoh Record Aktual Final

### Kejadian Banjir

| ID | Nama | Kecamatan | Severity | Status | Longitude | Latitude | Data | Sumber |
|---:|---|---|---|---|---:|---:|---|---|
| 18 | Banjir Depan RSUD Abdul Moeloek | Enggal | tinggi | surut | 105.2601 | -5.4160 | nyata | berita |
| 19 | Banjir Jalan Dokter Sutomo Penengahan | Kedaton | kritis | surut | 105.2638 | -5.3948 | nyata | berita |
| 20 | Genangan Jalan Pangeran Antasari - Transmart | Way Halim | tinggi | surut | 105.2878 | -5.3987 | nyata | berita |

### Titik Rawan Banjir

| ID | Nama | Kecamatan | Risk Level | Longitude | Latitude | Data | Sumber |
|---:|---|---|---|---:|---:|---|---|
| 26 | Risiko Banjir Way Kandis | Tanjung Senang | tinggi | 105.2920 | -5.3608 | nyata | jurnal |
| 27 | Risiko Banjir Sukabumi | Sukabumi | tinggi | 105.3110 | -5.4105 | nyata | jurnal |
| 28 | Risiko Banjir Bumi Kedamaian | Kedamaian | tinggi | 105.2860 | -5.3940 | nyata | jurnal |

### Titik Evakuasi

| ID | Nama | Tipe | Kecamatan | Kapasitas | Longitude | Latitude | Data |
|---:|---|---|---|---:|---:|---:|---|
| 11 | Masjid Al-Furqon Lungsir | masjid | Tanjung Karang Pusat | 300 | 105.2615707 | -5.4291549 | simulasi |
| 12 | GOR Saburai | aula | Enggal | 600 | 105.2598000 | -5.4218000 | simulasi |
| 13 | Kantor Kecamatan Teluk Betung Selatan | gedung_pemerintah | Teluk Betung Selatan | 180 | 105.2591000 | -5.4485000 | simulasi |

### Pos Alat Berat

| ID | Nama | Kecamatan | Status | Longitude | Latitude | Data |
|---:|---|---|---|---:|---:|---|
| 7 | Pos Alat Berat Panjang | Panjang | aktif | 105.3262000 | -5.4669000 | dummy |
| 8 | Pos Alat Berat Teluk Betung | Teluk Betung Selatan | aktif | 105.2590000 | -5.4442000 | dummy |
| 9 | Pos Alat Berat Rajabasa | Rajabasa | aktif | 105.2297280 | -5.3627526 | dummy |

## 5. Sumber dan Presisi Koordinat

Kejadian banjir adalah data nyata berbasis berita, tetapi koordinatnya adalah hasil geocoding/plotting lokasi jalan, landmark, kecamatan, atau area yang disebut dalam berita. Koordinat bukan hasil GPS lapangan operasional dan masih dapat disempurnakan melalui survei.

Titik rawan banjir adalah data nyata berbasis literatur akademik. Titik merupakan representasi/centroid area kelurahan dari kajian risiko banjir, bukan batas polygon risiko resmi.

Validasi Nominatim/OSM dilakukan terhadap 24 target data banjir dan risiko. Sebanyak 14 query memperoleh kandidat lokasi OSM, sedangkan 10 lokasi dipertahankan sebagai plotting manual/representatif karena Nominatim tidak menemukan lokasi spesifik.

## 6. Dampak pada Layer Intensitas Kecamatan

Agregasi `flood_events` per kecamatan kini membaca 12 kejadian nyata berbasis berita. Distribusi saat ini adalah Kedaton 2 kejadian, Way Halim 2 kejadian, dan masing-masing 1 kejadian pada Bumi Waras, Enggal, Kedamaian, Panjang, Rajabasa, Sukarame, Tanjung Karang Barat, serta Teluk Betung Selatan. Dengan klasifikasi tematik saat ini, kecamatan tersebut berada pada kategori rendah karena jumlahnya masih kurang dari 5 kejadian.

## 7. Nilai Akademik PostGIS

Dataset ini menunjukkan nilai akademik GIS karena titik disimpan sebagai geometry PostGIS, GeoJSON API membaca geometry dari database, analisis terdekat dilakukan dengan `ST_Distance`, rekomendasi tidak dihitung manual di frontend, dan rute OSRM memakai koordinat dari database.
