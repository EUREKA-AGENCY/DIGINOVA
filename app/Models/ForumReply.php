<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_thread_id',
        'user_id',
        'body',
        'external_author_id',
        'external_author_name',
    ];

    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'forum_thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'forum_reply_likes')->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_reply_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_reply_id');
    }
}
