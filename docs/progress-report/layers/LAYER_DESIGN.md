# LAYER DESIGN SIGAP BANJIR

## 1. Ringkasan Layer

| Layer | Sumber Data | Geometry | Simbol/Warna | Fungsi |
|---|---|---|---|---|
| Kejadian Banjir | `flood_events` | Point | Pin coral/red dengan ikon gelombang/kejadian | Pemilihan event, analisis resource, routing |
| Titik Rawan Banjir | `flood_risk_points` | Point | Pin orange/amber dengan ikon warning | Konteks mitigasi dan potensi rawan |
| Titik Evakuasi | `evacuation_points` | Point | Pin teal/green dengan ikon shelter/lokasi aman | Kandidat evakuasi terdekat |
| Pos Alat Berat | `heavy_equipment_posts` | Point | Pin gold/amber dengan ikon alat/resource | Kandidat resource alat berat |
| Rute Evakuasi Referensi | Routing API OSRM | LineString | Civic blue, outline putih, dashed | Jalur referensi ke titik evakuasi |

## 2. Layer Kejadian Banjir

| Aspek | Desain |
|---|---|
| Sumber tabel | `flood_events` |
| Endpoint | `/api/v1/geojson/flood-events` |
| Geometry | Point |
| Warna | Red/coral |
| Ikon | Gelombang/kejadian banjir |
| Atribut popup | Nama, status, severity, kecamatan, tinggi air, status data |
| Fungsi utama | Event dipilih untuk mencari resource terdekat dan menampilkan rute |

Layer ini menjadi pusat interaksi. Saat pengguna memilih satu kejadian banjir, panel kiri menampilkan detail singkat, tombol `Cari Evakuasi`, `Cari Alat Berat`, `Cari Resource`, dan `Tampilkan Rute Evakuasi`.

## 3. Layer Titik Rawan Banjir

| Aspek | Desain |
|---|---|
| Sumber tabel | `flood_risk_points` |
| Endpoint | `/api/v1/geojson/flood-risks` |
| Geometry | Point |
| Warna | Orange/amber |
| Ikon | Warning/segitiga |
| Atribut popup | Nama, risk level, kecamatan, status data |
| Fungsi utama | Menunjukkan konteks kawasan rawan banjir |

Layer ini membantu menjelaskan mitigasi dan sebaran lokasi berisiko, bukan hanya kejadian aktif.

## 4. Layer Titik Evakuasi

| Aspek | Desain |
|---|---|
| Sumber tabel | `evacuation_points` |
| Endpoint | `/api/v1/geojson/evacuation-points` |
| Geometry | Point |
| Warna | Teal/green |
| Ikon | Shelter/rumah/lokasi aman |
| Atribut popup | Nama, jenis, kapasitas, status, kecamatan |
| Fungsi utama | Kandidat titik evakuasi dan marker rekomendasi |

Marker titik evakuasi yang direkomendasikan diberi state visual berbeda agar mudah dibedakan dari marker biasa.

## 5. Layer Pos Alat Berat

| Aspek | Desain |
|---|---|
| Sumber tabel | `heavy_equipment_posts` |
| Endpoint | `/api/v1/geojson/heavy-equipment-posts` |
| Geometry | Point |
| Warna | Gold/amber |
| Ikon | Alat/resource |
| Atribut popup | Nama pos, status, kecamatan, alat tersedia |
| Fungsi utama | Kandidat resource pemulihan dan respons |

Popup pos alat berat menampilkan ringkasan unit tersedia dari relasi `heavy_equipment_units`.

## 6. Layer Rute Evakuasi Referensi

| Aspek | Desain |
|---|---|
| Sumber | Response Routing API OSRM |
| Endpoint | `/api/v1/routing/flood-events/{id}/to-nearest-evacuation` dan `/api/v1/routing/flood-events/{id}/to-evacuation/{evacuation_id}` |
| Geometry | LineString |
| Warna utama | Civic blue `#0058be` |
| Outline | Putih |
| Gaya garis | Dashed route style |
| Fungsi | Menunjukkan rute referensi dari kejadian menuju titik evakuasi |

Rute bersifat referensi akademik. Rute belum mempertimbangkan jalan tertutup, kondisi banjir aktual, lalu lintas, atau keputusan resmi petugas.

## 7. Basemap

Basemap yang dipakai sesuai implementasi aktual:

| Mode | Provider | Catatan |
|---|---|---|
| Standar | OpenStreetMap Standard | Default |
| Humanitarian | Humanitarian OpenStreetMap | Alternatif untuk konteks wilayah |
| Satelit | Esri World Imagery | Mode citra satelit tanpa token |

Jika basemap gagal dimuat, aplikasi menampilkan alert dan kembali ke mode standar.

## 8. Layer Toggle, Filter, dan Legend

Public Map Explorer memiliki:

1. Toggle untuk kejadian banjir.
2. Toggle untuk titik rawan banjir.
3. Toggle untuk titik evakuasi.
4. Toggle untuk pos alat berat.
5. Filter kejadian berdasarkan status, severity, dan kecamatan.
6. Search kejadian berdasarkan nama, alamat, kecamatan, kelurahan, status, atau severity.
7. Legend kategori marker dan rute referensi.
8. Counter jumlah data per layer.

## 9. Alur Interaksi Pengguna

1. Pengguna membuka `/peta`.
2. Leaflet memuat basemap dan layer GeoJSON.
3. Pengguna memfilter atau memilih kejadian banjir.
4. Marker kejadian aktif diberi state selected.
5. Pengguna meminta rekomendasi resource.
6. Backend menghitung resource terdekat menggunakan PostGIS.
7. Marker rekomendasi diberi state visual.
8. Pengguna meminta rute evakuasi.
9. Backend memanggil OSRM dan mengembalikan LineString.
10. Leaflet menggambar rute referensi di peta.

## 10. Keterkaitan PostGIS, GeoJSON, Leaflet, dan OSRM

```text
PostgreSQL + PostGIS
  -> Laravel GeoJSON API
  -> Leaflet layer dan marker
  -> Spatial Analysis API untuk rekomendasi terdekat
  -> Routing API OSRM untuk LineString rute referensi
```

Frontend tidak menghitung jarak spasial dan tidak memanggil OSRM langsung. Analisis utama tetap berada di backend agar sesuai prinsip SIG dan menjaga API key/provider tidak terekspos.
