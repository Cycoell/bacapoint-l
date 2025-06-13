<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReadingHistorySeeder extends Seeder
{
    public function run()
    {
        DB::table('reading_history')->insert([
            // ['user_id' => 1, 'book_id' => 5, 'completed_at' => now(), 'point_given' => 1],
            // ['user_id' => 1, 'book_id' => 6, 'completed_at' => now(), 'point_given' => 1],
        ]);
    }
}
