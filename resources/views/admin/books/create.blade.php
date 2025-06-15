@extends('library.header')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">Upload Buku Baru</h1>

        <form id="uploadBookForm" class="space-y-6" enctype="multipart/form-data">
            @csrf
            
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Buku</label>
                <input type="text" name="judul" id="judul" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="author" class="block text-sm font-medium text-gray-700">Penulis</label>
                <input type="text" name="author" id="author" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" min="1000" max="{{ date('Y') + 5 }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                <input type="text" name="genre" id="genre"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="point_value" class="block text-sm font-medium text-gray-700">Nilai Poin</label>
                <input type="number" name="point_value" id="point_value" required min="1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="cover_file" class="block text-sm font-medium text-gray-700">Cover Buku</label>
                <input type="file" name="cover_file" id="cover_file" required accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">Format: JPEG, PNG, JPG, GIF (Max. 2MB)</p>
            </div>

            <div>
                <label for="pdf_file" class="block text-sm font-medium text-gray-700">File PDF Buku</label>
                <input type="file" name="pdf_file" id="pdf_file" required accept=".pdf"
                    class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100">
                <p class="mt-1 text-sm text-gray-500">Format: PDF (Max. 50MB)</p>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Upload Buku
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('uploadBookForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Remove any existing error messages
    const existingErrors = document.querySelectorAll('.error-message');
    existingErrors.forEach(el => el.remove());
    
    const form = new FormData(this);
    
    try {
        const response = await fetch('{{ route('admin.books.store') }}', {
            method: 'POST',
            body: form,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });

        const result = await response.json();
        
        if (response.ok) {
            // Show success message in green
            const successDiv = document.createElement('div');
            successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4';
            successDiv.innerHTML = `<span class="block sm:inline">${result.message}</span>`;
            this.insertBefore(successDiv, this.firstChild);
            
            // Reset form
            this.reset();
            
            // Remove success message after 5 seconds
            setTimeout(() => successDiv.remove(), 5000);
        } else {
            // Show error message in red
            const errorDiv = document.createElement('div');
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
            
            if (result.errors) {
                // Handle validation errors
                const errorList = Object.values(result.errors).flat();
                errorDiv.innerHTML = `
                    <strong class="font-bold">Gagal mengunggah buku:</strong>
                    <ul class="list-disc list-inside">
                        ${errorList.map(err => `<li>${err}</li>`).join('')}
                    </ul>`;
            } else {
                // Handle other errors
                errorDiv.innerHTML = `
                    <strong class="font-bold">Gagal mengunggah buku:</strong>
                    <span class="block sm:inline">${result.message}</span>`;
            }
            
            this.insertBefore(errorDiv, this.firstChild);
        }
    } catch (error) {
        // Show error message in red
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 error-message';
        errorDiv.innerHTML = `
            <strong class="font-bold">Error:</strong>
            <span class="block sm:inline">${error.message || 'Terjadi kesalahan saat mengunggah buku.'}</span>`;
        this.insertBefore(errorDiv, this.firstChild);
    }
});
</script>

<!-- Add note about PDF protection -->
<div class="mt-4 p-4 bg-blue-50 text-blue-700 rounded-md">
    <p class="text-sm">
        <strong>Catatan:</strong> File PDF yang terenkripsi atau dilindungi tidak dapat diunggah. 
        Mohon gunakan file PDF yang tidak terproteksi.
    </p>
</div>
@endsection
