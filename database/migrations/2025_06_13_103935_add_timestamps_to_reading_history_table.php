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
            // Tambahkan kolom created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reading_history', function (Blueprint $table) {
            // Hapus kolom created_at dan updated_at saat rollback
            $table->dropTimestamps();
        });
    }
};