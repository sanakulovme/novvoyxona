<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Agar foydalanuvchi autentifikatsiyadan o'tmagan bo'lsa,
     * redirect emas, JSON qaytadi.
     */
    protected function redirectTo(Request $request): ?string
    {
        return null; // login route umuman kerak emas
    }

    protected function unauthenticated($request, array $guards)
    {
        // Faqat JSON qaytaradi
        throw new AuthenticationException(
            'Unauthenticated.', $guards
        );
    }
}
