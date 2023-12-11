<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RolePenjual
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role == "penjual") {
            return $next($request);
        }

        return response()->json(['error' => 'anda tidak memiliki akses.'], 403);
    }
}
