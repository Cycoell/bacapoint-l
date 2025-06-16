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
    // Tambahkan $totalPages sebagai parameter baru di sini
    public function store(array $data, UploadedFile $coverFile, UploadedFile $pdfFile, int $totalPages): Book
    {
        // Upload files
        $coverFileName = null;
        $pdfFileName = null;

        try {
            // Upload cover
            $coverFileName = $this->fileUploadService->uploadFile($coverFile, 'covers');
            
            // Upload PDF
            $pdfFileName = $this->fileUploadService->uploadFile($pdfFile, 'pdfs');
            
            // TIDAK PERLU lagi memanggil $this->fileUploadService->countPdfPages()
            // karena totalPages sudah diterima sebagai parameter.
            // $totalPages = $this->fileUploadService->countPdfPages('assets/buku/' . $pdfFileName); // HAPUS BARIS INI

            // Simpan ke database menggunakan Eloquent
            return Book::create([
                'judul' => $data['judul'],
                'author' => $data['author'],
                'tahun' => $data['tahun'],
                'genre' => $data['genre'],
                'cover_path' => $coverFileName,
                'pdf_path' => $pdfFileName,
                'total_pages' => $totalPages, // GUNAKAN $totalPages DARI PARAMETER
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