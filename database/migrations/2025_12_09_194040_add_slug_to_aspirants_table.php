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
        Schema::table('aspirants', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('last_name');
        });

        // Backfill slugs
        $aspirants = DB::table('aspirants')->get();
        foreach ($aspirants as $aspirant) {
            $slug = Str::slug($aspirant->first_name . ' ' . $aspirant->last_name);
            // Ensure uniqueness if needed, but for simple backfill:
            DB::table('aspirants')
                ->where('id', $aspirant->id)
                ->update(['slug' => $slug]);
        }

        // Now make it unique and not null
        Schema::table('aspirants', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aspirants', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
