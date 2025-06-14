<div>
    <h2 class="text-2xl font-bold mb-4">Koleksi Buku (Admin)</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden p-6 border border-gray-100">
        <div class="mb-4 flex justify-between items-center">
            <p class="text-gray-600">Kelola koleksi buku yang tersedia di platform.</p>
            <button onclick="showAddBookModal()" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span>Tambah Buku Baru</span>
            </button>
        </div>

        {{-- INI ADALAH TABEL DINAMIS UNTUK MENAMPILKAN DATA BUKU DARI DATABASE --}}
        <div class="overflow-x-auto mt-6 border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Halaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($books as $book) {{-- Loop melalui data buku dari database --}}
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $book->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->author }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->genre ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->total_pages }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $book->point_value }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    @empty
                        {{-- Ini adalah baris yang akan ditampilkan jika tidak ada buku di database --}}
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Belum ada buku dalam koleksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    
    
    <div id="addBookModal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-xl p-8 max-w-2xl w-full shadow-2xl relative overflow-y-auto max-h-[90vh]">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Tambah Buku Baru</h3>
                
                <button onclick="hideAddBookModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <form id="addBookForm" action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                            <input type="text" id="judul" name="judul" class="form-input" placeholder="Masukkan judul buku" required>
                            <div id="judul-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                            <input type="text" id="author" name="author" class="form-input" placeholder="Masukkan nama penulis" required>
                            <div id="author-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                            <input type="number" id="tahun" name="tahun" class="form-input" placeholder="Misal: 2023">
                            <div id="tahun-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div>
                            <label for="genre" class="block text-sm font-medium text-gray-700 mb-1">Genre</label>
                            <input type="text" id="genre" name="genre" class="form-input" placeholder="Misal: Fiksi, Sejarah">
                            <div id="genre-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="md:col-span-2">
                            <label for="point_value" class="block text-sm font-medium text-gray-700 mb-1">Nilai Poin (saat selesai)</label>
                            <input type="number" id="point_value" name="point_value" class="form-input" placeholder="Misal: 50" required min="1">
                            <div id="point_value-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="md:col-span-1">
                            <label for="cover_file" class="block text-sm font-medium text-gray-700 mb-1">Cover Buku (Gambar)</label>
                            <input type="file" id="cover_file" name="cover_file" class="form-input-file" required accept="image/*">
                            <div id="cover_file-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="md:col-span-1">
                            <label for="pdf_file" class="block text-sm font-medium text-gray-700 mb-1">File Buku (PDF)</label>
                            <input type="file" id="pdf_file" name="pdf_file" class="form-input-file" required accept="application/pdf">
                            <div id="pdf_file-error" class="text-red-500 text-xs mt-1"></div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3 border-t pt-5 border-gray-200">
                        <button type="button" onclick="hideAddBookModal()" class="bg-gray-400 text-white px-6 py-2 rounded-lg hover:bg-gray-500 transition-colors shadow-md">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors shadow-md">Simpan Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{-- Custom CSS untuk input --}}
<style>
    .form-input {
        @apply block w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 shadow-sm;
    }
    .form-input-file {
        @apply block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-colors duration-200;
    }
</style>