// Fungsi untuk menampilkan loading state
function showLoading() {
    document.getElementById('main-content').innerHTML = `
        <div class="flex justify-center items-center h-full">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
        </div>
    `;
}

// Fungsi untuk menampilkan error
function showError(message) {
    document.getElementById('main-content').innerHTML = `
        <div class="bg-red-50 border-l-4 border-red-500 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">${message}</p>
                </div>
            </div>
        </div>
    `;
}

// Fungsi untuk memuat konten berdasarkan nama section
function loadContent(page, el = null) {
    showLoading();

    fetch(`/profile/${page}`)
        .then(response => {
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error("Halaman tidak ditemukan");
                }
                throw new Error("Gagal memuat konten");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('main-content').innerHTML = html;

            // Hapus class active dari semua tombol
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('border-l-4', 'border-green-500', 'bg-green-100', 'font-semibold', 'text-green-700');
                btn.classList.add('text-gray-800', 'hover:font-semibold');
            });

            // Tambahkan class active ke tombol yang diklik
            if (el) {
                el.classList.add('border-l-4', 'border-green-500', 'bg-green-100', 'font-semibold', 'text-green-700');
                el.classList.remove('text-gray-800');
            }

            // Simpan ke sessionStorage supaya bisa dipakai ulang saat reload
            sessionStorage.setItem('currentPage', page);
        })
.catch(async error => {
    let errorMessage = error.message;
    try {
        const response = await fetch(`/profile/${sessionStorage.getItem('currentPage')}`);
        if (!response.ok) {
            const text = await response.text();
            errorMessage += ` - Server response: ${response.status} ${response.statusText} - ${text}`;
        }
    } catch (e) {
        errorMessage += ` - Additional fetch error: ${e.message}`;
    }
    showError(errorMessage);
});
}
window.loadContent = loadContent;

// Saat halaman pertama kali dibuka, langsung tampilkan "Profile"
document.addEventListener('DOMContentLoaded', function () {
    const current = sessionStorage.getItem('currentPage') || 'account';
    const activeBtn = document.getElementById(`btn-${current}`);
    loadContent(current, activeBtn);
});
