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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirant_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['infrastructure', 'education', 'health', 'agriculture', 'security', 'employment', 'youth_development', 'women_empowerment', 'others']);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->string('location');
            $table->string('beneficiaries')->nullable();
            
            // Project timeline
            $table->date('promise_date');
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            
            // Status tracking
            $table->enum('status', ['not_started', 'in_progress', 'on_hold', 'completed', 'abandoned'])->default('not_started');
            $table->integer('completion_percentage')->default(0);
            
            // Media and documentation
            $table->string('image_path')->nullable();
            $table->string('document_path')->nullable();
            
            // Additional metadata
            $table->boolean('is_public')->default(true);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['aspirant_id', 'status']);
            $table->index(['category', 'status']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
