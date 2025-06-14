<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BacaPoint</title>
    
    @include('library.icon')
    
    @vite('resources/css/app.css')
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body class="bg-gray-200 font-sans relative"> {{-- Ubah body background --}}
    <div class="absolute inset-0 z-0 overflow-hidden">
        <img src="{{ e(asset('assets/buku.jpg')) }}" alt="Background" class="w-full h-full object-cover filter brightness-75 transition-transform duration-500 ease-in-out"> {{-- Tambahkan brightness --}}
    </div>
    
    <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-10"></div> {{-- Ubah opacity --}}
    
    <div class="relative z-20"> {{-- Pastikan konten utama di atas overlay --}}
        @include('library.header')
        <section class="mt-10 mb-8"> {{-- Tambahkan margin bawah --}}
            <div class="container mx-auto max-w-4xl">
                <div class="swiper mySwiper w-full overflow-hidden rounded-2xl shadow-xl border border-gray-200"> {{-- Rounded lebih besar, shadow lebih kuat, border --}}
                    <div class="swiper-wrapper">
                        @foreach (['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg'] as $index => $slide)
                            <div class="swiper-slide group transition-transform duration-300 ease-in-out">
                                <img src="{{ e(asset('assets/' . $slide)) }}"
                                    alt="Slide gambar {{ $index + 1 }} BacaPoint"
                                    class="w-full h-[400px] object-cover rounded-2xl transform group-hover:scale-105 transition duration-300 shadow-lg" />
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>
        
        <section class="my-8"> {{-- Margin vertikal yang konsisten --}}
            <div class="container mx-auto max-w-4xl px-6 py-6 bg-white rounded-xl shadow-xl border border-gray-200"> {{-- Background putih, rounded, shadow, border --}}
                <div class="flex justify-end mb-5"> {{-- Margin bawah yang konsisten --}}
                    <a href="{{ route('all.books') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors duration-200">
                        Lihat Semua Buku &rarr;
                    </a>
                </div>
                
                <div class="overflow-x-auto scrollbar-hide"> {{-- Sembunyikan scrollbar native --}}
                    <div class="flex gap-6 pb-4 justify-center"> {{-- Tambahkan gap antar kartu dan padding bawah --}}
                        @if($books && $books->count() > 0)
                            @foreach ($books->where('id', '<=', 4) as $book)
                                @if($book && isset($book->id) && $book->id)
                                    @php
                                        $link = Auth::check() ? route('reading', ['id' => $book->id]) : route('reading.public', ['id' => $book->id]);
                                    @endphp
                                    <a href="{{ $link }}" class="block flex-shrink-0">
                                        <div class="w-48 h-[340px] bg-white rounded-xl shadow-md p-4 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 border border-gray-200"> {{-- Ukuran kartu, padding, shadow, hover effect --}}
                                            <div class="h-52 w-full overflow-hidden rounded-lg mb-3 border border-gray-100"> {{-- Ukuran gambar, rounded, border --}}
                                                @if(isset($book->cover_path) && $book->cover_path)
                                                    <img src="{{ e(asset($book->cover_path)) }}" {{-- Pastikan asset() digunakan --}}
                                                        alt="Cover buku {{ e($book->judul ?? 'Tanpa Judul') }}"
                                                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300" />
                                                @else
                                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500 text-sm">
                                                        <span>No Cover</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-wrap space-y-1">
                                                <h3 class="text-base font-semibold text-gray-800 leading-tight line-clamp-2">{{ e($book->judul ?? 'Tanpa Judul') }}</h3> {{-- Line clamp untuk judul panjang --}}
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ e($book->author ?? 'N/A') }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    {{ e($book->tahun ?? 'N/A') }} • {{ e($book->genre ?? 'N/A') }}
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
        @include('library.footer')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="{{ e(asset('src/main.js')) }}"></script>
    </div>
</body>
</html>