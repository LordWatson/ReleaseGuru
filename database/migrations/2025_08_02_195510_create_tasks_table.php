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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('release_id')->constrained()->onDelete('cascade');
            $table->foreignId('developer_id')->references('id')->on('users');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->foreignId('bug_report_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('type')->default('feature');
            $table->string('branch');
            $table->string('api_id')->nullable();
            $table->string('status')->nullable();
            $table->string('priority')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->longText('acceptance_criteria')->nullable();
            $table->text('technical_notes')->nullable();
            $table->text('deployment_notes')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            // indexes
            $table->index(['due_date']);

            // composite indexes (often queried together)
            $table->index(['status', 'priority']);
            $table->index(['project_id', 'status']);
            $table->index(['developer_id', 'status']);
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('tags');
    }
};
