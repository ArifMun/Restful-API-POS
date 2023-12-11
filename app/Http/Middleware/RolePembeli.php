<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RolePembeli
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role == 'pembeli') {
            return $next($request);
        }

        return response()->json(['error' => 'anda tidak memiliki akses.'], 403);
    }
}
