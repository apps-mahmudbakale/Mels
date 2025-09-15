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
        // First, drop the existing column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('beneficiaries');
        });

        // Then add it back as a JSON column
        Schema::table('projects', function (Blueprint $table) {
            $table->json('beneficiaries')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the JSON column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('beneficiaries');
        });

        // Add it back as a text column
        Schema::table('projects', function (Blueprint $table) {
            $table->text('beneficiaries')->nullable()->after('location');
        });
    }
};
