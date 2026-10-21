<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsWaiter
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $allowedRoles = ['admin', 'waiter'];

        if (! auth()->user()->hasAnyRole($allowedRoles)) {
            abort(403, 'Unauthorized action. You must be a waiter to access this page.');
        }

        return $next($request);
    }
}
