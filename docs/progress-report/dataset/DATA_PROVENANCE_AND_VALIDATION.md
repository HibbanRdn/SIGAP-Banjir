# DATA PROVENANCE AND VALIDATION

## 1. Status Sumber Dataset Saat Ini

Dataset SIGAP Banjir saat ini merupakan dataset pengembangan akademik yang sudah direvisi agar dua layer banjir utama berbasis sumber nyata:

| Kelompok Data | Jumlah | Source Type | Data Status | Status Penggunaan |
|---|---:|---|---|---|
| Kejadian banjir | 12 | berita | nyata | Data nyata berbasis pemberitaan banjir Bandar Lampung 14 April 2026 |
| Titik rawan banjir | 12 | jurnal | nyata | Data nyata berbasis literatur akademik risiko banjir |
| Titik evakuasi | 10 | admin_input | simulasi | Kandidat evakuasi untuk uji rekomendasi |
| Pos alat berat | 6 | dummy | dummy | Data resource dummy realistis untuk demo respons |
| Jenis alat berat | 6 | - | - | Master data pengembangan |
| Unit alat berat | 15 | - | - | Dummy realistis untuk uji ketersediaan resource |

SIGAP Banjir tetap tidak diklaim sebagai sistem operasional Pemerintah Kota Bandar Lampung atau BPBD. Perubahan ini mengganti data awal demo pada `flood_events` dan `flood_risk_points` menjadi data nyata berbasis sumber berita dan jurnal yang dicatat secara transparan.

## 2. Sumber Data Kejadian Banjir

`flood_events` menggunakan data nyata berbasis berita. Semua record memiliki:

| Atribut | Nilai |
|---|---|
| `data_status` | `nyata` |
| `source_type` | `berita` |
| `is_verified` | `true` |
| `status` | `surut` |

Sumber yang digunakan:

1. Detik/Antara: https://news.detik.com/berita/d-8445003/banjir-terjang-16-kecamatan-di-bandar-lampung-1-orang-meninggal
2. Rilis ID 34 titik: https://lampung.rilis.id/Breaking%20News/Berita/banjir-rendam-34-titik-di-bandar-lampung-1-warga-36kR?page=1
3. Rilis ID Kedaton: https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1
4. Rilis ID Kedaton halaman 2: https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1?page=2
5. Bongkar Post: https://bacabongkarpost.com/banjir-rendam-bandar-lampung-akibat-hujan-deras-aktivitas-warga-terganggu/

Catatan presisi: kejadian banjir merupakan data nyata berbasis pemberitaan media, tetapi koordinat merupakan hasil geocoding/plotting lokasi jalan, landmark, kecamatan, atau area yang disebut dalam berita. Koordinat bukan GPS lapangan operasional.

## 3. Sumber Data Titik Rawan Banjir

`flood_risk_points` menggunakan jurnal:

```text
Agustri & Asbi (2020), Tingkat Risiko Bencana Banjir di Kota Bandar Lampung dan Upaya Pengurangannya Berbasis Penataan Ruang
```

Semua record memiliki:

| Atribut | Nilai |
|---|---|
| `data_status` | `nyata` |
| `source_type` | `jurnal` |
| `is_verified` | `true` |

Jurnal tersebut digunakan sebagai dasar literatur karena memuat kelas risiko banjir rendah, sedang, dan tinggi di Kota Bandar Lampung, termasuk risiko tinggi seluas 3.781,12 ha atau 20,58 persen dari luas kota, serta sebaran risiko tinggi pada kelurahan seperti Way Kandis, Sukabumi, Bumi Kedamaian, Rajabasa Jaya, Bumi Waras, Kangkung, Way Tataan, Gedong Pakuan, Pesawahan, Sumberejo Sejahtera, Kampung Baru, dan Kota Karang Raya.

Catatan presisi: titik rawan banjir merupakan representasi/centroid area kelurahan berdasarkan hasil kajian risiko banjir, bukan titik GPS lapangan dan bukan batas polygon risiko resmi.

## 4. Komposisi Status Data Spasial

Status data spasial final:

| Status | Jumlah | Keterangan |
|---|---:|---|
| Nyata | 24 | 12 kejadian berbasis berita dan 12 titik risiko berbasis jurnal |
| Simulasi | 10 | Titik evakuasi pengembangan untuk uji rekomendasi |
| Dummy | 6 | Pos alat berat dummy realistis |
| Perlu validasi operasional | 16 | Titik evakuasi dan pos alat berat yang belum memiliki sumber resmi |

Tidak ada lagi record `flood_events` atau `flood_risk_points` yang berstatus `dummy` atau `simulasi`.

## 5. Mekanisme Transparansi yang Sudah Ada

| Mekanisme | Fungsi |
|---|---|
| `source_type` | Menjelaskan jenis sumber, misalnya `berita`, `jurnal`, `admin_input`, atau `dummy` |
| `source_reference` | Menyimpan URL berita atau referensi literatur |
| `data_status` | Membedakan `nyata`, `simulasi`, dan `dummy` |
| `is_verified` | Menandai apakah data sudah diverifikasi terhadap sumber yang tersedia |
| Halaman Sumber Data & Validasi | Menampilkan status sumber dan verifikasi per data |
| Popup peta | Menampilkan sumber dan status data pada marker kejadian/risiko |

## 6. Validasi yang Dilakukan

| Validasi | Hasil |
|---|---|
| Backup sebelum update | `docs/progress-report/dataset/backups/before_real_source_update/` |
| Jumlah row `flood_events.csv` | 12 record |
| Jumlah row `flood_risk_points.csv` | 12 record |
| Jumlah row `real_source_flood_dataset.csv` | 24 record |
| Status `flood_events` | 12 `nyata`, 0 `dummy/simulasi` |
| Status `flood_risk_points` | 12 `nyata`, 0 `dummy/simulasi` |
| Source type `flood_events` | 12 `berita` |
| Source type `flood_risk_points` | 12 `jurnal` |
| Longitude/latitude | Berada pada rentang wilayah studi Bandar Lampung |
| Nominatim/OSM | 24 target dicek; 14 memperoleh kandidat lokasi, 10 dipertahankan sebagai plotting manual/representatif |
| Credential | Tidak ada data user, password, atau credential yang diekspor |

## 7. Batasan Penggunaan

Dataset kejadian dan risiko sudah berbasis sumber nyata, tetapi belum setara dengan data operasional resmi pemerintah. Koordinat berita adalah titik representatif dari lokasi yang disebutkan media. Koordinat jurnal adalah centroid/representasi kelurahan dari kajian risiko. Titik evakuasi dan pos alat berat tetap dataset pengembangan untuk demo rekomendasi resource.

Rute evakuasi yang ditampilkan juga merupakan rute referensi dari OSRM dan belum mempertimbangkan jalan tertutup, tinggi banjir aktual, lalu lintas, atau keputusan petugas lapangan.
