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
                //
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
            else if (page === 'point') {
                initializePointSection();
            }
            else if (page === 'riwayat-membaca') {
            console.log('Riwayat Membaca section loaded.');
            }
        })
        .catch(async error => {
            console.error('Error loading content:', error);
            let errorMessage = "Terjadi kesalahan saat memuat konten.";
            if (error.message.includes('Halaman tidak ditemukan')) {
                errorMessage = "Halaman tidak ditemukan.";
            } else if (error.message.includes('HTTP error!')) {
                errorMessage = `Gagal memuat konten: ${error.message}`;
            }
            Swal.fire('Error', errorMessage, 'error');
            document.getElementById('main-content').innerHTML = `<p class="text-red-500 text-center">${errorMessage}</p>`;
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

// Fungsi untuk menampilkan modal Tambah/Edit Buku Baru (global agar bisa dipanggil dari onclick)
window.showAddBookModal = function() {
    const addBookModal = document.getElementById('addBookModal');
    if (addBookModal) {
        addBookModal.classList.remove('hidden');
        addBookModal.classList.add('flex');
    }
};

// Fungsi untuk menyembunyikan modal Tambah/Edit Buku Baru (global agar bisa dipanggil dari onclick)
window.hideAddBookModal = function() {
    const addBookModal = document.getElementById('addBookModal');
    if (addBookModal) {
        addBookModal.classList.add('hidden');
        addBookModal.classList.remove('flex');
        document.getElementById('addBookForm').reset();
        clearFormErrors('addBookForm');

        // Reset form ke mode "Tambah Buku Baru" default
        document.getElementById('addBookFormMethod').value = 'POST';
        document.getElementById('editBookId').value = '';
        document.getElementById('addBookModalTitle').textContent = 'Tambah Buku Baru';
        document.getElementById('cover_file').required = true;
        document.getElementById('pdf_file').required = true;
        document.getElementById('currentCoverInfo').textContent = '';
        document.getElementById('currentPdfInfo').textContent = '';
    }
};

// Fungsi untuk menampilkan modal konfirmasi (confirmModal) (global agar bisa dipanggil dari onclick)
window.showConfirmModal = function(bookIdToDelete) { // Terima bookIdToDelete
    const confirmModal = document.getElementById('confirmModal');
    const confirmYesBtn = document.getElementById('confirmYes');

    if (confirmModal && confirmYesBtn) {
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
        // Set data-book-id pada tombol Yes untuk digunakan saat konfirmasi
        confirmYesBtn.dataset.bookId = bookIdToDelete;
    }
};

// Fungsi untuk menyembunyikan modal konfirmasi (confirmModal) (global agar bisa dipanggil dari onclick)
window.hideConfirmModal = function() {
    const confirmModal = document.getElementById('confirmModal');
    const confirmYesBtn = document.getElementById('confirmYes');
    if (confirmModal) {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
        // Hapus data-book-id setelah modal disembunyikan
        if (confirmYesBtn) {
            delete confirmYesBtn.dataset.bookId;
        }
    }
};

// FUNGSI UNTUK KONFIRMASI HAPUS
window.confirmDelete = function(bookId) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak akan dapat mengembalikan ini!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteBook(bookId); // Panggil fungsi deleteBook jika dikonfirmasi
        }
    });
};

// FUNGSI UNTUK HAPUS BUKU VIA AJAX
function deleteBook(bookId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: 'Menghapus buku...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/books/${bookId}`, { // Sesuaikan dengan rute API delete Anda
        method: 'DELETE', // Menggunakan metode DELETE
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Terhapus!',
                data.message,
                'success'
            );
            // Muat ulang section 'collection' untuk memperbarui tabel
            window.loadContent('collection', document.getElementById('btn-collection'));
        } else {
            Swal.fire(
                'Gagal!',
                data.message,
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire(
            'Error',
            'Terjadi kesalahan saat menghapus buku: ' + error.message,
            'error'
        );
    });
}


// FUNGSI BARU UNTUK EDIT BUKU (MENAMPILKAN MODAL DAN MENGISI DATA)
window.editBook = function(bookId) {
    // Dapatkan baris buku dari tabel yang sedang aktif menggunakan ID unik di TR
    const bookRow = document.getElementById(`book-row-${bookId}`); // Menggunakan ID pada TR

    if (!bookRow) {
        console.error('Book row not found for ID:', bookId);
        Swal.fire('Error', 'Data buku tidak ditemukan di tabel.', 'error');
        return;
    }

    // Ambil data dari sel tabel. Sesuaikan indeks kolom jika struktur tabel berubah.
    const bookData = {
        id: bookId,
        judul: bookRow.children[1].textContent.trim(),
        author: bookRow.children[2].textContent.trim(),
        genre: bookRow.children[3].textContent.trim() === '-' ? '' : bookRow.children[3].textContent.trim(),
        total_pages: bookRow.children[4].textContent.trim(),
        point_value: bookRow.children[5].textContent.trim()
    };

    // Set modal ke mode "Edit"
    document.getElementById('addBookModalTitle').textContent = 'Edit Buku';
    document.getElementById('addBookFormMethod').value = 'PUT'; // Set method ke PUT
    document.getElementById('editBookId').value = bookId; // Simpan ID buku yang sedang diedit

    // Isi form dengan data buku
    document.getElementById('judul').value = bookData.judul;
    document.getElementById('author').value = bookData.author;
    // Kolom 'tahun' tidak ada di tabel, jadi tidak bisa langsung diambil dari children.
    // Jika perlu diisi, Anda harus mengambilnya dari data-attribute di TR, atau melalui AJAX fetch.
    document.getElementById('tahun').value = ''; // Biarkan kosong atau atur nilai default jika tidak ada di tabel
    document.getElementById('genre').value = bookData.genre;
    document.getElementById('point_value').value = bookData.point_value;

    // Untuk input file: kosongkan nilai dan berikan pesan bahwa bisa dikosongkan untuk mempertahankan yang lama.
    document.getElementById('cover_file').value = '';
    document.getElementById('pdf_file').value = '';
    document.getElementById('cover_file').required = false; // Tidak lagi wajib untuk edit
    document.getElementById('pdf_file').required = false; // Tidak lagi wajib untuk edit
    document.getElementById('currentCoverInfo').textContent = 'Biarkan kosong untuk mempertahankan cover lama.';
    document.getElementById('currentPdfInfo').textContent = 'Biarkan kosong untuk mempertahankan file PDF lama.';

    // Tampilkan modal
    showAddBookModal();
};


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
            const bookId = document.getElementById('editBookId').value;
            const isEdit = bookId !== '';

            Swal.fire({
                title: isEdit ? 'Memperbarui Buku...' : 'Menyimpan Buku...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Pastikan URL action sesuai dengan mode (add/edit)
            const actionUrl = isEdit ? `/admin/books/${bookId}` : form.action;

            fetch(actionUrl, {
                method: 'POST', // Method akan jadi POST, _method akan menangani PUT
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
                    // Muat ulang section 'collection' untuk memperbarui tabel
                    window.loadContent('collection', document.getElementById('btn-collection'));
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message !== 'Validation Failed') {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan buku.', 'error');
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

// FUNGSI INISIALISASI UNTUK SECTION 'POINT'
function initializePointSection() {
    const defaultTab = document.getElementById('tab-all');
    if (defaultTab) {
        defaultTab.classList.add('tab-point-active');
        document.getElementById('content-all').classList.remove('hidden');
    }
}

// INISIALISASI SAAT DOM LENGKAP
document.addEventListener('DOMContentLoaded', function () {
    const current = sessionStorage.getItem('currentPage') || 'account';
    const activeBtn = document.getElementById(`btn-${current}`);
    window.loadContent(current, activeBtn);

    const confirmYesBtn = document.getElementById('confirmYes');
    const confirmNoBtn = document.getElementById('confirmNo');

    if (confirmYesBtn) {
        confirmYesBtn.addEventListener('click', function() {
        });
    }

    if (confirmNoBtn) {
        confirmNoBtn.addEventListener('click', function() {
            console.log('Konfirmasi Batal diklik');
            window.hideConfirmModal();
        });
    }
});