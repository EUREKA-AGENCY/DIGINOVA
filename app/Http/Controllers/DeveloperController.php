<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\OauthClient;
use App\Models\Webhook;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class DeveloperController extends Controller
{
    /**
     * Show the developer space with the list of applications.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $clients = OauthClient::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function (OauthClient $client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'created_at' => $client->created_at?->diffForHumans(),
                ];
            });

        $webhooks = Webhook::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function (Webhook $webhook) {
                return [
                    'id' => $webhook->id,
                    'url' => $webhook->url,
                    'events' => $webhook->events ?? [],
                    'is_active' => $webhook->is_active,
                    'created_at' => $webhook->created_at?->diffForHumans(),
                ];
            });

        return Inertia::render('developer/Index', [
            'clients' => $clients,
            'webhooks' => $webhooks,
            'tokenEndpoint' => route('api.oauth.token'),
            'apiBaseUrl' => url('/api'),
            'docsUrl' => route('docs.api'),
            'newClient' => $request->session()->get('newClient'),
        ]);
    }

    /**
     * Create a new OAuth client application for the authenticated user.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'redirect_uri' => ['nullable', 'string', 'max:255'],
        ]);

        do {
            $clientId = 'app_'.Str::random(24);
        } while (OauthClient::where('id', $clientId)->exists());

        $clientSecret = Str::random(40);

        $client = OauthClient::create([
            'id' => $clientId,
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'secret' => Hash::make($clientSecret),
            'redirect_uri' => $data['redirect_uri'] ?: null,
        ]);

        // Génère immédiatement un jeton d'accès lié à ce client et à l'utilisateur actuel
        $plainToken = Str::random(60);
        $hashedToken = hash('sha256', $plainToken);

        $token = ApiToken::create([
            'user_id' => $request->user()->id,
            'client_id' => $client->id,
            'name' => 'developer_console',
            'token' => $hashedToken,
            'abilities' => ['forum:*'],
            'expires_at' => now()->addDays(30),
        ]);

        $request->session()->flash('newClient', [
            'client_id' => $client->id,
            'client_secret' => $clientSecret,
            'name' => $client->name,
            'token_type' => 'Bearer',
            'access_token' => $plainToken,
            'expires_at' => $token->expires_at?->toIso8601String(),
        ]);

        return Redirect::route('developer.index');
    }

    /**
     * Revoke (delete) an OAuth client application created by the authenticated user.
     */
    public function destroy(Request $request, OauthClient $client): RedirectResponse
    {
        abort_unless($client->user_id === $request->user()->id, 403);

        $client->delete();

        return Redirect::route('developer.index');
    }

    /**
     * Register a new webhook endpoint for the authenticated user.
     */
    public function storeWebhook(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'url' => ['required', 'url', 'max:2000'],
        ]);

        Webhook::create([
            'user_id' => $request->user()->id,
            'url' => $data['url'],
            'events' => ['*'],
            'is_active' => true,
        ]);

        return Redirect::route('developer.index');
    }

    /**
     * Delete a webhook endpoint belonging to the authenticated user.
     */
    public function destroyWebhook(Request $request, Webhook $webhook): RedirectResponse
    {
        abort_unless($webhook->user_id === $request->user()->id, 403);

        $webhook->delete();

        return Redirect::route('developer.index');
    }
}
