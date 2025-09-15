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
        DB::statement("ALTER TABLE project_updates MODIFY status ENUM('pending','in_progress','on_hold','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE project_updates MODIFY status ENUM('not_started','in_progress','on_hold','completed','abandoned') NOT NULL DEFAULT 'not_started'");
    }
};
