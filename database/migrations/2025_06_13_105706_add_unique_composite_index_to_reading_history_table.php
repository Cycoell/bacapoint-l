<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reading_history', function (Blueprint $table) {
            // Tambahkan indeks unik komposit pada user_id dan book_id.
            // Ini akan memastikan setiap user hanya memiliki satu entri progres per buku,
            // dan secara signifikan mempercepat pencarian.
            $table->unique(['user_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reading_history', function (Blueprint $table) {
            // Hapus indeks saat rollback
            $table->dropUnique(['user_id', 'book_id']);
        });
    }
};