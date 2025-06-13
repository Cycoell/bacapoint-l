<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3 border-gray-200">Pengaturan Akun</h2>
    
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex flex-col items-center space-y-4 w-full md:w-1/4 pt-4">
            <div class="relative w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden border-4 border-blue-300 shadow-md">
                @if (!empty($user->foto_profil))
                    <img src="{{ asset('uploads/profiles/' . $user->foto_profil) }}" 
                         alt="Foto Profil" class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @endif
            </div>
            {{-- Form untuk Upload Foto Profil --}}
            <form id="uploadProfilePictureForm" action="{{ route('profile.update_photo') }}" method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*" onchange="document.getElementById('uploadProfilePictureForm').submit()">
                <label for="profile_picture" class="cursor-pointer bg-blue-500 text-white px-4 py-2 rounded-full shadow-md hover:bg-blue-600 transition text-sm">
                    Ubah Foto
                </label>
            </form>
        </div>

        <div class="w-full md:w-3/4 space-y-5">
            {{-- Form untuk Update Data Profil --}}
            <form id="updateProfileForm" action="{{ route('profile.update') }}" method="post">
                @csrf
                @method('PUT') {{-- Gunakan metode PUT untuk update --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <div class="flex items-center space-x-2">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="input-field-profile" readonly>
                            <button type="button" onclick="toggleEdit('name')" class="edit-btn text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <div class="flex items-center space-x-2">
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="input-field-profile" readonly>
                            <button type="button" onclick="toggleEdit('email')" class="edit-btn text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <div class="flex items-center space-x-2">
                            <input type="password" value="********" 
                                   class="input-field-profile" readonly>
                            <button type="button" onclick="showChangePasswordModal()" class="edit-btn text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin</label>
                        <div class="flex items-center space-x-2">
                            <select name="jenis_kelamin" class="input-field-profile" disabled>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <button type="button" onclick="toggleEdit('jenis_kelamin')" class="edit-btn text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                        <div class="flex items-center space-x-2">
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" 
                                   class="input-field-profile" disabled>
                            <button type="button" onclick="toggleEdit('tanggal_lahir')" class="edit-btn text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                        <div class="flex items-center space-x-2">
                            <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" 
                                   class="input-field-profile" readonly>
                            <button type="button" onclick="toggleEdit('nomor_telepon')" class="edit-btn text-blue-600 hover:text-blue-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 text-right border-t pt-6 border-gray-200">
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-xl shadow-lg hover:bg-green-700 transition-all duration-300 transform hover:scale-105 font-semibold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50"> 
    <div class="flex items-center justify-center min-h-screen"> {{-- Tambahkan div ini untuk centering --}}
        <div class="bg-white rounded-xl p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Ubah Password</h3>
            <form id="changePasswordForm" action="{{ route('profile.change_password') }}" method="post">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="current_password">Password Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" required
                        class="input-field-modal">
                </div>
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" required
                        class="input-field-modal">
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                        class="input-field-modal">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden'); document.getElementById('passwordModal').classList.remove('flex');" 
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-5 rounded-full transition-colors shadow-md">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-full transition-colors shadow-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Custom CSS for input fields --}}
<style>
    .input-field-profile {
        @apply bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm transition-all duration-200;
    }
    .input-field-profile:read-only {
        @apply cursor-default;
    }
    .input-field-profile:disabled {
        @apply cursor-not-allowed;
    }
    .input-field-profile:not([readonly]):not([disabled]) {
        @apply bg-white border-blue-400 shadow-md;
    }
    .input-field-modal {
        @apply shadow-sm appearance-none border border-gray-300 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200;
    }
    .nav-btn.active {
        @apply border-l-4 border-green-500 bg-green-100 font-semibold text-green-700;
    }
    .nav-btn:not(.active) {
        @apply text-gray-800 hover:font-semibold;
    }
</style>