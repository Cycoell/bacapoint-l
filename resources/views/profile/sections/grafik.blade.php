<div>
    <h2 class="text-2xl font-bold mb-4">Grafik Genre Buku</h2>
    <div class="bg-white p-4 rounded-lg shadow">
        <canvas id="genreChart" width="400" height="200"></canvas>
        
        <div id="genreLabelsData" class="hidden">{{ $genreLabels ?? '[]' }}</div>
        <div id="genreCountsData" class="hidden">{{ $genreCounts ?? '[]' }}</div>
    </div>
    {{-- SCRIPT CHART.JS INLINE YANG LAMA DIHAPUS KARENA AKAN DIPINDAHKAN KE profile.js --}}
    {{-- Data genreLabels dan genreCounts akan dilewatkan melalui data-attributes atau variabel JS global jika perlu --}}
</div>