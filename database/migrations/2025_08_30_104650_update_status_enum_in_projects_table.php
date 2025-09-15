<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Align the DB enum with App\Enums\ProjectStatus and the Filament form
        DB::statement("ALTER TABLE projects MODIFY status ENUM('pending','in_progress','on_hold','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to the original enum from the create table migration
        DB::statement("ALTER TABLE projects MODIFY status ENUM('not_started','in_progress','on_hold','completed','abandoned') NOT NULL DEFAULT 'not_started'");
    }
};
