<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReadingController extends Controller
{
    /**
     * Show reading page for public (without login) - limited access
     * Access route: /reading/{id}
     */
    public function showReadingPublic($id)
    {
        // Validasi ID
        if (!is_numeric($id) || $id <= 0) {
            return redirect('/')->with('error', 'ID buku tidak valid');
        }

        // Cek apakah user sudah login, jika ya redirect ke reading-auth
        if (Auth::check()) {
            return redirect("/reading-auth/{$id}");
        }

        // Batasi akses hanya untuk buku ID 1-4 (tanpa login)
        if ($id > 4) {
            return redirect('/login')->with('error', 'Anda harus login untuk membaca buku ini');
        }

        // Get book data
        $book = DB::table('book_list')->where('id', $id)->first();
        
        if (!$book) {
            return redirect('/')->with('error', 'Buku tidak ditemukan');
        }

        // Prepare data for view
        $viewData = [
            'book' => $book,
            'judul' => $book->title ?? $book->judul ?? 'Judul Tidak Tersedia',
            'filePath' => $book->file_path ?? $book->pdf_path ?? asset('books/' . $book->filename),
            'bookId' => $id,
            'user' => null, // No user for public access
            'canEarnPoints' => false, // Cannot earn points without login
            'isLoggedIn' => false
        ];

        // Menggunakan view 'reading' yang sama
        return view('reading', $viewData);
    }

    /**
     * Show reading page for authenticated users - full access
     * Access route: /reading-auth/{id}
     */
    public function showReading($id)
    {
        // Validasi ID
        if (!is_numeric($id) || $id <= 0) {
            return redirect('/dashboard')->with('error', 'ID buku tidak valid');
        }

        // Get book data
        $book = DB::table('book_list')->where('id', $id)->first();
        
        if (!$book) {
            return redirect('/dashboard')->with('error', 'Buku tidak ditemukan');
        }

        // Get authenticated user
        $user = Auth::user();

        // Prepare data for view
        $viewData = [
            'book' => $book,
            'judul' => $book->title ?? $book->judul ?? 'Judul Tidak Tersedia',
            'filePath' => $book->file_path ?? $book->pdf_path ?? asset('books/' . $book->filename),
            'bookId' => $id,
            'user' => $user,
            'canEarnPoints' => true, // Can earn points when logged in
            'isLoggedIn' => true
        ];

        // Menggunakan view 'reading' yang sama
        return view('reading', $viewData);
    }

    /**
     * Handle reading page with query parameter
     * Access route: /reading-auth?id={id}
     */
    public function showReadingFromQuery(Request $request)
    {
        $id = $request->get('id');
        
        // Validasi ID dari query parameter
        if (!$id || !is_numeric($id) || $id <= 0) {
            return redirect('/dashboard')->with('error', 'ID buku tidak valid');
        }

        // Redirect ke route dengan parameter yang benar
        return redirect("/reading-auth/{$id}");
    }

    /**
     * API endpoint untuk mendapatkan data buku (untuk AJAX calls)
     */
    public function getBookData($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'ID buku tidak valid'
            ], 400);
        }

        $book = DB::table('book_list')->where('id', $id)->first();
        
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        // Check access permission
        $hasAccess = true;
        if (!Auth::check() && $id > 4) {
            $hasAccess = false;
        }

        return response()->json([
            'success' => true,
            'data' => $book,
            'has_access' => $hasAccess,
            'is_authenticated' => Auth::check()
        ]);
    }

    /**
     * Get chapter content (for AJAX pagination)
     */
    public function getChapterContent(Request $request, $bookId)
    {
        $chapter = $request->get('chapter', 1);
        
        // Validasi
        if (!is_numeric($bookId) || $bookId <= 0) {
            return response()->json(['success' => false, 'message' => 'ID buku tidak valid'], 400);
        }

        if (!is_numeric($chapter) || $chapter <= 0) {
            return response()->json(['success' => false, 'message' => 'Chapter tidak valid'], 400);
        }

        // Check access permission
        if (!Auth::check() && $bookId > 4) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda harus login untuk membaca buku ini'
            ], 403);
        }

        // Get book and chapter content (adjust based on your database structure)
        $book = DB::table('book_list')->where('id', $bookId)->first();
        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        // Assuming you have a chapters table or content field
        // Adjust this based on your actual database structure
        $chapterContent = DB::table('book_chapters')
            ->where('book_id', $bookId)
            ->where('chapter_number', $chapter)
            ->first();

        if (!$chapterContent) {
            return response()->json(['success' => false, 'message' => 'Chapter tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'book' => $book,
                'chapter' => $chapterContent,
                'current_chapter' => $chapter
            ]
        ]);
    }
}