# FINAL SUBMISSION CHECKLIST SIGAP BANJIR

## 1. File yang Diunggah ke Google Drive

| File/Folder | Status | Catatan |
|---|---|---|
| `LAPORAN_PROGRESS_SIGAP_BANJIR_FINAL.docx` | Wajib | File laporan utama untuk dosen |
| `LAPORAN_PROGRESS_SIGAP_BANJIR_FINAL_PREVIEW.pdf` | Opsional | Gunakan jika PDF preview berhasil dibuat atau dikonversi manual |
| Video progres | Wajib | Durasi target 5-8 menit mengikuti `VIDEO_SCRIPT_PROGRESS.md` |
| Folder `dataset/` | Disarankan | Berisi CSV final, data dictionary, provenance, summary, dan audit spasial |
| Folder `screenshots/` | Opsional | Berisi screenshot UI aktual berwarna |
| `VIDEO_SCRIPT_PROGRESS.md` | Opsional | Dapat diunggah sebagai lampiran persiapan presentasi |

## 2. Bagian yang Masih Harus Diisi Manual

| Bagian | Lokasi |
|---|---|
| Nama anggota kelompok | Cover laporan DOCX |
| NPM anggota kelompok | Cover laporan DOCX |
| Link Google Drive | Lampiran laporan |
| Link video jika video dipisahkan dari folder laporan | Lampiran laporan atau deskripsi Drive |

## 3. Checklist Sebelum Mengirim ke Dosen

| No | Pemeriksaan | Status |
|---:|---|---|
| 1 | Buka file DOCX dan cek cover, BAB, tabel, gambar, dan lampiran | [ ] |
| 2 | Isi nama anggota kelompok dan NPM | [ ] |
| 3 | Konversi DOCX ke PDF jika dosen meminta PDF | [ ] |
| 4 | Pastikan screenshot UI di laporan tetap berwarna dan teks/tabel tetap formal hitam putih | [ ] |
| 5 | Rekam video mengikuti `VIDEO_SCRIPT_PROGRESS.md` | [ ] |
| 6 | Cek video dapat diputar dari awal sampai akhir | [ ] |
| 7 | Upload DOCX/PDF, video, dan dataset ke Google Drive | [ ] |
| 8 | Atur sharing Google Drive menjadi dapat diakses dosen | [ ] |
| 9 | Tempelkan link Drive pada laporan atau pesan pengumpulan | [ ] |
| 10 | Kirim link ke dosen sebelum deadline | [ ] |

## 4. Catatan Transparansi Data

Saat menjelaskan dataset, gunakan narasi berikut:

```text
Dataset yang digunakan pada tahap progres merupakan dataset pengembangan berbasis lokasi nyata di wilayah Kota Bandar Lampung. Koordinat dan nama area telah diaudit menggunakan peta publik OpenStreetMap/Nominatim, sedangkan status operasional banjir, titik evakuasi, dan pos alat berat masih digunakan sebagai data simulasi/dummy untuk pengujian fungsi spasial aplikasi. Dataset ini belum diklaim sebagai data operasional resmi pemerintah.
```

## 5. Pengingat Rekaman Video

1. Jangan mengklaim data simulasi/dummy sebagai data resmi.
2. Sebutkan status final data spasial: 30 simulasi, 6 dummy, 0 nyata, dan 36 perlu validasi operasional.
3. Tunjukkan halaman `/admin/data-sources` untuk membuktikan transparansi dataset.
4. Tunjukkan `/peta`, dashboard, detail kejadian, dan rute referensi.
5. Jelaskan bahwa rute OSRM adalah referensi, bukan rute resmi kebencanaan.
