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
        Schema::create('bug_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->string('module')->nullable();
            $table->foreignId('reported_by')->references('id')->on('users');
            $table->string('title');
            $table->longText('description');
            $table->string('severity')->default('medium');
            $table->string('status')->default('open');
            $table->longText('steps_to_reproduce')->nullable();
            $table->longText('expected_behavior')->nullable();
            $table->longText('actual_behavior')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // indexes
            $table->index(['status']);
            $table->index(['resolved_at']);

            // composite indexes (often queried together)
            $table->index(['status', 'severity']);
            $table->index(['project_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bug_reports');
    }
};
