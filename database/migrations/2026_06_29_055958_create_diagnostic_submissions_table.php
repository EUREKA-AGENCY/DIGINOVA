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
        Schema::create('diagnostic_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('mail_situation');
            $table->unsignedInteger('mail_boxes_needed')->nullable();
            $table->string('sms_need');
            $table->unsignedInteger('sms_volume_monthly')->nullable();
            $table->text('main_need');
            $table->string('budget_range')->nullable();
            $table->text('ai_analysis')->nullable();
            $table->string('ai_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostic_submissions');
    }
};
