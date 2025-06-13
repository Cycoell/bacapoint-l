<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\BookSearchController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk semua buku
Route::get('/all-books', [HomeController::class, 'allBooks'])->name('all.books');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/profile', [ProfileController::class, 'showProfile'])->middleware('auth');
Route::get('/profile/{section}', [ProfileController::class, 'loadSection'])->middleware('auth')->name('profile.section');

// Tanpa login (bisa akses buku 1–4 saja)
Route::get('/reading/{id}', [ReadingController::class, 'showReadingPublic'])->name('reading.public');

// Pakai login (akses semua buku)
Route::get('/reading-auth/{id}', [ReadingController::class, 'showReading'])->middleware('auth')->name('reading');

// Route untuk handle query parameter (ROUTE YANG HILANG)
Route::get('/reading-auth', [ReadingController::class, 'showReadingFromQuery'])->middleware('auth')->name('reading.query');

// Routes untuk pencarian buku
Route::get('/api/search', [BookSearchController::class, 'search'])->name('books.search');
Route::get('/api/books/genre', [BookSearchController::class, 'getBooksByGenre'])->name('books.genre');
Route::get('/api/books/random-titles', [BookSearchController::class, 'getRandomBookTitles'])->name('books.random.titles');

// API routes untuk reading functionality (ROUTES YANG MUNGKIN DIBUTUHKAN)
Route::get('/api/books/{id}', [ReadingController::class, 'getBookData'])->name('api.books.show');
Route::get('/api/books/{bookId}/chapters', [ReadingController::class, 'getChapterContent'])->name('api.books.chapters');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showProfile']);
    Route::get('/profile/{section}', [ProfileController::class, 'loadSection'])->name('profile.section');
    
    // Rute baru untuk update profil
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    
    // Rute baru untuk upload foto profil
    Route::post('/profile/update-photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.update_photo');
    
    // Rute baru untuk ganti password
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');
});