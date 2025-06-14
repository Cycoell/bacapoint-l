<section class="mt-8 bg-gray-900 text-gray-300 py-12 shadow-2xl"> {{-- Ubah background ke gelap, padding, dan shadow --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> {{-- Lebarkan max-w dan tambahkan padding responsif --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-8 text-sm mb-12"> {{-- Tambahkan gap dan mb --}}

      <div class="col-span-2 md:col-span-1 flex flex-col items-center justify-center"> {{-- Pusatkan dan sesuaikan kolom --}}
        <a href="{{ Auth::check() ? url('/dashboard') : url('/') }}" class="block mb-2"> {{-- Gunakan Auth::check --}}
          <img src="{{ asset('assets/logo_bawah.png') }}" alt="Logo Bacapoint" class="w-24 h-24 mx-auto object-contain transition-transform duration-300 hover:scale-105" /> {{-- Perbesar logo dan tambahkan hover --}}
        </a>
        <p class="text-gray-400 text-center text-sm mt-2">Pengetahuan dengan Imbalan</p> {{-- Warna teks lebih lembut --}}
      </div>

      <div>
        <h3 class="font-bold text-lg mb-3 text-white">Tema Weebly</h3> {{-- Tebalkan dan perbesar judul, warna putih --}}
        <ul class="space-y-2 text-gray-400"> {{-- Tambahkan space-y --}}
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Kirim Tiket</a></li> {{-- Hover color --}}
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">FAQ Pra-penjualan</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-bold text-lg mb-3 text-white">Layanan</h3>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Penyesuaian Tema</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-bold text-lg mb-3 text-white">Tampilan</h3>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Widget Kit</a></li>
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Dukungan</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-bold text-lg mb-3 text-white">Tentang Kami</h3>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Hubungi Kami</a></li>
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Afiliasi</a></li>
          <li><a href="#" class="hover:text-green-400 transition-colors duration-200">Sumber Daya</a></li>
        </ul>
      </div>
    </div>

    <hr class="border-gray-700 mb-8"> {{-- Warna garis lebih gelap dan margin lebih besar --}}

    <div class="flex justify-center space-x-6 mb-6"> {{-- Tambahkan margin bawah --}}
      <a href="#" class="social-link p-2 rounded-full bg-gray-800 hover:bg-green-600 transition-colors duration-200"> {{-- Background dan hover pada ikon --}}
        <img src="{{ asset('assets/facebook-square.png') }}" alt="Facebook" class="w-6 h-6" /> {{-- Ukuran ikon --}}
      </a>
      <a href="#" class="social-link p-2 rounded-full bg-gray-800 hover:bg-green-600 transition-colors duration-200">
        <img src="{{ asset('assets/twitter.png') }}" alt="Twitter" class="w-6 h-6" />
      </a>
      <a href="#" class="social-link p-2 rounded-full bg-gray-800 hover:bg-green-600 transition-colors duration-200">
        <img src="{{ asset('assets/instagram.png') }}" alt="Instagram" class="w-6 h-6" />
      </a>
      <a href="#" class="social-link p-2 rounded-full bg-gray-800 hover:bg-green-600 transition-colors duration-200">
        <img src="{{ asset('assets/whatsapp.png') }}" alt="WhatsApp" class="w-6 h-6" />
      </a>
    </div>

    <p class="text-center text-gray-500 text-sm mt-4">©Copyright {{ date('Y') }} Bacapoint. Hak Cipta Dilindungi.</p> {{-- Warna teks lebih terang --}}
  </div>
</footer>
</section>