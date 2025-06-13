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

        return view('dashboard', compact('user', 'totalPoints', 'books'));
    }
}
