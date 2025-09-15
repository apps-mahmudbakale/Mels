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
        Schema::table('projects', function (Blueprint $table) {
            // First, drop the existing column
            $table->dropColumn('beneficiaries');
        });

        Schema::table('projects', function (Blueprint $table) {
            // Add it back as a simple string column
            $table->string('beneficiaries')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('beneficiaries');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->json('beneficiaries')->nullable()->after('location');
        });
    }
};
