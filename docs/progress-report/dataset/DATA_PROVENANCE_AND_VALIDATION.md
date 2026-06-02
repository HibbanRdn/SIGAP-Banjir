# DATA PROVENANCE AND VALIDATION

## 1. Status Sumber Dataset Saat Ini

Dataset SIGAP Banjir saat ini adalah dataset awal pengembangan untuk kebutuhan progres aplikasi GIS. Data digunakan untuk menguji struktur spasial, layer peta, analisis jarak, rekomendasi resource, dan rute evakuasi referensi.

Dataset belum digunakan sebagai dasar keputusan darurat resmi. SIGAP Banjir juga belum diklaim sebagai sistem operasional Pemerintah Kota Bandar Lampung atau BPBD.

## 2. Komposisi Data Pengembangan Final

| Kelompok Data | Jumlah | Status Penggunaan |
|---|---:|---|
| Kejadian banjir | 8 | Dataset simulasi spasial untuk skenario kejadian |
| Titik rawan banjir | 12 | Dataset simulasi spasial untuk layer risiko |
| Titik evakuasi | 10 | Dataset simulasi spasial untuk uji rekomendasi evakuasi |
| Pos alat berat | 6 | Rancangan data operasional/dummy realistis untuk resource respons |
| Jenis alat berat | 6 | Master data pengembangan |
| Unit alat berat | 15 | Dummy realistis untuk uji ketersediaan resource |

Status data spasial final setelah audit adalah 30 record `simulasi`, 6 record `dummy`, dan 0 record `nyata`. Seluruh 36 record spasial masih `is_verified = false` karena validasi yang dilakukan baru sebatas validasi lokasi/area melalui peta publik, bukan verifikasi operasional resmi.

## 3. Koreksi Inkonsistensi Status Data

Pada audit awal ditemukan satu record `Pos Alat Berat Panjang` yang berlabel `data_status = nyata`, `source_type = pemerintah`, dan `is_verified = true`, tetapi `source_reference` masih menunjukkan asal data dari seeder demo. Karena tidak tersedia URL, dokumen, atau bukti sumber resmi, record tersebut dikoreksi menjadi `data_status = dummy`, `source_type = dummy`, dan `is_verified = false`.

Koreksi ini merupakan koreksi inkonsistensi pelabelan status sumber data, bukan manipulasi data agar terlihat resmi. Backup record sebelum koreksi disimpan pada `audit_backups/heavy_equipment_posts_panjang_before_correction.json`.

## 4. Alasan Penggunaan Dataset Simulasi

Dataset simulasi digunakan secara sadar karena tahap progres berfokus pada pembuktian fungsi GIS, bukan validasi data operasional resmi. Penggunaan dataset simulasi membantu:

1. Menguji penyimpanan titik spasial pada PostGIS.
2. Menguji GeoJSON API untuk Leaflet.
3. Menguji filter, layer toggle, popup, dan marker kategori.
4. Menguji rekomendasi titik evakuasi terdekat.
5. Menguji rekomendasi pos alat berat terdekat.
6. Menguji rute evakuasi referensi dengan OSRM.
7. Menjaga demo tetap konsisten tanpa mengklaim data belum tervalidasi sebagai data resmi.

## 5. Mekanisme Transparansi yang Sudah Ada

| Mekanisme | Fungsi |
|---|---|
| `source_type` | Menjelaskan jenis sumber, misalnya `admin_input` atau `dummy` |
| `source_reference` | Menyimpan catatan sumber atau referensi dataset |
| `data_status` | Membedakan `simulasi`, `dummy`, dan `nyata` |
| `is_verified` | Menandai apakah data sudah diverifikasi secara operasional |
| Halaman Sumber Data & Validasi | Menampilkan status sumber dan verifikasi per data |
| Badge status di UI | Membuat status simulasi/dummy terlihat pada dashboard, detail, dan tabel |

## 6. Validasi yang Dilakukan Saat Ekspor Final

| Validasi | Hasil |
|---|---|
| Jumlah row `flood_events.csv` | 8 record |
| Jumlah row `flood_risk_points.csv` | 12 record |
| Jumlah row `evacuation_points.csv` | 10 record |
| Jumlah row `heavy_equipment_posts.csv` | 6 record |
| Jumlah row `equipment_types.csv` | 6 record |
| Jumlah row `heavy_equipment_units.csv` | 15 record |
| Longitude/latitude | Berada pada rentang wilayah studi Bandar Lampung |
| Reverse geocoding | 36 record terbaca pada area Bandar Lampung melalui OpenStreetMap/Nominatim |
| Quantity unit | `available_quantity <= quantity` |
| Credential | Tidak ada data user, password, atau credential yang diekspor |
| Sumber data | Tidak ada URL sumber resmi yang dikarang |

## 7. Rencana Pengembangan Dataset

Jika project dilanjutkan, tahap validasi dataset yang disarankan adalah:

1. Mengumpulkan data kejadian banjir dari berita, dokumen pemerintah, atau laporan yang dapat diverifikasi.
2. Menyimpan URL/dokumen sumber pada `source_reference`.
3. Melakukan verifikasi koordinat melalui peta atau survei sederhana.
4. Memisahkan data yang benar-benar nyata dari data dummy/simulasi.
5. Melakukan validasi bersama pihak terkait jika project dikembangkan di luar konteks akademik.

## 8. Batasan Penggunaan

Dataset saat ini tidak boleh digunakan sebagai dasar keputusan darurat resmi. Rute evakuasi yang ditampilkan juga merupakan rute referensi dari OSRM dan belum mempertimbangkan jalan tertutup, kondisi banjir aktual, lalu lintas, atau keputusan petugas lapangan.

Transparansi data ini bukan kelemahan sistem, tetapi praktik penting dalam pengembangan SIG akademik agar struktur dataset, status sumber, dan validasi dapat ditingkatkan secara bertahap.
