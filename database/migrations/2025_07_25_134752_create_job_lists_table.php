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
        Schema::create('job_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_task_id')->constrained('category_tasks')->onDelete('cascade');
            $table->foreignId('status_task_id')->constrained('status_tasks')->onDelete('cascade');
            $table->string('name_job_list');
            $table->text('description');
            $table->date('date_job')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_lists');
    }
};
