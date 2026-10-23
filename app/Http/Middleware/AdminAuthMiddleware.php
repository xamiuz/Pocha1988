<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('admin_authenticated') === true) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthenticated admin session',
            ], 401);
        }

        $request->session()->put('url.intended', $request->fullUrl());

        return redirect()->route('admin.login')->with('warning', 'Akses terbatas! Silakan masukkan password admin terlebih dahulu.');
    }
}
