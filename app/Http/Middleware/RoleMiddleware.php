<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /* enforce strict panel role access */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        try {
            $user = $request->user();
            if (!$user || $user->role !== $role) {
                return ResponseHelper::error('Unauthorized panel access', 403);
            }
            return $next($request);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}