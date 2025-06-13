<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function loadSection($section)
    {
        $user = Auth::user();
        
        // Validasi section yang diperbolehkan
        $allowedSections = ['account', 'transaksi', 'bookmark', 'point'];
        if ($user->role === 'admin') {
            $allowedSections[] = 'grafik';
            $allowedSections[] = 'collection';
        }

        if (!in_array($section, $allowedSections)) {
            return response()->view('errors.404', [], 404);
        }

        return view("profile.sections.{$section}", compact('user'));
    }
}
