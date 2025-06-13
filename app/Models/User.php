<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jenis_kelamin', // Ditambahkan: agar bisa di mass assign
        'tanggal_lahir', // Ditambahkan: agar bisa di mass assign
        'nomor_telepon', // Ditambahkan: agar bisa di mass assign
        'foto_profil',   // Ditambahkan: agar bisa di mass assign (jika digunakan)
        'poin',          // Ditambahkan: agar bisa di mass assign (jika digunakan)
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}