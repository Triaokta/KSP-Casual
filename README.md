<p align="center">
  <a href="https://404notfound.fun" target="_blank">
    <img src="https://avatars.githubusercontent.com/u/87377917?s=200&v=4" width="200" alt="404NFID Logo">
  </a>
</p>


## KSP-LMS 🚀

Sebuah aplikasi web yang dirancang untuk mengelola karyawan casual, mulai dari input karyawan, import karyawan dengan menggunakan excel, export karyawan dengan tipe xlsx, export karyawan dengan tipe pdf. Selain itu, aplikasi web ini juga menyediakan fitur absensi perhari, sehingga memudahkan admin ketika menghitung gaji karyawan.

## ✨ Fitur Andalan

- **🔑 Autentikasi & Hak Akses**  
  - Login/logout gampang
- **📊 Dashboard Super Informatif**  
  - Statistik jumlah karyawan casual secara keseluruhan, jumlah karyawan aktif, dan jumlah karyawan nonaktif
  - Jumlah karyawan dapat difilter berdasarkan departemen
- **👥 Manajemen Karyawan**  
  - Tambah, edit, hapus, ubah status, dan lihat detail karyawan casual
  - Pencarian cepat berdasarkan nama atau departemen
  - Untuk menambahkan karyawan dapat dilakukan import xlsx yang templatenya sudah disediakan dan dapat diunduh serta diedit
  - Data karyawan aktif dapat diunduh dengan format xlsx ataupun pdf
- **📅 Absensi**  
  - Absen dapat ditambahkan perharinya 
  - Jika karyawan nonaktif maka namanya tidak akan ada di dalam daftar absensi
  - Tidak dapat melakukan absen 2x dalam 1 hari yang sama
  - Data absensi dapat diunduh secara keseluruhan karyawan
  - Data absensi dapat diunduh untuk karyawan yang dipilih untuk diunduh
- **🛠️ Pengaturan Profil**  
  - Ubah nama, email, dan password
  - Ganti foto profil

## ⚡ Instalasi Super Cepat
### 🔥 Persyaratan
- **PHP > 8.2.0**
- **MySQL**

### 🚀 Setup dengan Makefile (Paling Gampang)
1. Clone repository ini, lalu jalankan:
   ```sh
   make setup
   ```
2. Buat database baru di MySQL dan sesuaikan `.env`
3. Jalankan setup database:
   ```sh
   make setup-db
   ```
4. (Opsional) Tambahkan data dummy:
   ```sh
   make setup-dummy
   ```
5. Jalankan aplikasi:
   ```sh
   make run
   ```

### 🛠️ Setup Manual (Kalau Mau Cara Lama)
1. Clone repository ini, lalu jalankan:
   ```sh
   composer install
   ```
2. Salin konfigurasi default:
   ```sh
   cp .env.example .env
   ```
3. Sesuaikan `.env` dengan database Anda.
4. Generate application key:
   ```sh
   php artisan key:generate
   ```
5. Buat symbolic link untuk storage:
   ```sh
   php artisan storage:link
   ```
6. Jalankan migrasi database:
   ```sh
   php artisan migrate
   ```
7. Tambahkan akun administrator:
   ```sh
   php artisan db:seed --class=UserSeeder
   ```
8. Tambahkan konfigurasi awal:
   ```sh
   php artisan db:seed --class=ConfigSeeder
   ```
9. (Opsional) Tambahkan data dummy:
   ```sh
   php artisan db:seed
   ```
10. Jalankan aplikasi:
   ```sh
   php artisan serve
   ```


## 🔑 Login
Gunakan akun berikut buat masuk:

| Email            | Kata Sandi |
|------------------|------------|
| admin@ksp.co.id | admin      |

## 🌍 Pengaturan Bahasa
Aplikasi ini support Bahasa Indonesia & Inggris. Ubah `config/app.php` bagian `locale` jadi `id` atau `en`.