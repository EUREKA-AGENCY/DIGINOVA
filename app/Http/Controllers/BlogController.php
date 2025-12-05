<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\User;
use App\Notifications\NewForumReplyNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $threads = ForumThread::with('user')
            ->withCount('replies')
            ->latest()
            ->paginate(10)
            ->through(function (ForumThread $thread) {
                return [
                    'id' => $thread->id,
                    'title' => $thread->title,
                    'excerpt' => str($thread->body)->limit(180),
                    'replies_count' => $thread->replies_count ?? $thread->replies_count,
                    'user' => [
                        'id' => $thread->user->id,
                        'name' => $thread->user->name,
                    ],
                    'created_at' => $thread->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('public/Blog', [
            'threads' => $threads,
            'canCreate' => auth()->check(),
            'showNewForm' => $request->boolean('new'),
        ]);
    }

    public function show(ForumThread $thread)
    {
        $thread->load(['user', 'replies.user']);

        return Inertia::render('public/Thread', [
            'thread' => [
                'id' => $thread->id,
                'title' => $thread->title,
                'body' => $thread->body,
                'user' => [
                    'id' => $thread->user->id,
                    'name' => $thread->user->name,
                ],
                'created_at' => $thread->created_at->diffForHumans(),
            ],
            'replies' => $thread->replies
                ->sortBy('created_at')
                ->map(function (ForumReply $reply) {
                    return [
                        'id' => $reply->id,
                        'body' => $reply->body,
                        'user' => [
                            'id' => $reply->user->id,
                            'name' => $reply->user->name,
                        ],
                        'created_at' => $reply->created_at->diffForHumans(),
                    ];
                })
                ->values(),
            'canReply' => auth()->check(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $thread = $request->user()->forumThreads()->create($data);

        return redirect()->route('blogs.show', $thread);
    }

    public function comment(Request $request, ForumThread $thread)
    {
        $data = $request->validate([
            'body' => ['required', 'string'],
        ]);

        $reply = $thread->replies()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        $thread->increment('replies_count');

        if ($thread->user_id !== $request->user()->id) {
            $thread->user->notify(new NewForumReplyNotification($thread->fresh(), $reply->fresh('user')));
        }

        return back();
    }

    public function user(User $user)
    {
        $threads = $user->forumThreads()
            ->withCount('replies')
            ->latest()
            ->take(10)
            ->get()
            ->map(function (ForumThread $thread) {
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
            ->take(10)
            ->get()
            ->map(function (ForumReply $reply) {
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

        return Inertia::render('public/Member', [
            'member' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'threads' => $threads,
            'replies' => $replies,
        ]);
    }
}
