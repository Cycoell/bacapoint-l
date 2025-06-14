<div class="p-6 bg-white rounded-lg shadow-lg border border-gray-100">
    <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Membaca</h2>
        <div class="flex items-center space-x-3">
            {{-- Filter/Tabs --}}
            <button class="px-4 py-2 rounded-full border border-green-500 text-green-600 font-medium text-sm hover:bg-green-50 transition-colors">
                Rak Buku
            </button>
            <button class="px-4 py-2 rounded-full border border-gray-300 text-gray-600 font-medium text-sm hover:bg-gray-100 transition-colors">
                Semua Buku
            </button>
            {{-- Search input (opsional, bisa ditambahkan nanti) --}}
            <input type="text" placeholder="Telusuri buku Anda" class="px-4 py-2 rounded-full border border-gray-300 text-sm focus:outline-none focus:ring-1 focus:ring-green-500 hidden md:block">
        </div>
    </div>

    {{-- Bagian Sedang Membaca --}}
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Sedang Membaca</h3>
            <a href="#" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                Tampilkan semua &rarr;
            </a>
        </div>
        <div class="flex overflow-x-auto pb-4 -mx-2 sm:-mx-4 md:-mx-6 scrollbar-hide"> {{-- Overflow horizontal, padding bawah --}}
            @forelse($sedangMembaca as $item)
                @php
                    $progressPercentage = $item->total_pages > 0 ? round(($item->last_page_read / $item->total_pages) * 100) : 0;
                    $linkToRead = Auth::check() ? route('reading', ['id' => $item->book_id]) : route('reading.public', ['id' => $item->book_id]);
                @endphp
                <div class="flex-shrink-0 w-48 mx-2 sm:mx-4 md:mx-6 bg-white rounded-lg shadow-md p-3 border border-gray-200 transform transition-all duration-300 hover:scale-105 hover:shadow-lg"> {{-- Kartu buku --}}
                    <a href="{{ $linkToRead }}">
                        <div class="h-48 w-full mb-3 rounded-md overflow-hidden border border-gray-100">
                            @if(isset($item->cover_path) && $item->cover_path)
                                <img src="{{ asset($item->cover_path) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500 text-xs">No Cover</div>
                            @endif
                        </div>
                        <h4 class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">{{ $item->judul }}</h4>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $item->author }}</p>
                    </a>
                    {{-- Progress Bar --}}
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-3">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1 text-right">{{ $progressPercentage }}%</p>
                </div>
            @empty
                <p class="text-gray-600 px-2 sm:px-4 md:px-6">Belum ada buku yang sedang Anda baca.</p>
            @endforelse
        </div>
    </div>

    {{-- Bagian Selesai Dibaca --}}
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800">Selesai Dibaca</h3>
            <a href="#" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors">
                Tampilkan semua &rarr;
            </a>
        </div>
        <div class="flex overflow-x-auto pb-4 -mx-2 sm:-mx-4 md:-mx-6 scrollbar-hide">
            @forelse($selesaiDibaca as $item)
                @php
                    $linkToRead = Auth::check() ? route('reading', ['id' => $item->book_id]) : route('reading.public', ['id' => $item->book_id]);
                @endphp
                <div class="flex-shrink-0 w-48 mx-2 sm:mx-4 md:mx-6 bg-white rounded-lg shadow-md p-3 border border-gray-200 transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <a href="{{ $linkToRead }}">
                        <div class="h-48 w-full mb-3 rounded-md overflow-hidden border border-gray-100">
                            @if(isset($item->cover_path) && $item->cover_path)
                                <img src="{{ asset($item->cover_path) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500 text-xs">No Cover</div>
                            @endif
                        </div>
                        <h4 class="font-semibold text-sm text-gray-800 leading-tight line-clamp-2">{{ $item->judul }}</h4>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $item->author }}</p>
                    </a>
                    {{-- Progress Bar (100% untuk selesai) --}}
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-3">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: 100%;"></div>
                    </div>
                    <p class="text-xs text-gray-600 mt-1 text-right">100% Selesai</p>
                </div>
            @empty
                <p class="text-gray-600 px-2 sm:px-4 md:px-6">Belum ada buku yang selesai Anda baca.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Custom CSS untuk scrollbar (bisa ditempatkan di app.css jika ingin global) --}}
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>