# Sistem Informasi Tugas Akhir (SI Tugas Akhir)

Sistem Informasi Tugas Akhir adalah aplikasi berbasis web yang dibangun menggunakan **Laravel 11** untuk mengelola dan memonitor seluruh proses skripsi mahasiswa, mulai dari pengajuan judul, proses bimbingan, hingga pendaftaran dan pelaksanaan sidang skripsi.

## Fitur Utama

Aplikasi ini memiliki beberapa peran (Role) dengan hak akses yang berbeda-beda:

### Mahasiswa

- Mengajukan judul skripsi beserta calon dosen pembimbing.
- Mengisi dan melaporkan _logbook_ bimbingan rutin kepada dosen pembimbing.
- Memantau persentase _progress_ skripsi.
- Mendaftar jadwal Sidang (Proposal maupun Sidang Akhir).
- Melihat riwayat revisi dan status kelulusan.

### Dosen Pembimbing & Penguji

- Menerima notifikasi dan me-review pengajuan bimbingan (ACC atau Revisi) beserta catatannya.
- Memantau _progress_ bimbingan mahasiswa wali (anak bimbingan).
- Mengubah _progress_ persentase mahasiswa secara manual jika diperlukan.
- Bertindak sebagai penguji dalam sidang dan memberikan penilaian.

### Kaprodi (Kepala Program Studi)

- Melakukan validasi (Setuju/Tolak) atas pengajuan judul skripsi mahasiswa.
- Memantau statistik mahasiswa skripsi melalui Dashboard Kaprodi.
- Mengidentifikasi mahasiswa yang "terkendala" (macet bimbingan lebih dari 30 hari).
- Mengirimkan pengingat (_reminder_) otomatis kepada dosen pembimbing terkait mahasiswa yang terkendala.

### Admin

- Mengelola data master sistem (Master Ruangan Sidang, dll).
- Melakukan penjadwalan sidang mahasiswa setelah mendapat persetujuan dosen.
- Menentukan dosen penguji 1, penguji 2, dan ruangan sidang dengan sistem pengecekan bentrok jadwal otomatis.

---

## Requirements

- PHP >= 8.2
- Composer
- Database MySQL / MariaDB
- Node.js & NPM (untuk kompilasi _asset_ frontend dengan Vite)

## Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di pc lokal Anda:

1. **Clone repositori ini**

    ```bash
    git clone https://github.com/achmdhisyam/si_tugas_akhir
    cd si_tugas_akhir
    ```

2. **Install dependensi PHP dan Node.js:**

    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment:**
   Salin file konfigurasi environment dan sesuaikan kredensial database Anda.

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Migrasi Database dan Seeder:**
   Aplikasi ini sudah dilengkapi dengan _Database Seeder_ terpusat yang otomatis membuatkan akun _dummy_ untuk Mahasiswa, Dosen, Kaprodi, dan Admin.

    ```bash
    php artisan migrate:fresh --seed
    ```

5. **Kompilasi Asset dan Jalankan Server:**
   Buka dua terminal terpisah untuk menjalankan _development server_:
    ```bash
    npm run dev
    ```
    ```bash
    php artisan serve
    ```

Aplikasi dapat diakses melalui browser di alamat `http://localhost:8000`.

## Kredensial Default (Seeder)

Setelah menjalankan `php artisan db:seed`, Anda dapat langsung masuk dengan menggunakan password `password` untuk semua akun di bawah ini:

- **Admin:** `admin@gmail.com`
- **Kaprodi:** `kaprodi@gmail.com`
- **Dosen:** `dosen@gmail.com` (atau `budi@gmail.com` sebagai Dosen Pembimbing dummy)
- **Mahasiswa:** `mahasiswa@gmail.com` (atau `andi@gmail.com` dan `siti@gmail.com` sebagai Mahasiswa dengan data logbook dummy)

---

## Teknologi yang Digunakan

- **Backend:** Laravel 11
- **Frontend:** Blade Templating, Tailwind CSS, Alpine.js
- **Database:** MySQL
