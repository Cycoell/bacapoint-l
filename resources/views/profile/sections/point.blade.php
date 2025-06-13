<div>
    <h2 class="text-2xl font-bold">Point Saya</h2>
    <div class="mt-4 p-4 bg-green-50 rounded-lg flex items-center justify-between">
        <div>
            <p class="text-sm text-green-600">Total Point</p>
            <p class="text-2xl font-bold text-green-700">{{ $user->point ?? 0 }}</p>
        </div>
        <div class="w-12 h-12">
            <img src="{{ asset('assets/icon_coin.png') }}" alt="Point Icon" class="w-full h-full object-contain">
        </div>
    </div>
</div>
