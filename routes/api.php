<?php

use App\Http\Controllers\Api\ForumController;
use App\Http\Controllers\Api\OAuthController;
use Illuminate\Support\Facades\Route;

Route::post('/oauth/token', [OAuthController::class, 'issueToken']);

Route::middleware('api_token')->prefix('forum')->group(function () {
    Route::get('/threads', [ForumController::class, 'index']);
    Route::get('/threads/{thread}', [ForumController::class, 'show']);
    Route::post('/threads', [ForumController::class, 'store']);
    Route::post('/threads/{thread}/replies', [ForumController::class, 'reply']);
    Route::post('/threads/{thread}/likes', [ForumController::class, 'like']);
    Route::delete('/threads/{thread}/likes', [ForumController::class, 'unlike']);

    Route::get('/notifications', [ForumController::class, 'notifications']);
    Route::post('/notifications/{id}/read', [ForumController::class, 'markNotificationAsRead']);
});

