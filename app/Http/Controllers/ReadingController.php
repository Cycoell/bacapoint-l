<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Impor model User
use App\Models\BookList; // Impor model BookList (asumsi Anda akan membuatnya) - Opsional jika hanya pakai DB::table()

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
            return redirect()->route('login', ['redirect' => url()->current()])->with('error', 'Anda harus login untuk membaca buku ini');
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
            'filePath' => asset($book->pdf_path),
            'bookId' => $id,
            'user' => null, // No user for public access
            'canEarnPoints' => false, // Cannot earn points without login
            'isLoggedIn' => false,
            'totalPages' => $book->total_pages // Pass total_pages
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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Prepare data for view
        $viewData = [
            'book' => $book,
            'judul' => $book->title ?? $book->judul ?? 'Judul Tidak Tersedia',
            'filePath' => asset($book->pdf_path),
            'bookId' => $id,
            'user' => $user,
            'canEarnPoints' => true, // Can earn points when logged in
            'isLoggedIn' => true,
            'totalPages' => $book->total_pages // Pass total_pages
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
     * Ini bisa dipertimbangkan untuk fetch total_pages jika tidak ingin pass lewat blade
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
            'is_authenticated' => Auth::check(),
            'total_pages' => $book->total_pages // Include total_pages in API response
        ]);
    }

    /**
     * Get chapter content (for AJAX pagination) - this method seems to be
     * designed for chapter-based content, not PDF page-based.
     * If you are using PDF.js, you might not need this for pagination.
     */
    public function getChapterContent(Request $request, $bookId)
    {
        // This method is for chapter-based reading, might not be needed for PDF.js.
        // It's still here from previous context but won't be used for PDF pagination.
        return response()->json(['success' => false, 'message' => 'Fungsionalitas chapter tidak diimplementasikan untuk pembacaan PDF.']);
    }

    /**
     * Save reading progress and award points.
     * This method will replace the old finish_reading.php logic.
     */
    public function saveProgress(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Login diperlukan.'], 401);
        }

        $request->validate([
            'book_id' => 'required|integer|exists:book_list,id',
            'current_page' => 'required|integer|min:1',
            'progress_percentage' => 'required|integer|min:0|max:100', // Persentase dari frontend
        ]);

        $bookId = $request->input('book_id');
        $currentPage = $request->input('current_page');
        $progressPercentage = $request->input('progress_percentage');

        $book = DB::table('book_list')->where('id', $bookId)->first();
        if (!$book || $book->total_pages === 0) {
            return response()->json(['success' => false, 'message' => 'Data buku tidak ditemukan atau total halaman belum diatur.'], 404);
        }

        // Dapatkan atau buat entri progres bacaan pengguna
        $readingProgress = DB::table('reading_history')
                                ->where('user_id', $user->id)
                                ->where('book_id', $bookId)
                                ->first();

        // Milestones poin dalam persentase
        $milestones = [25, 50, 75, 100]; 
        $pointsPerBook = $book->point_value; // Total poin untuk menyelesaikan buku ini

        $pointsToAwardThisSession = 0;
        // Ambil nilai dari DB, jika belum ada, default 0
        $currentProgressAwardedDB = $readingProgress ? $readingProgress->progress_percentage : 0;
        $pointsAwardedForBookDB = $readingProgress ? $readingProgress->points_awarded_for_book : 0;
        $isCompletedForPointsDB = $readingProgress ? $readingProgress->is_completed_for_points : false;
        $completedAtDB = $readingProgress ? $readingProgress->completed_at : null;

        // Inisialisasi variabel yang akan diupdate dan disimpan
        $newProgressPercentageToSave = max($currentProgressAwardedDB, $progressPercentage);
        $newPointsAwardedForBookCumulative = $pointsAwardedForBookDB;
        $newIsCompletedForPoints = $isCompletedForPointsDB;
        $newCompletedAt = $completedAtDB;


        // Logika pemberian poin berdasarkan milestone
        if (!$newIsCompletedForPoints) { // Hanya proses jika buku belum ditandai selesai untuk poin
            foreach ($milestones as $milestone) {
                // Jika persentase saat ini mencapai atau melewati milestone, DAN milestone tersebut belum pernah tercapai sebelumnya
                if ($progressPercentage >= $milestone && $currentProgressAwardedDB < $milestone) {
                    $expectedPointsAtMilestone = floor(($milestone / 100) * $pointsPerBook); 
                    
                    // Poin yang harus diberikan untuk mencapai milestone ini (dikurangi yang sudah diberikan sebelumnya)
                    $pointsToAddForThisMilestone = $expectedPointsAtMilestone - $newPointsAwardedForBookCumulative;
                    
                    if ($pointsToAddForThisMilestone > 0) {
                        $pointsToAwardThisSession += $pointsToAddForThisMilestone;
                        $newPointsAwardedForBookCumulative += $pointsToAddForThisMilestone; // Update total kumulatif
                    }
                }
            }
        }
        
        // Finalisasi: Jika 100% tercapai, pastikan sisa poin diberikan dan tandai selesai
        if ($progressPercentage >= 100 && !$newIsCompletedForPoints) {
            $finalRemainingPoints = $pointsPerBook - $newPointsAwardedForBookCumulative;
            if ($finalRemainingPoints > 0) {
                $pointsToAwardThisSession += $finalRemainingPoints;
                $newPointsAwardedForBookCumulative += $finalRemainingPoints; // Update total kumulatif
            }
            $newIsCompletedForPoints = true; // Tandai buku ini sebagai selesai untuk poin
            $newCompletedAt = now(); // Set waktu selesai
        }


        $updateData = [
            'last_page_read' => $currentPage,
            'progress_percentage' => $newProgressPercentageToSave,
            'last_read_at' => now(), // Selalu update waktu terakhir membaca
            'is_completed_for_points' => $newIsCompletedForPoints,
            'completed_at' => $newCompletedAt,
            'points_awarded_for_book' => $newPointsAwardedForBookCumulative, // Gunakan nilai kumulatif yang sudah dihitung
        ];


        if ($readingProgress) {
            // Update entri yang sudah ada
            DB::table('reading_history')
                ->where('id', $readingProgress->id)
                ->update($updateData);
        } else {
            // Buat entri baru
            $updateData['user_id'] = $user->id;
            $updateData['book_id'] = $bookId;
            $updateData['created_at'] = now(); // Set created_at hanya untuk entri baru
            DB::table('reading_history')->insert($updateData);
        }

        // Berikan poin ke pengguna jika ada poin baru yang didapatkan
        if ($pointsToAwardThisSession > 0) {
            $user->poin += $pointsToAwardThisSession;
            $user->save(); // Simpan perubahan poin pengguna
        }

        return response()->json([
            'success' => true,
            'message' => 'Progres disimpan. ' . ($pointsToAwardThisSession > 0 ? "Anda mendapatkan {$pointsToAwardThisSession} poin!" : ''),
            'current_page' => $currentPage,
            'total_pages' => $book->total_pages,
            'current_percentage' => $newProgressPercentageToSave, // Kirim persentase yang benar-benar disimpan
            'points_awarded_this_session' => $pointsToAwardThisSession,
            'user_total_points' => $user->poin,
            'last_awarded_percentage_on_server' => $newProgressPercentageToSave, // Sama dengan current_percentage yang disimpan
        ]);
    }

    /**
     * Get reading progress status for a user and book.
     * New method to serve `/api/reading-progress/status`
     */
    public function getProgressStatus(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Login diperlukan.'], 401);
        }

        $bookId = $request->input('book_id');
        // $userId = $request->input('user_id'); // userId sudah bisa didapat dari Auth::user()->id

        if (!is_numeric($bookId) || $bookId <= 0) {
            return response()->json(['success' => false, 'message' => 'ID buku tidak valid.'], 400);
        }

        $progress = DB::table('reading_history')
                        ->where('user_id', $user->id)
                        ->where('book_id', $bookId)
                        ->first();

        if ($progress) {
            return response()->json([
                'success' => true,
                'progress' => [
                    'last_page_read' => $progress->last_page_read,
                    'progress_percentage' => $progress->progress_percentage,
                    'points_awarded_for_book' => $progress->points_awarded_for_book,
                    'is_completed_for_points' => $progress->is_completed_for_points,
                    'completed_at' => $progress->completed_at, // Sertakan completed_at
                ]
            ]);
        } else {
            return response()->json(['success' => true, 'progress' => null, 'message' => 'Progres belum ada.']);
        }
    }
}