<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\QueryException; // Tambahkan ini

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'], // Aturan unique ini sudah ada
            'password' => ['required','string','min:8','confirmed'],
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');

        } catch (QueryException $e) {
            // Check for duplicate entry error (SQLSTATE 23000, MySQL error code 1062)
            if ($e->getCode() == '23000' || str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->withErrors([
                    'email' => 'Email ini sudah terdaftar, silakan gunakan email lain.', // Pesan custom
                ])->onlyInput('email');
            }
            // If it's another type of QueryException, re-throw or handle differently
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.', // Pesan generik untuk error DB lain
            ])->onlyInput('email');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if there's a redirect parameter
            $redirect = $request->get('redirect');
            if ($redirect) {
                return redirect($redirect);
            }

            // Default redirect to dashboard
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.', // Ubah pesan agar lebih ramah
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }
}