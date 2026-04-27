<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Akun Mahasiswa',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'name' => 'Akun Dosen',
            'email' => 'dosen@example.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        User::create([
            'name' => 'Akun Kaprodi',
            'email' => 'kaprodi@example.com',
            'password' => bcrypt('password'),
            'role' => 'kaprodi',
        ]);

        User::create([
            'name' => 'Akun Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
