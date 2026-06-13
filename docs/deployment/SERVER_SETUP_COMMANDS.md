# Server Setup Commands Ubuntu untuk SIGAP Banjir

Dokumen ini berisi command setup VPS Ubuntu untuk Laravel + Nginx + PHP-FPM + PostgreSQL/PostGIS. Jalankan di VPS, bukan di komputer lokal.

Gunakan placeholder berikut:

```text
IP_VPS = IP public VPS
DOMAIN = pindahtangan.my.id
APP_DIR = /var/www/sigap-banjir
```

## A. Login SSH

```bash
ssh root@IP_VPS
```

Jika provider memberi user non-root:

```bash
ssh USERNAME@IP_VPS
```

Lalu gunakan `sudo` untuk command administratif.

## B. Update Server

```bash
apt update && apt upgrade -y
reboot
```

Login lagi setelah reboot:

```bash
ssh root@IP_VPS
```

## C. Install Dependency Dasar

```bash
apt install -y nginx git curl unzip software-properties-common ufw ca-certificates gnupg lsb-release rsync
```

## D. Install PHP dan Extension Laravel

Project membutuhkan PHP `^8.2`. Rekomendasi utama: PHP 8.3.

### Ubuntu 24.04 LTS

Ubuntu 24.04 biasanya menyediakan PHP 8.3 dari repository default:

```bash
apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common \
  php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl \
  php8.3-zip php8.3-pgsql php8.3-intl php8.3-gd php8.3-opcache
```

Cek:

```bash
php -v
php-fpm8.3 -v
systemctl status php8.3-fpm
```

### Ubuntu 22.04 LTS

Ubuntu 22.04 default PHP bisa terlalu lama untuk Laravel 12. Gunakan PPA Ondrej untuk PHP 8.3:

```bash
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common \
  php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl \
  php8.3-zip php8.3-pgsql php8.3-intl php8.3-gd php8.3-opcache
```

## E. Install Composer

```bash
cd /tmp
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
composer -V
```

Jika ingin verifikasi hash installer secara ketat, ikuti command terbaru dari:

```text
https://getcomposer.org/download/
```

## F. Install Node.js dan npm

Vite 7 lebih aman dengan Node.js LTS modern. Rekomendasi: Node.js 22 LTS dari NodeSource.

```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | bash -
apt install -y nodejs
node -v
npm -v
```

Alternatif jika ingin memakai package Ubuntu:

```bash
apt install -y nodejs npm
node -v
npm -v
```

Gunakan alternatif Ubuntu hanya jika versinya cukup baru untuk `npm run build`.

## G. Install PostgreSQL + PostGIS

Install PostgreSQL:

```bash
apt install -y postgresql postgresql-contrib postgis
```

Install paket PostGIS sesuai versi PostgreSQL aktif.

Cek versi PostgreSQL:

```bash
psql --version
```

Cari paket PostGIS:

```bash
apt-cache search "postgresql-.*-postgis"
```

Contoh Ubuntu 24.04 dengan PostgreSQL 16:

```bash
apt install -y postgresql-16-postgis-3 postgresql-16-postgis-3-scripts
```

Contoh Ubuntu 22.04 dengan PostgreSQL 14:

```bash
apt install -y postgresql-14-postgis-3 postgresql-14-postgis-3-scripts
```

Jika wildcard tersedia di environment:

```bash
apt install -y postgresql-*-postgis-*
```

Cek service:

```bash
systemctl enable postgresql
systemctl status postgresql
```

## H. Setup Firewall

```bash
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw enable
ufw status
```

Pastikan SSH tetap allowed sebelum `ufw enable`.

## I. Buat Folder Aplikasi

```bash
mkdir -p /var/www/sigap-banjir
chown -R $USER:$USER /var/www/sigap-banjir
```

Jika login sebagai root, `$USER` adalah root. Permission final tetap akan diubah ke `www-data` setelah dependency dan build selesai.

## J. Upload atau Clone Project

Opsi Git:

```bash
cd /var/www
git clone URL_REPOSITORY sigap-banjir
cd /var/www/sigap-banjir
```

Opsi upload ZIP:

```bash
cd /var/www/sigap-banjir
# Upload/rsync project dari lokal ke folder ini.
```

## K. Install Dependency Project

```bash
cd /var/www/sigap-banjir
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

Jika RAM kecil dan `npm run build` gagal karena memory, lakukan build di lokal lalu upload folder `public/build`.

## L. Setup `.env`

```bash
cd /var/www/sigap-banjir
cp .env.example .env
nano .env
php artisan key:generate
```

Gunakan isi dari `ENV_PRODUCTION_EXAMPLE.md` sebagai acuan.

## M. Storage Link dan Cache Laravel

```bash
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika ada perubahan `.env`, jalankan ulang:

```bash
php artisan config:clear
php artisan config:cache
```

## N. Permission Final

```bash
chown -R www-data:www-data /var/www/sigap-banjir
find /var/www/sigap-banjir -type f -exec chmod 644 {} \;
find /var/www/sigap-banjir -type d -exec chmod 755 {} \;
chmod -R ug+rwx /var/www/sigap-banjir/storage /var/www/sigap-banjir/bootstrap/cache
```

## O. Setup Nginx

Buat file:

```bash
nano /etc/nginx/sites-available/sigap-banjir
```

Isi:

```nginx
server {
    listen 80;
    server_name pindahtangan.my.id www.pindahtangan.my.id;

    root /var/www/sigap-banjir/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Sesuaikan socket:

```bash
ls /run/php/
```

Aktifkan:

```bash
ln -s /etc/nginx/sites-available/sigap-banjir /etc/nginx/sites-enabled/sigap-banjir
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx
```

## P. SSL Let’s Encrypt

Jalankan setelah DNS domain mengarah ke VPS:

```bash
apt install -y certbot python3-certbot-nginx
certbot --nginx -d pindahtangan.my.id -d www.pindahtangan.my.id
certbot renew --dry-run
```

## Q. Command Cek Cepat

```bash
php artisan about
php artisan route:list
php artisan migrate:status
php artisan tinker --execute="echo App\\Models\\FloodEvent::count();"
php artisan tinker --execute="echo App\\Models\\FloodRiskPoint::count();"
```
