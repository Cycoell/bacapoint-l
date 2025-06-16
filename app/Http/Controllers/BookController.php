<?php

namespace App\Http\Controllers;

use App\Models\Book; // Pastikan ini ada di atas
use App\Repositories\BookRepository; // Pastikan ini ada di atas
use Illuminate\Http\Request;

class BookController extends Controller
{
    private $bookRepository; // Kembali ke injeksi BookRepository

    public function __construct(BookRepository $bookRepository) // Injeksi BookRepository
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Tampilkan form untuk membuat buku baru
     */
    public function create()
    {
        // Rute ini sekarang dialihkan ke halaman profil admin/collection,
        // jadi view ini tidak lagi langsung diakses, tapi tetap ada di sini.
        return view('admin.books.create');
    }

    /**
     * Simpan buku baru ke database
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'tahun' => 'nullable|integer|min:1000|max:' . (date('Y') + 5),
                'genre' => 'nullable|string|max:255',
                'cover_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'pdf_file' => 'required|mimes:pdf|max:50000',
                'point_value' => 'required|integer|min:1',
                'total_pages' => 'required|integer|min:1', // TAMBAH INI
            ], [
                'cover_file.required' => 'File cover buku wajib diunggah.',
                'cover_file.image' => 'File cover harus berupa gambar (jpeg, png, jpg, gif).',
                'cover_file.max' => 'Ukuran file cover tidak boleh lebih dari 2MB.',
                'pdf_file.required' => 'File PDF buku wajib diunggah.',
                'pdf_file.mimes' => 'File buku harus berformat PDF.',
                'pdf_file.max' => 'Ukuran file PDF tidak boleh lebih dari 50MB.',
                'point_value.required' => 'Nilai poin buku wajib diisi.',
                'point_value.min' => 'Nilai poin minimal 1.',
                'total_pages.required' => 'Jumlah halaman buku wajib diisi.',
                'total_pages.integer' => 'Jumlah halaman harus berupa angka.',
                'total_pages.min' => 'Jumlah halaman minimal 1.',
            ]);

            // Panggil BookRepository untuk menyimpan buku
            $this->bookRepository->store(
                $validated,
                $request->file('cover_file'),
                $request->file('pdf_file'),
                $validated['total_pages'] // TERUSKAN JUMLAH HALAMAN DARI INPUT MANUAL
            );

            return response()->json([
                'success' => true,
                'message' => 'Buku baru berhasil ditambahkan!'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Pesan error dari FileUploadService (jika ada masalah selain validasi)
            // akan tetap ditangkap di sini.
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan buku: ' . $e->getMessage()
            ], 500);
        }
    }
}