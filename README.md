# SPK Kenaikan Gaji dan Bonus Karyawan

Aplikasi Sistem Pendukung Keputusan (SPK) untuk membantu proses evaluasi performa karyawan, rekomendasi bonus, dan rekomendasi kenaikan gaji pada PT Alvarel Technology Innovation.

## Deskripsi Project

SPK Kenaikan Gaji dan Bonus Karyawan adalah aplikasi berbasis web yang membantu pengelolaan evaluasi performa karyawan secara lebih terstruktur. Aplikasi ini mengelola data karyawan, divisi, KPI, realisasi kerja, peer assessment, leaderboard, serta rekomendasi bonus dan kenaikan gaji.

Hasil rekomendasi dari sistem digunakan sebagai bahan pertimbangan bagi pihak terkait, bukan sebagai keputusan mutlak yang menggantikan proses evaluasi manajemen.

## Latar Belakang

Proses evaluasi karyawan membutuhkan data yang konsisten dari berbagai sumber, seperti KPI umum, KPI divisi, realisasi kerja, dan penilaian antar karyawan. Pembobotan kriteria juga diperlukan agar setiap indikator memiliki tingkat kepentingan yang jelas. Aplikasi ini dibuat untuk membantu proses tersebut agar lebih rapi, terukur, dan mudah ditelusuri.

## Tujuan Aplikasi

- Mengelola data karyawan dan divisi.
- Mengelola KPI umum dan KPI divisi.
- Mencatat realisasi KPI berdasarkan periode.
- Mendukung pembobotan kriteria menggunakan AHP.
- Mengelola peer assessment antar karyawan.
- Menampilkan leaderboard performa.
- Menghasilkan rekomendasi bonus.
- Menghasilkan rekomendasi kenaikan gaji.

## Role Pengguna

| Role | Fungsi |
| --- | --- |
| Owner | Melihat ringkasan performa, leaderboard, dan hasil rekomendasi sebagai bahan pertimbangan. |
| HR | Mengelola data master, KPI, bobot AHP, aspek penilaian, approval realisasi, dan laporan rekomendasi. |
| Leader / Kepala Divisi | Mengelola distribusi target KPI divisi dan memantau realisasi anggota divisi. |
| Karyawan | Mengisi realisasi KPI sesuai akses, mengisi peer assessment, dan melihat informasi performa pribadi. |

## Fitur Utama

- Authentication dan dashboard multi-role.
- Manajemen divisi.
- Manajemen user/karyawan.
- KPI umum.
- KPI divisi.
- Pembobotan AHP.
- Realisasi KPI umum.
- Realisasi KPI divisi.
- Peer assessment.
- Leaderboard.
- Rekomendasi bonus.
- Rekomendasi kenaikan gaji.

## Alur Kerja Sistem

1. HR mengelola data master seperti divisi, user, KPI, dan aspek penilaian.
2. HR mengatur KPI dan bobot kriteria.
3. Leader atau karyawan mengisi realisasi sesuai akses masing-masing.
4. Karyawan mengisi peer assessment sesuai periode penilaian.
5. Sistem menghitung skor berdasarkan data KPI, bobot, dan penilaian.
6. Sistem menampilkan leaderboard dan rekomendasi.
7. Owner dan HR dapat melihat hasil sebagai bahan pertimbangan.

## Metode Yang Digunakan

Aplikasi ini menggunakan KPI sebagai indikator performa, AHP untuk pembobotan kriteria, peer assessment sebagai komponen penilaian, serta perhitungan skor untuk leaderboard dan rekomendasi. Kombinasi metode tersebut membantu proses evaluasi menjadi lebih terstruktur dan berbasis data.

## Tech Stack

- Laravel 12
- PHP 8.2+
- PostgreSQL
- Blade Template
- Vite
- Bootstrap/Sneat Assets
- Composer
- NPM

## Struktur Project

| Folder/File | Keterangan |
| --- | --- |
| `app/Http/Controllers` | Berisi controller untuk dashboard, master data, KPI, AHP, realisasi, leaderboard, dan rekomendasi. |
| `app/Models` | Berisi model Eloquent untuk representasi tabel utama aplikasi. |
| `database/migrations` | Berisi struktur tabel database. |
| `database/seeders` | Berisi data awal dan data persiapan periode. |
| `resources/views` | Berisi Blade view untuk layout, dashboard, tabel, form, dan laporan. |
| `routes/web.php` | Berisi definisi route aplikasi web. |
| `public/assets` | Berisi asset UI/template yang digunakan aplikasi. |

## Instalasi

```bash
git clone <repository-url>
cd spk-rekomendasi-kenaikan-gaji-dan-bonus-karyawan
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Konfigurasi Database

Aplikasi menggunakan PostgreSQL. Buat database lokal, lalu sesuaikan konfigurasi `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=spk_alvarel_portfolio
DB_USERNAME=postgres
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan konfigurasi PostgreSQL lokal. Jangan mencantumkan password asli di repository.

## Migrasi dan Seeder

Jalankan migrasi dan seeder utama:

```bash
php artisan migrate:fresh --seed
```

Seeder utama mengisi data demo awal yang dapat digunakan untuk mencoba fitur dashboard, KPI, leaderboard, dan rekomendasi.

Untuk menyiapkan periode Januari sampai Desember 2026:

```bash
php artisan db:seed --class=Period2026PreparationSeeder
```

`Period2026PreparationSeeder` menyiapkan data dasar Jan-Des 2026 untuk input manual, seperti KPI, bobot, aspek penilaian, dan distribusi target. Seeder ini tidak mengisi realisasi KPI, peer assessment, atau hasil rekomendasi final.

## Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php -S 127.0.0.1:9200 -t public
```

Jalankan Vite di terminal lain:

```bash
npm run dev
```

Akses aplikasi melalui:

```text
http://127.0.0.1:9200
```

Gunakan host yang konsisten. Hindari mencampur `localhost` dan `127.0.0.1` agar session dan cookie tetap stabil.

## Akun Demo

| Role | Username | Password |
| --- | --- | --- |
| Owner | owner | password |
| HR | hradmin | password |
| Leader Technical Support | saepul | password |
| Leader Chat Sales | diar | password |
| Leader Creative Design | cahyono | password |
| Karyawan | handika | password |
| Karyawan | devan | password |
| Karyawan | rizal | password |
| Karyawan | ariyani | password |
| Karyawan | akmal | password |
| Karyawan | ratna | password |
| Karyawan | laela | password |

## Screenshot Aplikasi

Screenshot aplikasi dapat disimpan pada folder `docs/screenshots/`.

![Login](docs/screenshots/01-auth/login-page.png)

![Dashboard Owner](docs/screenshots/02-dashboard/dashboard-owner.png)

![Dashboard HR](docs/screenshots/02-dashboard/dashboard-hr.png)

![Dashboard Leader](docs/screenshots/02-dashboard/dashboard-leader.png)

![Dashboard Karyawan](docs/screenshots/02-dashboard/dashboard-karyawan.png)

![KPI Umum](docs/screenshots/04-kpi-ahp/kpi-umum.png)

![AHP Global](docs/screenshots/04-kpi-ahp/ahp-global.png)

![Realisasi KPI Umum](docs/screenshots/05-realisasi/realisasi-kpi-umum.png)

![Leaderboard](docs/screenshots/07-rekomendasi/leaderboard.png)

![Rekomendasi Kenaikan Gaji](docs/screenshots/07-rekomendasi/rekomendasi-kenaikan-gaji.png)

## Catatan Pengembangan

- Aplikasi ini dikembangkan sebagai sistem pendukung keputusan berbasis web.
- Hasil rekomendasi digunakan sebagai bahan pertimbangan, bukan keputusan mutlak.
- Pengembangan dapat dilanjutkan dengan validasi data lebih lanjut, pengujian otomatis, dan peningkatan keamanan.
- Project ini juga digunakan sebagai bahan dokumentasi dan showcase pengembangan aplikasi berbasis Laravel.
