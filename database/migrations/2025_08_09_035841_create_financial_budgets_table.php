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
        Schema::create('financial_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_category_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->integer('month');
            $table->integer('year');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_budgets');
    }
};
