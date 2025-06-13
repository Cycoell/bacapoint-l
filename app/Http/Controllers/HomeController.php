<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Mengambil data dari tabel book_list dengan pengecekan
            $books = DB::table('book_list')
                    ->whereNotNull('id')
                    ->whereNotNull('judul')
                    ->orderBy('id', 'asc')
                    ->get();
        
            // Log untuk debugging
            Log::info('Books data retrieved', ['count' => $books->count()]);
            
            // Mengirim data ke view
            return view('home', compact('books'));
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error in HomeController@index: ' . $e->getMessage());
            
            // Return view dengan data kosong jika ada error
            return view('home', ['books' => collect()]);
        }
    }
    
    public function allBooks()
    {
        try {
            $books = DB::table('book_list')
                    ->whereNotNull('id')
                    ->whereNotNull('judul')
                    ->orderBy('id', 'asc')
                    ->get();
            
            return view('all-books', compact('books'));
            
        } catch (\Exception $e) {
            Log::error('Error in HomeController@allBooks: ' . $e->getMessage());
            return view('all-books', ['books' => collect()]);
        }
    }
}