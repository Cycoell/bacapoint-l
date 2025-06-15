<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;

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
            // Validasi input
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

            // Gunakan repository untuk menyimpan buku
            $this->bookRepository->store(
                $validated,
                $request->file('cover_file'),
                $request->file('pdf_file')
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
            $statusCode = str_contains($e->getMessage(), 'PDF terenkripsi') ? 400 : 500;
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan buku: ' . $e->getMessage()
            ], $statusCode);
        }
    }
}
