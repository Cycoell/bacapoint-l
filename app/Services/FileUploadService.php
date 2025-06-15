<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class FileUploadService
{
    private $pdfParser;

    public function __construct(PdfParser $pdfParser)
    {
        $this->pdfParser = $pdfParser;
    }

    /**
     * Upload file dan return nama file yang disimpan
     */
    public function uploadFile(UploadedFile $file, string $type): string
    {
        $fileName = $type . '/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->putFileAs('assets/buku', $file, $fileName);
        
        return $fileName;
    }

    /**
     * Hapus file yang sudah diupload
     */
    public function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Cek status proteksi PDF
     * @return string|null 'encrypted', 'protected', or null if no protection
     */
    public function checkPdfProtectionStatus(string $pdfPath): ?string
    {
        try {
            $fileContent = Storage::disk('public')->get($pdfPath);
            $pdf = $this->pdfParser->parseContent($fileContent);
            $details = $pdf->getDetails();

            if (!empty($details['Encrypted']) && $details['Encrypted'] === true) {
                return 'encrypted';
            }

            // Additional checks for protection can be added here if supported by the library
            return null;
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Secured pdf file') || 
                str_contains($e->getMessage(), 'encrypted')) {
                return 'encrypted';
            }
            return null;
        }
    }

    /**
     * Hitung jumlah halaman PDF
     * @throws \Exception jika PDF terenkripsi atau error lainnya
     */
    public function countPdfPages(string $pdfPath): int
    {
        // Check protection status first
        $protectionStatus = $this->checkPdfProtectionStatus($pdfPath);
        if ($protectionStatus === 'encrypted') {
            throw new \Exception('File PDF terenkripsi atau dilindungi. Mohon gunakan file PDF yang tidak terproteksi.');
        }

        try {
            $fileContent = Storage::disk('public')->get($pdfPath);
            $pdf = $this->pdfParser->parseContent($fileContent);
            return $pdf->getDetails()['Pages'];
        } catch (\Exception $e) {
            throw new \Exception('Gagal memproses file PDF: ' . $e->getMessage());
        }
    }
}
