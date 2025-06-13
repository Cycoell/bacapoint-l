<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BacaPoint</title>
    
    <!-- Link Icon -->
    @include('library.icon')
    
    <!-- Link ke file CSS -->
    @vite('resources/css/app.css')
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body class="bg-slate-300 bg-no-repeat bg-cover relative" style="background-image: url('{{ e(asset('assets/buku.jpg')) }}')">
    <!-- Overlay for blur effect -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    
    <!-- Main content -->
    <div class="relative z-10">
        <!-- LINK HEADER -->
        @include('library.header')
        <!-- LINK HEADER -->
        
        <!-- GAMBAR BESAR SECTION -->
        <section class="mt-10">
            <div class="container mx-auto max-w-4xl">
                <div class="swiper mySwiper w-full overflow-hidden rounded-xl shadow-lg">
                    <div class="swiper-wrapper">
                        @foreach (['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg'] as $index => $slide)
                            <div class="swiper-slide px-0.5 group transition-transform duration-300 ease-in-out">
                                <img src="{{ e(asset('assets/' . $slide)) }}"
                                    alt="Slide gambar {{ $index + 1 }} BacaPoint"
                                    class="w-full h-[400px] object-cover rounded-xl transform group-hover:scale-105 transition duration-300 shadow-md" />
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
        
        <!-- BAR SECTION -->
        <section class="mt-2">
            <div class="container mx-auto max-w-4xl px-5 py-6 bg-slate-100 rounded-lg">
                <!-- Link ke semua buku -->
                <div class="flex justify-end mb-4">
                    <a href="{{ route('all.books') }}" class="text-sm text-blue-600 hover:underline font-semibold">
                        Lihat Semua Buku →
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <div class="flex gap-4 whitespace-nowrap justify-center">
                        @if($books && $books->count() > 0)
                            @foreach ($books->where('id', '<=', 4) as $book)
                                @if($book && isset($book->id) && $book->id)
                                    @php
                                        $link = route('reading.public', ['id' => $book->id]);
                                    @endphp
                                    <a href="{{ $link }}" class="block">
                                        <div class="w-44 h-80 flex-none bg-slate-300 rounded-lg shadow p-3 mr-4 hover:shadow-lg transition">
                                            <div class="h-48 w-full overflow-hidden rounded mb-2">
                                                @if(isset($book->cover_path) && $book->cover_path)
                                                    <img src="{{ e($book->cover_path) }}"
                                                        alt="Cover buku {{ e($book->judul ?? 'Tanpa Judul') }}"
                                                        class="w-full h-full object-cover" />
                                                @else
                                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-gray-500">No Cover</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-wrap space-y-1">
                                                <h3 class="text-base font-semibold">{{ e($book->judul ?? 'Tanpa Judul') }}</h3>
                                                <p class="text-xs text-gray-500">
                                                    {{ e($book->tahun ?? 'N/A') }} • {{ e($book->genre ?? 'N/A') }} • {{ e($book->author ?? 'N/A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <div class="w-full text-center py-8">
                                <p class="text-gray-500">Belum ada buku tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- BAR SECTION -->
        
        <!-- LINK FOOTER -->
        @include('library.footer')
        <!-- LINK FOOTER -->
        
        <!-- Swiper library dan scripts lainnya di akhir body untuk performa lebih baik -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="{{ e(asset('src/main.js')) }}"></script>
    </div>
</body>
</html>