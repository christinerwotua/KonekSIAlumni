<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi halaman untuk peran tertentu.
 * Contoh di routes: ->middleware('role:alumni')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless($request->user() && in_array($request->user()->role, $roles, true), 403,
            'Halaman ini hanya untuk akun ' . implode(' / ', $roles) . '.');

        return $next($request);
    }
}
