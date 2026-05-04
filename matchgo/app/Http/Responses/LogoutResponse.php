<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LogoutResponse as LogoutResponseContract; // ✅ namespace benar

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return redirect('/admin/login');
    }
}