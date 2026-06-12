# API.md

# Dokumentasi API Aktual SIGAP Banjir

Dokumen ini mencatat endpoint API yang aktif pada MVP SIGAP Banjir. Semua endpoint berada di bawah base path:

```text
/api/v1
```

API digunakan untuk:

- layer GeoJSON Leaflet;
- analisis spasial berbasis PostGIS;
- routing evakuasi referensi berbasis OSRM demo server.

Endpoint admin CRUD pada MVP ini memakai route web Blade, bukan API JSON terpisah.

## Prinsip Umum

1. Koordinat database, GeoJSON, dan OSRM memakai urutan `longitude, latitude`.
2. Endpoint GeoJSON mengembalikan `FeatureCollection` langsung tanpa pembungkus `success/message`.
3. Endpoint JSON biasa memakai struktur:

```json
{
  "success": true,
  "message": "Pesan singkat.",
  "data": {}
}
```

4. Error JSON memakai struktur:

```json
{
  "success": false,
  "message": "Pesan error.",
  "errors": {}
}
```

5. Analisis jarak dilakukan di backend dengan PostGIS:

```sql
ST_Distance(source.geom::geography, target.geom::geography)
```

6. Filter radius memakai:

```sql
ST_DWithin(source.geom::geography, target.geom::geography, max_distance_meters)
```

7. Routing provider dipanggil dari backend Laravel. Frontend tidak memanggil OSRM langsung.

## GeoJSON API

### GET `/api/v1/geojson/flood-events`

Layer kejadian banjir.

Filter query yang didukung:

- `status`
- `severity_level`
- `district`
- `data_status`
- `source_type`

Response:

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [105.2607, -5.4478]
      },
      "properties": {
        "id": 9,
        "name": "Banjir Teluk Betung Selatan",
        "status": "aktif",
        "severity_level": "kritis",
        "district": "Teluk Betung Selatan",
        "data_status": "simulasi"
      }
    }
  ]
}
```

### GET `/api/v1/geojson/flood-risks`

Layer titik rawan banjir.

Filter query yang didukung:

- `risk_level`
- `district`
- `data_status`
- `source_type`

### GET `/api/v1/geojson/evacuation-points`

Layer titik evakuasi.

Filter query yang didukung:

- `status`
- `type`
- `district`
- `data_status`
- `source_type`

### GET `/api/v1/geojson/heavy-equipment-posts`

Layer pos alat berat.

Filter query yang didukung:

- `status`
- `district`
- `data_status`
- `source_type`

### GET `/api/v1/geojson/district-flood-intensity`

Layer polygon kecamatan untuk peta tematik intensitas kejadian banjir.

Response berupa `FeatureCollection` langsung. Setiap feature memakai geometry polygon kecamatan Bandar Lampung dari file statis GeoJSON, lalu properties diisi dari agregasi aktual tabel `flood_events`.

Properties utama:

- `district`
- `source_district_name`
- `district_code`
- `city`
- `province`
- `total_events`
- `active_events`
- `critical_active_events`
- `intensity_level`
- `intensity_label`
- `intensity_range`
- `color_key`

Klasifikasi intensitas:

| Range `total_events` | `intensity_level` | Label |
|---:|---|---|
| 0 | `none` | Tidak ada kejadian |
| 1-4 | `low` | Rendah |
| 5-7 | `medium` | Sedang |
| 8+ | `high` | Tinggi |

Catatan boundary:

- Boundary disimpan di `storage/app/public/geojson/bandar-lampung-districts.geojson`.
- Sumber boundary: BNPB ArcGIS REST Services, layer `Batas Kecamatan`.
- Nama kecamatan dari boundary dinormalisasi di backend agar cocok dengan penulisan `district` pada data aplikasi.

## Spatial Analysis API

Endpoint analisis menggunakan data dari `flood_events` sebagai sumber, lalu menghitung jarak ke resource target dengan PostGIS.

### GET `/api/v1/analysis/flood-events/{floodEvent}/nearest-evacuation`

Mencari titik evakuasi aktif terdekat dari satu kejadian banjir.

Query parameter:

- `limit`: integer, default `3`, maksimal `10`.
- `max_distance_meters`: integer positif, opsional.
- `type`: opsional, salah satu:
  - `sekolah`
  - `masjid`
  - `gedung_pemerintah`
  - `aula`
  - `lapangan`
  - `puskesmas`

Response ringkas:

```json
{
  "success": true,
  "message": "Rekomendasi titik evakuasi terdekat berhasil diambil.",
  "data": {
    "flood_event": {
      "id": 9,
      "name": "Banjir Teluk Betung Selatan",
      "status": "aktif",
      "severity_level": "kritis",
      "district": "Teluk Betung Selatan",
      "longitude": 105.2607,
      "latitude": -5.4478
    },
    "recommendations": [
      {
        "rank": 1,
        "id": 11,
        "name": "Masjid Al-Furqon Lungsir",
        "type": "masjid",
        "status": "aktif",
        "capacity": 250,
        "district": "Teluk Betung Utara",
        "longitude": 105.2634,
        "latitude": -5.4442,
        "distance_meters": 500.12,
        "distance_label": "500 m"
      }
    ]
  }
}
```

### GET `/api/v1/analysis/flood-events/{floodEvent}/nearest-equipment`

Mencari pos alat berat aktif terdekat yang memiliki unit tersedia.

Query parameter:

- `limit`: integer, default `3`, maksimal `10`.
- `max_distance_meters`: integer positif, opsional.
- `equipment_type`: opsional, contoh:
  - `excavator`
  - `dump_truck`
  - `wheel_loader`
  - `pompa_air`
  - `mobil_tangki`
  - `pickup_operasional`

Response menyertakan `available_equipment`.

### GET `/api/v1/analysis/flood-events/{floodEvent}/nearest-resources`

Menggabungkan rekomendasi titik evakuasi dan pos alat berat terdekat.

Query parameter:

- `evacuation_limit`: integer, default `3`, maksimal `10`.
- `equipment_limit`: integer, default `3`, maksimal `10`.
- `max_distance_meters`: integer positif, opsional.

Response:

```json
{
  "success": true,
  "message": "Rekomendasi resource terdekat berhasil diambil.",
  "data": {
    "flood_event": {},
    "nearest_evacuations": [],
    "nearest_equipment_posts": []
  }
}
```

## Routing API

Routing memakai OSRM demo server:

```text
https://router.project-osrm.org
```

Konfigurasi:

```env
ROUTING_PROVIDER=osrm
OSRM_BASE_URL=https://router.project-osrm.org
```

Tidak ada token atau API key.

### GET `/api/v1/routing/flood-events/{floodEvent}/to-nearest-evacuation`

Mengambil titik evakuasi aktif terdekat, lalu meminta rute referensi dari OSRM.

Query parameter:

- `type`: opsional, filter jenis titik evakuasi.
- `max_distance_meters`: integer positif, opsional.

### GET `/api/v1/routing/flood-events/{floodEvent}/to-evacuation/{evacuationPoint}`

Mengambil rute referensi dari kejadian banjir ke titik evakuasi aktif yang dipilih.

Titik evakuasi yang tidak aktif ditolak dengan error `422`.

Response sukses:

```json
{
  "success": true,
  "message": "Rute evakuasi referensi berhasil diambil.",
  "data": {
    "provider": "osrm",
    "route_status": "referensi",
    "note": "Rute ini bersifat referensi dan belum mempertimbangkan jalan tertutup akibat banjir.",
    "origin": {
      "type": "flood_event",
      "id": 9,
      "name": "Banjir Teluk Betung Selatan",
      "longitude": 105.2607,
      "latitude": -5.4478
    },
    "destination": {
      "type": "evacuation_point",
      "id": 11,
      "name": "Masjid Al-Furqon Lungsir",
      "longitude": 105.2634,
      "latitude": -5.4442
    },
    "distance_meters": 500.12,
    "distance_label": "500 m",
    "duration_seconds": 113.3,
    "duration_label": "2 menit",
    "geometry": {
      "type": "LineString",
      "coordinates": [
        [105.2607, -5.4478],
        [105.2634, -5.4442]
      ]
    }
  }
}
```

## Error Penting

| Kondisi | Status | Bentuk |
|---|---:|---|
| Model route binding tidak ditemukan | 404 | JSON error |
| Parameter query invalid | 422 | JSON error dengan `errors` |
| Flood event tanpa `geom` | 422 | JSON error |
| Evacuation point tidak aktif untuk routing | 422 | JSON error |
| OSRM tidak merespons | 502 | JSON error |
| OSRM tidak menemukan rute | 422 | JSON error |
| Response provider tidak valid | 502 | JSON error |

Contoh validasi parameter:

```json
{
  "success": false,
  "message": "Validasi parameter gagal.",
  "errors": {
    "limit": ["The limit field must not be greater than 10."]
  }
}
```

## Endpoint yang Tidak Aktif pada MVP

Endpoint berikut belum menjadi bagian aktif MVP dan tidak boleh diklaim sudah tersedia:

- `/api/v1/map/summary`
- `/api/v1/map/layers`
- `/api/v1/routing/coordinates`
- `/api/v1/geojson/districts`
- `/api/v1/flood-events/{id}`
- API admin CRUD JSON terpisah
- route history API
- rute menuju pos alat berat
- impact radius API

Jika dibutuhkan, endpoint tersebut masuk backlog setelah MVP.
