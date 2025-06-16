<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk DB::table()

class BookmarkController extends Controller
{
    /**
     * Toggle a book's bookmark status for the authenticated user.
     * Accessible via POST to /api/bookmarks/toggle
     */
    public function toggle(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Autentikasi diperlukan.'], 401);
        }

        $request->validate([
            'book_id' => 'required|integer|exists:book_list,id',
        ]);

        $bookId = $request->input('book_id');

        // Periksa apakah buku sudah di-bookmark
        $existingBookmark = DB::table('bookmarks')
                                ->where('user_id', $user->id)
                                ->where('book_id', $bookId)
                                ->first();

        if ($existingBookmark) {
            // Jika sudah di-bookmark, hapus bookmark
            DB::table('bookmarks')
                ->where('user_id', $user->id)
                ->where('book_id', $bookId)
                ->delete();
            $message = 'Buku berhasil dihapus dari bookmark.';
            $isBookmarked = false;
        } else {
            // Jika belum di-bookmark, tambahkan bookmark
            DB::table('bookmarks')->insert([
                'user_id' => $user->id,
                'book_id' => $bookId,
                'created_at' => \now(),
                'updated_at' => \now(),
            ]);
            $message = 'Buku berhasil ditambahkan ke bookmark.';
            $isBookmarked = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_bookmarked' => $isBookmarked,
        ]);
    }

    /**
     * Check if a book is bookmarked by the authenticated user.
     * Accessible via GET to /api/bookmarks/check/{book_id}
     */
    public function check(Request $request, $bookId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Autentikasi diperlukan.'], 401);
        }

        if (!is_numeric($bookId) || $bookId <= 0) {
            return response()->json(['success' => false, 'message' => 'ID buku tidak valid.'], 400);
        }

        $isBookmarked = DB::table('bookmarks')
                            ->where('user_id', $user->id)
                            ->where('book_id', $bookId)
                            ->exists();

        return response()->json([
            'success' => true,
            'is_bookmarked' => $isBookmarked,
        ]);
    }

    /**
     * Tampilkan halaman terpisah untuk semua buku favorit (bookmark) pengguna yang login.
     */
    public function showAllBookmarks()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            // Jika tidak login, arahkan ke halaman login
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat buku favorit Anda.');
        }

        $bookmarkedBooks = DB::table('bookmarks')
                                ->where('user_id', $user->id)
                                ->join('book_list', 'bookmarks.book_id', '=', 'book_list.id')
                                ->select(
                                    'book_list.id',
                                    'book_list.judul',
                                    'book_list.author',
                                    'book_list.tahun',
                                    'book_list.genre',
                                    'book_list.cover_path',
                                    'book_list.pdf_path'
                                )
                                ->orderBy('bookmarks.created_at', 'desc')
                                ->get();

        return view('all-bookmarks', compact('bookmarkedBooks')); // Mengirim ke view baru
    }
}