<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();
        $user_id = $user->id;

        // Query untuk mengambil total poin dari tabel users
        $totalPoints = DB::table('users')->where('id', $user_id)->value('poin');

        // Ambil daftar buku untuk ditampilkan
        $books = DB::table('book_list')->get();

        $bookmarkedBooks = DB::table('bookmarks')
                                    ->where('user_id', $user->id)
                                    ->join('book_list', 'bookmarks.book_id', '=', 'book_list.id')
                                    ->select(
                                        'book_list.id',
                                        'book_list.judul',
                                        'book_list.author',
                                        'book_list.cover_path',
                                        'book_list.pdf_path' // Sertakan path PDF untuk link baca
                                    )
                                       ->orderBy('bookmarks.created_at', 'desc') // Urutkan berdasarkan waktu bookmark
                                       ->limit(5) // Tampilkan hanya 5 buku terbaru di dashboard
                                        ->get();

               return view('dashboard', compact('user', 'totalPoints', 'books', 'bookmarkedBooks')); // **TERUSKAN 'bookmarkedBooks' KE VIEW**
    }
}
