<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skripsi;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyMacetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari atau buat dosen pembimbing
        $dosen = User::updateOrCreate(
            ['email' => 'budi@kampus.ac.id'],
            [
                'name' => 'Dr. Budi Santoso, M.Kom',
                'password' => Hash::make('password'),
                'role' => 'dosen',
            ]
        );

        // Buat Mahasiswa Macet 1
        $mhs1 = User::updateOrCreate(
            ['email' => 'andi.macet@mahasiswa.ac.id'],
            [
                'name' => 'Andi Macet',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );

        $skripsi1 = Skripsi::updateOrCreate(
            ['user_id' => $mhs1->id],
            [
                'dosen_id' => $dosen->id,
                'judul' => 'Sistem Pakar Diagnosa Penyakit Tanaman',
                'status' => 'disetujui',
                'progress' => 30,
            ]
        );

        Bimbingan::updateOrCreate(
            [
                'skripsi_id' => $skripsi1->id,
                'tanggal' => Carbon::now()->subDays(45)->toDateString(),
            ],
            [
                'catatan' => 'Bimbingan Bab 1 - Revisi Latar Belakang',
                'catatan_dosen' => 'Latar belakang kurang kuat. Tambahkan data riset terbaru.',
                'status' => 'direvisi',
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => Carbon::now()->subDays(45),
            ]
        );

        // Buat Mahasiswa Macet 2
        $mhs2 = User::updateOrCreate(
            ['email' => 'siti.stuck@mahasiswa.ac.id'],
            [
                'name' => 'Siti Stuck',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );

        $skripsi2 = Skripsi::updateOrCreate(
            ['user_id' => $mhs2->id],
            [
                'dosen_id' => $dosen->id,
                'judul' => 'Analisis Sentimen Menggunakan NLP',
                'status' => 'disetujui',
                'progress' => 80,
            ]
        );

        Bimbingan::updateOrCreate(
            [
                'skripsi_id' => $skripsi2->id,
                'tanggal' => Carbon::now()->subDays(60)->toDateString(),
            ],
            [
                'catatan' => 'Bimbingan Bab 4 - Hasil Pengujian',
                'catatan_dosen' => 'Hasil pengujian sudah bagus, lanjut Bab 5.',
                'status' => 'disetujui',
                'created_at' => Carbon::now()->subDays(60),
                'updated_at' => Carbon::now()->subDays(60),
            ]
        );

        $this->command->info('Dummy data mahasiswa macet berhasil dibuat!');
    }
}
