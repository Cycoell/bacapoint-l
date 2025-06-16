<div class="p-6 bg-white rounded-lg shadow-lg border border-gray-100">
    <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Point Saya</h2>
        <a href="{{ route('all.books') }}" class="text-green-600 font-semibold hover:text-green-700 transition-colors flex items-center gap-1">
            Dapatkan koin <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

    {{-- Kartu Total Poin --}}
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-md flex items-center justify-between mb-6 transform transition-all duration-300 hover:scale-102">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                <img src="{{ asset('assets/icon_coin.png') }}" alt="Point Icon" class="w-10 h-10 object-contain">
            </div>
            <div>
                <p class="text-sm opacity-90">Total Poin Tersedia</p>
                <p class="text-4xl font-extrabold">{{ $user->poin ?? 0 }}</p>
            </div>
        </div>
        {{-- Tombol untuk menarik poin, misalnya --}}
        <a href="#" class="bg-white text-green-600 font-semibold px-5 py-2 rounded-full shadow-lg hover:bg-gray-100 transition-colors text-sm">Tarik Poin</a>
    </div>

    {{-- Tabs untuk Riwayat --}}
    <div class="border-b border-gray-200 mb-4">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchPointTab('all')" id="tab-all" class="tab-point whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 text-green-600 border-green-500">
                Semua Riwayat
            </button>
            <button onclick="switchPointTab('earnings')" id="tab-earnings" class="tab-point whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Penghasilan
            </button>
            <button onclick="switchPointTab('spending')" id="tab-spending" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Pembelanjaan
            </button>
        </nav>
    </div>

    {{-- Konten Tab --}}
    <div id="point-tab-content">
        <div id="content-all" class="tab-content-point">
            <h3 class="font-semibold text-lg mb-3">Riwayat Poin Keseluruhan</h3>
            <p class="text-gray-600">Daftar semua transaksi poin Anda akan ditampilkan di sini.</p>
            <ul class="mt-4 space-y-3">
                @forelse($pointHistory as $history)
                    <li class="bg-gray-50 p-3 rounded-lg flex justify-between items-center text-sm">
                        <span>+{{ $history->points_awarded_for_book }} Poin dari "{{ $history->judul }}" ({{ $history->progress_percentage }}%)</span>
                        <span class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($history->updated_at)->locale('id')->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-600 text-center py-4">Belum ada riwayat poin tersedia.</li>
                @endforelse
            </ul>
        </div>
        <div id="content-earnings" class="tab-content-point hidden">
            <h3 class="font-semibold text-lg mb-3">Poin yang Anda Dapatkan</h3>
            <p class="text-gray-600">Detail poin yang Anda peroleh dari aktivitas membaca buku, event, dll.</p>
            {{-- Bagian ini masih bisa Anda kustomisasi lebih lanjut untuk filter 'Penghasilan' --}}
            @forelse($pointHistory->filter(function($item) { return $item->points_awarded_for_book > 0; }) as $earning)
                <li class="bg-gray-50 p-3 rounded-lg flex justify-between items-center text-sm">
                    <span>+{{ $earning->points_awarded_for_book }} Poin dari "{{ $earning->judul }}"</span>
                    <span class="text-gray-500 text-xs">{{ \Carbon\Carbon::parse($earning->updated_at)->locale('id')->diffForHumans() }}</span>
                </li>
            @empty
                <li class="text-gray-600 text-center py-4">Belum ada riwayat penghasilan poin.</li>
            @endforelse
        </div>
        <div id="content-spending" class="tab-content-point hidden">
            <h3 class="font-semibold text-lg mb-3">Poin yang Anda Belanjakan</h3>
            <p class="text-gray-600">Detail poin yang Anda gunakan untuk membeli fitur, hadiah, dll.</p>
            {{-- Anda perlu tabel terpisah atau logika untuk melacak pembelanjaan poin di sini --}}
            <li class="text-gray-600 text-center py-4">Belum ada riwayat pembelanjaan poin.</li>
        </div>
    </div>
</div>

{{-- Custom CSS untuk tab --}}
<style>
    .tab-point.active {
        @apply text-green-600 border-green-500;
    }
    .tab-point:not(.active) {
        @apply border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300;
    }
    .tab-content-point {
        @apply py-4;
    }
</style>