<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Las rutas que no requieren token CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/forgot-password',
        'api/reset-password',
        'api/email/resend',

    ];
}
