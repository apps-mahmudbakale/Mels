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
        Schema::create('project_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Who made the update
            
            // Update details
            $table->string('title');
            $table->text('description');
            
            // Progress tracking
            $table->enum('status', ['not_started', 'in_progress', 'on_hold', 'completed', 'abandoned']);
            $table->integer('completion_percentage')->default(0);
            
            // Media and documentation
            $table->string('image_path')->nullable();
            $table->string('document_path')->nullable();
            
            // Financials (if applicable)
            $table->decimal('amount_spent', 15, 2)->nullable();
            $table->string('funding_source')->nullable();
            
            // Timeline
            $table->date('update_date');
            
            // Next steps
            $table->text('next_steps')->nullable();
            $table->date('next_update_date')->nullable();
            
            // Approval/verification
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['project_id', 'update_date']);
            $table->index(['status', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_updates');
    }
};
