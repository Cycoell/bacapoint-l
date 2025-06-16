<?php

namespace App\Repositories;

use App\Models\Book; 
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;

class BookRepository
{
    private $fileUploadService;
    
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Simpan buku baru ke database
     * @throws \Exception jika terjadi error saat upload atau proses PDF
     */
    public function store(array $data, UploadedFile $coverFile, UploadedFile $pdfFile): Book
    {
        // Upload files
        $coverFileName = null;
        $pdfFileName = null;
        $totalPages = 0; // Inisialisasi

        try {
            // Upload cover
            $coverFileName = $this->fileUploadService->uploadFile($coverFile, 'covers');
            
            // Upload PDF
            $pdfFileName = $this->fileUploadService->uploadFile($pdfFile, 'pdfs');
            
            // Hitung jumlah halaman PDF secara otomatis
            // PERHATIAN: Ini akan menyebabkan error jika PDF terenkripsi/terproteksi!
            $totalPages = $this->fileUploadService->countPdfPages('assets/buku/' . $pdfFileName);

            // Simpan ke database menggunakan Eloquent
            return Book::create([
                'judul' => $data['judul'],
                'author' => $data['author'],
                'tahun' => $data['tahun'],
                'genre' => $data['genre'],
                'cover_path' => $coverFileName,
                'pdf_path' => $pdfFileName,
                'total_pages' => $totalPages,
                'point_value' => $data['point_value']
            ]);

        } catch (\Exception $e) {
            // Hapus file yang sudah diupload jika terjadi error
            if ($coverFileName) {
                $this->fileUploadService->deleteFile('assets/buku/' . $coverFileName);
            }
            if ($pdfFileName) {
                $this->fileUploadService->deleteFile('assets/buku/' . $pdfFileName);
            }
            throw $e;
        }
    }
}