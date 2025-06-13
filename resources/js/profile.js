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

            // Setelah konten dimuat, inisialisasi event listener spesifik untuk section tersebut
            if (page === 'account') {
                initializeAccountSectionListeners();
            }
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
window.loadContent = loadContent; // Membuat loadContent bisa diakses global

// Fungsi untuk mengaktifkan/menonaktifkan input (global agar bisa dipanggil dari onclick)
window.toggleEdit = function(field) {
    const input = document.querySelector(`[name="${field}"]`);
    if (input) {
        if (input.readOnly === true || input.disabled === true) {
            input.readOnly = false;
            input.disabled = false;
            input.classList.remove('bg-gray-50');
            input.classList.add('bg-white');
            input.focus();
        } else {
            input.readOnly = true;
            input.disabled = true;
            input.classList.add('bg-gray-50');
            input.classList.remove('bg-white');
        }
    }
};

// Fungsi untuk menampilkan modal ganti password (global agar bisa dipanggil dari onclick)
window.showChangePasswordModal = function() {
    const passwordModal = document.getElementById('passwordModal');
    if (passwordModal) {
        passwordModal.classList.remove('hidden');
        passwordModal.classList.add('flex'); // Tambahkan flex saat ditampilkan
    }
};


// Fungsi untuk menginisialisasi event listener setelah section 'account' dimuat
function initializeAccountSectionListeners() {
    // Handle form submission for profile update using AJAX
    const updateProfileForm = document.getElementById('updateProfileForm');
    if (updateProfileForm) {
        updateProfileForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit form default

            const form = e.target;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Menyimpan Perubahan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(form.action, {
                method: 'POST', // Method akan jadi POST karena @method('PUT')
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', data.message, 'success');
                    // Optional: Update UI if needed, or reload section if necessary
                    // loadContent('account'); // Reload bagian akun untuk menampilkan data terbaru (jika diperlukan)
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan perubahan.', 'error');
            });
        });
    }

    // Handle form submission for password change using AJAX
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit form default

            const form = e.target;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Mengubah Password...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(form.action, {
                method: 'POST', // Method akan jadi POST karena @method('PUT')
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', data.message, 'success');
                    document.getElementById('passwordModal').classList.add('hidden'); // Sembunyikan modal
                    document.getElementById('passwordModal').classList.remove('flex'); // Hapus flex juga
                    form.reset(); // Reset form password
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat mengubah password.', 'error');
            });
        });
    }

    // Menangani perubahan input file untuk upload foto
    const uploadProfilePictureForm = document.getElementById('uploadProfilePictureForm');
    if (uploadProfilePictureForm) {
        const profilePictureInput = document.getElementById('profile_picture');
        if (profilePictureInput) {
            profilePictureInput.removeEventListener('change', function() { /* remove previous listener if any */ }); // Remove listener to prevent duplicates
            profilePictureInput.addEventListener('change', function() {
                // Form akan otomatis terkirim via onchange di label for input file
                Swal.fire({
                    title: 'Mengunggah Foto...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                uploadProfilePictureForm.submit(); // Submit form saat file dipilih
            });
        }
    }
}


// Saat halaman pertama kali dibuka, langsung tampilkan "Profile"
document.addEventListener('DOMContentLoaded', function () {
    const current = sessionStorage.getItem('currentPage') || 'account';
    const activeBtn = document.getElementById(`btn-${current}`);
    loadContent(current, activeBtn);
});