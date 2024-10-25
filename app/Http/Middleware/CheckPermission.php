<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        // Check if the authenticated user has the specified permission
//        if (!auth()->user()?->hasPermissionTo($permission)) {
//            return response()->json(['message' => 'This action unauthorized'], 403);
//        }

        return $next($request);
    }
}
