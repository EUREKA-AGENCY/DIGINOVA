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
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->string('external_author_id')->nullable()->after('user_id');
            $table->string('external_author_name')->nullable()->after('external_author_id');
        });

        Schema::table('forum_replies', function (Blueprint $table) {
            $table->string('external_author_id')->nullable()->after('user_id');
            $table->string('external_author_name')->nullable()->after('external_author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->dropColumn(['external_author_id', 'external_author_name']);
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn(['external_author_id', 'external_author_name']);
        });
    }
};
