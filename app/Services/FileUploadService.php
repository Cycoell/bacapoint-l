<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // TAMBAH BARIS INI

class FileUploadService
{
    private $pdfParser;

    public function __construct(PdfParser $pdfParser)
    {
        $this->pdfParser = $pdfParser;
    }

    /**
     * Upload file dan return nama file yang disimpan (MENGGUNAKAN NAMA ASLI FILE)
     * PERHATIAN: Menggunakan nama asli file dapat menyebabkan konflik nama jika ada file dengan nama yang sama.
     * Pertimbangkan untuk menambahkan prefix unik jika ada potensi konflik.
     */
    public function uploadFile(UploadedFile $file, string $type): string
    {
        $originalFileName = $file->getClientOriginalName(); // Dapatkan nama asli file

        // Opsional: Untuk mengurangi potensi konflik nama, Anda bisa tetap menambahkan prefix unik
        // $fileNameToStore = time() . '_' . $originalFileName; 
        
        // Atau, jika Anda hanya ingin nama asli tanpa tambahan apapun:
        $fileNameToStore = $originalFileName; 

        // Simpan file langsung di 'assets/buku'
        Storage::disk('public')->putFileAs('assets/buku', $file, $fileNameToStore); 
        
        return $fileNameToStore; // Kembalikan nama file yang digunakan untuk penyimpanan
    }

    /**
     * Hapus file yang sudah diupload
     */
    public function deleteFile(?string $path): void
    {
        // Path yang diterima di sini sudah termasuk 'assets/buku/', contoh: 'assets/buku/namafile.png'
        // Kita perlu menghapus prefiks 'assets/buku/' agar Storage::delete bisa bekerja dengan benar jika path_attribute di Book Model sudah menambahkannya.
        // Cek apakah path dimulai dengan 'assets/buku/', jika ya, ambil relatif pathnya.
        if (!empty($path) && Str::contains($path, 'assets/buku/')) { // Menggunakan Str::contains
            $relativePath = Str::after($path, 'assets/buku/'); // Menggunakan Str::after
            if (Storage::disk('public')->exists('assets/buku/' . $relativePath)) {
                Storage::disk('public')->delete('assets/buku/' . $relativePath);
            }
        } else if (!empty($path) && Storage::disk('public')->exists($path)) {
            // Ini untuk kasus jika path yang disimpan tidak memiliki prefiks 'assets/buku/'
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
            if (Str::contains($e->getMessage(), 'Secured pdf file') || // Menggunakan Str::contains
                Str::contains($e->getMessage(), 'encrypted')) { // Menggunakan Str::contains
                return 'encrypted';
            }
            return null;
        }
    }

    /**
     * Hitung jumlah halaman PDF. Mengembalikan 0 jika PDF terenkripsi atau tidak dapat diproses.
     * @return int Jumlah halaman, atau 0 jika terenkripsi/error.
     */
    public function countPdfPages(string $pdfPath): int
    {
        if (!Storage::disk('public')->exists($pdfPath)) {
            return 0;
        }
        
        try {
            $fileContent = Storage::disk('public')->get($pdfPath);
            $pdf = $this->pdfParser->parseContent($fileContent);
            return $pdf->getDetails()['Pages'] ?? 0;
        } catch (\Exception $e) {
            Log::warning('Gagal menghitung halaman PDF atau PDF terenkripsi: ' . $e->getMessage(), ['path' => $pdfPath]);
            return 0;
        }
    }
}