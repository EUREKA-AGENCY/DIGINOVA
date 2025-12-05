<?php

namespace App\Notifications;

use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewForumReplyNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ForumThread $thread,
        protected ForumReply $reply,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'forum_reply',
            'thread_id' => $this->thread->id,
            'thread_title' => $this->thread->title,
            'reply_id' => $this->reply->id,
            'reply_excerpt' => str($this->reply->body)->limit(160),
            'author_id' => $this->reply->user->id,
            'author_name' => $this->reply->user->name,
            'created_at' => $this->reply->created_at,
        ];
    }
}

