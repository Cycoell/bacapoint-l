<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Auto-increment id
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nomor_telepon', 20)->nullable();
            $table->string('foto_profil')->nullable();
            $table->timestamps(); // created_at dan updated_at
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->integer('poin')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
