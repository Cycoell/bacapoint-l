// resources/js/search.js

class BookSearch {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.searchResults = document.getElementById('searchResults');
        this.searchResultsContent = document.getElementById('searchResultsContent');
        this.searchLoading = document.getElementById('searchLoading');
        this.searchEmpty = document.getElementById('searchEmpty');
        this.resultsSection = document.getElementById('resultsSection');
        this.resultsTitle = document.getElementById('resultsTitle');
        this.resultsCount = document.getElementById('resultsCount');
        this.resultsGrid = document.getElementById('resultsGrid');
        this.resultsLoading = document.getElementById('resultsLoading');
        this.dynamicNavLinks = document.getElementById('dynamicNavLinks');
        
        this.searchTimeout = null;
        this.currentQuery = '';
        this.navUpdateInterval = null;
        
        this.init();
    }

    init() {
        // Search input events
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => this.handleSearchInput(e));
            this.searchInput.addEventListener('focus', () => this.showSearchResults());
            this.searchInput.addEventListener('blur', (e) => this.handleSearchBlur(e));
        }

        // Click outside to close search results
        document.addEventListener('click', (e) => this.handleDocumentClick(e));

        // Initialize dynamic nav links
        this.initDynamicNavLinks();
    }

    async initDynamicNavLinks() {
        // Load initial nav links
        await this.updateNavLinks();
        
        // Set interval to update nav links every 1 minute (60000ms)
        this.navUpdateInterval = setInterval(() => {
            this.updateNavLinks();
        }, 60000);
    }

    async updateNavLinks() {
        try {
            const response = await fetch('/api/books/random-titles?limit=5');
            const data = await response.json();
            
            if (data.success && data.data.length > 0) {
                this.displayNavLinks(data.data);
            } else {
                // Fallback to default links if API fails
                this.displayFallbackNavLinks();
            }
        } catch (error) {
            console.error('Error updating nav links:', error);
            this.displayFallbackNavLinks();
        }
    }

    displayNavLinks(books) {
        const navLinksHtml = books.map(book => `
            <a href="#" 
               class="relative group hover:text-green-600 transition-all duration-500 nav-link cursor-pointer truncate max-w-48"
               data-book-id="${book.id}"
               data-book-title="${book.title}"
               title="${book.title}">
                ${this.truncateText(book.title, 25)}
                <span class="absolute left-0 -bottom-1 h-0.5 w-0 bg-green-500 group-hover:w-full transition-all duration-300"></span>
            </a>
        `).join('');

        // Add fade effect during transition
        this.dynamicNavLinks.style.opacity = '0.5';
        
        setTimeout(() => {
            this.dynamicNavLinks.innerHTML = navLinksHtml;
            this.dynamicNavLinks.style.opacity = '1';
            
            // Re-attach event listeners to new nav links
            this.attachNavLinkEvents();
        }, 200);
    }

    displayFallbackNavLinks() {
        const fallbackLinks = [
            { id: null, title: 'Omniscient Reader' },
            { id: null, title: 'Solo Leveling' },
            { id: null, title: 'Eleceed' },
            { id: null, title: 'Sweet Home' },
            { id: null, title: 'The Beginning After The End' }
        ];

        const navLinksHtml = fallbackLinks.map(link => `
            <a href="#" 
               class="relative group hover:text-green-600 transition-all duration-300 nav-link cursor-pointer"
               data-genre="${link.title}">
                ${link.title}
                <span class="absolute left-0 -bottom-1 h-0.5 w-0 bg-green-500 group-hover:w-full transition-all duration-300"></span>
            </a>
        `).join('');

        this.dynamicNavLinks.innerHTML = navLinksHtml;
        this.attachNavLinkEvents();
    }

    attachNavLinkEvents() {
        const navLinks = this.dynamicNavLinks.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => this.handleNavClick(e));
        });
    }

    truncateText(text, maxLength) {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength).trim() + '...';
    }

    handleSearchInput(e) {
        const query = e.target.value.trim();
        
        // Clear previous timeout
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        if (query.length === 0) {
            this.hideSearchResults();
            this.hideMainResults();
            return;
        }

        // Debounce search
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, 300);
    }

    handleSearchBlur(e) {
        // Delay hiding to allow clicking on results
        setTimeout(() => {
            if (!this.searchResults.contains(document.activeElement)) {
                this.hideSearchResults();
            }
        }, 150);
    }

    handleNavClick(e) {
        e.preventDefault();
        
        const bookId = e.target.getAttribute('data-book-id');
        const bookTitle = e.target.getAttribute('data-book-title');
        const genre = e.target.getAttribute('data-genre');
        
        if (bookId && bookTitle) {
            // Search by specific book title
            this.searchByTitle(bookTitle);
        } else if (genre) {
            // Fallback to genre search
            this.searchByGenre(genre);
        }
        
        this.hideSearchResults();
    }

    handleDocumentClick(e) {
        if (!this.searchInput.contains(e.target) && !this.searchResults.contains(e.target)) {
            this.hideSearchResults();
        }
    }

    async performSearch(query) {
        if (query === this.currentQuery) return;
        this.currentQuery = query;

        this.showSearchLoading();
        
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                this.displaySearchResults(data.data);
                this.displayMainResults(data.data, `Hasil pencarian untuk "${query}"`, data.count);
            } else {
                this.showSearchEmpty();
            }
        } catch (error) {
            console.error('Search error:', error);
            this.showSearchEmpty();
        }
    }

    async searchByTitle(title) {
        this.showMainLoading();
        
        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(title)}`);
            const data = await response.json();
            
            if (data.success) {
                this.displayMainResults(data.data, `Buku terkait "${title}"`, data.count);
            } else {
                this.hideMainResults();
            }
        } catch (error) {
            console.error('Title search error:', error);
            this.hideMainResults();
        }
    }

    async searchByGenre(genre) {
        this.showMainLoading();
        
        try {
            const response = await fetch(`/api/books/genre?genre=${encodeURIComponent(genre)}`);
            const data = await response.json();
            
            if (data.success) {
                this.displayMainResults(data.data, `Buku ${genre}`, data.count);
            } else {
                this.hideMainResults();
            }
        } catch (error) {
            console.error('Genre search error:', error);
            this.hideMainResults();
        }
    }

    displaySearchResults(books) {
        this.hideSearchLoading();
        this.hideSearchEmpty();
        
        if (books.length === 0) {
            this.showSearchEmpty();
            return;
        }

        const resultsHtml = books.map(book => `
            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors duration-200" onclick="openBook(${book.id})">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-16 bg-gray-200 rounded flex-shrink-0 overflow-hidden">
                        ${book.cover_path ? 
                            `<img src="/${book.cover_path}" alt="${book.judul}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Cover</div>`
                        }
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium text-gray-900 truncate">${book.judul}</h4>
                        <p class="text-sm text-gray-600 truncate">${book.author}</p>
                        <div class="flex items-center gap-2 mt-1">
                            ${book.genre ? `<span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">${book.genre}</span>` : ''}
                            ${book.tahun ? `<span class="text-xs text-gray-500">${book.tahun}</span>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        this.searchResultsContent.innerHTML = resultsHtml;
        this.showSearchResults();
    }

    displayMainResults(books, title, count) {
        this.hideMainLoading();
        
        this.resultsTitle.textContent = title;
        this.resultsCount.textContent = `${count} buku ditemukan`;
        
        if (books.length === 0) {
            this.resultsGrid.innerHTML = '<div class="col-span-full text-center text-gray-500">Tidak ada buku ditemukan</div>';
        } else {
            const resultsHtml = books.map(book => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 cursor-pointer" onclick="openBook(${book.id})">
                    <div class="aspect-[3/4] bg-gray-200 overflow-hidden">
                        ${book.cover_path ? 
                            `<img src="/${book.cover_path}" alt="${book.judul}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">` :
                            `<div class="w-full h-full flex items-center justify-center text-gray-400">No Cover</div>`
                        }
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2" title="${book.judul}">${book.judul}</h3>
                        <p class="text-sm text-gray-600 mb-2" title="${book.author}">${book.author}</p>
                        <div class="flex items-center justify-between">
                            ${book.genre ? `<span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">${book.genre}</span>` : '<span></span>'}
                            ${book.tahun ? `<span class="text-xs text-gray-500">${book.tahun}</span>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
            
            this.resultsGrid.innerHTML = resultsHtml;
        }
        
        this.showMainResults();
    }

    // Cleanup method untuk menghapus interval saat component di-destroy
    destroy() {
        if (this.navUpdateInterval) {
            clearInterval(this.navUpdateInterval);
        }
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }
    }

    showSearchResults() {
        this.searchResults.classList.remove('hidden');
    }

    hideSearchResults() {
        this.searchResults.classList.add('hidden');
    }

    showSearchLoading() {
        this.searchLoading.classList.remove('hidden');
        this.searchEmpty.classList.add('hidden');
        this.searchResultsContent.innerHTML = '';
        this.showSearchResults();
    }

    hideSearchLoading() {
        this.searchLoading.classList.add('hidden');
    }

    showSearchEmpty() {
        this.searchEmpty.classList.remove('hidden');
        this.searchLoading.classList.add('hidden');
        this.searchResultsContent.innerHTML = '';
        this.showSearchResults();
    }

    hideSearchEmpty() {
        this.searchEmpty.classList.add('hidden');
    }

    showMainResults() {
        this.resultsSection.classList.remove('hidden');
    }

    hideMainResults() {
        this.resultsSection.classList.add('hidden');
    }

    showMainLoading() {
        this.resultsLoading.classList.remove('hidden');
        this.hideMainResults();
    }

    hideMainLoading() {
        this.resultsLoading.classList.add('hidden');
    }
}

// Global function untuk membuka buku
window.openBook = function(bookId) {
    // Redirect ke halaman reading dengan ID buku
    window.location.href = `/reading/${bookId}`;
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.bookSearchInstance = new BookSearch();
});

// Cleanup saat window di-close
window.addEventListener('beforeunload', () => {
    if (window.bookSearchInstance) {
        window.bookSearchInstance.destroy();
    }
});

export default BookSearch;