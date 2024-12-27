<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Roles;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $roles = Roles::where('name', 'admin')->first();

        if ($user->role_id !== $roles->id) {
            return response()->json([
                'message' => 'Tidak memiliki hak akses, hanya admin'
            ], 403);
        }
        return $next($request);
    }
}