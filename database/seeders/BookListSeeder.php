<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookListSeeder extends Seeder
{
    public function run()
    {
        DB::table('book_list')->insert([
            [
                'id' => 1,
                'judul' => '48 Hukum Kekuasaan',
                'author' => 'Robert Greene',
                'tahun' => 1998,
                'genre' => 'Self-Development',
                'cover_path' => 'assets/buku/48hukumkekuasaan.png',
                'pdf_path' => 'assets/buku/48hukumkekuasaan.pdf',
                'created_at' => '2025-04-14 06:15:45',
                'total_pages' => 849, // Contoh: Anggap ada 480 halaman
                'point_value' => 50,  // Contoh: 50 poin jika selesai
            ],
            [
                'id' => 2,
                'judul' => 'Sejarah Dunia Kuno',
                'author' => 'Susan Wise Bauer',
                'tahun' => 2010,
                'genre' => 'Sejarah',
                'cover_path' => 'assets/buku/Sejarahduniakunodaricer.png',
                'pdf_path' => 'assets/buku/Sejarahduniakunodaricer.pdf',
                'created_at' => '2025-04-14 07:08:25',
                'total_pages' => 1002, // Contoh
                'point_value' => 70,  // Contoh
            ],
            [
                'id' => 3,
                'judul' => 'Building a Second Brain',
                'author' => 'Tiago Forte',
                'tahun' => 2022,
                'genre' => 'Productivity',
                'cover_path' => 'assets/buku/BuildingaSecondBrainAP.png',
                'pdf_path' => 'assets/buku/BuildingaSecondBrainAP.pdf',
                'created_at' => '2025-04-14 07:10:22',
                'total_pages' => 320, // Contoh
                'point_value' => 40,  // Contoh
            ],
            [
                'id' => 4,
                'judul' => 'Storyworthy',
                'author' => 'Matthew Dicks',
                'tahun' => 2018,
                'genre' => 'Self-Development',
                'cover_path' => 'assets/buku/Storyworthy.png',
                'pdf_path' => 'assets/buku/Storyworthy.pdf',
                'created_at' => '2025-04-14 07:12:26',
                'total_pages' => 280, // Contoh
                'point_value' => 35,  // Contoh
            ],
            [
                'id' => 5,
                'judul' => 'Retorika Seni Berbicara',
                'author' => 'Aristoteles',
                'tahun' => 2000,
                'genre' => 'Filsafat',
                'cover_path' => 'assets/buku/RetorikaSeniBerbicara.png',
                'pdf_path' => 'assets/buku/RetorikaSeniBerbicara.pdf',
                'created_at' => '2025-04-14 07:15:19',
                'total_pages' => 150, // Contoh
                'point_value' => 20,  // Contoh
            ],
            [
                'id' => 6,
                'judul' => 'The Art Of War Sun Tzu',
                'author' => 'James Clavell',
                'tahun' => 2002,
                'genre' => 'Self-Development',
                'cover_path' => 'assets/buku/TheArtOfWarSunTzuSeni.png',
                'pdf_path' => 'assets/buku/TheArtOfWarSunTzuSeni.pdf',
                'created_at' => '2025-04-14 07:17:23',
                'total_pages' => 100, // Contoh
                'point_value' => 15,  // Contoh
            ],
            [
                'id' => 9,
                'judul' => 'Jangan Membuat Masalah Kecil dalam Hubungan Cinta Menjadi Masalah Besar',
                'author' => 'Richard Carlson and Kristine Carlson',
                'tahun' => 2020,
                'genre' => 'Self-Love',
                'cover_path' => 'assets/buku/JanganMembuatMasalahKecildalamHubunganCintaMenjadiMasalahBesar.png',
                'pdf_path' => 'assets/buku/JanganMembuatMasalahKecildalamHubunganCintaMenjadiMasalahBesar.pdf',
                'created_at' => '2025-04-17 04:43:53',
                'total_pages' => 250, // Contoh
                'point_value' => 30,  // Contoh
            ],
            [
                'id' => 10,
                'judul' => 'Jangan Membuat Masalah Kecil Jadi Masalah Besar',
                'author' => 'Richard Carlson and Kristine Carlson',
                'tahun' => 2020,
                'genre' => 'Self-Love',
                'cover_path' => 'assets/buku/JanganMembuatMasalahKecilJadiMasalahBesar.png',
                'pdf_path' => 'assets/buku/JanganMembuatMasalahKecilJadiMasalahBesar.pdf',
                'created_at' => '2025-04-17 04:45:01',
                'total_pages' => 220, // Contoh
                'point_value' => 28,  // Contoh
            ],
            [
                'id' => 11,
                'judul' => '30 Hari Jago Jualan',
                'author' => 'Dewa Eka Prayoga',
                'tahun' => 2014,
                'genre' => 'Financial',
                'cover_path' => 'assets/buku/30HariJagoJualan.png',
                'pdf_path' => 'assets/buku/30HariJagoJualan.pdf',
                'created_at' => '2025-04-17 04:45:56',
                'total_pages' => 180, // Contoh
                'point_value' => 25,  // Contoh
            ],
            [
                'id' => 12,
                'judul' => 'English Grammar for Dummies',
                'author' => 'Geraldine Woods',
                'tahun' => 2014,
                'genre' => 'English',
                'cover_path' => 'assets/buku/EnglishGrammarforDummies.png',
                'pdf_path' => 'assets/buku/EnglishGrammarforDummies.pdf',
                'created_at' => '2025-04-17 04:48:12',
                'total_pages' => 350, // Contoh
                'point_value' => 45,  // Contoh
            ],
            [
                'id' => 13,
                'judul' => 'ENSIKLOPEDI ALIRAN DAN MADZHAB DI DUNIA ISLAM',
                'author' => 'Tim Riset Majelis Tinggi Urusan Islam Mesir',
                'tahun' => 2000,
                'genre' => 'Religion',
                'cover_path' => 'assets/buku/ENSIKLOPEDI_ALIRAN_DAN_MADZHAB_DI_DUNIA_ISLAM.png',
                'pdf_path' => 'assets/buku/ENSIKLOPEDI_ALIRAN_DAN_MADZHAB_DI_DUNIA_ISLAM.pdf',
                'created_at' => '2025-04-17 04:49:48',
                'total_pages' => 600, // Contoh
                'point_value' => 60,  // Contoh
            ],
            [
                'id' => 14,
                'judul' => 'Kitab Anti-Bodoh Terampil Berpikir Benar Terhindar dari Cacat Logika & Sesat Pikir',
                'author' => 'Bo Bennett, Ph.D',
                'tahun' => 2015,
                'genre' => 'Self-Development',
                'cover_path' => 'assets/buku/KitabAnti-BodohTerampilBerpikirBenarTerhindardariCacatLogika&SesatPikir.png',
                'pdf_path' => 'assets/buku/KitabAnti-BodohTerampilBerpikirBenarTerhindardariCacatLogika&SesatPikir.pdf',
                'created_at' => '2025-04-17 04:51:22',
                'total_pages' => 300, // Contoh
                'point_value' => 38,  // Contoh
            ],
            [
                'id' => 15,
                'judul' => 'Last Human Vol. 001-010',
                'author' => 'Wen Qing',
                'tahun' => 2000,
                'genre' => 'Comic',
                'cover_path' => 'assets/buku/LastHumanVol001-010.png',
                'pdf_path' => 'assets/buku/LastHumanVol001-010.pdf',
                'created_at' => '2025-04-17 04:52:28',
                'total_pages' => 80, // Contoh
                'point_value' => 10,  // Contoh
            ],
            [
                'id' => 16,
                'judul' => 'Bumi',
                'author' => 'TereLiye',
                'tahun' => 2014,
                'genre' => 'Novel',
                'cover_path' => 'assets/buku/TereLiyeBumi.png',
                'pdf_path' => 'assets/buku/TereLiyeBumi.pdf',
                'created_at' => '2025-04-17 04:53:48',
                'total_pages' => 400, // Contoh
                'point_value' => 42,  // Contoh
            ],
            [
                'id' => 17,
                'judul' => 'Terjemah Mukhtashar Ihya Ulumuddin Ringkasan Ihya Ulumuddin',
                'author' => 'Imam Al-Ghazali',
                'tahun' => 2009,
                'genre' => 'Religion',
                'cover_path' => 'assets/buku/Terjemah_Mukhtashar_Ihya_Ulumuddin_Ringkasan_Ihya_Ulumuddin.png',
                'pdf_path' => 'assets/buku/Terjemah_Mukhtashar_Ihya_Ulumuddin_Ringkasan_Ihya_Ulumuddin.pdf',
                'created_at' => '2025-04-17 04:57:08',
                'total_pages' => 500, // Contoh
                'point_value' => 55,  // Contoh
            ],
            // Tambahkan data lainnya sesuai dengan dump SQL Anda
        ]);
    }
}