<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Notifications\NewForumReplyNotification;
use App\Services\WebhookDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $threads = ForumThread::with('user')
            ->withCount(['replies', 'likes'])
            ->latest()
            ->paginate(10);

        $threads->getCollection()->transform(function (ForumThread $thread) use ($user) {
            return [
                'id' => $thread->id,
                'title' => $thread->title,
                'excerpt' => str($thread->body)->limit(200),
                'replies_count' => $thread->replies_count ?? $thread->replies_count,
                'likes_count' => $thread->likes_count ?? $thread->likes_count,
                'liked' => $user ? $thread->likes()->where('user_id', $user->id)->exists() : false,
                'user' => [
                    'id' => $thread->user->id,
                    'name' => $thread->user->name,
                ],
                'created_at' => $thread->created_at,
            ];
        });

        return response()->json($threads);
    }

    public function show(Request $request, ForumThread $thread): JsonResponse
    {
        $user = $request->user();

        $thread->load(['user', 'replies.user', 'replies.likes', 'likes']);

        $data = [
            'id' => $thread->id,
            'title' => $thread->title,
            'body' => $thread->body,
            'user' => [
                'id' => $thread->user->id,
                'name' => $thread->user->name,
            ],
            'created_at' => $thread->created_at,
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
                        'created_at' => $reply->created_at,
                        'likes_count' => $reply->likes->count(),
                        'liked' => $user ? $reply->likes->contains('id', $user->id) : false,
                        'parent_reply_id' => $reply->parent_reply_id,
                    ];
                })
                ->values(),
            'likes_count' => $thread->likes()->count(),
            'liked' => $user ? $thread->likes()->where('user_id', $user->id)->exists() : false,
        ];

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $thread = $request->user()->forumThreads()->create($data);

        $payload = [
            'id' => $thread->id,
            'title' => $thread->title,
            'body' => $thread->body,
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'created_at' => $thread->created_at->toIso8601String(),
        ];

        $externalUserId = $request->attributes->get('external_user_id');

        if ($externalUserId !== null) {
            $payload['external_user_id'] = $externalUserId;
        }

        WebhookDispatcher::dispatch('forum.thread.created', $payload);

        return response()->json([
            'id' => $thread->id,
            'title' => $thread->title,
            'body' => $thread->body,
            'created_at' => $thread->created_at,
        ], 201);
    }

    public function reply(Request $request, ForumThread $thread): JsonResponse
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

        $payload = [
            'id' => $reply->id,
            'thread_id' => $thread->id,
            'body' => $reply->body,
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'created_at' => $reply->created_at->toIso8601String(),
        ];

        $externalUserId = $request->attributes->get('external_user_id');

        if ($externalUserId !== null) {
            $payload['external_user_id'] = $externalUserId;
        }

        WebhookDispatcher::dispatch('forum.reply.created', $payload);

        return response()->json([
            'id' => $reply->id,
            'body' => $reply->body,
            'thread_id' => $thread->id,
            'created_at' => $reply->created_at,
        ], 201);
    }

    public function like(Request $request, ForumThread $thread): JsonResponse
    {
        $user = $request->user();

        $thread->likes()->syncWithoutDetaching([$user->id]);

        $likesCount = $thread->likes()->count();

        $payload = [
            'thread_id' => $thread->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'likes_count' => $likesCount,
        ];

        $externalUserId = $request->attributes->get('external_user_id');

        if ($externalUserId !== null) {
            $payload['external_user_id'] = $externalUserId;
        }

        WebhookDispatcher::dispatch('forum.thread.liked', $payload);

        return response()->json([
            'liked' => true,
            'likes_count' => $likesCount,
        ]);
    }

    public function unlike(Request $request, ForumThread $thread): JsonResponse
    {
        $user = $request->user();

        $thread->likes()->detach($user->id);

        $likesCount = $thread->likes()->count();

        $payload = [
            'thread_id' => $thread->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'likes_count' => $likesCount,
        ];

        $externalUserId = $request->attributes->get('external_user_id');

        if ($externalUserId !== null) {
            $payload['external_user_id'] = $externalUserId;
        }

        WebhookDispatcher::dispatch('forum.thread.unliked', $payload);

        return response()->json([
            'liked' => false,
            'likes_count' => $likesCount,
        ]);
    }

    public function likeReply(Request $request, ForumReply $reply): JsonResponse
    {
        $user = $request->user();

        $reply->likes()->syncWithoutDetaching([$user->id]);

        $likesCount = $reply->likes()->count();

        $payload = [
            'reply_id' => $reply->id,
            'thread_id' => $reply->thread->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'likes_count' => $likesCount,
        ];

        $externalUserId = $request->attributes->get('external_user_id');

        if ($externalUserId !== null) {
            $payload['external_user_id'] = $externalUserId;
        }

        WebhookDispatcher::dispatch('forum.reply.liked', $payload);

        return response()->json([
            'liked' => true,
            'likes_count' => $likesCount,
        ]);
    }

    public function unlikeReply(Request $request, ForumReply $reply): JsonResponse
    {
        $user = $request->user();

        $reply->likes()->detach($user->id);

        $likesCount = $reply->likes()->count();

        $payload = [
            'reply_id' => $reply->id,
            'thread_id' => $reply->thread->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'likes_count' => $likesCount,
        ];

        $externalUserId = $request->attributes->get('external_user_id');

        if ($externalUserId !== null) {
            $payload['external_user_id'] = $externalUserId;
        }

        WebhookDispatcher::dispatch('forum.reply.unliked', $payload);

        return response()->json([
            'liked' => false,
            'likes_count' => $likesCount,
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            });

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markNotificationAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->where('id', $id)->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'id' => $notification->id,
            'read_at' => $notification->read_at,
        ]);
    }
}
