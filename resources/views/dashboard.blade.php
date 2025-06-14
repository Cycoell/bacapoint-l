<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BacaPoint</title>
    
    @include('library.icon')

    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>
<body class="bg-gray-200 font-sans relative"> {{-- Ubah body background --}}

  <div class="absolute inset-0 z-0 overflow-hidden">
      <img src="{{ asset('assets/buku.jpg') }}" alt="Background" class="w-full h-full object-cover filter brightness-75 transition-transform duration-500 ease-in-out"> {{-- Tambahkan brightness --}}
  </div>

  <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-10"></div> {{-- Ubah opacity --}}

  <div class="relative z-20">
   @include('library.header')
   <section class="mt-10 mb-8">
      <div class="container mx-auto max-w-4xl">
        <div class="swiper mySwiper w-full overflow-hidden rounded-2xl shadow-xl border border-gray-200">
          <div class="swiper-wrapper">

            @foreach (['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg'] as $slide)
              <div class="swiper-slide group transition-transform duration-300 ease-in-out">
                <img src="{{ asset('assets/' . $slide) }}" alt="Slide"
                  class="w-full h-[400px] object-cover rounded-2xl transform group-hover:scale-105 transition duration-300 shadow-lg" />
              </div>
            @endforeach

          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>
    <section class="my-8">
      <div class="container mx-auto max-w-4xl px-6 py-5 bg-white rounded-xl shadow-xl border border-gray-200 flex items-center gap-6 min-h-16">

        <div class="flex items-center flex-wrap gap-x-6 gap-y-3 text-sm text-gray-700 flex-1">

          <a href="{{ url('/profile') }}" class="flex items-center gap-2 bg-orange-100 text-orange-600 px-3 py-1.5 rounded-full font-semibold shadow-sm hover:bg-orange-200 transition-colors duration-200"> {{-- Tambahkan padding vertikal, shadow, hover --}}
            <img src="{{ asset('assets/icon_coin.png') }}" alt="Coin" class="w-5 h-5 object-contain" /> {{-- Perbesar ikon --}}
            <span data-user-points="{{ $totalPoints }}">{{ $totalPoints }}</span> {{-- Tambahkan data attribute untuk JS update --}}
            <span>BacaPoin</span>
          </a>

          <a href="pages/develop.php" class="flex items-center gap-2 border-l border-gray-300 pl-4 text-gray-700 hover:text-green-600 transition-colors duration-200">
            <img src="{{ asset('assets/icon_scan.png') }}" alt="Scan" class="w-5 h-5" />
            <span>Scan</span>
          </a>

          <a href="pages/develop.php" class="flex items-center gap-2 border-l border-gray-300 pl-4 text-gray-700 hover:text-green-600 transition-colors duration-200">
            <img src="{{ asset('assets/icon_topup.png') }}" alt="TopUp" class="w-5 h-5" />
            <span>TopUp</span>
          </a>

          <a href="pages/develop.php" class="flex items-center gap-2 border-l border-gray-300 pl-4 text-gray-700 hover:text-green-600 transition-colors duration-200">
            <img src="{{ asset('assets/icon_history.png') }}" alt="Riwayat" class="w-5 h-5" />
            <span>Riwayat</span>
          </a>
        </div>

        <a href="pages/develop.php" class="border border-green-600 text-green-600 font-semibold text-sm px-4 py-1.5 rounded-full hover:bg-green-600 hover:text-white transition-all duration-300 shadow-sm whitespace-nowrap">
          Jadi Member Premium
        </a>

      </div>
    </section>
    <section class="my-8">
        <div class="container mx-auto max-w-4xl px-6 py-6 bg-white rounded-xl shadow-xl border border-gray-200">
          <div class="flex justify-end mb-5">
          <a href="{{ route('all.books') }}" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors duration-200">
            Lihat Semua Buku &rarr;
          </a>
        </div>
          <div class="overflow-x-auto scrollbar-hide">
            <div class="flex gap-6 pb-4 justify-center">

            @foreach ($books as $index => $book)
              @if ($index < 4) @php
                    $link = route('reading', ['id' => $book->id]); // Selalu reading-auth untuk user login
                @endphp
                <a href="{{ $link }}" class="block flex-shrink-0">
                  <div class="w-48 h-[340px] bg-white rounded-xl shadow-md p-4 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 border border-gray-200">
                    <div class="h-52 w-full overflow-hidden rounded-lg mb-3 border border-gray-100">
                      <img src="{{ asset($book->cover_path) }}" alt="Cover" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-300" />
                    </div>
                    <div class="text-wrap space-y-1">
                      <h3 class="text-base font-semibold text-gray-800 leading-tight line-clamp-2">{{ $book->judul }}</h3>
                      <p class="text-xs text-gray-500 mt-1">
                        {{ $book->author }}
                      </p>
                      <p class="text-xs text-gray-400">
                        {{ $book->tahun }} • {{ $book->genre }}
                      </p>
                    </div>
                  </div>
                </a>
              @endif
            @endforeach

          </div>
        </div>
      </section>
      <section class="my-8">
      <div class="container mx-auto max-w-4xl bg-gradient-to-r from-emerald-200 to-green-300 px-6 py-6 rounded-xl shadow-xl border border-green-400"> {{-- Background gradien, padding, rounded, shadow, border --}}
        
        <h2 class="text-xl font-bold text-gray-900 mb-5">Library Anda</h2> {{-- Judul lebih menonjol --}}

        <div class="flex gap-6 overflow-x-auto scrollbar-hide pb-4"> {{-- Sesuaikan gap dan padding --}}
          <a href="#" class="block flex-shrink-0"> {{-- Tambahkan a tag dan flex-shrink-0 --}}
            <div class="w-40 h-[280px] bg-neutral-100 rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 border border-gray-200"> {{-- Ukuran kartu, rounded, shadow, hover --}}
              <div class="h-[200px] w-full overflow-hidden"> {{-- Ukuran gambar --}}
                <img src="{{ asset('assets/comic1.jpg') }}" alt="The Guy Upstairs" class="w-full h-full object-cover">
              </div>
              <div class="p-3 text-center"> {{-- Padding dan teks di tengah --}}
                <h3 class="font-bold text-sm text-gray-800 line-clamp-2">The Guy Upstairs</h3>
                <p class="text-xs text-gray-500">Ryu Ahnan</p>
              </div>
            </div>
          </a>
          <a href="#" class="block flex-shrink-0">
            <div class="w-40 h-[280px] bg-neutral-100 rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 border border-gray-200">
              <div class="h-[200px] w-full overflow-hidden">
                <img src="{{ asset('assets/comic1.jpg') }}" alt="The Guy Upstairs" class="w-full h-full object-cover">
              </div>
              <div class="p-3 text-center">
                <h3 class="font-bold text-sm text-gray-800 line-clamp-2">The Guy Upstairs</h3>
                <p class="text-xs text-gray-500">Ryu Ahnan</p>
              </div>
            </div>
          </a>
          <a href="#" class="block flex-shrink-0">
            <div class="w-40 h-[280px] bg-neutral-100 rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 border border-gray-200">
              <div class="h-[200px] w-full overflow-hidden">
                <img src="{{ asset('assets/comic1.jpg') }}" alt="The Guy Upstairs" class="w-full h-full object-cover">
              </div>
              <div class="p-3 text-center">
                <h3 class="font-bold text-sm text-gray-800 line-clamp-2">The Guy Upstairs</h3>
                <p class="text-xs text-gray-500">Ryu Ahnan</p>
              </div>
            </div>
          </a>
          <a href="#" class="block flex-shrink-0">
            <div class="w-40 h-[280px] bg-neutral-100 rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 border border-gray-200">
              <div class="h-[200px] w-full overflow-hidden">
                <img src="{{ asset('assets/comic1.jpg') }}" alt="The Guy Upstairs" class="w-full h-full object-cover">
              </div>
              <div class="p-3 text-center">
                <h3 class="font-bold text-sm text-gray-800 line-clamp-2">The Guy Upstairs</h3>
                <p class="text-xs text-gray-500">Ryu Ahnan</p>
              </div>
            </div>
          </a>
          <a href="#" class="block flex-shrink-0">
            <div class="w-40 h-[280px] bg-neutral-100 rounded-lg overflow-hidden shadow-sm transition-all duration-300 hover:shadow-md transform hover:-translate-y-1 border border-gray-200">
              <div class="h-[200px] w-full overflow-hidden">
                <img src="{{ asset('assets/comic1.jpg') }}" alt="The Guy Upstairs" class="w-full h-full object-cover">
              </div>
              <div class="p-3 text-center">
                <h3 class="font-bold text-sm text-gray-800 line-clamp-2">The Guy Upstairs</h3>
                <p class="text-xs text-gray-500">Ryu Ahnan</p>
              </div>
            </div>
          </a>

        </div>  
      </div>
    </section>

    @include('library.footer')
    <script src="{{ asset('src/main.js') }}"></script>

    </div>
</body>
</html>