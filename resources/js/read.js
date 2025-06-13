// PDF.js Variables
let pdfDoc = null,
    pageNum = 1,
    scale = 1,
    initialScale = 1,
    pageRendering = false,
    canvas = null,
    ctx = null;

// DOM Elements
let elements = {};

// Configuration
const config = {
    pdfWorkerSrc: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js',
    finishReadingEndpoint: '../config/finish_reading.php',
    zoomStep: 0.05,
    maxZoom: 3,
    minZoom: 0.3
};

/**
 * Initialize DOM elements
 */
function initializeElements() {
    elements = {
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
        finishReading: document.getElementById("finishReading"),
        bookId: document.getElementById("bookId"),
        userId: document.getElementById("userId"),
        canEarnPoints: document.getElementById("canEarnPoints"),
        isLoggedIn: document.getElementById("isLoggedIn")
    };

    canvas = elements.canvas;
    ctx = canvas ? canvas.getContext("2d") : null;
}

/**
 * Show error message and hide loading
 */
function showError() {
    if (elements.loadingMessage) {
        elements.loadingMessage.classList.add('hidden');
    }
    if (elements.errorMessage) {
        elements.errorMessage.classList.remove('hidden');
    }
}

/**
 * Show PDF canvas and hide loading
 */
function showPDF() {
    if (elements.loadingMessage) {
        elements.loadingMessage.classList.add('hidden');
    }
    if (elements.canvas) {
        elements.canvas.classList.remove('hidden');
    }
}

/**
 * Check if file path is valid
 */
function validateFilePath() {
    const filePath = elements.pdfContainer?.dataset.filepath;
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
        });
    }).catch(error => {
        console.error('Error rendering page:', error);
        pageRendering = false;
    });
};

/**
 * Queue render page to avoid conflicts
 */
const queueRenderPage = (num) => {
    if (pageRendering) {
        setTimeout(() => queueRenderPage(num), 100);
    } else {
        renderPage(num);
    }
};

/**
 * Update page info display
 */
function updatePageInfo() {
    if (elements.pageInfo && pdfDoc) {
        elements.pageInfo.textContent = `Page ${pageNum} of ${pdfDoc.numPages}`;
    }
}

/**
 * Update zoom level display
 */
function updateZoomLevel() {
    if (elements.zoomLevel) {
        elements.zoomLevel.textContent = `${Math.round((scale / initialScale) * 100)}%`;
    }
}

/**
 * Navigate to previous page
 */
function goToPrevPage() {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
}

/**
 * Navigate to next page
 */
function goToNextPage() {
    if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
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

    pdfDoc.getPage(pageNum).then(page => {
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        
        page.render(renderContext);
        updateZoomLevel();
        updatePageInfo();
    }).catch(error => {
        console.error('Error resizing canvas:', error);
    });
}

/**
 * Resize canvas to fit container
 */
function resizeCanvasToFitContainer() {
    if (!pdfDoc || !elements.pdfContainer) return;
    
    const containerWidth = elements.pdfContainer.clientWidth;
    const containerHeight = elements.pdfContainer.clientHeight;

    pdfDoc.getPage(pageNum).then(page => {
        const tempViewport = page.getViewport({ scale: 1.0 });
        const scaleX = containerWidth / tempViewport.width;
        const scaleY = containerHeight / tempViewport.height;

        scale = Math.min(scaleX, scaleY) * 0.9; // 90% to add some padding
        initialScale = scale;
        renderPage(pageNum);
    }).catch(error => {
        console.error('Error fitting canvas to container:', error);
    });
}

/**
 * Load PDF from file path
 */
function loadPDF() {
    const filePath = elements.pdfContainer?.dataset.filepath;
    
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
            showPDF();
            
            // Wait a bit for DOM to be ready, then fit to container
            setTimeout(() => {
                resizeCanvasToFitContainer();
            }, 300);
        })
        .catch(error => {
            console.error("Error loading PDF:", error);
            showError();
        });
}

/**
 * Handle finish reading functionality
 */
function handleFinishReading() {
    const bookId = elements.bookId?.value;
    const userId = elements.userId?.value;
    const isLoggedIn = elements.isLoggedIn?.value === 'true';

    // Check if user is logged in
    if (!isLoggedIn || !userId) {
        Swal.fire({
            icon: 'warning',
            title: 'Login Diperlukan',
            text: 'Anda harus login terlebih dahulu untuk menyelesaikan pembacaan dan mendapatkan poin.',
            showCancelButton: true,
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to login page
                window.location.href = '/login';
            }
        });
        return;
    }

    // Proceed with finish reading confirmation
    Swal.fire({
        title: 'Konfirmasi',
        text: "Apakah Anda yakin sudah menyelesaikan membaca buku ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Selesai!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            submitFinishReading(bookId, userId);
        }
    });
}

/**
 * Submit finish reading to server
 */
function submitFinishReading(bookId, userId) {
    fetch(config.finishReadingEndpoint, {
        method: "POST",
        headers: { 
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: `book_id=${bookId}&user_id=${userId}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Sukses!',
                text: data.message,
                icon: 'success',
                confirmButtonText: 'OK',
                willClose: () => {
                    // Remove finish reading button
                    if (elements.finishReading) {
                        elements.finishReading.remove();
                    }
                    
                    // Update points in UI if element exists
                    updateUserPoints(data.points);
                }
            });
        } else {
            Swal.fire('Gagal', data.message, 'error');
        }
    })
    .catch(error => {
        console.error("Error submitting finish reading:", error);
        Swal.fire('Error', 'Terjadi kesalahan saat memproses', 'error');
    });
}

/**
 * Update user points in UI
 */
function updateUserPoints(newPoints) {
    const pointsElement = document.querySelector("[data-user-points]");
    if (pointsElement && newPoints) {
        const currentPoints = parseInt(pointsElement.textContent) || 0;
        pointsElement.textContent = currentPoints + newPoints;
    }
}

/**
 * Add event listeners
 */
function addEventListeners() {
    // Navigation buttons
    if (elements.prevPage) {
        elements.prevPage.addEventListener("click", goToPrevPage);
    }
    
    if (elements.nextPage) {
        elements.nextPage.addEventListener("click", goToNextPage);
    }

    // Zoom buttons
    if (elements.zoomInBtn) {
        elements.zoomInBtn.addEventListener("click", zoomIn);
    }
    
    if (elements.zoomOutBtn) {
        elements.zoomOutBtn.addEventListener("click", zoomOut);
    }

    if (elements.resetZoomBtn) {
        elements.resetZoomBtn.addEventListener("click", resetZoom);
    }

    // Finish reading button - only for logged in users
    if (elements.finishReading && elements.canEarnPoints?.value === 'true' && elements.isLoggedIn?.value === 'true') {
        elements.finishReading.addEventListener("click", handleFinishReading);
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
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
        return;
    }

    initializeElements();

    // Check if required elements exist
    if (!elements.canvas || !elements.pdfContainer) {
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