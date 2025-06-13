<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Semua Buku - BacaPoint</title>
    
    <!-- Link Icon -->
    @include('library.icon')
    
    <!-- Link ke file CSS -->
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-300 bg-no-repeat bg-cover relative" style="background-image: url('{{ e(asset('assets/buku.jpg')) }}')">
    <!-- Overlay for blur effect -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    
    <!-- Main content -->
    <div class="relative z-10">
        <!-- LINK HEADER -->
        @include('library.header')
        <!-- LINK HEADER -->
        
        <!-- SEMUA BUKU SECTION -->
        <section class="mt-10 mb-10">
            <div class="container mx-auto max-w-6xl px-5 py-6 bg-slate-100 rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Semua Buku</h1>
                    <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline font-semibold">
                        ← Kembali ke Beranda
                    </a>
                </div>
                
                @if($books && $books->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach ($books as $book)
            @if($book && isset($book->id) && $book->id)
                @php
                    // Periksa apakah user sudah login, jika ya gunakan reading-auth
                    // Jika tidak, atau jika buku ID <= 4, gunakan reading.public
                    $link = Auth::check() ? route('reading', ['id' => $book->id]) : route('reading.public', ['id' => $book->id]);
                @endphp
                <a href="{{ $link }}" class="block group">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-3">
                        <div class="h-48 w-full overflow-hidden rounded mb-3">
                            @if(isset($book->cover_path) && $book->cover_path)
                                <img src="{{ e($book->cover_path) }}"
                                    alt="Cover buku {{ e($book->judul ?? 'Tanpa Judul') }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-gray-500">No Cover</span>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2">{{ e($book->judul ?? 'Tanpa Judul') }}</h3>
                            <p class="text-xs text-gray-500">
                                {{ e($book->author ?? 'N/A') }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ e($book->tahun ?? 'N/A') }} • {{ e($book->genre ?? 'N/A') }}
                            </p>
                            @if($book->id > 4 && !Auth::check()) {{-- Tampilkan hanya jika buku > 4 dan user belum login --}}
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">
                                    Login Required
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
@else
    <div class="text-center py-16">
        <div class="text-gray-400 mb-4">
            <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Buku</h3>
        <p class="text-gray-500">Belum ada buku yang tersedia saat ini.</p>
    </div>
@endif
            </div>
        </section>
        
        <!-- LINK FOOTER -->
        @include('library.footer')
        <!-- LINK FOOTER -->
    </div>
</body>
</html>