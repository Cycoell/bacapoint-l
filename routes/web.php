<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\BookSearchController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookmarkController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk semua buku
Route::get('/all-books', [HomeController::class, 'allBooks'])->name('all.books');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard'); 

Route::get('/logout', [AuthController::class, 'showLogin'])->name('login');

Route::post('/logout', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);
Route::post('/login', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile']);
    Route::get('/profile/{section}', [ProfileController::class, 'loadSection'])->name('profile.section');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.update_photo');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');

    // Pakai login (akses semua buku)
    Route::get('/reading-auth/{id}', [ReadingController::class, 'showReading'])->name('reading');
    Route::get('/reading-auth', [ReadingController::class, 'showReadingFromQuery'])->name('reading.query');

    // Rute API untuk update progres bacaan
    Route::post('/api/reading-progress', [ReadingController::class, 'saveProgress'])->name('api.reading.progress');
    // Rute API untuk mendapatkan progres bacaan
    Route::get('/api/reading-progress/status', [ReadingController::class, 'getProgressStatus'])->name('api.reading.progress.status');

    // Admin Book Management Routes
    Route::prefix('admin')->group(function () {
        // GANTI RUTE INI: Alihkan ke halaman profil admin (bagian collection)
        Route::get('/books/create', function () {
            return redirect()->route('profile.section', ['section' => 'collection']);
        })->middleware(['auth', 'admin'])->name('admin.books.create');

        // Rute untuk menyimpan buku (tetap ada karena modal memposting ke sini)
        Route::post('/books', [BookController::class, 'store'])
            ->middleware(['auth', 'admin'])
            ->name('admin.books.store');

        // Rute untuk menghapus buku
        Route::delete('/books/{id}', [BookController::class, 'destroy'])
            ->middleware(['auth', 'admin'])
            ->name('admin.books.destroy');

        // Rute untuk memperbarui buku
        Route::put('/books/{id}', [BookController::class, 'update'])
            ->middleware(['auth', 'admin'])
            ->name('admin.books.update');
    });

    // Rute untuk Bookmark
    Route::post('/api/bookmarks/toggle', [BookmarkController::class, 'toggle'])->name('api.bookmarks.toggle');
    Route::get('/api/bookmarks/check/{book_id}', [BookmarkController::class, 'check'])->name('api.bookmarks.check');

    Route::get('/all-bookmarks', [BookmarkController::class, 'showAllBookmarks'])->name('all.bookmarks');
});

// Tanpa login (bisa akses buku 1–4 saja)
Route::get('/reading/{id}', [ReadingController::class, 'showReadingPublic'])->name('reading.public');


// Routes untuk pencarian buku
Route::get('/api/search', [BookSearchController::class, 'search'])->name('books.search');
Route::get('/api/books/genre', [BookSearchController::class, 'getBooksByGenre'])->name('books.genre');
Route::get('/api/books/random-titles', [BookSearchController::class, 'getRandomBookTitles'])->name('books.random.titles');

// API routes untuk reading functionality
Route::get('/api/books/{id}', [ReadingController::class, 'getBookData'])->name('api.books.show');
Route::get('/api/books/{bookId}/chapters', [ReadingController::class, 'getChapterContent'])->name('api.books.chapters');