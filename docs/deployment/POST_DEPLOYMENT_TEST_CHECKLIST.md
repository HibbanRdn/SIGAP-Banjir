# Post Deployment Test Checklist SIGAP Banjir

Gunakan checklist ini setelah aplikasi online di VPS.

Domain production:

```text
https://pindahtangan.my.id
```

## A. Test Public Page

| No | Test | Target |
|---:|---|---|
| 1 | Buka `https://pindahtangan.my.id` | Home tampil tanpa error |
| 2 | Buka `https://pindahtangan.my.id/peta` | Public map tampil |
| 3 | Basemap tampil | OpenStreetMap/Humanitarian/Satelit bisa dipilih |
| 4 | Marker kejadian banjir tampil | 12 marker kejadian terbaca |
| 5 | Marker titik rawan tampil | 12 marker risiko terbaca |
| 6 | Marker titik evakuasi tampil | 10 marker evakuasi terbaca |
| 7 | Marker pos alat berat tampil | 6 marker pos terbaca |
| 8 | Polygon intensitas kecamatan tampil | Layer `Intensitas Kecamatan` aktif dan transparan |
| 9 | Toggle layer berjalan | Layer bisa ditampilkan/disembunyikan |
| 10 | Legend tampil | Legend marker dan intensitas kecamatan muncul |
| 11 | Popup kejadian tampil | Popup memuat status, severity, sumber `berita`, data `nyata` |
| 12 | Popup titik rawan tampil | Popup memuat risiko, sumber `jurnal`, data `nyata` |
| 13 | Filter kejadian berjalan | Filter status/severity/kecamatan mempengaruhi list dan marker |
| 14 | Route OSRM tampil | Klik `Tampilkan Rute` menghasilkan garis rute referensi |

## B. Test API GeoJSON

Jalankan dari lokal atau VPS:

```bash
curl -s https://pindahtangan.my.id/api/v1/geojson/flood-events | head
curl -s https://pindahtangan.my.id/api/v1/geojson/flood-risks | head
curl -s https://pindahtangan.my.id/api/v1/geojson/evacuation-points | head
curl -s https://pindahtangan.my.id/api/v1/geojson/heavy-equipment-posts | head
curl -s https://pindahtangan.my.id/api/v1/geojson/district-flood-intensity | head
```

Target:

| Endpoint | Target |
|---|---|
| `/api/v1/geojson/flood-events` | `FeatureCollection`, 12 feature |
| `/api/v1/geojson/flood-risks` | `FeatureCollection`, 12 feature |
| `/api/v1/geojson/evacuation-points` | `FeatureCollection`, 10 feature |
| `/api/v1/geojson/heavy-equipment-posts` | `FeatureCollection`, 6 feature |
| `/api/v1/geojson/district-flood-intensity` | `FeatureCollection`, polygon kecamatan tampil |

Jika server punya `jq`:

```bash
curl -s https://pindahtangan.my.id/api/v1/geojson/flood-events | jq '.type, (.features | length)'
curl -s https://pindahtangan.my.id/api/v1/geojson/district-flood-intensity | jq '.type, (.features | length)'
```

## C. Test Spatial Analysis API

Pilih salah satu ID `flood_events` yang ada di production:

```bash
php artisan tinker --execute="echo App\\Models\\FloodEvent::query()->value('id');"
```

Misalnya hasilnya `18`, test:

```bash
curl -s https://pindahtangan.my.id/api/v1/analysis/flood-events/18/nearest-resources | head
curl -s https://pindahtangan.my.id/api/v1/analysis/flood-events/18/nearest-evacuation | head
curl -s https://pindahtangan.my.id/api/v1/analysis/flood-events/18/nearest-equipment | head
```

Target:

1. Response JSON sukses.
2. Ada jarak dalam meter/kilometer.
3. Tidak ada error PostGIS.

## D. Test Routing API

```bash
curl -s https://pindahtangan.my.id/api/v1/routing/flood-events/18/to-nearest-evacuation | head
```

Target:

1. Response JSON sukses.
2. Ada geometry route.
3. Jika OSRM gagal, UI menampilkan error yang bisa dipahami.

Catatan: OSRM demo server adalah layanan eksternal dan bisa rate limit/down. Itu bukan berarti Laravel/PostGIS gagal.

## E. Test Admin

| No | Test | Target |
|---:|---|---|
| 1 | Buka `/admin/login` | Form login tampil |
| 2 | Login admin | Berhasil masuk dashboard |
| 3 | Buka `/admin/dashboard` | Statistik database tampil |
| 4 | Buka `/admin/flood-events` | List kejadian tampil |
| 5 | Buka detail kejadian | Mini map, metadata, analisis resource tampil |
| 6 | Buka `/admin/flood-risks` | Titik rawan berbasis jurnal tampil |
| 7 | Buka `/admin/data-sources` | Data berita/jurnal tampil `nyata` dan terverifikasi |
| 8 | Test CRUD ringan | Form create/edit dapat dibuka |
| 9 | Logout | Session berakhir |

Jangan menampilkan password admin di rekaman atau screenshot.

## F. Test Security Dasar

| Test | Command/Cara Cek | Target |
|---|---|---|
| APP_DEBUG | `php artisan about` | Debug Mode OFF |
| HTTPS | Buka domain di browser | Lock icon aktif |
| `.env` tidak bisa diakses | `curl -I https://pindahtangan.my.id/.env` | 403/404, bukan isi file |
| Directory listing mati | Buka path folder sembarang | Tidak menampilkan daftar file |
| Admin protected | Buka `/admin/dashboard` tanpa login | Redirect ke login |
| Database tidak public | Cek firewall/security group | Port 5432 tidak dibuka ke publik |
| Nginx config valid | `nginx -t` | successful |
| SSL renew | `certbot renew --dry-run` | sukses |

## G. Test Laravel Server

Di VPS:

```bash
cd /var/www/sigap-banjir
php artisan about
php artisan route:list
php artisan migrate:status
php artisan tinker --execute="echo App\\Models\\FloodEvent::count();"
php artisan tinker --execute="echo App\\Models\\FloodRiskPoint::count();"
php artisan tinker --execute="echo App\\Models\\EvacuationPoint::count();"
php artisan tinker --execute="echo App\\Models\\HeavyEquipmentPost::count();"
```

Target count:

```text
FloodEvent: 12
FloodRiskPoint: 12
EvacuationPoint: 10
HeavyEquipmentPost: 6
```

## H. Test Log

Setelah semua test, cek log:

```bash
tail -n 100 /var/www/sigap-banjir/storage/logs/laravel.log
tail -n 100 /var/log/nginx/error.log
```

Target:

1. Tidak ada error baru.
2. Tidak ada stack trace database.
3. Tidak ada permission denied.
4. Tidak ada Vite manifest error.
