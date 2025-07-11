<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BacaPoint</title>

    @vite('resources/css/app.css')

    @include('library.icon')
</head>
<body class="min-h-screen flex items-center justify-center bg-green-100">
    <div id="container" class="relative w-[768px] h-[480px] bg-white rounded-[30px] overflow-hidden shadow-2xl transition-all duration-700">
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

            {{-- PASSWORD INPUT FOR SIGN UP --}}
            <div class="relative mb-3">
                <input type="password" name="password" id="signup-password" placeholder="Password" minlength="8"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500 pr-10" required />
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500"
                    onclick="togglePasswordVisibility('signup-password', 'signup-password-icon')"> {{-- UBAH INI --}}
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="signup-password-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.616-1.079a3 3 0 114.5-4.5M10.287 14.113A7.001 7.001 0 0012 14c2.972 0 5.426-1.571 6.558-3.957m-9.754-5.321C11.458 4.606 12 5 12 5s.458-.394.942-.843A10.05 10.05 0 0112 3c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-1.563 3.029m-5.616 1.079a3 3 0 11-4.5 4.5"></path>
                    </svg>
                </span>
            </div>

            {{-- CONFIRM PASSWORD INPUT FOR SIGN UP --}}
            <div class="relative mb-3">
                <input type="password" name="password_confirmation" id="signup-password_confirmation" placeholder="Confirm Password" minlength="8"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500 pr-10" required />
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500"
                    onclick="togglePasswordVisibility('signup-password_confirmation', 'signup-password_confirmation-icon')"> {{-- UBAH INI --}}
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="signup-password_confirmation-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.616-1.079a3 3 0 114.5-4.5M10.287 14.113A7.001 7.001 0 0012 14c2.972 0 5.426-1.571 6.558-3.957m-9.754-5.321C11.458 4.606 12 5 12 5s.458-.394.942-.843A10.05 10.05 0 0112 3c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-1.563 3.029m-5.616 1.079a3 3 0 11-4.5 4.5"></path>
                    </svg>
                </span>
            </div>

            <button type="submit"
                class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Sign Up
            </button>
        </form>

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

            {{-- PASSWORD INPUT FOR SIGN IN --}}
            <div class="relative mb-3">
                <input type="password" name="password" id="signin-password" placeholder="Password"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-green-500 pr-10" required />
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-500"
                    onclick="togglePasswordVisibility('signin-password', 'signin-password-icon')"> {{-- UBAH INI --}}
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="signin-password-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.616-1.079a3 3 0 114.5-4.5M10.287 14.113A7.001 7.001 0 0012 14c2.972 0 5.426-1.571 6.558-3.957m-9.754-5.321C11.458 4.606 12 5 12 5s.458-.394.942-.843A10.05 10.05 0 0112 3c4.478 0 8.268 2.943 9.543 7a9.97 9.97 0 01-1.563 3.029m-5.616 1.079a3 3 0 11-4.5 4.5"></path>
                    </svg>
                </span>
            </div>

            <div class="text-right text-sm mb-3">
                <a href="#" class="text-green-500 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit"
                class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Sign In
            </button>

            
        </form>

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