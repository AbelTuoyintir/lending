<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->customer_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Administrator accounts do not have a customer profile. Please use the administrative portal.');
        }

        return $next($request);
    }
}
