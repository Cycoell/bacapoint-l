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
        // Perubahan pada tabel reading_history
        Schema::table('reading_history', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->integer('last_page_read')->default(0)->after('book_id');
            $table->integer('progress_percentage')->default(0)->after('last_page_read');
            $table->integer('points_awarded_for_book')->default(0)->after('progress_percentage');

            // Ganti nama dan ubah kolom `point_given`
            // Ini akan memastikan kolom boolean dengan default false
            $table->boolean('point_given')->default(false)->change();
            $table->renameColumn('point_given', 'is_completed_for_points');

            // Tambahkan kolom `last_read_at` (opsional, untuk melacak waktu terakhir membaca)
            $table->timestamp('last_read_at')->nullable()->after('completed_at');
            // Ubah `completed_at` agar bisa null (jika buku belum selesai)
            $table->timestamp('completed_at')->nullable()->change();
        });

        // Perubahan pada tabel book_list
        Schema::table('book_list', function (Blueprint $table) {
            // Tambahkan kolom `point_value` jika belum ada
            // Anda bisa menentukan default value poin per buku
            $table->integer('point_value')->default(10)->after('pdf_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback perubahan pada tabel reading_history
        Schema::table('reading_history', function (Blueprint $table) {
            $table->dropColumn('last_page_read');
            $table->dropColumn('progress_percentage');
            $table->dropColumn('points_awarded_for_book');
            $table->dropColumn('last_read_at');
            
            // Mengembalikan kolom `point_given` ke kondisi semula jika perlu
            $table->renameColumn('is_completed_for_points', 'point_given');
            $table->boolean('point_given')->default(0)->change(); // Sesuaikan dengan default asli Anda
            // Mengembalikan `completed_at` ke tidak nullable jika asli tidak nullable
            // $table->timestamp('completed_at')->nullable(false)->change(); // Batalkan jika tidak nullable
        });

        // Rollback perubahan pada tabel book_list
        Schema::table('book_list', function (Blueprint $table) {
            $table->dropColumn('point_value');
        });
    }
};