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
        // First, create a temporary JSON column
        Schema::table('projects', function (Blueprint $table) {
            $table->json('beneficiaries_json')->nullable()->after('beneficiaries');
        });

        // Convert existing data to JSON
        \DB::table('projects')->get()->each(function ($project) {
            $beneficiaries = $project->beneficiaries ? explode(',', $project->beneficiaries) : [];
            \DB::table('projects')
                ->where('id', $project->id)
                ->update(['beneficiaries_json' => json_encode($beneficiaries)]);
        });

        // Remove the old column and rename the new one
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('beneficiaries');
            $table->renameColumn('beneficiaries_json', 'beneficiaries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, create a temporary string column
        Schema::table('projects', function (Blueprint $table) {
            $table->text('beneficiaries_string')->nullable()->after('beneficiaries');
        });

        // Convert JSON data back to comma-separated string
        \DB::table('projects')->get()->each(function ($project) {
            $beneficiaries = $project->beneficiaries ? implode(',', json_decode($project->beneficiaries, true)) : '';
            \DB::table('projects')
                ->where('id', $project->id)
                ->update(['beneficiaries_string' => $beneficiaries]);
        });

        // Remove the JSON column and rename the string one
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('beneficiaries');
            $table->renameColumn('beneficiaries_string', 'beneficiaries');
        });
    }
};
