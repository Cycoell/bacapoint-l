<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Bacapoint</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Pastikan ini ada untuk AJAX --}}

    @include('library.icon')

    @vite('resources/css/app.css')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">

    @include('library.header')
    <div class="flex flex-col md:flex-row max-w-7xl mx-auto my-8 bg-white shadow-xl rounded-2xl overflow-hidden min-h-[600px] border border-gray-200">
    
        <aside class="w-full md:w-1/4 bg-gradient-to-br from-gray-50 to-white p-6 md:p-8 border-b md:border-b-0 md:border-r border-gray-100 shadow-inner">
            <div class="mb-8 text-center">
                <div class="flex flex-col items-center space-y-4">
                    <div class="relative w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                        @if (!empty($user->foto_profil))
                            <img src="{{ asset('uploads/profiles/' . $user->foto_profil) }}" 
                                alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-xl text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <nav class="space-y-2 text-sm">
                <button onclick="loadContent('account', this)" id="btn-account" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Akun</span>
                </button>
                <button onclick="loadContent('transaksi', this)" id="btn-transaksi" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m-9 4h8a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>BP Store</span>
                </button>
                <button onclick="loadContent('bookmark', this)" id="btn-bookmark" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    <span>Bookmark</span>
                </button>
                <button onclick="loadContent('riwayat-membaca', this)" id="btn-riwayat-membaca" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path></svg>
                    <span>Riwayat Membaca</span>
                </button>
                <button onclick="loadContent('point', this)" id="btn-point" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                    <img src="{{ asset('assets/icon_coin.png') }}" class="w-5 h-5 object-contain" alt="Point Icon">
                    <span>Point</span>
                </button>
                
                @if ($user->role === 'admin')
                    <div class="border-t border-gray-200 pt-3 mt-3"></div> {{-- Divider --}}
                    <button onclick="loadContent('grafik', this)" id="btn-grafik" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3m0 0l3 3m-3-3v8m0-9a9 9 0 110 18 9 9 0 010-18z"></path></svg>
                        <span>Grafik Genre</span>
                    </button>
                    <button onclick="loadContent('collection', this)" id="btn-collection" class="nav-btn flex items-center gap-3 px-4 py-2 rounded-lg w-full text-left font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>Koleksi Buku (Admin)</span>
                    </button>
                @endif

                <div class="border-t border-gray-200 pt-3 mt-3"></div> {{-- Divider --}}
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="block text-red-500 hover:font-bold px-4 py-2 rounded-lg w-full text-left font-medium hover:bg-red-50 transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Logout</span>
                        </div>
                    </button>
                </form>
            </nav>
        </aside>

        <main id="main-content" class="w-full md:w-3/4 p-6 md:p-8 transition-all duration-300 ease-in-out">
            </main>
    </div>

    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen"> {{-- Tambahkan div ini untuk centering --}}
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full text-center">
                <p class="mb-4 text-gray-700">Apakah Anda yakin ingin menghapus buku ini?</p>
                <div class="flex justify-center space-x-4">
                    <button id="confirmYes" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">Ya</button>
                    <button id="confirmNo" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded transition-colors">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @vite('resources/js/profile.js')
</body>
</html>