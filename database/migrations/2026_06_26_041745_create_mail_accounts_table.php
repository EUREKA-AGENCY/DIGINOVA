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
        Schema::create('mail_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mail_domain_id')->constrained('mail_domains')->cascadeOnDelete();
            $table->string('local_part');
            $table->timestamps();

            $table->unique(['mail_domain_id', 'local_part']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_accounts');
    }
};
