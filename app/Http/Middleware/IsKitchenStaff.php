<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsKitchenStaff
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

        $allowedRoles = ['admin', 'kitchen_chef'];

        if (! auth()->user()->hasAnyRole($allowedRoles)) {
            abort(403, 'Unauthorized action. You must be kitchen staff to access this page.');
        }

        return $next($request);
    }
}
