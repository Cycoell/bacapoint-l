<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Pengaturan Akun</h2>
    
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex flex-col items-center space-y-4 w-full md:w-1/4">
            <div class="relative w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                @if (!empty($user->foto_profil))
                    <img src="{{ asset('uploads/profiles/' . $user->foto_profil) }}" 
                         alt="Foto Profil" class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                @endif
            </div>
            {{-- Form untuk Upload Foto Profil --}}
            <form id="uploadProfilePictureForm" action="{{ route('profile.update_photo') }}" method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*" onchange="document.getElementById('uploadProfilePictureForm').submit()">
                <label for="profile_picture" class="cursor-pointer border border-blue-500 text-blue-500 px-4 py-1 rounded-md hover:bg-blue-50 text-sm transition">
                    Ubah Foto
                </label>
            </form>
        </div>

        <div class="w-full md:w-3/4 space-y-5">
            {{-- Form untuk Update Data Profil --}}
            <form id="updateProfileForm" action="{{ route('profile.update') }}" method="post">
                @csrf
                @method('PUT') {{-- Gunakan metode PUT untuk update --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                        <div class="flex items-center">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 profile-input" readonly>
                            <button type="button" onclick="toggleEdit('name')" class="ml-2 text-blue-600 hover:text-blue-800 edit-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <div class="flex items-center">
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 profile-input" readonly>
                            <button type="button" onclick="toggleEdit('email')" class="ml-2 text-blue-600 hover:text-blue-800 edit-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Password</label>
                        <div class="flex items-center">
                            <input type="password" value="********" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" readonly>
                            <button type="button" onclick="showChangePasswordModal()" class="ml-2 text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                        <div class="flex items-center">
                            <select name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 profile-input" disabled>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <button type="button" onclick="toggleEdit('jenis_kelamin')" class="ml-2 text-blue-600 hover:text-blue-800 edit-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                        <div class="flex items-center">
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 profile-input" disabled>
                            <button type="button" onclick="toggleEdit('tanggal_lahir')" class="ml-2 text-blue-600 hover:text-blue-800 edit-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Telepon</label>
                        <div class="flex items-center">
                            <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 profile-input" readonly>
                            <button type="button" onclick="toggleEdit('nomor_telepon')" class="ml-2 text-blue-600 hover:text-blue-800 edit-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Ubah Password</h3>
        <form id="changePasswordForm" action="{{ route('profile.change_password') }}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password_confirmation">Konfirmasi Password Baru</label> {{-- Konfirmasi password di Laravel umumnya menggunakan _confirmation --}}
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="document.getElementById('passwordModal').classList.add('hidden')" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

