<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Query pencarian tidak boleh kosong'
            ]);
        }

        $books = DB::table('book_list')
            ->where('judul', 'LIKE', '%' . $query . '%')
            ->orWhere('author', 'LIKE', '%' . $query . '%')
            ->orWhere('genre', 'LIKE', '%' . $query . '%')
            ->select('id', 'judul', 'author', 'genre', 'cover_path', 'tahun')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $books,
            'count' => $books->count()
        ]);
    }

    public function getBooksByGenre(Request $request)
    {
        $genre = $request->get('genre', '');
        
        if (empty($genre)) {
            return response()->json([
                'success' => false,
                'message' => 'Genre tidak boleh kosong'
            ]);
        }

        $books = DB::table('book_list')
            ->where('genre', 'LIKE', '%' . $genre . '%')
            ->orWhere('judul', 'LIKE', '%' . $genre . '%')
            ->select('id', 'judul', 'author', 'genre', 'cover_path', 'tahun')
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $books,
            'count' => $books->count()
        ]);
    }

    // Method baru untuk mendapatkan judul buku acak untuk nav links
    public function getRandomBookTitles(Request $request)
    {
        $limit = $request->get('limit', 5);
        
        $books = DB::table('book_list')
            ->select('id', 'judul', 'genre')
            ->inRandomOrder()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $books->map(function($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->judul,
                    'genre' => $book->genre
                ];
            })
        ]);
    }
}