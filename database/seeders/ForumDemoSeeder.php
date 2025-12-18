<?php

namespace Database\Seeders;

use App\Models\ForumReply;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utilisateur principal de démonstration (espace membre)
        $mainUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ],
        );

        // Crée quelques utilisateurs de démonstration pour la communauté (ou les récupère s'ils existent déjà)
        $alice = User::firstOrCreate(
            ['email' => 'alice.forum@diginova.local'],
            [
                'name' => 'Alice - Communauté',
                'password' => 'password',
            ],
        );

        $bob = User::firstOrCreate(
            ['email' => 'bob.forum@diginova.local'],
            [
                'name' => 'Bob - Communauté',
                'password' => 'password',
            ],
        );

        $charlie = User::firstOrCreate(
            ['email' => 'charlie.forum@diginova.local'],
            [
                'name' => 'Charlie - Communauté',
                'password' => 'password',
            ],
        );

        // Sujets d'exemple
        $thread1 = ForumThread::firstOrCreate(
            [
                'user_id' => $alice->id,
                'title' => 'Comment digitaliser mon processus commercial ?',
            ],
            [
                'body' => "Bonjour à tous,\n\nJe souhaite moderniser le suivi de mes prospects et clients (CRM, automatisation, reporting...).\nQuels outils utilisez-vous et quels sont vos retours d'expérience avec DIGINOVA ?",
            ],
        );

        $thread2 = ForumThread::firstOrCreate(
            [
                'user_id' => $bob->id,
                'title' => 'Vos retours sur la mise en place d’un site vitrine',
            ],
            [
                'body' => "Salut la communauté,\n\nJe prépare la refonte de notre site vitrine.\nQuelles sections vous semblent indispensables pour valoriser une PME de services ?",
            ],
        );

        $thread3 = ForumThread::firstOrCreate(
            [
                'user_id' => $charlie->id,
                'title' => 'Automatisation marketing : par où commencer ?',
            ],
            [
                'body' => "Bonjour,\n\nJe débute sur les sujets d'emailing automatisé et de scénarios marketing.\nPar quoi commencer pour ne pas se perdre ?",
            ],
        );

        // Sujet d'exemple rattaché à l'utilisateur principal (pour que le dashboard ne soit pas vide)
        $mainThread = ForumThread::firstOrCreate(
            [
                'user_id' => $mainUser->id,
                'title' => 'Bienvenue dans mon espace membre DIGINOVA',
            ],
            [
                'body' => "Bonjour à tous,\n\nJe découvre l'espace membre et le forum DIGINOVA.\nN'hésitez pas à partager vos conseils et vos retours d'expérience !",
            ],
        );

        // Réponses d'exemple
        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread1->id,
                'user_id' => $bob->id,
                'body' => "Nous avons commencé par centraliser toutes les données clients dans un seul outil, puis connecté DIGINOVA pour le reporting.",
            ],
        );

        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread1->id,
                'user_id' => $charlie->id,
                'body' => "Pense aussi à impliquer les équipes commerciales dès le début, ça facilite l'adoption de la solution.",
            ],
        );

        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread2->id,
                'user_id' => $alice->id,
                'body' => "Un bloc \"Nos services\", des témoignages clients et un formulaire de contact clair sont pour moi indispensables.",
            ],
        );

        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $thread3->id,
                'user_id' => $alice->id,
                'body' => "Commence par une simple séquence d'onboarding pour les nouveaux contacts, puis ajoute petit à petit d'autres scénarios.",
            ],
        );

        ForumReply::firstOrCreate(
            [
                'forum_thread_id' => $mainThread->id,
                'user_id' => $alice->id,
                'body' => "Bienvenue ! Vous pouvez commencer par parcourir les derniers sujets du forum pour vous inspirer.",
            ],
        );

        // Met à jour les compteurs de réponses sur les sujets concernés
        foreach ([$thread1, $thread2, $thread3, $mainThread] as $thread) {
            $thread->update([
                'replies_count' => $thread->replies()->count(),
            ]);
        }
    }
}
