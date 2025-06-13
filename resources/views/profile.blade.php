<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - Bacapoint</title>
    
    @include('library.icon')

    <!-- Link CSS -->
    @vite('resources/css/app.css')

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    
</head>

<body class="bg-gray-100 text-gray-800">

    <!--LINK HEADER  -->
    @include('library.header')
    <!--LINK HEADER  -->

    <!-- Main Content -->
    <div class="flex md:flex-row max-w-7xl mx-auto mt-8 bg-white shadow rounded overflow-hidden min-h-[600px]">
    
        <!-- Sidebar Navigasi -->
        <aside class="w-full md:w-1/4 bg-gray-50 p-6 border-r md:border-b-0 md:border-r">
            <div class="mb-6">
                <div class="flex items-center space-x-3">
                    <div class="relative w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if (!empty($user->foto_profil))
                            <img src="{{ asset('uploads/profiles/' . $user->foto_profil) }}" 
                                alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

<nav class="space-y-3 text-sm">
    <button onclick="loadContent('account', this)" id="btn-account" class="nav-btn block w-full text-left">Akun</button>
    <button onclick="loadContent('transaksi', this)" id="btn-transaksi" class="nav-btn block w-full text-left">Transaksi</button>
    <button onclick="loadContent('bookmark', this)" id="btn-bookmark" class="nav-btn block w-full text-left">Bookmark</button>
    <button onclick="loadContent('point', this)" id="btn-point" class="nav-btn block w-full text-left">Point</button>
    
    @if ($user->role === 'admin')
        <button onclick="loadContent('grafik', this)" id="btn-grafik" class="nav-btn block w-full text-left">Grafik Genre</button>
        <button onclick="loadContent('collection', this)" id="btn-collection" class="nav-btn block w-full text-left">Koleksi Buku (Admin)</button>
    @endif

    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="block text-red-500 hover:font-bold px-3 py-2 w-full text-left">Logout</button>
    </form>
</nav>
        </aside>

        <!-- Konten Dinamis -->
        <main id="main-content" class="w-full md:w-3/4 p-4 md:p-8">
            <!-- Konten akan dimuat di sini -->
        </main>
    </div>

    <!-- Modal Konfirmasi -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-6 rounded-lg shadow-lg max-w-sm w-full text-center">
            <p class="mb-4 text-gray-700">Apakah Anda yakin ingin menghapus buku ini?</p>
            <div class="flex justify-center space-x-4">
                <button id="confirmYes" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Ya</button>
                <button id="confirmNo" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Batal</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite('resources/js/profile.js')
</body>
</html>
