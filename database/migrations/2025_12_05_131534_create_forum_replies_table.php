<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('forum_replies')) {
            Schema::create('forum_replies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('forum_thread_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('body');
                $table->timestamps();
            });
        } else {
            Schema::table('forum_replies', function (Blueprint $table) {
                if (! Schema::hasColumn('forum_replies', 'forum_thread_id')) {
                    $table->foreignId('forum_thread_id')->after('id')->constrained()->onDelete('cascade');
                }

                if (! Schema::hasColumn('forum_replies', 'user_id')) {
                    $table->foreignId('user_id')->after('forum_thread_id')->constrained()->onDelete('cascade');
                }

                if (! Schema::hasColumn('forum_replies', 'body')) {
                    $table->text('body')->after('user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_replies');
    }
};
