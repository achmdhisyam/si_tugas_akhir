<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Skripsi;
use App\Models\Bimbingan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun-akun Master Utama
        User::create([
            'name' => 'Akun Mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'name' => 'Akun Dosen',
            'email' => 'dosen@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        User::create([
            'name' => 'Akun Kaprodi',
            'email' => 'kaprodi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kaprodi',
        ]);

        User::create([
            'name' => 'Akun Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Akun Dosen Pembimbing Dummy
        $dosen = User::create([
            'name' => 'Dr. Budi Santoso, M.Kom',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        // 3. Mahasiswa Dummy 1 (Andi Pratama)
        $mhs1 = User::create([
            'name' => 'Andi Pratama',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $skripsi1 = Skripsi::create([
            'user_id' => $mhs1->id,
            'dosen_id' => $dosen->id,
            'judul' => 'Sistem Pakar Diagnosa Penyakit Tanaman',
            'status' => 'disetujui',
            'progress' => 30,
        ]);

        Bimbingan::create([
            'skripsi_id' => $skripsi1->id,
            'tanggal' => Carbon::now()->subDays(45)->toDateString(),
            'catatan' => 'Bimbingan Bab 1 - Revisi Latar Belakang',
            'catatan_dosen' => 'Latar belakang kurang kuat. Tambahkan data riset terbaru.',
            'status' => 'direvisi',
            'created_at' => Carbon::now()->subDays(45),
            'updated_at' => Carbon::now()->subDays(45),
        ]);

        // 4. Mahasiswa Dummy 2 (Siti Rahma)
        $mhs2 = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $skripsi2 = Skripsi::create([
            'user_id' => $mhs2->id,
            'dosen_id' => $dosen->id,
            'judul' => 'Analisis Sentimen Menggunakan NLP',
            'status' => 'disetujui',
            'progress' => 80,
        ]);

        Bimbingan::create([
            'skripsi_id' => $skripsi2->id,
            'tanggal' => Carbon::now()->subDays(60)->toDateString(),
            'catatan' => 'Bimbingan Bab 4 - Hasil Pengujian',
            'catatan_dosen' => 'Hasil pengujian sudah bagus, lanjut Bab 5.',
            'status' => 'disetujui',
            'created_at' => Carbon::now()->subDays(60),
            'updated_at' => Carbon::now()->subDays(60),
        ]);
    }
}
