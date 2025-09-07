<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, ...$roles)
{
    if (!session()->has('role') || !in_array(session('role'), $roles)) {
        session()->flush();
        return redirect()->route('login')->with('error', 'Akses ditolak, silakan login kembali.');
    }

    return $next($request);
}
}
