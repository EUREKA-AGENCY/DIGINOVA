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
        $user = auth()->user();

        $threads = ForumThread::with('user')
            ->withCount(['replies', 'likes'])
            ->latest()
            ->paginate(10)
            ->through(function (ForumThread $thread) use ($user) {
                return [
                    'id' => $thread->id,
                    'title' => $thread->title,
                    'excerpt' => str($thread->body)->limit(180),
                    'replies_count' => $thread->replies_count ?? $thread->replies_count,
                    'likes_count' => $thread->likes_count ?? $thread->likes_count,
                    'liked' => $user ? $thread->likes()->where('user_id', $user->id)->exists() : false,
                    'user' => [
                        'id' => $thread->user->id,
                        'name' => $thread->user->name,
                    ],
                    'display_name' => $thread->external_author_name ?: $thread->user->name,
                    'author_is_external' => ! empty($thread->external_author_name),
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
        $user = auth()->user();

        $thread->load(['user', 'replies.user', 'replies.likes', 'likes']);

        return Inertia::render('public/Thread', [
            'thread' => [
                'id' => $thread->id,
                'title' => $thread->title,
                'body' => $thread->body,
                'user' => [
                    'id' => $thread->user->id,
                    'name' => $thread->user->name,
                ],
                'display_name' => $thread->external_author_name ?: $thread->user->name,
                'author_is_external' => ! empty($thread->external_author_name),
                'created_at' => $thread->created_at->diffForHumans(),
                'likes_count' => $thread->likes()->count(),
                'liked' => $user ? $thread->likes()->where('user_id', $user->id)->exists() : false,
            ],
            'replies' => $thread->replies
                ->sortBy('created_at')
                ->map(function (ForumReply $reply) use ($user) {
                    return [
                        'id' => $reply->id,
                        'body' => $reply->body,
                        'user' => [
                            'id' => $reply->user->id,
                            'name' => $reply->user->name,
                        ],
                        'display_name' => $reply->external_author_name ?: $reply->user->name,
                        'author_is_external' => ! empty($reply->external_author_name),
                        'created_at' => $reply->created_at->diffForHumans(),
                        'likes_count' => $reply->likes->count(),
                        'liked' => $user ? $reply->likes->contains('id', $user->id) : false,
                        'parent_reply_id' => $reply->parent_reply_id,
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
            'parent_reply_id' => ['nullable', 'integer', 'exists:forum_replies,id'],
        ]);

        $payload = [
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ];

        if (! empty($data['parent_reply_id'])) {
            $parent = ForumReply::where('id', $data['parent_reply_id'])
                ->where('forum_thread_id', $thread->id)
                ->first();

            if ($parent) {
                $payload['parent_reply_id'] = $parent->id;
            }
        }

        $reply = $thread->replies()->create($payload);

        $thread->increment('replies_count');

        if ($thread->user_id !== $request->user()->id) {
            $thread->user->notify(new NewForumReplyNotification($thread->fresh(), $reply->fresh('user')));
        }

        return back();
    }

    public function like(Request $request, ForumThread $thread)
    {
        $thread->likes()->syncWithoutDetaching([$request->user()->id]);

        return back();
    }

    public function unlike(Request $request, ForumThread $thread)
    {
        $thread->likes()->detach($request->user()->id);

        return back();
    }

    public function likeReply(Request $request, ForumReply $reply)
    {
        $reply->likes()->syncWithoutDetaching([$request->user()->id]);

        return back();
    }

    public function unlikeReply(Request $request, ForumReply $reply)
    {
        $reply->likes()->detach($request->user()->id);

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
                    'display_name' => $thread->external_author_name ?: $thread->user->name,
                    'author_is_external' => ! empty($thread->external_author_name),
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
                    'display_name' => $reply->external_author_name ?: $reply->user->name,
                    'author_is_external' => ! empty($reply->external_author_name),
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
