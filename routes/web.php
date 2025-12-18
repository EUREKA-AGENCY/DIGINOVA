<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    $threads = $user->forumThreads()
        ->withCount('replies')
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($thread) {
            return [
                'id' => $thread->id,
                'title' => $thread->title,
                'replies_count' => $thread->replies_count,
                'created_at' => $thread->created_at->diffForHumans(),
            ];
        });

    $replies = $user->forumReplies()
        ->with('thread')
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($reply) {
            return [
                'id' => $reply->id,
                'body' => str($reply->body)->limit(120),
                'thread' => [
                    'id' => $reply->thread->id,
                    'title' => $reply->thread->title,
                ],
                'created_at' => $reply->created_at->diffForHumans(),
            ];
        });

    return Inertia::render('Dashboard', [
        'threads' => $threads,
        'replies' => $replies,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/developer', [DeveloperController::class, 'index'])->name('developer.index');
    Route::post('/developer/apps', [DeveloperController::class, 'store'])->name('developer.apps.store');
    Route::delete('/developer/apps/{client}', [DeveloperController::class, 'destroy'])->name('developer.apps.destroy');
    Route::post('/developer/webhooks', [DeveloperController::class, 'storeWebhook'])->name('developer.webhooks.store');
    Route::delete('/developer/webhooks/{webhook}', [DeveloperController::class, 'destroyWebhook'])->name('developer.webhooks.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/services', [PublicController::class, 'services'])->name('services.index');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/quote', [PublicController::class, 'quote'])->name('quote');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/achievements', [PublicController::class, 'achievements'])->name('achievements');

// Forum / blog communautaire
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{thread}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/members/{user}', [BlogController::class, 'user'])->name('members.show');

Route::middleware('auth')->group(function () {
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::post('/blogs/{thread}/comments', [BlogController::class, 'comment'])->name('blogs.comment');
    Route::post('/blogs/{thread}/likes', [BlogController::class, 'like'])->name('blogs.like');
    Route::delete('/blogs/{thread}/likes', [BlogController::class, 'unlike'])->name('blogs.unlike');
    Route::post('/blogs/replies/{reply}/likes', [BlogController::class, 'likeReply'])->name('blogs.replies.like');
    Route::delete('/blogs/replies/{reply}/likes', [BlogController::class, 'unlikeReply'])->name('blogs.replies.unlike');
});

Route::get('/blog', function () {
    return redirect()->route('blogs.index');
});

Route::get('/docs/api', function () {
    return view('api-docs');
})->name('docs.api');

Route::get('/docs/api/openapi.yaml', function () {
    $path = base_path('openapi.yaml');

    abort_unless(file_exists($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/yaml',
    ]);
})->name('docs.api.spec');

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
