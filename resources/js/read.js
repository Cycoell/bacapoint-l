// PDF.js Variables
let pdfDoc = null;
window.pageNum = 1; // Variabel global untuk akses dari konsol
let scale = 1;
let initialScale = 1;
let pageRendering = false;
let canvas = null;
let ctx = null;

// DOM Elements
window.elements = {}; // Variabel global untuk akses dari konsol

// Configuration
const config = {
    pdfWorkerSrc: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js',
    saveProgressEndpoint: '/api/reading-progress', // Laravel API endpoint untuk menyimpan progres
    getProgressEndpoint: '/api/reading-progress/status', // Laravel API endpoint untuk mendapatkan progres
    zoomStep: 0.05,
    maxZoom: 3,
    minZoom: 0.3,
    progressMilestones: [25, 50, 75, 100], // Milestone poin dalam persentase
    progressSaveInterval: 10000, // Simpan progres setiap 10 detik (ms)
};

// Progress Tracking Variables
window.totalPages = 1; // Variabel global untuk akses dari konsol
let bookId = 0;
let userId = '';
let isLoggedIn = false;
let lastSavedPage = 0; // Halaman terakhir yang berhasil disimpan ke backend
let lastSavedPercentage = 0; // Persentase terakhir yang berhasil disimpan ke backend
let progressSaveTimer = null; // Timer untuk menyimpan progres

/**
 * Initialize DOM elements
 */
function initializeElements() {
    // Menginisialisasi objek elements sebagai properti window
    window.elements = { 
        canvas: document.getElementById("pdfCanvas"),
        pdfContainer: document.getElementById("pdfContainer"),
        loadingMessage: document.getElementById("loadingMessage"),
        errorMessage: document.getElementById("errorMessage"),
        pageInfo: document.getElementById("pageInfo"),
        zoomLevel: document.getElementById("zoomLevel"),
        prevPage: document.getElementById("prevPage"),
        nextPage: document.getElementById("nextPage"),
        zoomInBtn: document.getElementById("zoomInBtn"),
        zoomOutBtn: document.getElementById("zoomOutBtn"),
        resetZoomBtn: document.getElementById("resetZoomBtn"),
        bookIdInput: document.getElementById("bookId"),
        userIdInput: document.getElementById("userId"),
        isLoggedInInput: document.getElementById("isLoggedIn"),
        totalPagesInput: document.getElementById("totalPages"),
        userTotalPointsSpan: document.getElementById("userTotalPoints"), // Span untuk menampilkan total poin user
    };

    // Menggunakan window.elements untuk mengakses properti objek
    canvas = window.elements.canvas; 
    // Pastikan ini juga benar: ctx = canvas ? canvas.getContext("2d") : null;
    ctx = canvas ? canvas.getContext("2d") : null;


    // Inisialisasi variabel global dari hidden inputs, menggunakan window.elements
    bookId = parseInt(window.elements.bookIdInput?.value) || 0;
    userId = window.elements.userIdInput?.value || '';
    isLoggedIn = window.elements.isLoggedInInput?.value === 'true';
    window.totalPages = parseInt(window.elements.totalPagesInput?.value) || 1; // Mengisi window.totalPages dari input tersembunyi
}

/**
 * Show error message and hide loading
 */
function showError() {
    if (window.elements.loadingMessage) { // Menggunakan window.elements
        window.elements.loadingMessage.classList.add('hidden');
    }
    if (window.elements.errorMessage) { // Menggunakan window.elements
        window.elements.errorMessage.classList.remove('hidden');
    }
}

/**
 * Show PDF canvas and hide loading
 */
function showPDF() {
    if (window.elements.loadingMessage) { // Menggunakan window.elements
        window.elements.loadingMessage.classList.add('hidden');
    }
    if (window.elements.canvas) { // Menggunakan window.elements
        window.elements.canvas.classList.remove('hidden');
    }
}

/**
 * Check if file path is valid
 */
function validateFilePath() {
    const filePath = window.elements.pdfContainer?.dataset.filepath; // Menggunakan window.elements
    return filePath && filePath !== '';
}

/**
 * Render a single page
 */
const renderPage = (num) => {
    if (!pdfDoc || !canvas || !ctx) return;
    
    pageRendering = true;
    
    pdfDoc.getPage(num).then(page => {
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        
        page.render(renderContext).promise.then(() => {
            pageRendering = false;
            updatePageInfo();
            // Setiap kali halaman baru dirender, coba simpan progres
            saveProgress(); 
        });
    }).catch(error => {
        console.error('Error rendering page:', error);
        pageRendering = false;
        showError(); // Tampilkan pesan error jika render gagal
    });
};

/**
 * Queue render page to avoid conflicts
 */
const queueRenderPage = (num) => {
    if (pageRendering) {
        // Jika rendering sedang berlangsung, tunggu sebentar lalu coba lagi
        setTimeout(() => queueRenderPage(num), 100);
    } else {
        renderPage(num);
    }
};

/**
 * Update page info display
 */
function updatePageInfo() {
    if (window.elements.pageInfo && pdfDoc) { // Menggunakan window.elements
        window.elements.pageInfo.textContent = `Page ${window.pageNum} of ${window.totalPages}`; // Menggunakan window.pageNum dan window.totalPages
    }
}

/**
 * Update zoom level display
 */
function updateZoomLevel() {
    if (window.elements.zoomLevel) { // Menggunakan window.elements
        window.elements.zoomLevel.textContent = `${Math.round((scale / initialScale) * 100)}%`;
    }
}

/**
 * Navigate to previous page
 */
function goToPrevPage() {
    if (window.pageNum <= 1) return; // Menggunakan window.pageNum
    window.pageNum--; // Menggunakan window.pageNum
    queueRenderPage(window.pageNum); // Menggunakan window.pageNum
}

/**
 * Navigate to next page
 */
function goToNextPage() {
    if (!pdfDoc || window.pageNum >= window.totalPages) return; // Menggunakan window.pageNum dan window.totalPages
    window.pageNum++; // Menggunakan window.pageNum
    queueRenderPage(window.pageNum); // Menggunakan window.pageNum
}

/**
 * Zoom in
 */
function zoomIn() {
    scale += config.zoomStep;
    scale = Math.min(scale, config.maxZoom);
    resizeCanvasToFitScale();
}

/**
 * Zoom out
 */
function zoomOut() {
    scale -= config.zoomStep;
    scale = Math.max(scale, config.minZoom);
    resizeCanvasToFitScale();
}

/**
 * Reset zoom to initial scale
 */
function resetZoom() {
    scale = initialScale;
    resizeCanvasToFitScale();
}

/**
 * Resize canvas with current scale
 */
function resizeCanvasToFitScale() {
    if (!pdfDoc || !canvas || !ctx) return;

    pdfDoc.getPage(window.pageNum).then(page => { // Menggunakan window.pageNum
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        
        page.render(renderContext);
        updateZoomLevel();
    }).catch(error => {
        console.error('Error resizing canvas:', error);
    });
}

/**
 * Resize canvas to fit container
 */
function resizeCanvasToFitContainer() {
    if (!pdfDoc || !window.elements.pdfContainer) return; // Menggunakan window.elements
    
    const containerWidth = window.elements.pdfContainer.clientWidth; // Menggunakan window.elements
    const containerHeight = window.elements.pdfContainer.clientHeight; // Menggunakan window.elements

    pdfDoc.getPage(window.pageNum).then(page => { // Menggunakan window.pageNum
        const tempViewport = page.getViewport({ scale: 1.0 });
        const scaleX = containerWidth / tempViewport.width;
        const scaleY = containerHeight / tempViewport.height;

        scale = Math.min(scaleX, scaleY) * 0.9; // 90% to add some padding
        initialScale = scale;
        renderPage(window.pageNum); // Menggunakan window.pageNum
    }).catch(error => {
        console.error('Error fitting canvas to container:', error);
    });
}

/**
 * Load PDF from file path
 */
function loadPDF() {
    const filePath = window.elements.pdfContainer?.dataset.filepath; // Menggunakan window.elements
    
    if (!filePath || filePath === '') {
        console.error('No file path provided');
        showError();
        return;
    }

    fetch(filePath)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.arrayBuffer();
        })
        .then(data => {
            const typedarray = new Uint8Array(data);

            return pdfjsLib.getDocument({
                data: typedarray,
                workerSrc: config.pdfWorkerSrc
            }).promise;
        })
        .then(pdf => {
            pdfDoc = pdf;
            // Dapatkan total halaman dari PDF.js, pastikan konsisten dengan backend
            window.totalPages = pdfDoc.numPages; // Mengisi window.totalPages dari PDF.js
            window.elements.totalPagesInput.value = window.totalPages; // Mengisi window.elements dan window.totalPages


            showPDF();
            
            // Muat progres terakhir pengguna dari backend jika user login
            if (isLoggedIn && bookId) {
                // Request untuk mendapatkan progres awal
                fetch(`${config.getProgressEndpoint}?book_id=${bookId}&user_id=${userId}`)
                    .then(response => {
                        if (!response.ok) {
                            // Jika ada error non-2xx (misal 404), throw error
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json(); // Pastikan parsing sebagai JSON
                    })
                    .then(data => {
                        if (data.success && data.progress) {
                            window.pageNum = data.progress.last_page_read > 0 ? data.progress.last_page_read : 1; // Mengisi window.pageNum
                            lastSavedPage = window.pageNum;
                            lastSavedPercentage = data.progress.progress_percentage || 0;
                        } else {
                            window.pageNum = 1; // Mengisi window.pageNum
                            lastSavedPage = 1;
                            lastSavedPercentage = 0;
                        }
                        // Render halaman setelah mendapatkan progres atau set ke halaman 1
                        setTimeout(() => {
                            resizeCanvasToFitContainer();
                        }, 300);
                    })
                    .catch(error => {
                        console.error('Error loading initial progress:', error);
                        // Jika gagal ambil progres, tetap render dari halaman 1
                        window.pageNum = 1; // Mengisi window.pageNum
                        lastSavedPage = 1;
                        lastSavedPercentage = 0;
                        setTimeout(() => {
                            resizeCanvasToFitContainer();
                        }, 300);
                    });
            } else {
                // Jika tidak login, selalu mulai dari halaman 1
                window.pageNum = 1; // Mengisi window.pageNum
                lastSavedPage = 1;
                lastSavedPercentage = 0;
                setTimeout(() => {
                    resizeCanvasToFitContainer();
                }, 300);
            }

            // Mulai timer untuk menyimpan progres secara berkala
            if (isLoggedIn && bookId) {
                startProgressSaveTimer();
            }
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            showError();
        });
}

/**
 * Send reading progress to the server.
 */
function saveProgress() {
    if (!isLoggedIn || bookId === 0 || userId === '' || window.totalPages === 0) { // Menggunakan window.totalPages
        return; // Jangan simpan progres jika tidak login atau info penting hilang
    }

    const currentProgressPercentage = Math.min(100, Math.round((window.pageNum / window.totalPages) * 100)); // Menggunakan window.pageNum dan window.totalPages


    // Hanya simpan jika halaman atau persentase telah berubah dari yang terakhir disimpan
    // Kecualikan kasus halaman 100% agar selalu terkirim untuk memastikan completion
    if (window.pageNum === lastSavedPage && currentProgressPercentage === lastSavedPercentage && currentProgressPercentage !== 100) { // Menggunakan window.pageNum
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(config.saveProgressEndpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            book_id: bookId,
            user_id: userId, // User ID is handled by middleware on backend, but good to send for clarity
            current_page: window.pageNum, // Menggunakan window.pageNum
            progress_percentage: currentProgressPercentage,
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            lastSavedPage = data.current_page; // Perbarui halaman terakhir yang disimpan
            lastSavedPercentage = data.current_percentage; // Perbarui persentase terakhir yang disimpan
            
            if (data.points_awarded_this_session > 0) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: `+${data.points_awarded_this_session} Poin!`,
                    text: data.message,
                });
                // Perbarui total poin di UI
                if (window.elements.userTotalPointsSpan) { // Menggunakan window.elements
                    window.elements.userTotalPointsSpan.textContent = data.user_total_points;
                }
            }
            console.log('Progres berhasil disimpan:', data.message);
        } else {
            console.error('Gagal menyimpan progres:', data.message);
            // Swal.fire('Gagal', data.message, 'error'); // Opsional: mungkin terlalu mengganggu
        }
    })
    .catch(error => {
        console.error('Error saat menyimpan progres:', error);
        // Swal.fire('Error', 'Terjadi kesalahan saat menyimpan progres.', 'error'); // Opsional: mungkin terlalu mengganggu
    });
}

/**
 * Start periodic progress saving timer.
 */
function startProgressSaveTimer() {
    // Hapus timer sebelumnya jika ada
    if (progressSaveTimer) {
        clearInterval(progressSaveTimer);
    }

    // Mulai timer untuk menyimpan progres setiap X detik
    progressSaveTimer = setInterval(() => {
        saveProgress(); // Kirim halaman dan persentase saat ini
    }, config.progressSaveInterval);
}

/**
 * Add event listeners
 */
function addEventListeners() {
    // Navigation buttons
    if (window.elements.prevPage) { // Menggunakan window.elements
        window.elements.prevPage.addEventListener("click", goToPrevPage);
    }
    
    if (window.elements.nextPage) { // Menggunakan window.elements
        window.elements.nextPage.addEventListener("click", goToNextPage);
    }

    // Zoom buttons
    if (window.elements.zoomInBtn) { // Menggunakan window.elements
        window.elements.zoomInBtn.addEventListener("click", zoomIn);
    }
    
    if (window.elements.zoomOutBtn) { // Menggunakan window.elements
        window.elements.zoomOutBtn.addEventListener("click", zoomOut);
    }

    if (window.elements.resetZoomBtn) { // Menggunakan window.elements
        window.elements.resetZoomBtn.addEventListener("click", resetZoom);
    }

    // Window resize
    window.addEventListener('resize', resizeCanvasToFitContainer);

    // Keyboard shortcuts
    document.addEventListener('keydown', handleKeyboardShortcuts);
}

/**
 * Handle keyboard shortcuts
 */
function handleKeyboardShortcuts(event) {
    if (event.target.tagName.toLowerCase() === 'input') return;

    switch(event.key) {
        case 'ArrowLeft':
            event.preventDefault();
            goToPrevPage();
            break;
        case 'ArrowRight':
            event.preventDefault();
            goToNextPage();
            break;
        case '+':
        case '=':
            event.preventDefault();
            zoomIn();
            break;
        case '-':
            event.preventDefault();
            zoomOut();
            break;
        case '0':
            if (event.ctrlKey) {
                event.preventDefault();
                resetZoom();
            }
            break;
    }
}

/**
 * Initialize the application
 */
function initialize() {
    // Pastikan GlobalWorkerOptions.workerSrc diatur sebelum memuat PDF apa pun
    // Ini mengatasi peringatan "Deprecated API usage"
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = config.pdfWorkerSrc;
    } else {
        console.warn('pdfjsLib is not defined. PDF.js might not be loaded correctly.');
    }

    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
        return;
    }

    initializeElements();

    // Check if required elements exist
    if (!window.elements.canvas || !window.elements.pdfContainer) { // Menggunakan window.elements
        console.error('Required DOM elements not found');
        showError();
        return;
    }

    // Validate file path
    if (!validateFilePath()) {
        console.error('Invalid file path');
        showError();
        return;
    }

    addEventListeners();
    
    // Load PDF after a short delay to ensure everything is ready
    setTimeout(() => {
        loadPDF();
    }, 100);
}

// Auto-initialize when script loads
initialize();

// Global functions for backward compatibility (if needed)
window.zoomIn = zoomIn;
window.zoomOut = zoomOut;
window.resetZoom = resetZoom;

// Cleanup timer when leaving the page
window.addEventListener('beforeunload', () => {
    if (progressSaveTimer) {
        clearInterval(progressSaveTimer);
        // Coba simpan progres terakhir sebelum meninggalkan halaman
        saveProgress();
    }
});