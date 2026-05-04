<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        session()->forget('url.intended');

        if ($user->hasRole('super_admin')) {
            return redirect()->to('/admin');
        }

        if ($user->hasRole('admin_field')) {
            return redirect()->to('/field-admin');
        }

        if ($user->hasRole('auditor')) {
            return redirect()->to('/auditor');
        }

        return redirect()->to('/dashboard');
    }
}