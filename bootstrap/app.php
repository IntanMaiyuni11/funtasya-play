<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan baris api ini ada
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // 1. PENGECUALIAN CSRF (PENTING: Agar Midtrans bisa mengirim data ke Laravel)
        $middleware->validateCsrfTokens(except: [
            'api/midtrans-callback', 
        ]);

        // 2. DAFTAR ALIAS MIDDLEWARE ROLE (Milik kamu sebelumnya)
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 3. PAKSA REDIRECT: Jika sudah login, lempar ke '/'
        $middleware->redirectUsersTo(fn () => '/');
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();