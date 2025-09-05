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
        // Add indexes to financial_transactions table for better performance
        Schema::table('financial_transactions', function (Blueprint $table) {
            // Composite index for date-based queries
            $table->index(['transaction_date', 'type'], 'idx_transactions_date_type');
            
            // Index for category-based queries
            $table->index(['financial_category_id', 'type', 'transaction_date'], 'idx_transactions_category_type_date');
            
            // Index for account-based queries
            $table->index(['financial_account_id', 'transaction_date'], 'idx_transactions_account_date');
            
            // Index for monthly queries
            $table->index(['type', 'transaction_date'], 'idx_transactions_type_date');
        });

        // Add indexes to financial_budgets table
        Schema::table('financial_budgets', function (Blueprint $table) {
            // Index for monthly budget queries
            $table->index(['month', 'year'], 'idx_budgets_month_year');
            
            // Index for category-based budget queries
            $table->index(['financial_category_id', 'month', 'year'], 'idx_budgets_category_month_year');
        });

        // Add indexes to financial_goals table
        Schema::table('financial_goals', function (Blueprint $table) {
            // Index for active goals
            $table->index(['status', 'target_date'], 'idx_goals_status_target_date');
        });

        // Add indexes to financial_categories table
        Schema::table('financial_categories', function (Blueprint $table) {
            // Index for type-based queries
            $table->index(['type'], 'idx_categories_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_transactions_date_type');
            $table->dropIndex('idx_transactions_category_type_date');
            $table->dropIndex('idx_transactions_account_date');
            $table->dropIndex('idx_transactions_type_date');
        });

        Schema::table('financial_budgets', function (Blueprint $table) {
            $table->dropIndex('idx_budgets_month_year');
            $table->dropIndex('idx_budgets_category_month_year');
        });

        Schema::table('financial_goals', function (Blueprint $table) {
            $table->dropIndex('idx_goals_status_target_date');
        });

        Schema::table('financial_categories', function (Blueprint $table) {
            $table->dropIndex('idx_categories_type');
        });
    }
};