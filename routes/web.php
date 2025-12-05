<?php

use App\Http\Controllers\BlogController;
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
});

Route::get('/blog', function () {
    return redirect()->route('blogs.index');
});

require __DIR__.'/auth.php';
