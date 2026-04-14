<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return redirect('/admin');
        }

        if ($user->hasRole('admin_field')) {
            return redirect('/field-admin');
        }

        if ($user->hasRole('auditor')) {
            return redirect('/auditor');
        }

        return redirect('/');
    }
}