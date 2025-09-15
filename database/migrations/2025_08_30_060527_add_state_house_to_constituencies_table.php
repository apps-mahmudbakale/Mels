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
        \DB::statement("ALTER TABLE constituencies MODIFY COLUMN type ENUM('federal', 'state', 'senatorial', 'state_house', 'lga') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove state_house type from enum
        \DB::statement("DELETE FROM constituencies WHERE type = 'state_house'");
        \DB::statement("ALTER TABLE constituencies MODIFY COLUMN type ENUM('federal', 'state', 'senatorial', 'lga') NOT NULL");
    }
};
