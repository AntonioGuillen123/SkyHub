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
        Schema::create('journey', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained('destination')->onDelete('cascade');
            $table->foreignId('arrival_id')->constrained('destination')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journey');
    }
};
