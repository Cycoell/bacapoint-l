<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BacaPoint</title>
    
    <!-- Link ke file CSS -->
    @vite('resources/css/app.css')

    <!-- Link Icon -->
    @include('library.icon')
</head>
<body class="min-h-screen flex items-center justify-center bg-green-100">
    <div id="container" class="relative w-[768px] h-[480px] bg-white rounded-[30px] overflow-hidden shadow-2xl transition-all duration-700">
        <!-- Sign Up Form -->
        <form method="POST" action="{{ url('/register') }}" id="signUpForm" 
            class="absolute top-0 right-0 w-1/2 h-full px-10 py-16 opacity-0 pointer-events-none z-10 transition-all duration-700">
            @csrf
            <h2 class="text-2xl font-bold text-green-600 mb-4">Sign Up</h2>

            @error('name')
                <div class="mb-3 text-red-600 text-sm">{{ $message }}</div>
            @enderror
            @error('email')
                <div class="mb-3 text-red-600 text-sm">{{ $message }}</div>
            @enderror
            @error('password')
                <div class="mb-3 text-red-600 text-sm">{{ $message }}</div>
            @enderror

            <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" 
                class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" 
                class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />
            <input type="password" name="password" placeholder="Password" minlength="8" 
                class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />
            <input type="password" name="password_confirmation" placeholder="Confirm Password" minlength="8" 
                class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />
            <button type="submit" 
                class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Sign Up
            </button>
        </form>

        <!-- Sign In Form -->
        <form method="POST" action="{{ url('/login') }}" id="signInForm" 
            class="absolute top-0 left-0 w-1/2 h-full px-10 py-16 opacity-100 z-20 transition-all duration-700">
            @csrf
            <h2 class="text-2xl font-bold text-green-600 mb-4">Sign In</h2>

            @error('email')
                <div class="mb-3 text-red-600 text-sm">{{ $message }}</div>
            @enderror
            @if(session('success'))
                <div class="mb-3 text-green-600 text-sm">{{ session('success') }}</div>
            @endif

            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" 
                class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />
            <input type="password" name="password" placeholder="Password" 
                class="w-full mb-3 p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500" required />

            <!-- Forgot Password Link -->
            <div class="text-right text-sm mb-3">
                <a href="#" class="text-green-500 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" 
                class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Sign In
            </button>

            <!-- Divider -->
            <div class="flex items-center my-4">
                <hr class="flex-grow border-gray-300" />
                <span class="mx-3 text-sm text-gray-500">or</span>
                <hr class="flex-grow border-gray-300" />
            </div>

            <!-- Sign in with Google -->
            <button type="button" 
                class="w-full flex items-center justify-center border border-gray-300 rounded px-4 py-2 hover:bg-gray-100 transition"
                onclick="alert('Fitur Google Sign In belum tersedia')">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="w-5 h-5 mr-2" />
                <span class="text-sm text-gray-700 font-medium">Sign in with Google</span>
            </button>
        </form>

        <!-- Overlay -->
        <div id="overlayContainer" class="absolute top-0 left-1/2 w-1/2 h-full z-30 transition-all duration-700 ease-in-out">
            <div id="overlay" class="w-full h-full bg-gradient-to-r from-green-500 to-green-400 text-white flex flex-col justify-center items-center px-8 text-center rounded-l-[150px] transition-all duration-700">
                <a href="/">
                    <div class="bg-white w-32 h-32 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('assets/logo_bawah.png') }}" class="w-[90px] h-[90px] object-contain" alt="BacaPoint Logo" />
                    </div>
                </a>
                <h2 id="overlayTitle" class="text-2xl font-bold mb-2">Hello, Friend!</h2>
                <p id="overlayText" class="mb-6">Register with your personal details to start journey</p>
                <button id="overlayBtn" class="border border-white px-5 py-2 rounded hover:bg-white hover:text-green-600 transition">
                    Sign Up
                </button>
            </div>
        </div>
    </div>

    @vite('resources/js/login.js')
</body>
</html>
