<div class="space-y-6">
    <h2 class="text-2xl font-bold">Informasi Akun</h2>
    
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <p class="mt-1 p-2 bg-gray-50 rounded">{{ $user->name }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 p-2 bg-gray-50 rounded">{{ $user->email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <p class="mt-1 p-2 bg-gray-50 rounded capitalize">{{ $user->role }}</p>
            </div>
        </div>
    </div>
</div>
