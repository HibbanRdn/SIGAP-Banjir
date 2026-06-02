# DATASET SUMMARY SIGAP BANJIR

## 1. Ringkasan Jumlah Record Final

| Dataset | Jenis Geometri | Jumlah Record | Fungsi dalam Sistem | Status Data |
|---|---:|---:|---|---|
| Kejadian Banjir | Point | 8 | Menunjukkan lokasi kejadian dan severity | Simulasi |
| Titik Rawan Banjir | Point | 12 | Menunjukkan potensi/rawan banjir | Simulasi |
| Titik Evakuasi | Point | 10 | Tujuan rekomendasi evakuasi | Simulasi |
| Pos Alat Berat | Point | 6 | Lokasi resource pemulihan | Dummy realistis |
| Unit Alat Berat | Non-spasial/relasional | 15 | Ketersediaan resource per pos | Dummy realistis |
| Jenis Alat | Master data | 6 | Kategori alat berat | Dummy realistis |

Total data spasial utama: **36 record**.

Status data spasial final setelah audit dan koreksi inkonsistensi:

| Status | Jumlah |
|---|---:|
| Simulasi | 30 |
| Dummy | 6 |
| Nyata | 0 |
| Perlu validasi operasional | 36 |

Dataset yang digunakan merupakan dataset awal pengembangan berbasis lokasi nyata di wilayah Kota Bandar Lampung. Koordinat dan nama area diaudit menggunakan OpenStreetMap/Nominatim, sedangkan status kejadian banjir, fungsi titik evakuasi, dan pos alat berat tetap merupakan data simulasi/dummy untuk kebutuhan pengujian aplikasi.

## 2. Tujuan Setiap Dataset

| Dataset | Tujuan |
|---|---|
| `flood_events` | Menampilkan kejadian banjir simulasi dan menjadi titik asal analisis nearest resource |
| `flood_risk_points` | Menampilkan layer titik rawan banjir untuk konteks mitigasi berbasis skenario |
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

| ID | Nama | Kecamatan | Severity | Status | Longitude | Latitude | Data |
|---:|---|---|---|---|---:|---:|---|
| 9 | Banjir Teluk Betung Selatan | Teluk Betung Selatan | kritis | aktif | 105.2607000 | -5.4478000 | simulasi |
| 10 | Genangan Way Halim | Way Halim | tinggi | aktif | 105.2746909 | -5.3823404 | simulasi |
| 11 | Banjir Sukarame | Sukarame | tinggi | ditangani | 105.2946540 | -5.3974767 | simulasi |

### Titik Rawan Banjir

| ID | Nama | Kecamatan | Risk Level | Longitude | Latitude | Data |
|---:|---|---|---|---:|---:|---|
| 13 | Rawan Banjir Way Halim | Way Halim | tinggi | 105.2746909 | -5.3823404 | simulasi |
| 14 | Rawan Banjir Teluk Betung Selatan | Teluk Betung Selatan | tinggi | 105.2608000 | -5.4469000 | simulasi |
| 15 | Rawan Banjir Panjang Utara | Panjang | tinggi | 105.3229645 | -5.4721335 | simulasi |

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

## 5. Hasil Audit Spasial

Audit `spatial_validation_audit.csv` mencatat 36 record spasial final. Seluruh record berada pada wilayah Kota Bandar Lampung menurut reverse geocoding OpenStreetMap/Nominatim tingkat area. Koreksi dilakukan pada record yang sebelumnya terbaca bergeser area atau memiliki label status sumber yang tidak konsisten.

## 6. Nilai Akademik PostGIS

Dataset ini menunjukkan nilai akademik GIS karena titik disimpan sebagai geometry PostGIS, GeoJSON API membaca geometry dari database, analisis terdekat dilakukan dengan `ST_Distance`, rekomendasi tidak dihitung manual di frontend, dan rute OSRM memakai koordinat dari database.
