<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * @param  array<int, string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $roleName = $user->role?->name;
        $allowed = collect($roles)->map(fn ($r) => trim((string) $r))->filter()->contains($roleName);

        if (! $allowed) {
            abort(403);
        }

        return $next($request);
    }
}

