<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // **TAMBAHKAN INI**

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsional: Jika Anda ingin memastikan tabel users bersih setiap kali seeder dijalankan
        // tanpa migrate:fresh, Anda bisa mengaktifkan baris ini.
        // DB::table('users')->truncate();

        DB::table('users')->insert([
            // User 'Zakky'
            [
                'name' => 'Zakky',
                'email' => 'actvtymhs@gmail.com',
                'password' => Hash::make('password'), // Menggunakan Hash::make
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
                'nomor_telepon' => '08123456789',
                'foto_profil' => null,
                'role' => 'user',
                'poin' => 0,
                'created_at' => now(), // **TAMBAHKAN INI**
                'updated_at' => now(), // **TAMBAHKAN INI**
            ],
            // ** ADMIN USER BARU (menggantikan admin@example.com) **
            [
                'name' => 'SuperAdmin',
                'email' => 'superadmin@bacapoint.com',
                'password' => Hash::make('password'),
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2000-01-01', // Contoh tanggal lahir untuk admin baru
                'nomor_telepon' => '08123456780', // Contoh nomor telepon
                'foto_profil' => null,
                'role' => 'admin',
                'poin' => 0, // Admin dimulai dengan 0 poin
                'created_at' => now(), // **TAMBAHKAN INI**
                'updated_at' => now(), // **TAMBAHKAN INI**
            ],
            // User 'Lucy' (ditambahkan kembali agar relevan dengan debugging sebelumnya)
            [
                'name' => 'Lucy',
                'email' => 'lucy@gmail.com',
                'password' => Hash::make('password'),
                'jenis_kelamin' => 'P', // Contoh
                'tanggal_lahir' => '1995-05-10', // Contoh
                'nomor_telepon' => '08765432100', // Contoh
                'foto_profil' => null,
                'role' => 'user',
                'poin' => 12, // Poin awal sesuai konteks sebelumnya
                'created_at' => now(), // **TAMBAHKAN INI**
                'updated_at' => now(), // **TAMBAHKAN INI**
            ],
        ]);
    }
}