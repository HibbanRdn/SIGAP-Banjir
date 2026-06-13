# DNS Rumahweb Guide untuk pindahtangan.my.id

Panduan ini menjelaskan cara mengarahkan domain Rumahweb ke VPS. Domain tetap dikelola di Rumahweb, sedangkan aplikasi berjalan di VPS.

Domain:

```text
pindahtangan.my.id
```

## A. Ambil IP Public VPS

Di panel VPS, cari IPv4 public. Contoh placeholder:

```text
IP_VPS
```

Jangan memakai IP private/internal.

## B. Login ke Rumahweb

1. Login ke Client Area Rumahweb.
2. Buka menu domain.
3. Pilih domain `pindahtangan.my.id`.
4. Buka DNS Management / DNS Zone Editor / Manage DNS.

Nama menu dapat berbeda tergantung tampilan Rumahweb, tetapi intinya adalah mengedit DNS zone domain.

## C. A Record Domain Utama

Tambahkan atau ubah record:

| Field | Isi |
|---|---|
| Type | A |
| Host/Name | `@` |
| Value/Address | `IP_VPS` |
| TTL | Default |

`@` berarti domain root:

```text
pindahtangan.my.id
```

## D. Record `www`

Gunakan salah satu opsi.

### Opsi 1: A Record

| Field | Isi |
|---|---|
| Type | A |
| Host/Name | `www` |
| Value/Address | `IP_VPS` |
| TTL | Default |

### Opsi 2: CNAME

| Field | Isi |
|---|---|
| Type | CNAME |
| Host/Name | `www` |
| Value/Target | `pindahtangan.my.id` |
| TTL | Default |

Pilih salah satu saja untuk `www`, jangan membuat A Record dan CNAME untuk host yang sama secara bersamaan.

## E. Tunggu Propagasi DNS

Propagasi bisa beberapa menit sampai beberapa jam. Cek dari lokal:

```bash
dig pindahtangan.my.id
dig www.pindahtangan.my.id
ping pindahtangan.my.id
```

Atau cek dari VPS:

```bash
dig pindahtangan.my.id
curl -I http://pindahtangan.my.id
```

Target:

```text
pindahtangan.my.id -> IP_VPS
www.pindahtangan.my.id -> IP_VPS
```

## F. Hubungan DNS, VPS, dan Nginx

Alurnya:

```text
Browser user -> DNS Rumahweb -> IP VPS -> Nginx -> Laravel public/index.php
```

DNS hanya mengarahkan domain ke IP VPS. DNS tidak mengupload project, tidak membuat database, dan tidak mengaktifkan SSL secara otomatis.

## G. Setelah DNS Aktif

Jalankan SSL:

```bash
certbot --nginx -d pindahtangan.my.id -d www.pindahtangan.my.id
```

Lalu cek:

```bash
curl -I https://pindahtangan.my.id
curl -I https://www.pindahtangan.my.id
```

Response ideal:

```text
HTTP/2 200
```

atau redirect dari `www` ke non-www jika Nginx/Certbot mengaturnya.

## H. Masalah Umum DNS

### `dig` masih menunjukkan IP lama

Penyebab:

1. DNS belum propagasi.
2. Record lama belum dihapus.
3. Nameserver domain tidak memakai DNS Rumahweb yang sedang diedit.

Cek nameserver:

```bash
dig NS pindahtangan.my.id
```

### Domain sudah mengarah, tetapi website 404/502

DNS sudah benar, masalah kemungkinan di Nginx, PHP-FPM, permission Laravel, atau `.env`.

Cek di VPS:

```bash
nginx -t
systemctl status nginx
systemctl status php8.3-fpm
tail -n 100 /var/log/nginx/error.log
tail -n 100 /var/www/sigap-banjir/storage/logs/laravel.log
```
