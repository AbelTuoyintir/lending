<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isCustomer()) {
            return redirect()->route('portal.dashboard')
                ->with('error', 'Access restricted. Customers can only access the Customer Portal.');
        }

        return $next($request);
    }
}
