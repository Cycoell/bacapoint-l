<!-- HEADER SECTION -->
<section class="bg-white border-b border-gray-200 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 backdrop-blur-md bg-white/80">
        <!-- Baris 1: Logo, Search, Auth -->
        <div class="flex flex-col lg:flex-row items-center justify-between relative gap-4">
            <div class="w-16 h-16"></div> <!-- Logo -->
            <div class="w-[140px] h-[140px] -top-8 left-8 absolute">
                <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" 
                    class="transition-transform duration-300 hover:scale-110">
                    <img src="{{ asset('assets/logo_samping.png') }}" alt="BacaPoint" 
                    class="bg-contain w-full h-full object-contain" />
                </a>
            </div>

            <!-- Search Container -->
            <div class="flex flex-1 max-w-2xl w-full items-center gap-3 mt-4 lg:mt-0 relative">
                <div class="relative w-full">
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Cari judul buku, atau penulis" 
                        class="w-full border border-gray-300 rounded-full px-4 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-300"
                        autocomplete="off"
                    />
                    
                    <!-- Search Results Dropdown -->
                    <div id="searchResults" 
                        class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-96 overflow-y-auto hidden z-50">
                        <div id="searchResultsContent"></div>
                        <div id="searchLoading" class="hidden p-4 text-center text-gray-500">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-500 mx-auto"></div>
                            <span class="mt-2 block">Mencari...</span>
                        </div>
                        <div id="searchEmpty" class="hidden p-4 text-center text-gray-500">
                            <span>Tidak ada hasil ditemukan</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auth Buttons -->
            <div class="space-x-2 mt-4 lg:mt-0">
                @guest
                    <a href="{{ url('/login') }}" 
                    class="px-4 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition-all duration-300">
                        Masuk
                    </a>
                @else
                    <a href="{{ url('/profile') }}" 
                    class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 ring-1 ring-inset ring-green-600 rounded-full hover:bg-green-200 transition-all duration-300">
                        <img src="{{ asset('assets/icon_person.png') }}" 
                            class="w-6 h-6 rounded-full object-cover" />
                        <span class="text-sm">{{ Auth::user()->name }}</span>
                    </a>
                @endguest
            </div>
        </div>

        <!-- Baris 2: Nav Links dengan gradasi background - UPDATED untuk dynamic content -->
        <nav class="flex justify-center flex-wrap gap-4 mt-4 text-sm text-gray-500 bg-gradient-to-r from-green-50 via-white to-green-50 rounded-lg py-2 shadow-inner">
            <!-- Dynamic nav links akan diisi oleh JavaScript -->
            <div id="dynamicNavLinks" class="flex justify-center flex-wrap gap-4">
                <!-- Loading placeholder -->
                <div class="animate-pulse flex gap-4">
                    <div class="h-4 bg-gray-300 rounded w-20"></div>
                    <div class="h-4 bg-gray-300 rounded w-24"></div>
                    <div class="h-4 bg-gray-300 rounded w-18"></div>
                    <div class="h-4 bg-gray-300 rounded w-22"></div>
                    <div class="h-4 bg-gray-300 rounded w-20"></div>
                </div>
            </div>
        </nav>
    </div>
</section>

<!-- Results Section (untuk menampilkan hasil pencarian/navigasi) -->
<section id="resultsSection" class="hidden container mx-auto px-4 py-6">
    <div class="mb-4">
        <h2 id="resultsTitle" class="text-2xl font-bold text-gray-800"></h2>
        <p id="resultsCount" class="text-gray-600 mt-1"></p>
    </div>
    
    <div id="resultsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <!-- Results akan diisi oleh JavaScript -->
    </div>
</section>

<!-- Loading untuk hasil pencarian -->
<section id="resultsLoading" class="hidden container mx-auto px-4 py-12 text-center">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto"></div>
    <p class="mt-4 text-gray-600">Memuat hasil...</p>
</section>

@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search.js'])