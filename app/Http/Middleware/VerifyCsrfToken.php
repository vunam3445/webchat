<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Các URI sẽ được loại trừ khỏi kiểm tra CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        'auth/login',
    ];
}
