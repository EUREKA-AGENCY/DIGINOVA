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
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_account_id')->constrained('sms_accounts')->cascadeOnDelete();
            $table->string('sender');
            $table->string('destination');
            $table->text('message');
            $table->unsignedTinyInteger('segments')->default(1);
            $table->string('status');
            $table->text('provider_response')->nullable();
            $table->timestamps();

            $table->index(['sms_account_id', 'created_at']);
            $table->index('destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
