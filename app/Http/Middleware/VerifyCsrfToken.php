<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'http://localhost:8080/*',
        'http://flatmate-server.test/*',
        'https://flatmate.api.mullercedric.com/*',
        'broadcasting/auth',
    ];
}
