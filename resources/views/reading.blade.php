<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BacaPoint - {{ $judul ?? 'Membaca Buku' }}</title>

    {{-- Meta CSRF Token (PENTING untuk AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('library.icon')

    @vite('resources/css/app.css')
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

</head>
<body class="bg-gray-300 font-sans w-screen h-screen overflow-hidden flex flex-col">
    <div class="flex flex-col min-h-screen">

        <header class="bg-white px-4 py-2 flex justify-between items-center shadow relative">
            <div class="flex items-center space-x-2 flex-1">
                <button onclick="window.history.back()" class="text-3xl font-bold hover:text-gray-600 transition-colors">&larr;</button>
            </div>

            <div class="w-20 h-24 left-16 ml-10 absolute">
                @if(file_exists(public_path('assets/logo_samping.png')))
                    <img src="{{ e(asset('assets/logo_samping.png')) }}" alt="Logo BacaPoint" class="h-full w-full object-contain" />
                @else
                    <div class="h-full w-full flex items-center justify-center bg-gray-200 text-gray-500 text-xs">
                        Logo
                    </div>
                @endif
            </div>

            <div class="text-center text-sm text-gray-500 items-center absolute top-4 left-1/2 transform -translate-x-1/2 max-w-xs truncate">
                {{ e($judul ?? 'Judul Tidak Tersedia') }}
            </div>

            <div class="flex items-center space-x-2 flex-1 justify-end">
                {{-- **TAMBAHKAN TOMBOL BOOKMARK INI** --}}
                @if($user ?? false) {{-- Hanya tampilkan jika user login --}}
                    <button id="bookmarkBtn" class="p-2 rounded-full hover:bg-gray-100 transition-colors" title="Bookmark Buku">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>
                @endif

                <div class="flex items-center border rounded px-2 py-1">
                    <button id="zoomOutBtn" class="px-2 py-1 hover:bg-gray-100 transition-colors">−</button>
                    <span id="zoomLevel" class="px-2 min-w-[50px] text-center">100%</span>
                    <button id="zoomInBtn" class="px-2 py-1 hover:bg-gray-100 transition-colors">+</button>
                </div>
            </div>

            <div class="mx-4">
                <button id="resetZoomBtn" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded transition-colors">Reset Zoom</button>
            </div>
        </header>

        <main id="pdfContainer" data-filepath="{{ e($filePath ?? '') }}" class="flex-grow bg-gray-200 overflow-auto flex justify-center items-center">
            <div id="loadingMessage" class="text-center text-gray-600">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto mb-4"></div>
                <p>Memuat PDF...</p>
            </div>
            <canvas id="pdfCanvas" class="bg-white shadow-lg hidden"></canvas>
            <div id="errorMessage" class="text-center text-red-600 hidden">
                <p class="text-lg font-semibold mb-2">Gagal memuat PDF</p>
                <p class="text-sm">Silakan coba refresh halaman atau hubungi administrator</p>
            </div>
        </main>

        <footer class="bg-white p-4 flex justify-center items-center space-x-4 relative">
            <button id="prevPage" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                Sebelumnya
            </button> 
            <span id="pageInfo" class="text-gray-700 px-4">Page 1 of 1</span>
            <button id="nextPage" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                Selanjutnya
            </button>

            @if ($user ?? false)
                <div class="absolute right-5 flex items-center gap-2 text-green-700 font-semibold">
                    <img src="{{ asset('assets/icon_coin.png') }}" alt="Point Icon" class="w-5 h-5">
                    <span id="userTotalPoints" data-user-points="{{ $user->poin ?? 0 }}">{{ $user->poin ?? 0 }}</span> Poin
                </div>
            @endif
        </footer>
    </div>

    <input type="hidden" id="bookId" value="{{ e($bookId ?? 0) }}">
    
    @if($user ?? false)
        <input type="hidden" id="userId" value="{{ e($user->id ?? 0) }}">
    @else
        <input type="hidden" id="userId" value="">
    @endif

    <input type="hidden" id="isLoggedIn" value="{{ ($isLoggedIn ?? false) ? 'true' : 'false' }}">
    <input type="hidden" id="totalPages" value="{{ e($totalPages ?? 1) }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    

    @vite(['resources/js/read.js'])
</body>
</html>