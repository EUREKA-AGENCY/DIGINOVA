<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');

        if (! $authorization || ! str_starts_with($authorization, 'Bearer ')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $plainToken = substr($authorization, 7);

        if ($plainToken === '') {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $hashed = hash('sha256', $plainToken);

        $token = ApiToken::with('user')->where('token', $hashed)->first();

        if (! $token || ! $token->user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($token->expires_at && $token->expires_at->isPast()) {
            return response()->json(['message' => 'Token expired.'], 401);
        }

        $token->forceFill([
            'last_used_at' => now(),
        ])->save();

        Auth::setUser($token->user);

        return $next($request);
    }
}

