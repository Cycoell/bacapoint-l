<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BookController extends Controller
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Tampilkan form untuk membuat buku baru
     */
    public function create()
    {
        return view('admin.books.create');
    }

    /**
     * Simpan buku baru ke database
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'tahun' => 'nullable|integer|min:1000|max:' . (date('Y') + 5),
                'genre' => 'nullable|string|max:255',
                'cover_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'pdf_file' => 'required|mimes:pdf|max:50000',
                'point_value' => 'required|integer|min:1',
            ], [
                'cover_file.required' => 'File cover buku wajib diunggah.',
                'cover_file.image' => 'File cover harus berupa gambar (jpeg, png, jpg, gif).',
                'cover_file.max' => 'Ukuran file cover tidak boleh lebih dari 2MB.',
                'pdf_file.required' => 'File PDF buku wajib diunggah.',
                'pdf_file.mimes' => 'File buku harus berformat PDF.',
                'pdf_file.max' => 'Ukuran file PDF tidak boleh lebih dari 50MB.',
                'point_value.required' => 'Nilai poin buku wajib diisi.',
                'point_value.min' => 'Nilai poin minimal 1.',
            ]);

            // Jika Anda benar-benar tidak ingin menyentuh file, bahkan saat 'store',
            // Anda harus menghapus bagian uploadFile dari BookRepository::store()
            // atau memodifikasi BookRepository::store() untuk tidak mengunggah file.
            // Saat ini, store() masih akan mengunggah file.
            $this->bookRepository->store(
                $validated,
                $request->file('cover_file'),
                $request->file('pdf_file'),
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
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan buku: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus buku dari database saja. File fisik akan dipertahankan.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.'], 404);
        }

        try {
            $book->delete();

            return response()->json(['success' => true, 'message' => 'Buku berhasil dihapus dari database.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus buku dari database: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update buku yang sudah ada di database, tanpa menyentuh file fisik.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan.'], 404);
        }

        try {
            // Validasi input untuk update. File cover_file dan pdf_file masih divalidasi
            // jika ada di request, tapi tidak akan disimpan.
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'tahun' => 'nullable|integer|min:1000|max:' . (date('Y') + 5),
                'genre' => 'nullable|string|max:255',
                'cover_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Tetap validasi, tapi tidak akan disimpan
                'pdf_file' => 'nullable|mimes:pdf|max:50000', // Tetap validasi, tapi tidak akan disimpan
                'point_value' => 'required|integer|min:1',
            ], [
                'cover_file.image' => 'File cover harus berupa gambar (jpeg, png, jpg, gif).',
                'cover_file.max' => 'Ukuran file cover tidak boleh lebih dari 2MB.',
                'pdf_file.mimes' => 'File buku harus berformat PDF.',
                'pdf_file.max' => 'Ukuran file PDF tidak boleh lebih dari 50MB.',
                'point_value.required' => 'Nilai poin buku wajib diisi.',
                'point_value.min' => 'Nilai poin minimal 1.',
            ]);

            // Siapkan data untuk update, hanya kolom non-file
            $updateData = [
                'judul' => $validated['judul'],
                'author' => $validated['author'],
                'tahun' => $validated['tahun'],
                'genre' => $validated['genre'],
                'point_value' => $validated['point_value']
            ];

            // Tidak ada logika untuk menghapus file lama atau mengunggah file baru di sini.
            // `cover_path` dan `pdf_path` di database tidak akan diubah oleh proses ini.
            // `total_pages` juga tidak akan diperbarui secara otomatis dari PDF baru.

            // Update data buku di database
            $book->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Data buku berhasil diperbarui di database. File fisik tidak diubah.'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data buku: ' . $e->getMessage()
            ], 500);
        }
    }
}