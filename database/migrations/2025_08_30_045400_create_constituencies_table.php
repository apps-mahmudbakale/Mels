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
        Schema::create('constituencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['federal', 'state', 'senatorial', 'lga']);
            $table->foreignId('state_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Pivot table for many-to-many relationship between constituencies and LGAs
        Schema::create('constituency_lga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('constituency_id')->constrained()->onDelete('cascade');
            $table->foreignId('lga_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constituency_lga');
        Schema::dropIfExists('constituencies');
    }
};
