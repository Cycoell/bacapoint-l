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

// Fungsi untuk membersihkan pesan error form
function clearFormErrors(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    form.querySelectorAll('.text-red-500.text-xs.mt-1').forEach(errorDiv => {
        errorDiv.textContent = ''; // Hapus teks error
    });
    form.querySelectorAll('.form-input, .form-input-file, .input-field-profile, .input-field-modal').forEach(input => {
        input.classList.remove('border-red-500'); // Hapus border merah
    });
}

// Fungsi untuk menampilkan pesan error validasi
function displayFormErrors(errors, formId) {
    clearFormErrors(formId); // Bersihkan error lama dulu
    const form = document.getElementById(formId);
    if (!form) return;

    for (const field in errors) {
        // Sesuaikan nama field untuk input file agar sesuai dengan ID error
        let errorField = field;
        if (field === 'cover_file') {
            errorField = 'cover_file';
        } else if (field === 'pdf_file') {
            errorField = 'pdf_file';
        }

        const errorDiv = document.getElementById(`${errorField}-error`);
        const inputField = form.querySelector(`[name="${field}"]`);

        if (errorDiv) {
            errorDiv.textContent = errors[field][0]; // Tampilkan pesan error pertama
        }
        if (inputField) {
            inputField.classList.add('border-red-500'); // Tambahkan border merah
        }
    }
}


// ** --- FUNGSI GLOBAL UNTUK ONCLICK DI BLADE --- **

// Fungsi untuk memuat konten berdasarkan nama section
window.loadContent = function(page, el = null) {
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
            } else if (page === 'collection') {
                initializeCollectionSectionListeners(); // Memastikan listener untuk collection dimuat
            } else if (page === 'grafik') { 
                initializeGrafikSection(); // Memastikan listener untuk grafik dimuat
            }
            // Tambahkan inisialisasi untuk section lain di sini jika diperlukan
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
};


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

// Fungsi untuk menampilkan modal Tambah Buku Baru (global agar bisa dipanggil dari onclick)
window.showAddBookModal = function() {
    const addBookModal = document.getElementById('addBookModal');
    if (addBookModal) {
        addBookModal.classList.remove('hidden');
        addBookModal.classList.add('flex');
    }
};

// Fungsi untuk menyembunyikan modal Tambah Buku Baru (global agar bisa dipanggil dari onclick)
window.hideAddBookModal = function() {
    const addBookModal = document.getElementById('addBookModal');
    if (addBookModal) {
        addBookModal.classList.add('hidden');
        addBookModal.classList.remove('flex');
        document.getElementById('addBookForm').reset();
        clearFormErrors('addBookForm');
    }
};

// Fungsi untuk menampilkan modal konfirmasi (confirmModal) (global agar bisa dipanggil dari onclick)
window.showConfirmModal = function() {
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) {
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    }
};

// Fungsi untuk menyembunyikan modal konfirmasi (confirmModal) (global agar bisa dipanggil dari onclick)
window.hideConfirmModal = function() {
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    }
};


// ** --- FUNGSI INISIALISASI LISTENER UNTUK SECTION DINAMIS --- **

// Fungsi untuk menginisialisasi event listener setelah section 'account' dimuat
function initializeAccountSectionListeners() {
    // Handle form submission for profile update using AJAX
    const updateProfileForm = document.getElementById('updateProfileForm');
    if (updateProfileForm) {
        updateProfileForm.addEventListener('submit', function(e) {
            e.preventDefault();

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
            .then(response => {
                if (response.status === 422) { // Unprocessable Entity (Validation Error)
                    return response.json().then(data => {
                        displayFormErrors(data.errors, 'updateProfileForm');
                        Swal.close();
                        Swal.fire('Gagal!', 'Terdapat kesalahan validasi.', 'error');
                        throw new Error('Validation Failed');
                    });
                }
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', data.message, 'success');
                    // Optional: Update UI if needed, or reload section if necessary
                    // window.loadContent('account'); // Bisa memuat ulang section untuk menampilkan data terbaru
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'Validation Failed') {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan perubahan.', 'error');
                }
            });
        });
    }

    // Handle form submission for password change using AJAX
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();

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
            .then(response => {
                if (response.status === 422) {
                    return response.json().then(data => {
                        displayFormErrors(data.errors, 'changePasswordForm');
                        Swal.close();
                        Swal.fire('Gagal!', 'Terdapat kesalahan validasi.', 'error');
                        throw new Error('Validation Failed');
                    });
                }
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', data.message, 'success');
                    document.getElementById('passwordModal').classList.add('hidden');
                    document.getElementById('passwordModal').classList.remove('flex');
                    form.reset();
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'Validation Failed') {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengubah password.', 'error');
                }
            });
        });
    }

    // Menangani perubahan input file untuk upload foto
    const uploadProfilePictureForm = document.getElementById('uploadProfilePictureForm');
    if (uploadProfilePictureForm) {
        const profilePictureInput = document.getElementById('profile_picture');
        if (profilePictureInput) {
            profilePictureInput.removeEventListener('change', function() { /* remove previous listener if any */ });
            profilePictureInput.addEventListener('change', function() {
                Swal.fire({
                    title: 'Mengunggah Foto...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                uploadProfilePictureForm.submit();
            });
        }
    }
}


// Fungsi untuk menginisialisasi event listener setelah section 'collection' dimuat
function initializeCollectionSectionListeners() {
    const addBookForm = document.getElementById('addBookForm');
    if (addBookForm) {
        addBookForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            Swal.fire({
                title: 'Menyimpan Buku...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (response.status === 422) {
                    return response.json().then(data => {
                        displayFormErrors(data.errors, 'addBookForm');
                        Swal.close();
                        Swal.fire('Gagal!', 'Terdapat kesalahan validasi.', 'error');
                        throw new Error('Validation Failed');
                    });
                }
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Sukses!', data.message, 'success');
                    hideAddBookModal();
                    // TODO: Refresh daftar buku di tabel (akan diimplementasikan nanti)
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'Validation Failed') {
                    Swal.fire('Error', 'Terjadi kesalahan saat menambahkan buku.', 'error');
                }
            });
        });
    }
}


// Fungsi untuk menginisialisasi event listener setelah section 'grafik' dimuat
// Dibuat secara eksplisit karena mungkin ada masalah caching atau scoping
window.initializeGrafikSection = function() { // Pastikan fungsi ini global
    const ctx = document.getElementById('genreChart')?.getContext('2d');
    if (!ctx) {
        console.error('Canvas for genreChart not found.');
        return;
    }

    const genreLabelsElement = document.getElementById('genreLabelsData');
    const genreCountsElement = document.getElementById('genreCountsData');

    // Menggunakan variabel lokal yang dideklarasikan di sini.
    // Variabel ini akan diinisialisasi dalam scope fungsi ini.
    let labels = []; 
    let counts = [];

    if (genreLabelsElement && genreCountsElement) {
        try {
            labels = JSON.parse(genreLabelsElement.textContent || '[]'); 
            counts = JSON.parse(genreCountsElement.textContent || '[]'); 
        } catch (e) {
            console.error("Error parsing genre data:", e);
            labels = ['Tidak ada data']; 
            counts = [0]; 
        }
    } else {
        // Fallback jika elemen tidak ditemukan (misal, tidak ada data genre)
        labels = ['Tidak ada data'];
        counts = [0];
    }
    
    // Hancurkan instance Chart sebelumnya jika ada
    if (window.genreChartInstance) {
        window.genreChartInstance.destroy();
    }

    // console.log untuk debugging, pastikan berada setelah 'labels' dan 'counts' diisi
    console.log("Data for chart:", { labels: labels, counts: counts }); 

    window.genreChartInstance = new Chart(ctx, { 
        type: 'bar',
        data: {
            labels: labels, 
            datasets: [{
                label: 'Jumlah Buku per Genre',
                data: counts, 
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)', 
                    'rgba(255, 99, 132, 0.6)', 
                    'rgba(255, 206, 86, 0.6)', 
                    'rgba(75, 192, 192, 0.6)', 
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'  
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, 
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y;
                            return label;
                        }
                    }
                }
            }
        }
    });
}


// ** --- INISIALISASI SAAT DOM LENGKAP --- **

// Saat halaman pertama kali dibuka, langsung tampilkan "Profile"
document.addEventListener('DOMContentLoaded', function () {
    const current = sessionStorage.getItem('currentPage') || 'account';
    const activeBtn = document.getElementById(`btn-${current}`);
    window.loadContent(current, activeBtn); // Menggunakan window.loadContent

    // Event listener untuk tombol Yes/No di confirmModal (karena confirmModal ada di layout utama)
    const confirmYesBtn = document.getElementById('confirmYes');
    const confirmNoBtn = document.getElementById('confirmNo');

    if (confirmYesBtn) {
        confirmYesBtn.addEventListener('click', function() {
            console.log('Konfirmasi Ya diklik');
            // Logika ketika 'Ya' diklik - Anda akan memanggil fungsi untuk menghapus di sini
            // Misalnya: deleteBookAction(); // Panggil fungsi penghapusan buku
            window.hideConfirmModal(); // Sembunyikan modal setelah aksi
        });
    }

    if (confirmNoBtn) {
        confirmNoBtn.addEventListener('click', function() {
            console.log('Konfirmasi Batal diklik');
            window.hideConfirmModal(); // Sembunyikan modal
        });
    }
});