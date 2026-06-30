<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('client_bp')->nullable()->after('client_company');
            $table->string('client_siege_social')->nullable()->after('client_bp');
            $table->string('client_address')->nullable()->after('client_siege_social');
        });

        Schema::create('invoice_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('bp')->nullable();
            $table->string('siege_social')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('unit_price');
            $table->unsignedInteger('years')->default(1);
            $table->unsignedInteger('discount_percent')->default(0);
            $table->unsignedInteger('line_total');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_companies');
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['client_bp', 'client_siege_social', 'client_address']);
        });
    }
};
