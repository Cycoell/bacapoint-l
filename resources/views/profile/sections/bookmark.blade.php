<div class="p-6 bg-white rounded-lg shadow-lg border border-gray-100">
    <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">Bookmark Saya</h2>
        {{-- Tombol atau link tambahan bisa ditambahkan di sini, misalnya filter --}}
    </div>

    @forelse($bookmarkedBooks as $book)
        @php
            $linkToRead = Auth::check() ? route('reading', ['id' => $book->id]) : route('reading.public', ['id' => $book->id]);
        @endphp
        <div class="flex items-center space-x-4 p-4 border-b border-gray-100 last:border-b-0">
            <a href="{{ $linkToRead }}" class="flex-shrink-0">
                <div class="w-20 h-28 rounded-md overflow-hidden shadow-sm border border-gray-200">
                    @if(isset($book->cover_path) && $book->cover_path)
                        <img src="{{ asset($book->cover_path) }}" alt="Cover {{ $book->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500 text-xs">No Cover</div>
                    @endif
                </div>
            </a>
            <div class="flex-grow">
                <a href="{{ $linkToRead }}" class="text-lg font-semibold text-gray-900 hover:text-green-600 transition-colors line-clamp-2">
                    {{ $book->judul }}
                </a>
                <p class="text-sm text-gray-600 mt-1">{{ $book->author }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $book->tahun ?? 'N/A' }} &bull; {{ $book->genre ?? 'N/A' }}</p>
            </div>
            <div class="flex-shrink-0">
                {{-- Tombol atau ikon untuk menghapus bookmark (akan diimplementasikan nanti) --}}
                <button onclick="removeBookmark({{ $book->id }})" class="p-2 rounded-full text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors" title="Hapus dari Bookmark">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
    @empty
        <div class="py-8 text-center text-gray-600">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            <p class="text-lg font-medium">Belum ada buku di Bookmark Anda.</p>
            <p class="text-sm mt-1">Anda bisa menambahkan buku favorit dari halaman baca atau daftar semua buku.</p>
        </div>
    @endforelse
</div>