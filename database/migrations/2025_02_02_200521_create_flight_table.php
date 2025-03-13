<?php

use App\Models\Airplane;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flight', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airplane_id')->constrained('airplane')->onDelete('cascade');
            $table->foreignId('journey_id')->constrained('journey')->onDelete('cascade');
            $table->boolean('state')->default(1);
            $table->integer('remaining_places')->nullable();
            $table->timestamp('flight_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('arrival_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->double('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight');
    }
};
