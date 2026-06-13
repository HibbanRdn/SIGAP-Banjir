# REAL SOURCE DATA AUDIT

## 1. Tujuan Audit

Audit ini mendokumentasikan revisi dataset banjir SIGAP Banjir dari data awal demo menjadi data yang lebih berbasis sumber nyata. Fokus audit adalah dua tabel:

1. `flood_events`
2. `flood_risk_points`

Revisi dilakukan secara targeted dan idempotent melalui `FloodEventSeeder` dan `FloodRiskPointSeeder`. Tidak ada `migrate:fresh` dan tidak ada `db:seed` global yang dijalankan.

## 2. Backup Sebelum Update

Backup CSV dibuat sebelum update pada folder:

```text
docs/progress-report/dataset/backups/before_real_source_update/
```

File backup:

| File | Isi |
|---|---|
| `flood_events.csv` | Ekspor 8 record kejadian banjir sebelum revisi |
| `flood_risk_points.csv` | Ekspor 12 record titik rawan banjir sebelum revisi |

## 3. Perubahan Dataset

| Tabel | Sebelum | Sesudah |
|---|---|---|
| `flood_events` | 8 record simulasi | 12 record nyata berbasis berita |
| `flood_risk_points` | 12 record simulasi | 12 record nyata berbasis jurnal |

Record lama dengan `data_status` `dummy` atau `simulasi` pada dua tabel tersebut dihapus secara targeted sebelum record baru di-upsert. Dataset lain tidak diubah oleh proses ini.

## 4. Sumber Data Kejadian Banjir

Kejadian banjir menggunakan sumber berita:

| Sumber | URL | Fakta yang Dipakai |
|---|---|---|
| Detik/Antara | https://news.detik.com/berita/d-8445003/banjir-terjang-16-kecamatan-di-bandar-lampung-1-orang-meninggal | 16 kecamatan terdampak, satu warga meninggal, genangan lebih dari 1 meter di sejumlah titik |
| Rilis ID 34 titik | https://lampung.rilis.id/Breaking%20News/Berita/banjir-rendam-34-titik-di-bandar-lampung-1-warga-36kR?page=1 | 34 titik banjir 14 April 2026, curah hujan 75,2 mm, korban di Jalan Pandawa Garuntang |
| Rilis ID Kedaton | https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1 | Way Halim, Tanjung Senang, Kedaton, Kaliawi, Jalan Dokter Sutomo, genangan depan RSUD Abdul Moeloek |
| Rilis ID Kedaton halaman 2 | https://lampung.rilis.id/Peristiwa/Berita/bandar-lampung-dikepung-banjir-di-kedaton-kEg1?page=2 | Genangan sepanjang Jalan Pangeran Antasari hingga kawasan Transmart |
| Bongkar Post | https://bacabongkarpost.com/banjir-rendam-bandar-lampung-akibat-hujan-deras-aktivitas-warga-terganggu/ | Way Halim, Kedaton, Sukarame, Rajabasa, Teluk Betung, Kelapa Tiga, Kaliawi, Jalan Teuku Umar, Ki Maja, Antasari |

Semua record kejadian banjir diberi:

```text
source_type = berita
data_status = nyata
is_verified = true
status = surut
```

## 5. Sumber Data Titik Rawan Banjir

Titik rawan/risiko banjir menggunakan jurnal:

```text
Agustri & Asbi (2020), Tingkat Risiko Bencana Banjir di Kota Bandar Lampung dan Upaya Pengurangannya Berbasis Penataan Ruang
```

Fakta literatur yang digunakan:

1. Kota Bandar Lampung memiliki tiga kelas risiko banjir: rendah, sedang, dan tinggi.
2. Risiko tinggi memiliki luas 3.781,12 ha atau 20,58 persen dari luas kota.
3. Rajabasa Jaya disebut sebagai kelurahan dengan tingkat risiko tinggi terluas.
4. Tabel sebaran risiko tinggi memuat kelurahan Way Kandis, Sukabumi, Bumi Kedamaian, Rajabasa Jaya, Bumi Waras, Kangkung, Way Tataan, Gedong Pakuan, Pesawahan, Sumberejo Sejahtera, Kampung Baru, dan Kota Karang Raya.

Semua record titik rawan diberi:

```text
source_type = jurnal
data_status = nyata
is_verified = true
```

## 6. Catatan Presisi Koordinat

Kejadian banjir adalah data nyata berbasis berita. Titik koordinatnya adalah hasil geocoding/plotting lokasi jalan, landmark, kecamatan, atau area yang disebut dalam berita. Titik tersebut bukan GPS lapangan operasional.

Titik rawan banjir adalah data nyata berbasis jurnal. Koordinatnya merupakan representasi/centroid area kelurahan berdasarkan literatur risiko banjir, bukan batas polygon risiko resmi.

Validasi koordinat menggunakan Nominatim/OSM menghasilkan:

| Item | Jumlah |
|---|---:|
| Total target dicek | 24 |
| Query memperoleh kandidat lokasi OSM | 14 |
| Query tidak memperoleh lokasi spesifik | 10 |

Untuk 10 lokasi yang tidak ditemukan spesifik oleh Nominatim, koordinat awal dipertahankan sebagai plotting manual/representatif dan dicatat pada `geocoding_validation/nominatim_real_source_validation.json`.

## 7. Hasil Setelah Update

| Query | Hasil |
|---|---:|
| `FloodEvent::count()` | 12 |
| `FloodRiskPoint::count()` | 12 |
| `FloodEvent::where('data_status', 'nyata')->count()` | 12 |
| `FloodRiskPoint::where('data_status', 'nyata')->count()` | 12 |
| `FloodEvent::whereIn('data_status', ['dummy', 'simulasi'])->count()` | 0 |
| `FloodRiskPoint::whereIn('data_status', ['dummy', 'simulasi'])->count()` | 0 |

## 8. Dampak pada Peta

Pada `/peta`, marker kejadian banjir sekarang berasal dari data nyata berbasis berita. Popup marker menampilkan `Sumber: Berita` dan `Data: Nyata`.

Titik rawan banjir sekarang berasal dari literatur akademik. Popup marker menampilkan `Sumber: Jurnal` dan `Data: Nyata`.

Layer intensitas kecamatan membaca agregasi 12 record `flood_events` aktual. Distribusi saat audit:

| Kecamatan | Jumlah Kejadian |
|---|---:|
| Kedaton | 2 |
| Way Halim | 2 |
| Bumi Waras | 1 |
| Enggal | 1 |
| Kedamaian | 1 |
| Panjang | 1 |
| Rajabasa | 1 |
| Sukarame | 1 |
| Tanjung Karang Barat | 1 |
| Teluk Betung Selatan | 1 |

Dengan klasifikasi 0, 1-4, 5-7, dan 8+, seluruh kecamatan yang memiliki kejadian saat ini berada pada kategori rendah karena total per kecamatan masih kurang dari 5.

## 9. Batasan

Perubahan ini bukan membuat data palsu dan bukan mengklaim data operasional resmi. Perubahan ini mengganti dataset awal demo menjadi dataset nyata berbasis berita dan jurnal yang disediakan user, dengan catatan presisi spasial yang jelas.
