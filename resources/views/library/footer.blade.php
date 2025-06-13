<!-- FOOTER SECTION -->
<section class="mt-6">
<footer class="bg-white text-gray-800 py-10 border-t">
  <div class="max-w-6xl mx-auto px-4">
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-sm mb-10">

      <!-- Logo dan Slogan -->
      <div>
        <a href="{{ null !== session('user') ? url('/dashboard') : url('/') }}">
          <img src="{{ asset('assets/logo_bawah.png') }}" alt="Logo Bacapoint" class="w-20 h-20 mx-auto object-contain" />
        </a>
        <p class="text-gray-500 text-center">Pengetahuan dengan Imbalan</p>
      </div>

      <!-- Kolom 1 -->
      <div>
        <h3 class="font-semibold mb-2 text-gray-700">Tema Weebly</h3>
        <ul class="space-y-1 text-gray-500">
          <li><a href="#" class="hover:underline">Kirim Tiket</a></li>
          <li><a href="#" class="hover:underline">FAQ Pra-penjualan</a></li>
        </ul>
      </div>

      <!-- Kolom 2 -->
      <div>
        <h3 class="font-semibold mb-2 text-gray-700">Layanan</h3>
        <ul class="space-y-1 text-gray-500">
          <li><a href="#" class="hover:underline">Penyesuaian Tema</a></li>
        </ul>
      </div>

      <!-- Kolom 3 -->
      <div>
        <h3 class="font-semibold mb-2 text-gray-700">Tampilan</h3>
        <ul class="space-y-1 text-gray-500">
          <li><a href="#" class="hover:underline">Widget Kit</a></li>
          <li><a href="#" class="hover:underline">Dukungan</a></li>
        </ul>
      </div>

      <!-- Kolom 4 -->
      <div>
        <h3 class="font-semibold mb-2 text-gray-700">Tentang Kami</h3>
        <ul class="space-y-1 text-gray-500">
          <li><a href="#" class="hover:underline">Hubungi Kami</a></li>
          <li><a href="#" class="hover:underline">Afiliasi</a></li>
          <li><a href="#" class="hover:underline">Sumber Daya</a></li>
        </ul>
      </div>
    </div>

    <!-- Garis -->
    <hr class="border-gray-300 mb-6">

    <!-- Ikon Sosial -->
    <div class="flex justify-center space-x-6 mb-4">
      <a href="#" class="social-link">
        <img src="{{ asset('assets/facebook-square.png') }}" alt="Facebook" class="w-6 h-6 hover:scale-110 transition-transform duration-200" />
      </a>
      <a href="#" class="social-link">
        <img src="{{ asset('assets/twitter.png') }}" alt="Twitter" class="w-6 h-6 hover:scale-110 transition-transform duration-200" />
      </a>
      <a href="#" class="social-link">
        <img src="{{ asset('assets/instagram.png') }}" alt="Instagram" class="w-6 h-6 hover:scale-110 transition-transform duration-200" />
      </a>
      <a href="#" class="social-link">
        <img src="{{ asset('assets/whatsapp.png') }}" alt="WhatsApp" class="w-6 h-6 hover:scale-110 transition-transform duration-200" />
      </a>
    </div>

    <!-- Copyright -->
    <p class="text-center text-gray-400 text-sm">©Copyright {{ date('Y') }} Bacapoint. Hak Cipta Dilindungi.</p>
  </div>
</footer>
</section>
<!-- FOOTER SECTION -->