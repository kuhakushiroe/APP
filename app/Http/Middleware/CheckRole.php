<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Periksa apakah pengguna sudah terautentikasi dan memiliki peran yang sesuai
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            // Jika tidak, arahkan ke halaman 'home' atau halaman lain sesuai
            return redirect()->route('home');
        }

        return $next($request);
    }
}
