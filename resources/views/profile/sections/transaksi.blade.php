<div class="p-6 bg-white rounded-lg shadow-lg border border-gray-100">
    <div class="flex items-center justify-between mb-6 border-b pb-3 border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">BP Store</h2>
        {{-- Tabs untuk kategori Voucher / Buku --}}
        <div class="flex items-center space-x-2 bg-gray-100 rounded-full p-1">
            <button class="px-4 py-2 rounded-full text-sm font-semibold bg-green-500 text-white shadow-sm transition-colors duration-200">Voucher</button>
            <button class="px-4 py-2 rounded-full text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors duration-200">Buku</button>
        </div>
    </div>

    {{-- Konten Voucher --}}
    <div id="voucher-content">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {{-- Kartu Voucher 1: Diskon Buku Fisik 50% --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 transition-transform duration-200 hover:scale-[1.02]">
                <div class="p-4 flex flex-col items-center">
                    <div class="relative w-full h-32 mb-3 rounded-lg overflow-hidden flex items-center justify-center
                                bg-gradient-to-br from-green-300 to-green-500 text-white font-extrabold text-4xl leading-none shadow-inner">
                        <img src="{{ asset('assets/discount-coupon.png') }}" alt="Discount Coupon" class="absolute inset-0 w-full h-full object-cover opacity-20">
                        <span class="relative">50% <br> OFF</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 text-center">Voucher Pembelian Buku Fisik</h3>
                    <p class="text-sm text-gray-600 mb-4 text-center">Diskon 50%</p>
                    <div class="flex items-center justify-between w-full">
                        <span class="text-xl font-bold text-green-600">250 Poin</span>
                        <button class="bg-gray-100 text-gray-700 p-2 rounded-full shadow-sm hover:bg-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kartu Voucher 2: Diskon Buku Fisik 25% --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 transition-transform duration-200 hover:scale-[1.02]">
                <div class="p-4 flex flex-col items-center">
                    <div class="relative w-full h-32 mb-3 rounded-lg overflow-hidden flex items-center justify-center
                                bg-gradient-to-br from-green-200 to-green-400 text-white font-extrabold text-4xl leading-none shadow-inner">
                        <img src="{{ asset('assets/discount-coupon.png') }}" alt="Discount Coupon" class="absolute inset-0 w-full h-full object-cover opacity-20">
                        <span class="relative">25% <br> OFF</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 text-center">Voucher Pembelian Buku Fisik</h3>
                    <p class="text-sm text-gray-600 mb-4 text-center">Diskon 25%</p>
                    <div class="flex items-center justify-between w-full">
                        <span class="text-xl font-bold text-green-600">150 Poin</span>
                        <button class="bg-gray-100 text-gray-700 p-2 rounded-full shadow-sm hover:bg-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kartu Voucher 3: Diskon Makanan 25% --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 transition-transform duration-200 hover:scale-[1.02]">
                <div class="p-4 flex flex-col items-center">
                    <div class="relative w-full h-32 mb-3 rounded-lg overflow-hidden flex items-center justify-center
                                bg-gradient-to-br from-purple-300 to-purple-500 text-white font-extrabold text-4xl leading-none shadow-inner">
                        <img src="{{ asset('assets/discount-coupon.png') }}" alt="Discount Coupon" class="absolute inset-0 w-full h-full object-cover opacity-20">
                        <span class="relative">25% <br> OFF</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 text-center">Voucher Pembelian Makanan</h3>
                    <p class="text-sm text-gray-600 mb-4 text-center">Diskon 25%</p>
                    <div class="flex items-center justify-between w-full">
                        <span class="text-xl font-bold text-green-600">150 Poin</span>
                        <button class="bg-gray-100 text-gray-700 p-2 rounded-full shadow-sm hover:bg-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kartu Voucher 4: Diskon Alat Tulis 50% --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 transition-transform duration-200 hover:scale-[1.02]">
                <div class="p-4 flex flex-col items-center">
                    <div class="relative w-full h-32 mb-3 rounded-lg overflow-hidden flex items-center justify-center
                                bg-gradient-to-br from-orange-300 to-orange-500 text-white font-extrabold text-4xl leading-none shadow-inner">
                        <img src="{{ asset('assets/discount-coupon.png') }}" alt="Discount Coupon" class="absolute inset-0 w-full h-full object-cover opacity-20">
                        <span class="relative">50% <br> OFF</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 text-center">Voucher Pembelian Alat Tulis</h3>
                    <p class="text-sm text-gray-600 mb-4 text-center">Diskon 50%</p>
                    <div class="flex items-center justify-between w-full">
                        <span class="text-xl font-bold text-green-600">250 Poin</span>
                        <button class="bg-gray-100 text-gray-700 p-2 rounded-full shadow-sm hover:bg-gray-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Konten Buku (placeholder untuk tab "Buku") --}}
    <div id="book-content" class="hidden">
        <p class="text-gray-600 text-center py-8">Katalog buku untuk ditukar akan ditampilkan di sini.</p>
    </div>
</div>

{{-- Untuk gambar discount-coupon.png, pastikan Anda memiliki gambar tersebut di folder public/assets/ --}}
{{-- Atau ganti dengan background CSS atau icon jika gambar tidak tersedia --}}
<style>
    /* Jika discount-coupon.png tidak tersedia, bisa gunakan ini sebagai fallback atau alternatif */
    .discount-coupon-placeholder {
        background-color: #a7f3d0; /* warna hijau terang */
        background-image: linear-gradient(45deg, #6ee7b7, #10b981); /* gradien hijau */
        position: relative;
    }
    .discount-coupon-placeholder::before {
        content: "Discount Coupon";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-15deg);
        color: rgba(255, 255, 255, 0.3);
        font-size: 1.5rem;
        font-weight: bold;
        white-space: nowrap;
    }
</style>