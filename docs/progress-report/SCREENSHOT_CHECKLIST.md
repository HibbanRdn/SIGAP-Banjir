# SCREENSHOT CHECKLIST PROGRES SIGAP BANJIR

Gunakan checklist ini jika screenshot otomatis tidak berhasil atau jika ingin mengambil ulang gambar manual dengan resolusi presentasi. Screenshot UI untuk laporan sebaiknya tetap berwarna agar marker, layer, badge status, dan desain aplikasi terbaca jelas.

| No | URL | Bagian yang Harus Terlihat | Nama File | Caption Laporan |
|---:|---|---|---|---|
| 1 | `/peta` | Semua layer aktif, panel kiri, basemap selector, legend, marker peta | `01_public_map_explorer.png` | Gambar 5.1 Tampilan Peta Publik SIGAP Banjir |
| 2 | `/admin/login` | Split layout login, hero civic spatial, form login | `02_admin_login.png` | Gambar 5.2 Tampilan Login Admin SIGAP Banjir |
| 3 | `/admin/dashboard` | Statistik real, kejadian terbaru, status dataset, quick action | `03_admin_dashboard.png` | Gambar 5.3 Dashboard Admin SIGAP Banjir |
| 4 | `/admin/flood-events/10` | Mini map, rekomendasi resource, panel rute referensi | `04_detail_kejadian_decision_support.png` | Gambar 5.4 Detail Kejadian Banjir sebagai Decision Support |
| 5 | `/admin/data-sources` | Statistik transparansi data, filter, tabel status validasi | `05_data_sources_validation.png` | Gambar 5.5 Halaman Sumber Data dan Validasi |
| 6 | `/admin/flood-events` | Daftar CRUD kejadian banjir, filter, badge status/severity | `06_crud_flood_events.png` | Gambar 5.6 Halaman CRUD Kejadian Banjir |
| 7 | `/admin/evacuation-points/18` | Detail titik evakuasi, mini map, kapasitas/status | `07_detail_evakuasi.png` | Gambar 5.7 Detail Titik Evakuasi |
| 8 | `/admin/heavy-equipment-posts/7` | Detail pos alat berat, mini map, unit tersedia | `08_detail_pos_alat_berat.png` | Gambar 5.8 Detail Pos Alat Berat |

## Data Contoh yang Disarankan

| Kebutuhan | Data |
|---|---|
| Kejadian untuk demo route | `Genangan Way Halim`, ID 10 |
| Titik evakuasi contoh | `Balai Warga Rajabasa - Simulasi`, ID 18 |
| Pos alat berat contoh | `Pos Alat Berat Panjang`, ID 7 |

## Catatan Pengambilan

1. Jalankan server lokal dengan `php artisan serve --host=127.0.0.1 --port=8000` atau gunakan port lain jika 8000 sudah dipakai.
2. Buka `http://127.0.0.1:8000/peta` untuk screenshot publik.
3. Login admin melalui `/admin/login`.
4. Gunakan akun demo lokal sesuai README jika diperlukan.
5. Jangan menyebut screenshot sebagai mockup; gunakan hanya tampilan aplikasi aktual.
6. Jika tile basemap eksternal lambat, tunggu beberapa detik atau gunakan basemap Standar.
