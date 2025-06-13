<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'zakky',
                'email' => 'actvtymhs@gmail.com',
                'password' => bcrypt('password'), // Pastikan password di-hash
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
                'nomor_telepon' => '08123456789',
                'foto_profil' => null, // Jika tidak ada, bisa diisi null
                'role' => 'user',
                'poin' => 0,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Pastikan password di-hash
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2025-01-01',
                'nomor_telepon' => null, // Jika tidak ada, bisa diisi null
                'foto_profil' => null, // Jika tidak ada, bisa diisi null
                'role' => 'admin',
                'poin' => 0,
            ],
        ]);
    }
}
