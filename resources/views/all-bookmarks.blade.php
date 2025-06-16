<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Semua Buku Favorit - BacaPoint</title>

    @include('library.icon')

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-300 bg-no-repeat bg-cover relative" style="background-image: url('{{ e(asset('assets/buku.jpg')) }}')">
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <div class="relative z-10">
        @include('library.header')
        <section class="mt-10 mb-10">
            <div class="container mx-auto max-w-6xl px-5 py-6 bg-slate-100 rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Semua Buku Favorit</h1>
                    {{-- Tombol kembali ke dashboard --}}
                    <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline font-semibold">
                        ← Kembali ke Dashboard
                    </a>
                </div>

                {{-- Container untuk grid buku favorit --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                    @forelse($bookmarkedBooks as $book)
                        @php
                            $linkToRead = Auth::check() ? route('reading', ['id' => $book->id]) : route('reading.public', ['id' => $book->id]);
                        @endphp
                        {{-- Setiap buku menjadi sebuah kartu dalam grid --}}
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group relative">
                            <a href="{{ $linkToRead }}" class="block">
                                <div class="aspect-[3/4] w-full overflow-hidden rounded-t-lg">
                                    @if(isset($book->cover_path) && $book->cover_path)
                                        <img src="{{ asset($book->cover_path) }}" alt="Cover {{ $book->judul }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500 text-xs">No Cover</div>
                                    @endif
                                </div>
                                <div class="p-3 text-center">
                                    <h3 class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">{{ $book->judul }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $book->author }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $book->tahun ?? 'N/A' }} &bull; {{ $book->genre ?? 'N/A' }}</p>
                                </div>
                            </a>
                            {{-- Tombol untuk menghapus bookmark (akan diimplementasikan nanti) --}}
                            {{-- Ini hanya tampil di halaman profile/bookmark, bukan di sini untuk kesederhanaan --}}
                            {{-- Jika Anda ingin fungsi hapus di sini juga, Anda perlu menambahkan JS handler untuk itu --}}
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-gray-600">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            <p class="text-lg font-medium">Belum ada buku di Bookmark Anda.</p>
                            <p class="text-sm mt-1">Anda bisa menambahkan buku favorit dari halaman baca atau daftar semua buku.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        @include('library.footer')
        </div>
</body>
</html>