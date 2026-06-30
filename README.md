# SPK Kenaikan Gaji dan Bonus Karyawan

Aplikasi Sistem Pendukung Keputusan (SPK) untuk rekomendasi kenaikan gaji dan bonus karyawan pada PT Alvarel Technology Innovation.

Project ini dibuat sebagai aplikasi portfolio berbasis Laravel. Sistem mengelola data karyawan, KPI umum, KPI divisi, penilaian antar karyawan, pembobotan AHP, leaderboard, serta rekomendasi bonus dan kenaikan gaji.

## Tujuan Aplikasi

Aplikasi ini membantu owner, HR, leader, dan karyawan dalam proses evaluasi performa berbasis data. Penilaian dilakukan melalui kombinasi:

- KPI umum
- KPI divisi
- Peer assessment
- Bobot AHP global
- Realisasi target bulanan
- Hasil rekomendasi bonus dan kenaikan gaji

## Fitur Utama

- Login dan logout multi-role
- Dashboard owner
- Dashboard HR
- Dashboard leader
- Dashboard karyawan
- Manajemen divisi
- Manajemen user dan karyawan
- Manajemen KPI umum
- Pembobotan KPI umum dengan AHP
- Realisasi KPI umum
- Manajemen KPI divisi
- Pembobotan KPI divisi dengan AHP
- Distribusi target KPI divisi
- Realisasi KPI divisi kuantitatif
- Realisasi KPI divisi kualitatif
- Realisasi KPI divisi response
- Realisasi KPI divisi persentase
- Aspek penilaian karyawan
- Peer assessment
- Leaderboard global, karyawan, dan divisi
- Rekomendasi bonus
- Rekomendasi kenaikan gaji

## Tech Stack

- PHP 8.2+
- Laravel 12
- PostgreSQL
- Blade Template
- Vite
- Bootstrap/Sneat assets
- Composer
- NPM

## Periode Demo

Data demo portfolio difokuskan pada:

```text
Agustus 2025
```

Gunakan periode tersebut saat mengecek dashboard, realisasi KPI, leaderboard, rekomendasi bonus, dan rekomendasi kenaikan gaji.

## Instalasi

Clone repository:

```bash
git clone <repository-url>
cd spk_rekomendasi_kenaikan_gaji_dan_bonus-main
```

Install dependency backend:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Salin file environment:

```bash
cp .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

## Konfigurasi PostgreSQL

Buat database PostgreSQL lokal, misalnya:

```text
spk_alvarel_portfolio
```

Atur `.env`:

```env
APP_NAME="SPK Alvarel Portfolio"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:9200

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=spk_alvarel_portfolio
DB_USERNAME=postgres
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi PostgreSQL lokal.

## Migrasi dan Seeder

Reset database dan isi data demo:

```bash
php artisan migrate:fresh --seed
```

Bersihkan cache konfigurasi jika diperlukan:

```bash
php artisan optimize:clear
```

## Menjalankan Aplikasi

Jalankan Laravel server:

```bash
php -S 127.0.0.1:9200 -t public
```

Jalankan Vite di terminal lain:

```bash
npm run dev
```

Buka aplikasi:

```text
http://127.0.0.1:9200
```

Gunakan host yang konsisten. Jangan mencampur `localhost` dan `127.0.0.1` agar session/cookie tidak bermasalah.

## Akun Demo

Semua akun demo menggunakan password:

```text
password
```

| Role | Username |
| --- | --- |
| Owner | owner |
| HR | hradmin |
| Leader Technical Support | saepul |
| Leader Chat Sales | diar |
| Leader Creatif Desain | cahyono |
| Karyawan | handika |
| Karyawan | devan |
| Karyawan | rizal |
| Karyawan | ariyani |
| Karyawan | akmal |
| Karyawan | ratna |
| Karyawan | laela |

## Halaman Yang Disarankan Untuk Demo

Gunakan periode Agustus 2025 saat mengecek halaman berikut:

- Dashboard HR
- Dashboard owner
- Dashboard leader
- Dashboard karyawan
- Data Divisi
- Data User
- KPI Umum
- Bobot KPI Umum
- Realisasi KPI Umum
- KPI Divisi
- Bobot KPI Divisi
- Distribusi Target KPI Divisi
- Realisasi KPI Divisi
- Aspek Penilaian
- Peer Assessment
- Leaderboard
- Rekomendasi Bonus
- Rekomendasi Kenaikan Gaji

## Catatan Portfolio

Project ini adalah aplikasi skripsi/portfolio lama yang sedang dirapikan secara bertahap. Refactor dilakukan ringan dan aman tanpa mengubah formula SPK/AHP utama, struktur database besar, atau flow role/access aplikasi.
