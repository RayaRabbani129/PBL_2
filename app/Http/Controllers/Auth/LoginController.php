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
        if (Auth::check()) {
            return redirect($this->redirectPathFor(Auth::user()));
        }

        return view('auth.login');
    }

    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            return redirect($this->redirectPathFor(Auth::user()));
        }

        return view('auth.login', ['isAdminLogin' => true]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        $user = User::where('email', $request->email)->first();

        if ($user && $user->hasAnyRole(['super_admin', 'admin_field', 'auditor'])) {
            return redirect('/admin/login')
                ->with('status', 'Akun manajemen masuk melalui halaman Admin MATCHGO.');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            if ($remember) {
                $request->session()->put('auth_remember', true);
            } else {
                $request->session()->forget('auth_remember');
            }
            $request->session()->regenerate();

            $request->session()->forget('url.intended');

            return redirect('/dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! $user->hasAnyRole(['super_admin', 'admin_field', 'auditor'])) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun ini bukan akun manajemen MATCHGO.',
                ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->forget('url.intended');

            return redirect($this->redirectPathFor($user));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Email atau password salah.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectPathFor(User $user): string
    {
        if ($user->hasRole('super_admin')) {
            return '/admin';
        }

        if ($user->hasRole('admin_field')) {
            return '/field-admin';
        }

        if ($user->hasRole('auditor')) {
            return '/auditor';
        }

        return '/dashboard';
    }
}
