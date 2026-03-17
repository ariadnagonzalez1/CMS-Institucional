<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar si el usuario está activo (según tu tabla users)
        if (Auth::user()->activo != 1) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Usuario inactivo');
        }

        return $next($request);
    }
}