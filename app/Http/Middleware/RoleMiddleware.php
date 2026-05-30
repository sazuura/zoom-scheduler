<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Mendukung satu atau beberapa role sekaligus.
     *
     * Contoh pemakaian di routes:
     *   middleware('role:admin')
     *   middleware('role:admin,inventaris')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            abort(403, 'Akses ditolak');
        }

        $userRole = auth()->user()->role;

        if (! in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}
