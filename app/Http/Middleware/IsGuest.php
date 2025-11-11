<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Jika pengguna terautentikasi, redirect ke halaman home atau halaman lain yang sesuai
            return redirect('/');
        }

        // Jika pengguna tidak terautentikasi, lanjutkan ke permintaan berikutnya
        return $next($request);
    }
}
