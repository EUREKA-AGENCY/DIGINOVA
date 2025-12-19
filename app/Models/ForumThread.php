<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'external_author_id',
        'external_author_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(ForumReply::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'forum_thread_likes')->withTimestamps();
    }
}
