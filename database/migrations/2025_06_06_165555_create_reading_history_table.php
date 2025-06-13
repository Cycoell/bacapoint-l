<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateReadingHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('reading_history', function (Blueprint $table) {
            $table->id(); // Auto-increment id
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamp('completed_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('point_given')->default(0);

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('book_list')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reading_history');
    }
}
