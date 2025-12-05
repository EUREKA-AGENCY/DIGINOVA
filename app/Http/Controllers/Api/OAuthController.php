<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\OauthClient;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function issueToken(Request $request): JsonResponse
    {
        $data = $request->validate([
            'grant_type' => ['required', 'in:password'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $client = OauthClient::find($data['client_id']);

        if (! $client || ! Hash::check($data['client_secret'], $client->secret)) {
            return response()->json(['error' => 'invalid_client'], 401);
        }

        $user = User::where('email', $data['username'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['error' => 'invalid_grant'], 401);
        }

        $plainToken = Str::random(60);
        $hashedToken = hash('sha256', $plainToken);

        $token = ApiToken::create([
            'user_id' => $user->id,
            'client_id' => $client->id,
            'name' => 'password_grant',
            'token' => $hashedToken,
            'abilities' => ['forum:*'],
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $plainToken,
            'expires_at' => $token->expires_at?->toIso8601String(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}

