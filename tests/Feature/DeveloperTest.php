<?php

use App\Models\ApiToken;
use App\Models\OauthClient;
use App\Models\User;

test('guests are redirected from developer space', function () {
    $response = $this->get('/developer');

    $response->assertRedirect('/login');
});

test('authenticated users can access developer space', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/developer');

    $response->assertOk();
});

test('authenticated users can create oauth applications', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/developer/apps', [
            'name' => 'My Test App',
            'redirect_uri' => 'https://example.com/callback',
        ]);

    $response->assertRedirect('/developer');

    $this->assertDatabaseHas('oauth_clients', [
        'name' => 'My Test App',
        'user_id' => $user->id,
    ]);

    $client = OauthClient::where('name', 'My Test App')->first();

    expect($client)->not->toBeNull();
    expect($client->secret)->not->toBeEmpty();

    $token = ApiToken::where('client_id', $client->id)
        ->where('user_id', $user->id)
        ->first();

    expect($token)->not->toBeNull();
});

test('authenticated users can revoke their oauth applications', function () {
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->post('/developer/apps', [
            'name' => 'App To Revoke',
            'redirect_uri' => null,
        ]);

    $client = OauthClient::where('name', 'App To Revoke')->firstOrFail();

    $this
        ->actingAs($user)
        ->delete(route('developer.apps.destroy', $client->id))
        ->assertRedirect('/developer');

    $this->assertDatabaseMissing('oauth_clients', [
        'id' => $client->id,
    ]);

    $this->assertDatabaseMissing('api_tokens', [
        'client_id' => $client->id,
    ]);
});
