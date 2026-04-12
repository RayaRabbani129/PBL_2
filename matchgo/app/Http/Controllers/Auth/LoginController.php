<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai player, redirect dashboard
        if (Auth::guard('web')->check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Blok admin masuk dari form player
        $user = User::where('email', $request->email)->first();

        if ($user?->isAdmin()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun admin, silakan login melalui halaman admin.',
                ]);
        }

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}