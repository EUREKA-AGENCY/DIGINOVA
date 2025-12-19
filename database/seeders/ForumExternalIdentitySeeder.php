<?php

namespace Database\Seeders;

use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumExternalIdentitySeeder extends Seeder
{
    /**
     * Run the database seeds that exercise the external identity flow.
     */
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'api.partner@example.com'],
            [
                'name' => 'API Partner',
                'password' => 'password',
            ],
        );

        $thread = ForumThread::updateOrCreate(
            [
                'user_id' => $owner->id,
                'title' => '[API] Exemple de sujet envoyé par un intégrateur',
            ],
            [
                'body' => "Ce sujet illustre comment l'API forum enregistre l'identité externe fournie dans les en-têtes.\nChaque message affiche le nom déclaré par le système tiers.",
                'external_author_id' => 'partner-'.Str::lower(Str::random(6)),
                'external_author_name' => 'Alice – CRM Partner',
            ],
        );

        $reply = ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread->id,
                'body' => "Bonjour à tous,\nVoici un commentaire envoyé via l'API avec une identité fournie par la plateforme externe.",
            ],
            [
                'user_id' => $owner->id,
                'external_author_id' => 'ext-user-'.Str::lower(Str::random(5)),
                'external_author_name' => 'Bob – Support',
            ],
        );

        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread->id,
                'body' => "Réponse imbriquée pour montrer que l'identité externe est propagée jusque dans les sous-commentaires.",
            ],
            [
                'user_id' => $owner->id,
                'parent_reply_id' => $reply->id,
                'external_author_id' => 'ext-user-'.Str::lower(Str::random(5)),
                'external_author_name' => 'Carla – Success Manager',
            ],
        );

        $thread->update([
            'replies_count' => $thread->replies()->count(),
        ]);

        $thread->likes()->syncWithoutDetaching([$owner->id]);
    }
}

