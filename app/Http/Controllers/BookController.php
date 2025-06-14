<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Untuk upload file
use Smalot\PdfParser\Parser as PdfParser;
// Karena EncryptedPdfException tidak ada di library versi ini, kita tidak perlu mengimpornya.
// use Smalot\PdfParser\Exception\EncryptedPdfException; 

class BookController extends Controller
{
    /**
     * Show the form for creating a new book.
     * Accessible via /admin/books/create (admin-only)
     */
    public function create()
    {
        // Memastikan hanya admin yang bisa mengakses form ini
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses Dilarang.'); // Forbidden
        }

        // View ini akan dibuat di resources/views/admin/books/create.blade.php
        return view('admin.books.create'); 
    }

    /**
     * Store a newly created book in storage.
     * Accessible via POST to /admin/books (admin-only)
     */
    public function store(Request $request)
    {
        // Memastikan hanya admin yang bisa mengunggah buku
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Akses Dilarang. Hanya Admin yang dapat menambahkan buku.'], 403);
        }

        // 1. Validasi Data Input Form
        $request->validate([
            'judul' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'tahun' => 'nullable|integer|min:1000|max:' . (\date('Y') + 5), // Menggunakan \date()
            'genre' => 'nullable|string|max:255',
            'cover_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'pdf_file' => 'required|mimes:pdf|max:50000', // Max 50MB
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

        $coverStoredPath = null; // Path relatif ke storage/app/public setelah disimpan
        $pdfStoredPath = null;   // Path relatif ke storage/app/public setelah disimpan
        $totalPages = 0;
        
        try {
            // 2. Unggah File Cover
            $coverFile = $request->file('cover_file');
            // Menentukan nama file dan path penyimpanan di storage/app/public/assets/buku/covers/
            $coverFileName = 'covers/' . \time() . '_' . \uniqid() . '.' . $coverFile->getClientOriginalExtension(); // Menggunakan \time(), \uniqid()
            $coverStoredPath = Storage::disk('public')->putFileAs('assets/buku', $coverFile, $coverFileName);

            // 3. Unggah File PDF
            $pdfFile = $request->file('pdf_file');
            // Menentukan nama file dan path penyimpanan di storage/app/public/assets/buku/pdfs/
            $pdfFileName = 'pdfs/' . \time() . '_' . \uniqid() . '.' . $pdfFile->getClientOriginalExtension(); // Menggunakan \time(), \uniqid()
            $pdfStoredPath = Storage::disk('public')->putFileAs('assets/buku', $pdfFile, $pdfFileName);

            // 4. Baca Jumlah Halaman dari PDF yang diunggah
            $pdfParser = new PdfParser();
            // Mendapatkan konten file dari storage untuk di-parse
            $fileContent = Storage::disk('public')->get($pdfStoredPath);
            
            try {
                $pdf = $pdfParser->parseContent($fileContent);
                $totalPages = $pdf->getDetails()['Pages'];
            } catch (\Exception $e) { 
                // Tangani semua exception dari parsing PDF
                $errorMessageFromPdf = $e->getMessage();
                // Periksa pesan error untuk mengetahui apakah itu PDF terenkripsi/dilindungi
                if (\str_contains($errorMessageFromPdf, 'Secured pdf file') || \str_contains($errorMessageFromPdf, 'encrypted')) { // Menggunakan \str_contains
                    throw new \Exception('File PDF terenkripsi atau dilindungi dan tidak didukung.');
                } else {
                    throw new \Exception('Gagal memproses file PDF: ' . $errorMessageFromPdf);
                }
            }

            // 5. Simpan Data Buku ke Database
            DB::table('book_list')->insert([
                'judul' => $request->judul,
                'author' => $request->author,
                'tahun' => $request->tahun,
                'genre' => $request->genre,
                'cover_path' => 'assets/buku/' . $coverFileName, // Path yang akan disimpan di DB
                'pdf_path' => 'assets/buku/' . $pdfFileName,     // Path yang akan disimpan di DB
                'total_pages' => $totalPages,
                'point_value' => $request->point_value,
                'created_at' => \now(), // Menggunakan \now()
                'updated_at' => \now(), // Menggunakan \now()
            ]);

            return response()->json(['success' => true, 'message' => 'Buku baru berhasil ditambahkan!'], 201);

        } catch (\Exception $e) {
            // Menangkap semua exception yang terjadi selama proses (unggah, parsing, simpan DB)
            // Hapus file yang mungkin sudah terunggah jika terjadi error
            if ($coverStoredPath) {
                Storage::disk('public')->delete($coverStoredPath);
            }
            if ($pdfStoredPath) {
                Storage::disk('public')->delete($pdfStoredPath);
            }

            // Pesan error spesifik untuk respon JSON
            $errorMessage = $e->getMessage();
            $statusCode = 500; 

            // Jika error adalah karena PDF terenkripsi (dari throw di atas)
            if (\str_contains($errorMessage, 'File PDF terenkripsi')) { // Menggunakan \str_contains
                $statusCode = 400; // Bad Request untuk masalah klien
            }
            // Tambahkan pemeriksaan error spesifik lainnya jika diperlukan

            return response()->json(['success' => false, 'message' => 'Gagal menambahkan buku: ' . $errorMessage], $statusCode);
        }
    }
}