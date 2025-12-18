<?php

use App\Models\ApiToken;
use App\Models\OauthClient;
use App\Models\User;
use App\Models\Webhook;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

test('forum actions can carry an external user id header into webhooks', function () {
    Http::fake();

    $owner = User::factory()->create();

    $client = OauthClient::create([
        'id' => 'app_'.Str::random(24),
        'user_id' => $owner->id,
        'name' => 'External User ID Test Client',
        'secret' => Hash::make('test-secret'),
        'redirect_uri' => null,
    ]);

    $plainToken = Str::random(60);

    ApiToken::create([
        'user_id' => $owner->id,
        'client_id' => $client->id,
        'name' => 'external_user_id_test',
        'token' => hash('sha256', $plainToken),
        'abilities' => ['forum:*'],
        'expires_at' => now()->addDay(),
    ]);

    Webhook::create([
        'user_id' => $owner->id,
        'url' => 'https://example.com/webhook',
        'events' => ['*'],
        'is_active' => true,
    ]);

    $externalUserId = 'external-user-123';

    $response = $this->withHeaders([
        'Authorization' => 'Bearer '.$plainToken,
        'X-External-User-Id' => $externalUserId,
    ])->postJson('/api/forum/threads', [
        'title' => 'Thread with external user id',
        'body' => 'Created while carrying an external user id.',
    ]);

    $response->assertCreated();

    Http::assertSent(function ($request) use ($externalUserId) {
        $data = $request->data();

        return ($data['event'] ?? null) === 'forum.thread.created'
            && ($data['data']['external_user_id'] ?? null) === $externalUserId;
    });
});
