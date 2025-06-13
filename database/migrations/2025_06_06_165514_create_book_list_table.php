<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookListTable extends Migration
{
    public function up()
    {
        Schema::create('book_list', function (Blueprint $table) {
            $table->id(); // Auto-increment id
            $table->string('judul');
            $table->string('author');
            $table->integer('tahun')->nullable();
            $table->string('genre')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps(); // created_at dan updated_at
            $table->integer('total_pages')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_list');
    }
}
