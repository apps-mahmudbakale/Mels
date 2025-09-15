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
        // First, ensure the column is text to avoid any type issues
        Schema::table('projects', function (Blueprint $table) {
            $table->text('beneficiaries')->change();
        });

        // Then convert to JSON
        Schema::table('projects', function (Blueprint $table) {
            $table->json('beneficiaries')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to text if needed
        Schema::table('projects', function (Blueprint $table) {
            $table->text('beneficiaries')->change();
        });
    }
};
