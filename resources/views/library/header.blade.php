<section class="bg-white border-b border-gray-200 shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 backdrop-blur-md bg-white/80">
        <div class="flex items-center justify-between relative gap-4">
            {{-- Logo --}}
            <div class="flex-shrink-0 relative h-16 w-16"> {{-- Container untuk logo --}}
                <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" 
                   class="absolute -top-8 left-0 h-[120px] w-[120px] transition-transform duration-300 hover:scale-110 flex items-center justify-center">
                    <img src="{{ asset('assets/logo_samping.png') }}" alt="BacaPoint" 
                        class="h-full w-full object-contain" />
                </a>
            </div>

            <div class="flex flex-1 max-w-2xl w-full items-center gap-3 relative ml-8 md:ml-0">
                <div class="relative w-full">
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Cari judul buku, atau penulis" 
                        class="w-full border border-gray-300 rounded-full px-5 py-2.5 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 pl-10"
                        autocomplete="off"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <div id="searchResults" 
                        class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-2 max-h-96 overflow-y-auto hidden z-50">
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

            <div class="space-x-2 flex-shrink-0">
                @guest
                    <a href="{{ url('/login') }}" 
                    class="px-5 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition-all duration-300 shadow-md text-sm">
                        Masuk
                    </a>
                @else
                    <a href="{{ url('/profile') }}" 
                    class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 ring-1 ring-inset ring-green-600 rounded-full hover:bg-green-200 transition-all duration-300 shadow-md">
                        <img src="{{ asset('assets/icon_person.png') }}" 
                            class="w-7 h-7 rounded-full object-cover" alt="Profile Icon" />
                        <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                    </a>
                @endguest
            </div>
        </div>

        <nav class="flex justify-center flex-wrap gap-x-6 gap-y-2 mt-4 text-sm text-gray-600 bg-gradient-to-r from-green-50 via-white to-green-50 rounded-xl py-3 shadow-inner">
            <div id="dynamicNavLinks" class="flex justify-center flex-wrap gap-x-6 gap-y-2">
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

<section id="resultsSection" class="hidden container mx-auto px-4 py-6">
    <div class="mb-4">
        <h2 id="resultsTitle" class="text-2xl font-bold text-gray-800"></h2>
        <p id="resultsCount" class="text-gray-600 mt-1"></p>
    </div>
    
    <div id="resultsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        </div>
</section>

<section id="resultsLoading" class="hidden container mx-auto px-4 py-12 text-center">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto"></div>
    <p class="mt-4 text-gray-600">Memuat hasil...</p>
</section>

{{-- Pastikan resources/js/search.js dimuat di main layout atau di sini --}}
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search.js'])