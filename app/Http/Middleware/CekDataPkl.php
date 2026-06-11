<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekDataPkl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // Kalau belum isi data PKL
        if (!Auth::user()->pesertaPkl) {

            // Halaman yang masih boleh diakses
            if (
                $request->is('dashboard') ||
                $request->is('lengkapi-data')
            ) {
                return $next($request);
            }

            // Selain itu diarahkan ke dashboard
            return redirect('pesertapkl.dashboard')
                ->with('error', 'Silakan lengkapi data PKL terlebih dahulu');
        }

        return $next($request);
    }
}
