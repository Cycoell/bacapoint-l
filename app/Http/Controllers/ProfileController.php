<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Pastikan ini diimpor dengan benar

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function loadSection($section)
    {
        /** @var \App\Models\User $user */ 
        $user = Auth::user();
        
        // Validasi section yang diperbolehkan
        $allowedSections = ['account', 'transaksi', 'bookmark', 'point', 'riwayat-membaca']; // **TAMBAHKAN INI**
        if ($user->role === 'admin') {
            $allowedSections[] = 'grafik';
            $allowedSections[] = 'collection';
        }

        if (!in_array($section, $allowedSections)) {
            abort(404);
        }

        $data = compact('user'); // Data dasar untuk view

        // Logika untuk section 'collection'
        if ($section === 'collection' && $user->role === 'admin') {
            $books = DB::table('book_list')->orderBy('judul', 'asc')->get();
            $data['books'] = $books; 
        } 
        // Logika untuk section 'grafik'
        else if ($section === 'grafik' && $user->role === 'admin') {
            $genreData = DB::table('book_list')
                            ->select(DB::raw('genre, count(*) as total_books'))
                            ->groupBy('genre')
                            ->whereNotNull('genre') 
                            ->orderBy('total_books', 'desc')
                            ->get();

            $labels = $genreData->pluck('genre')->toArray();
            $counts = $genreData->pluck('total_books')->toArray();

            $data['genreLabels'] = json_encode($labels); 
            $data['genreCounts'] = json_encode($counts); 
        }
        // **TAMBAHKAN LOGIKA INI UNTUK SECTION 'RIWAYAT MEMBACA'**
        else if ($section === 'riwayat-membaca') {
            $readingHistory = DB::table('reading_history')
                                ->where('user_id', $user->id)
                                ->join('book_list', 'reading_history.book_id', '=', 'book_list.id')
                                ->select(
                                    'reading_history.*', // Ambil semua kolom dari riwayat
                                    'book_list.judul',
                                    'book_list.author',
                                    'book_list.cover_path',
                                    'book_list.total_pages',
                                    'book_list.pdf_path' // Mungkin dibutuhkan untuk link baca
                                )
                                ->orderBy('reading_history.last_read_at', 'desc') // Urutkan berdasarkan waktu terakhir membaca
                                ->get();

            $sedangMembaca = $readingHistory->filter(function($item) {
                // Sedang membaca: progres kurang dari 100% DAN belum ditandai selesai
                return $item->progress_percentage < 100 && $item->is_completed_for_points == 0;
            });

            $selesaiDibaca = $readingHistory->filter(function($item) {
                // Selesai dibaca: progres 100% ATAU sudah ditandai selesai
                return $item->progress_percentage >= 100 || $item->is_completed_for_points == 1;
            });

            $data['sedangMembaca'] = $sedangMembaca;
            $data['selesaiDibaca'] = $selesaiDibaca;
        }
        else if ($section === 'bookmark') {
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
                                ->orderBy('bookmarks.created_at', 'desc') // Urutkan berdasarkan waktu bookmark
                                ->get();
            $data['bookmarkedBooks'] = $bookmarkedBooks;
        }

        return view("profile.sections.{$section}", $data); 
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */ // Type hinting untuk Intelephense
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak terautentikasi.'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'nomor_telepon' => 'nullable|string|max:20',
        ]);

        try {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->jenis_kelamin = $request->input('jenis_kelamin');
            $user->tanggal_lahir = $request->input('tanggal_lahir');
            $user->nomor_telepon = $request->input('nomor_telepon');
            $user->save(); // Method save() akan tersedia karena $user adalah instance model User

            return response()->json(['success' => true, 'message' => 'Profil berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui profil: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update user profile photo.
     */
    public function updateProfilePhoto(Request $request)
    {
        /** @var \App\Models\User $user */ // Type hinting untuk Intelephense
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak terautentikasi.'], 401);
        }

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        try {
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::disk('public')->exists('uploads/profiles/' . $user->foto_profil)) {
                Storage::disk('public')->delete('uploads/profiles/' . $user->foto_profil);
            }

            // Simpan foto baru
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/profiles', $fileName, 'public');

            $user->foto_profil = $fileName;
            $user->save(); // Method save() akan tersedia

            return response()->json(['success' => true, 'message' => 'Foto profil berhasil diunggah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah foto profil: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        /** @var \App\Models\User $user */ // Type hinting untuk Intelephense
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak terautentikasi.'], 401);
        }

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini salah.');
                }
            }],
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        try {
            $user->password = Hash::make($request->input('new_password'));
            $user->save(); // Method save() akan tersedia

            return response()->json(['success' => true, 'message' => 'Password berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengubah password: ' . $e->getMessage()], 500);
        }
    }
}