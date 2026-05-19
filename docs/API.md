# API.md

# Rancangan API Sistem Informasi Geografis Mitigasi dan Respons Banjir Kota Bandar Lampung

## A. Ringkasan API

API digunakan sebagai penghubung antara frontend peta, backend Laravel, database PostgreSQL/PostGIS, dan layanan routing eksternal.

Fungsi utama API:

1. Menyediakan data peta ke Leaflet.
2. Menyediakan response GeoJSON untuk layer spasial.
3. Menjalankan CRUD admin.
4. Menjalankan query PostGIS untuk analisis titik terdekat.
5. Menyediakan rekomendasi titik evakuasi terdekat.
6. Menyediakan rekomendasi pos alat berat terdekat.
7. Mengambil rute evakuasi dari OSRM/OpenRouteService.
8. Menyediakan data ringkasan untuk dashboard admin.

API ini dirancang untuk MVP akademik SIG, sehingga fokusnya adalah sederhana, konsisten, dan mendukung analisis spasial.

## B. Prinsip Desain API

Prinsip desain:

1. Endpoint harus konsisten dan mudah dipahami.
2. Data spasial untuk Leaflet dikirim dalam format GeoJSON.
3. Data CRUD biasa dikirim dalam format JSON biasa.
4. Query spasial diproses di backend menggunakan PostGIS.
5. Frontend tidak boleh langsung mengakses database.
6. Endpoint publik dan endpoint admin harus dipisahkan.
7. Response JSON harus memiliki pola `success`, `message`, dan `data`.
8. Error response harus konsisten.
9. API key OpenRouteService tidak boleh diekspos ke frontend.
10. Routing eksternal sebaiknya dipanggil dari backend.
11. Data nyata, dummy, dan simulasi tetap dikirim dengan metadata sumber.
12. API tidak dibuat terlalu enterprise agar tetap realistis untuk project kuliah.

## C. Base URL dan Versi API

Base URL:

```text
/api/v1
```

Contoh endpoint:

```text
/api/v1/geojson/flood-events
/api/v1/admin/flood-events
/api/v1/analysis/flood-events/{id}/nearest-evacuation
```

Alasan menggunakan versi API:

1. Struktur endpoint lebih rapi.
2. Mudah dikembangkan jika nanti ada perubahan format.
3. Membantu dokumentasi akademik terlihat lebih profesional.
4. Tidak menambah kompleksitas berarti pada MVP.

## D. Kategori Endpoint

| Kategori | Fungsi |
|---|---|
| Public map endpoints | Data ringkas dan detail yang bisa diakses tanpa login |
| GeoJSON endpoints | Data spasial untuk layer Leaflet |
| Admin authentication endpoints | Login, logout, dan data admin |
| Admin CRUD endpoints | Manajemen data utama |
| Spatial analysis endpoints | Rekomendasi evakuasi dan alat berat terdekat |
| Routing endpoints | Rute evakuasi menggunakan OSRM/OpenRouteService |
| Master data endpoints | Daftar enum/status untuk form |
| Optional reporting/history endpoints | Riwayat rute dan dispatch jika fitur ditambahkan |

## E. Standar Response JSON

Response sukses:

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {}
}
```

Response error:

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {}
}
```

Gunakan `data` object untuk detail satu data.  
Gunakan `data` array untuk daftar data.  
Gunakan `meta` jika response memiliki pagination, filter aktif, atau informasi tambahan.

Contoh dengan meta:

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [],
  "meta": {
    "total": 10,
    "limit": 10
  }
}
```

## F. Standar Response GeoJSON

Endpoint GeoJSON menggunakan format:

```json
{
  "type": "FeatureCollection",
  "features": []
}
```

Setiap feature berisi:

```json
{
  "type": "Feature",
  "geometry": {},
  "properties": {}
}
```

Ketentuan:

1. `geometry` berasal dari `ST_AsGeoJSON(geom)`.
2. `properties` berisi atribut non-spasial.
3. Koordinat wajib memakai urutan `[longitude, latitude]`.
4. Endpoint GeoJSON tidak perlu dibungkus `success/message` agar langsung kompatibel dengan Leaflet.
5. Jika perlu metadata, gunakan properti tambahan di luar `features`, tetapi untuk MVP sebaiknya tetap sederhana.

## G. Public Map Endpoints

Endpoint publik tidak membutuhkan login.

| Method | Path | Fungsi | Query Parameter | Status |
|---|---|---|---|---|
| GET | `/api/v1/map/summary` | Ringkasan jumlah data peta | `district`, `status` | 200 |
| GET | `/api/v1/map/layers` | Daftar layer yang tersedia | - | 200 |
| GET | `/api/v1/flood-events/{id}` | Detail kejadian banjir | - | 200, 404 |
| GET | `/api/v1/flood-risks/{id}` | Detail titik rawan banjir | - | 200, 404 |
| GET | `/api/v1/evacuation-points/{id}` | Detail titik evakuasi | - | 200, 404 |
| GET | `/api/v1/heavy-equipment-posts/{id}` | Detail pos alat berat | - | 200, 404 |

Contoh response `/api/v1/map/summary`:

```json
{
  "success": true,
  "message": "Ringkasan peta berhasil diambil",
  "data": {
    "flood_risk_points": 15,
    "active_flood_events": 4,
    "evacuation_points": 10,
    "heavy_equipment_posts": 6
  }
}
```

Catatan implementasi:

1. Endpoint publik hanya mengirim data yang aman ditampilkan.
2. Detail pos alat berat boleh menampilkan jumlah unit tersedia, tetapi tidak perlu menampilkan informasi sensitif.
3. Data source tetap boleh ditampilkan untuk transparansi akademik.

## H. GeoJSON Endpoints

Endpoint GeoJSON digunakan langsung oleh Leaflet.

| Method | Path | Fungsi | Query Parameter |
|---|---|---|---|
| GET | `/api/v1/geojson/flood-risks` | Layer titik rawan banjir | `risk_level`, `district`, `subdistrict`, `data_status`, `source_type`, `limit` |
| GET | `/api/v1/geojson/flood-events` | Layer kejadian banjir | `status`, `severity_level`, `district`, `subdistrict`, `data_status`, `source_type`, `limit` |
| GET | `/api/v1/geojson/evacuation-points` | Layer titik evakuasi | `status`, `type`, `district`, `subdistrict`, `data_status`, `source_type`, `limit` |
| GET | `/api/v1/geojson/heavy-equipment-posts` | Layer pos alat berat | `status`, `district`, `subdistrict`, `equipment_type`, `data_status`, `source_type`, `limit` |
| GET | `/api/v1/geojson/districts` | Layer batas kecamatan | `name` |
| GET | `/api/v1/geojson/flood-events/{id}/impact-radius` | Radius terdampak opsional | `radius`, `severity_based` |

Contoh filter:

```text
/api/v1/geojson/flood-events?status=aktif&severity_level=tinggi
/api/v1/geojson/evacuation-points?status=aktif&district=Panjang
```

Properti `flood-events`:

```json
{
  "id": 1,
  "name": "Simulasi Banjir Teluk Betung",
  "severity_level": "tinggi",
  "status": "aktif",
  "water_depth_cm": 60,
  "district": "Teluk Betung",
  "data_status": "simulasi"
}
```

Status code:

| Status | Kondisi |
|---|---|
| 200 | Data berhasil dikirim sebagai GeoJSON |
| 400 | Query parameter tidak valid |
| 500 | Error server atau query spasial |

## I. Admin Authentication Endpoints

Rekomendasi final auth untuk MVP Laravel:

```text
Laravel Sanctum dengan session/cookie auth
```

Alasan:

1. Cocok untuk Laravel.
2. Aman untuk admin panel berbasis web.
3. Tidak terlalu kompleks.
4. Bisa digunakan oleh request JavaScript dari halaman admin.

Endpoint:

| Method | Path | Fungsi | Auth | Status |
|---|---|---|---|---|
| POST | `/api/v1/admin/login` | Login admin | Public | 200, 401, 422 |
| POST | `/api/v1/admin/logout` | Logout admin | Admin | 200, 401 |
| GET | `/api/v1/admin/me` | Data admin aktif | Admin | 200, 401 |

Request login:

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Response login:

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin SIG",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
}
```

Validasi:

1. `email` wajib dan format email.
2. `password` wajib.
3. User harus memiliki role admin.

## J. Admin CRUD Endpoint: Flood Risk Points

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/flood-risks` | Daftar titik rawan |
| POST | `/api/v1/admin/flood-risks` | Tambah titik rawan |
| GET | `/api/v1/admin/flood-risks/{id}` | Detail titik rawan |
| PUT/PATCH | `/api/v1/admin/flood-risks/{id}` | Update titik rawan |
| DELETE | `/api/v1/admin/flood-risks/{id}` | Hapus titik rawan |

Request create/update:

```json
{
  "name": "Rawan Banjir Teluk Betung",
  "address": "Deskripsi lokasi",
  "district": "Teluk Betung",
  "subdistrict": "Perlu validasi",
  "risk_level": "tinggi",
  "description": "Sering tergenang saat hujan deras",
  "source_type": "observasi",
  "source_reference": "Catatan observasi",
  "data_status": "simulasi",
  "is_verified": false,
  "longitude": 105.2501,
  "latitude": -5.4401
}
```

Validasi:

1. `name` wajib.
2. `risk_level` wajib: `rendah`, `sedang`, `tinggi`.
3. `longitude` dan `latitude` wajib numeric.
4. Koordinat harus berada di sekitar Bandar Lampung.
5. `source_reference` wajib jika `data_status = nyata`.

Catatan PostGIS:

Backend mengubah `longitude` dan `latitude` menjadi:

```text
ST_SetSRID(ST_MakePoint(longitude, latitude), 4326)
```

## K. Admin CRUD Endpoint: Flood Events

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/flood-events` | Daftar kejadian banjir |
| POST | `/api/v1/admin/flood-events` | Tambah kejadian banjir |
| GET | `/api/v1/admin/flood-events/{id}` | Detail kejadian banjir |
| PUT/PATCH | `/api/v1/admin/flood-events/{id}` | Update kejadian banjir |
| DELETE | `/api/v1/admin/flood-events/{id}` | Hapus kejadian banjir |

Field request:

```json
{
  "name": "Simulasi Banjir Teluk Betung",
  "address": "Deskripsi lokasi",
  "district": "Teluk Betung",
  "subdistrict": "Perlu validasi",
  "severity_level": "tinggi",
  "water_depth_cm": 60,
  "status": "aktif",
  "description": "Genangan menutup sebagian jalan",
  "occurred_at": "2026-05-19 08:00:00",
  "reported_at": "2026-05-19 08:30:00",
  "source_type": "admin_input",
  "source_reference": "Skenario demo",
  "data_status": "simulasi",
  "is_verified": false,
  "longitude": 105.2601,
  "latitude": -5.4452
}
```

Enum:

| Field | Nilai |
|---|---|
| severity_level | `rendah`, `sedang`, `tinggi`, `kritis` |
| status | `aktif`, `surut`, `ditangani`, `arsip` |
| data_status | `nyata`, `dummy`, `simulasi` |
| source_type | `pemerintah`, `berita`, `jurnal`, `observasi`, `admin_input`, `dummy` |

Validasi:

1. `name`, `severity_level`, `status`, `reported_at`, `longitude`, `latitude` wajib.
2. `water_depth_cm` tidak boleh negatif.
3. `source_reference` wajib jika `data_status = nyata`.
4. Geometry dibuat di backend, bukan dikirim langsung oleh frontend.

## L. Admin CRUD Endpoint: Evacuation Points

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/evacuation-points` | Daftar titik evakuasi |
| POST | `/api/v1/admin/evacuation-points` | Tambah titik evakuasi |
| GET | `/api/v1/admin/evacuation-points/{id}` | Detail titik evakuasi |
| PUT/PATCH | `/api/v1/admin/evacuation-points/{id}` | Update titik evakuasi |
| DELETE | `/api/v1/admin/evacuation-points/{id}` | Hapus titik evakuasi |

Request body:

```json
{
  "name": "Masjid Evakuasi Teluk Betung",
  "type": "masjid",
  "address": "Deskripsi lokasi",
  "district": "Teluk Betung",
  "subdistrict": "Perlu validasi",
  "capacity": 300,
  "facilities": ["aula", "toilet", "parkir"],
  "contact_person": "Pengurus",
  "contact_phone": "08xxxxxxxxxx",
  "status": "aktif",
  "description": "Titik evakuasi sementara",
  "source_type": "observasi",
  "source_reference": "Validasi peta",
  "data_status": "simulasi",
  "is_verified": false,
  "longitude": 105.252,
  "latitude": -5.43
}
```

Validasi:

1. `name`, `type`, `status`, `longitude`, `latitude` wajib.
2. `capacity` tidak boleh negatif.
3. `status`: `aktif`, `penuh`, `tidak_aktif`.
4. `type`: `sekolah`, `masjid`, `gedung_pemerintah`, `aula`, `lapangan`, `puskesmas`.

## M. Admin CRUD Endpoint: Heavy Equipment

### Heavy Equipment Posts

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/heavy-equipment-posts` | Daftar pos alat berat |
| POST | `/api/v1/admin/heavy-equipment-posts` | Tambah pos |
| GET | `/api/v1/admin/heavy-equipment-posts/{id}` | Detail pos |
| PUT/PATCH | `/api/v1/admin/heavy-equipment-posts/{id}` | Update pos |
| DELETE | `/api/v1/admin/heavy-equipment-posts/{id}` | Hapus pos |

Request body:

```json
{
  "name": "Pos Dummy Panjang",
  "address": "Sekitar Panjang, perlu validasi",
  "district": "Panjang",
  "subdistrict": "Perlu validasi",
  "contact_person": "Koordinator Pos",
  "contact_phone": "08xxxxxxxxxx",
  "status": "aktif",
  "description": "Pos dummy realistis untuk demo",
  "source_type": "dummy",
  "source_reference": "Data demo akademik",
  "data_status": "dummy",
  "is_verified": false,
  "longitude": 105.32,
  "latitude": -5.47
}
```

### Equipment Types

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/equipment-types` | Daftar jenis alat |
| POST | `/api/v1/admin/equipment-types` | Tambah jenis alat |
| PUT/PATCH | `/api/v1/admin/equipment-types/{id}` | Update jenis alat |
| DELETE | `/api/v1/admin/equipment-types/{id}` | Hapus jenis alat |

Request body:

```json
{
  "name": "excavator",
  "description": "Membersihkan material dan lumpur"
}
```

### Heavy Equipment Units

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/heavy-equipment-units` | Daftar unit alat |
| POST | `/api/v1/admin/heavy-equipment-units` | Tambah unit alat per pos |
| PUT/PATCH | `/api/v1/admin/heavy-equipment-units/{id}` | Update unit alat |
| DELETE | `/api/v1/admin/heavy-equipment-units/{id}` | Hapus unit alat |

Request body:

```json
{
  "post_id": 1,
  "equipment_type_id": 2,
  "quantity": 2,
  "available_quantity": 1,
  "status": "tersedia",
  "notes": "Satu unit siap digunakan"
}
```

Validasi:

1. `post_id` harus ada di `heavy_equipment_posts`.
2. `equipment_type_id` harus ada di `equipment_types`.
3. `quantity` tidak boleh negatif.
4. `available_quantity` tidak boleh negatif.
5. `available_quantity <= quantity`.
6. `status`: `tersedia`, `digunakan`, `perawatan`, `tidak_aktif`.

## N. Spatial Analysis Endpoints

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/analysis/flood-events/{id}/nearest-evacuation` | Titik evakuasi terdekat |
| GET | `/api/v1/analysis/flood-events/{id}/nearest-equipment` | Pos alat berat terdekat |
| GET | `/api/v1/analysis/flood-events/{id}/nearest-resources` | Evakuasi dan alat berat sekaligus |
| GET | `/api/v1/analysis/flood-events/{id}/impact-radius` | Radius terdampak opsional |

Parameter:

| Parameter | Fungsi |
|---|---|
| limit | Membatasi jumlah hasil |
| max_distance | Maksimum jarak dalam meter |
| equipment_type | Filter jenis alat berat |
| only_available | Hanya alat tersedia |
| status | Filter status |
| include_route | Sertakan rute jika diperlukan |

Query konseptual nearest evacuation:

```sql
SELECT e.*,
ST_Distance(f.geom::geography, e.geom::geography) AS distance_meters
FROM flood_events f
JOIN evacuation_points e ON e.status = 'aktif'
WHERE f.id = :id
ORDER BY distance_meters ASC
LIMIT :limit;
```

Query konseptual nearest equipment:

```sql
SELECT
  p.*,
ST_Distance(f.geom::geography, p.geom::geography) AS distance_meters
FROM flood_events f
JOIN heavy_equipment_posts p ON p.status = 'aktif'
JOIN heavy_equipment_units u ON u.post_id = p.id
WHERE f.id = :id
AND u.status = 'tersedia'
AND u.available_quantity > 0
GROUP BY f.geom, p.id
ORDER BY distance_meters ASC
LIMIT :limit;
```

Contoh response nearest evacuation:

```json
{
  "success": true,
  "message": "Titik evakuasi terdekat berhasil dihitung",
  "data": [
    {
      "id": 3,
      "name": "Masjid Evakuasi Teluk Betung",
      "type": "masjid",
      "capacity": 300,
      "status": "aktif",
      "distance_meters": 850.4,
      "coordinates": {
        "longitude": 105.252,
        "latitude": -5.43
      }
    }
  ]
}
```

Catatan:

1. Jarak dihitung dengan `ST_Distance(geom::geography, geom::geography)`.
2. Hasil diurutkan dari jarak terdekat.
3. Endpoint harus mengabaikan titik evakuasi `tidak_aktif`.
4. Endpoint alat berat sebaiknya hanya mengambil unit dengan `available_quantity > 0`.

## O. Routing Endpoints

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/routing/flood-events/{id}/to-nearest-evacuation` | Rute ke evakuasi terdekat |
| GET | `/api/v1/routing/flood-events/{id}/to-evacuation/{evacuation_id}` | Rute ke titik evakuasi tertentu |
| GET | `/api/v1/routing/coordinates` | Rute dari koordinat manual |

Parameter `/routing/coordinates`:

```text
origin_lng
origin_lat
destination_lng
destination_lat
provider
```

Alur:

1. Backend mengambil koordinat banjir dari database.
2. Backend mengambil koordinat evakuasi dari database.
3. Backend memanggil OSRM/OpenRouteService.
4. Frontend menerima rute dalam bentuk GeoJSON LineString.
5. Leaflet menggambar rute pada peta.

Catatan:

1. Rute hanya referensi.
2. Rute belum mempertimbangkan jalan tertutup.
3. Jika provider gagal, API harus mengembalikan error jelas.
4. API key OpenRouteService tidak boleh dikirim ke frontend.

Opsi provider:

| Provider | Kelebihan | Kekurangan |
|---|---|---|
| OSRM | Bisa tanpa API key untuk demo, response cepat | Public server tidak selalu stabil untuk produksi |
| OpenRouteService | Dokumentasi baik, fitur lengkap | Membutuhkan API key dan limit kuota |

Rekomendasi final MVP:

```text
Gunakan OSRM untuk MVP jika ingin cepat tanpa API key.
Gunakan OpenRouteService jika ingin kontrol lebih baik dan siap menyimpan API key di backend.
```

Untuk project kuliah, OSRM lebih praktis sebagai default.

## P. Dashboard Summary Endpoints

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/admin/dashboard/summary` | Ringkasan card dashboard |
| GET | `/api/v1/admin/dashboard/flood-events-by-status` | Rekap banjir per status |
| GET | `/api/v1/admin/dashboard/flood-events-by-district` | Rekap banjir per kecamatan |
| GET | `/api/v1/admin/dashboard/equipment-availability` | Rekap ketersediaan alat |

Contoh response summary:

```json
{
  "success": true,
  "message": "Ringkasan dashboard berhasil diambil",
  "data": {
    "active_flood_events": 4,
    "high_severity_events": 2,
    "evacuation_points_active": 10,
    "equipment_posts_active": 6,
    "available_equipment_units": 12
  }
}
```

## Q. Master Data Endpoints

Endpoint:

| Method | Path | Fungsi |
|---|---|---|
| GET | `/api/v1/master/flood-statuses` | Pilihan status banjir |
| GET | `/api/v1/master/severity-levels` | Pilihan severity |
| GET | `/api/v1/master/risk-levels` | Pilihan risiko |
| GET | `/api/v1/master/evacuation-types` | Jenis titik evakuasi |
| GET | `/api/v1/master/equipment-statuses` | Status alat berat |
| GET | `/api/v1/master/data-statuses` | Status data |
| GET | `/api/v1/master/source-types` | Jenis sumber data |

Manfaat:

1. Frontend tidak perlu hardcode enum.
2. Pilihan form lebih konsisten.
3. Jika status berubah, cukup diperbarui di backend.

## R. Validasi Request

Validasi umum:

1. `name` wajib.
2. `longitude` wajib untuk data spasial.
3. `latitude` wajib untuk data spasial.
4. `longitude` dan `latitude` harus numeric.
5. Koordinat harus berada di sekitar Bandar Lampung.
6. Enum status harus sesuai daftar yang ditentukan.
7. `capacity` tidak boleh negatif.
8. `quantity` tidak boleh negatif.
9. `available_quantity` tidak boleh melebihi `quantity`.
10. `source_reference` wajib jika `data_status = nyata`.
11. `geom` dibuat dari longitude dan latitude di backend.
12. Frontend tidak mengirim `geom` langsung pada CRUD.

Validasi koordinat konseptual:

```text
longitude sekitar 105.x
latitude sekitar -5.x
```

## S. Status Code

| Status Code | Penggunaan |
|---|---|
| 200 OK | Data berhasil diambil atau update berhasil |
| 201 Created | Data berhasil dibuat |
| 204 No Content | Data berhasil dihapus tanpa body |
| 400 Bad Request | Parameter tidak valid |
| 401 Unauthorized | User belum login |
| 403 Forbidden | User tidak punya akses |
| 404 Not Found | Data tidak ditemukan |
| 422 Validation Error | Validasi request gagal |
| 500 Server Error | Error internal server |
| 502 Bad Gateway | Provider routing gagal |

## T. Error Handling

Contoh data tidak ditemukan:

```json
{
  "success": false,
  "message": "Data kejadian banjir tidak ditemukan"
}
```

Contoh validasi gagal:

```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "longitude": ["Longitude wajib diisi"],
    "severity_level": ["Severity tidak valid"]
  }
}
```

Contoh tidak ada evakuasi aktif:

```json
{
  "success": false,
  "message": "Tidak ada titik evakuasi aktif yang tersedia"
}
```

Contoh routing provider gagal:

```json
{
  "success": false,
  "message": "Gagal mengambil rute dari provider",
  "errors": {
    "provider": "OSRM tidak merespons"
  }
}
```

Kasus error yang harus ditangani:

1. Data tidak ditemukan.
2. Validasi gagal.
3. Koordinat tidak valid.
4. Tidak ada titik evakuasi aktif.
5. Tidak ada alat berat tersedia.
6. Routing provider gagal.
7. Query PostGIS gagal.
8. User tidak login.

## U. Contoh Response Detail

### 1. Detail Flood Event

```json
{
  "success": true,
  "message": "Detail kejadian banjir berhasil diambil",
  "data": {
    "id": 1,
    "name": "Simulasi Banjir Teluk Betung",
    "district": "Teluk Betung",
    "severity_level": "tinggi",
    "water_depth_cm": 60,
    "status": "aktif",
    "data_status": "simulasi",
    "coordinates": {
      "longitude": 105.2601,
      "latitude": -5.4452
    }
  }
}
```

### 2. Nearest Evacuation

```json
{
  "success": true,
  "message": "Titik evakuasi terdekat ditemukan",
  "data": {
    "flood_event_id": 1,
    "results": [
      {
        "id": 3,
        "name": "Masjid Evakuasi Teluk Betung",
        "distance_meters": 850.4,
        "status": "aktif"
      }
    ]
  }
}
```

### 3. Nearest Equipment

```json
{
  "success": true,
  "message": "Pos alat berat terdekat ditemukan",
  "data": {
    "flood_event_id": 1,
    "results": [
      {
        "id": 2,
        "name": "Pos Dummy Teluk Betung",
        "distance_meters": 1200.7,
        "available_equipment": [
          {
            "type": "excavator",
            "available_quantity": 1
          }
        ]
      }
    ]
  }
}
```

### 4. Route to Evacuation

```json
{
  "success": true,
  "message": "Rute evakuasi berhasil diambil",
  "data": {
    "provider": "osrm",
    "distance_meters": 3200,
    "duration_seconds": 540,
    "route": {
      "type": "Feature",
      "geometry": {
        "type": "LineString",
        "coordinates": [
          [105.2601, -5.4452],
          [105.258, -5.44],
          [105.252, -5.43]
        ]
      },
      "properties": {
        "from": "Simulasi Banjir Teluk Betung",
        "to": "Masjid Evakuasi Teluk Betung"
      }
    }
  }
}
```

### 5. Dashboard Summary

```json
{
  "success": true,
  "message": "Dashboard berhasil diambil",
  "data": {
    "active_flood_events": 4,
    "flood_risk_points": 15,
    "evacuation_points": 10,
    "available_equipment_units": 12
  }
}
```

## V. Contoh Response GeoJSON

### 1. Flood Events

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
        "name": "Simulasi Banjir Teluk Betung",
        "severity_level": "tinggi",
        "status": "aktif"
      }
    }
  ]
}
```

### 2. Flood Risks

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.25, -5.44]
      },
      "properties": {
        "id": 1,
        "name": "Rawan Banjir Teluk Betung",
        "risk_level": "tinggi",
        "data_status": "simulasi"
      }
    }
  ]
}
```

### 3. Evacuation Points

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.252, -5.43]
      },
      "properties": {
        "id": 3,
        "name": "Masjid Evakuasi Teluk Betung",
        "type": "masjid",
        "capacity": 300,
        "status": "aktif"
      }
    }
  ]
}
```

### 4. Heavy Equipment Posts

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.32, -5.47]
      },
      "properties": {
        "id": 2,
        "name": "Pos Dummy Panjang",
        "status": "aktif",
        "available_equipment": [
          {
            "type": "pompa_air",
            "available_quantity": 2
          }
        ]
      }
    }
  ]
}
```

### 5. Route LineString

```json
{
  "type": "Feature",
  "geometry": {
    "type": "LineString",
    "coordinates": [
      [105.2601, -5.4452],
      [105.258, -5.44],
      [105.252, -5.43]
    ]
  },
  "properties": {
    "distance_meters": 3200,
    "duration_seconds": 540,
    "provider": "osrm"
  }
}
```

## W. Keamanan API

Keamanan MVP:

1. Endpoint publik boleh diakses tanpa login.
2. Endpoint admin wajib login.
3. Gunakan Laravel Sanctum/session cookie untuk admin.
4. Validasi semua input.
5. Batasi nilai enum.
6. Jangan expose API key OpenRouteService di frontend.
7. Sanitasi input teks.
8. Gunakan rate limit sederhana untuk endpoint publik jika diperlukan.
9. Foto atau upload file tidak masuk MVP.
10. Jangan tampilkan password, token, atau data sensitif dalam response.

## X. Struktur Controller dan Service Laravel

Controller yang disarankan:

| Controller | Tanggung Jawab |
|---|---|
| PublicMapController | Summary dan detail publik |
| GeoJsonController | Semua response GeoJSON |
| AdminAuthController | Login, logout, me |
| FloodRiskPointController | CRUD titik rawan banjir |
| FloodEventController | CRUD kejadian banjir |
| EvacuationPointController | CRUD titik evakuasi |
| HeavyEquipmentPostController | CRUD pos alat berat |
| EquipmentTypeController | CRUD jenis alat berat |
| HeavyEquipmentUnitController | CRUD unit alat berat |
| SpatialAnalysisController | Nearest evacuation/equipment |
| RoutingController | Integrasi OSRM/OpenRouteService |
| DashboardController | Statistik dashboard |
| MasterDataController | Enum dan pilihan form |

Service yang disarankan:

| Service | Tanggung Jawab |
|---|---|
| GeoJsonService | Membentuk FeatureCollection |
| NearestEvacuationService | Query evakuasi terdekat |
| NearestEquipmentService | Query alat berat terdekat |
| RoutingService | Memanggil provider routing |
| SpatialQueryService | Helper query PostGIS umum |

## Y. Urutan Implementasi API

Urutan implementasi realistis:

1. Auth admin.
2. CRUD data utama.
3. GeoJSON endpoints.
4. Spatial analysis endpoints.
5. Routing endpoints.
6. Dashboard endpoints.
7. Master data endpoints.
8. Testing endpoint dan validasi response.

Prioritas pertama setelah auth adalah CRUD dan GeoJSON, karena peta membutuhkan data layer sebelum analisis spasial dapat diuji.

## Z. Checklist API.md

- [ ] Public endpoint sudah jelas.
- [ ] Admin endpoint sudah jelas.
- [ ] GeoJSON endpoint sudah jelas.
- [ ] CRUD endpoint sudah lengkap.
- [ ] Endpoint analisis spasial sudah jelas.
- [ ] Endpoint routing sudah jelas.
- [ ] Validasi request sudah jelas.
- [ ] Response JSON konsisten.
- [ ] Response GeoJSON sesuai Leaflet.
- [ ] Status code sudah ditentukan.
- [ ] Error handling sudah dirancang.
- [ ] Siap lanjut ke `UI.md` atau `TASKS.md`.

## Keputusan Akhir API

### 1. Endpoint Wajib MVP

Endpoint wajib:

1. Auth admin.
2. CRUD `flood_risk_points`.
3. CRUD `flood_events`.
4. CRUD `evacuation_points`.
5. CRUD `heavy_equipment_posts`.
6. CRUD `equipment_types`.
7. CRUD `heavy_equipment_units`.
8. GeoJSON semua layer utama.
9. Nearest evacuation.
10. Nearest equipment.
11. Routing ke titik evakuasi.
12. Dashboard summary sederhana.

### 2. Endpoint Opsional

Endpoint opsional:

1. GeoJSON districts.
2. Impact radius.
3. Route histories.
4. Equipment dispatch logs.
5. Rekap lanjutan per wilayah.

### 3. Endpoint yang Ditunda

Endpoint yang sebaiknya ditunda:

1. Laporan banjir dari publik.
2. Upload foto.
3. Validasi multi-role.
4. Tracking alat berat real-time.
5. Routing dengan jalan tertutup.

### 4. Pendekatan Auth

Rekomendasi final:

```text
Gunakan Laravel Sanctum dengan session/cookie auth untuk admin MVP.
```

### 5. Pendekatan Routing

Rekomendasi final:

```text
Gunakan OSRM sebagai default MVP, dengan opsi OpenRouteService jika membutuhkan provider yang lebih stabil dan API key disimpan di backend.
```

### 6. Dokumen Berikutnya

Dokumen berikutnya yang disarankan:

```text
UI.md
```

Alasannya, setelah requirements, database, dataset, dan API sudah jelas, langkah paling penting berikutnya adalah merancang halaman, alur interaksi, layer peta, marker, popup, form admin, dan dashboard agar implementasi frontend tidak asal jadi.
