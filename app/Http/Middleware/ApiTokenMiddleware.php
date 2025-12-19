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

        $externalUserId = trim((string) $request->header('X-External-User-Id', ''));
        $externalUserName = trim((string) $request->header('X-External-User-Name', ''));
        $externalReference = trim((string) $request->header('X-External-User-Reference', ''));

        if ($externalUserId !== '') {
            $request->attributes->set('external_user_id', $externalUserId);
        }

        if ($externalUserId !== '' && $externalUserName !== '') {
            $request->attributes->set('external_identity', [
                'id' => $externalUserId,
                'name' => $externalUserName,
                'reference' => $externalReference !== '' ? $externalReference : null,
            ]);
        }

        return $next($request);
    }
}
