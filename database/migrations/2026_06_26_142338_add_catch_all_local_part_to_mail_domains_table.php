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
        Schema::table('mail_domains', function (Blueprint $table) {
            $table->string('catch_all_local_part')->nullable()->after('owner_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mail_domains', function (Blueprint $table) {
            $table->dropColumn('catch_all_local_part');
        });
    }
};
