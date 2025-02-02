<?php

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
        DB::unprepared('
            CREATE TRIGGER before_insert_flight
            BEFORE INSERT ON flight
            FOR EACH ROW
            BEGIN
                DECLARE maximum_flight_places INT;
                DECLARE remaining_places INT;
                
                SET remaining_places = NEW.remaining_places;

                SELECT maximum_places INTO maximum_flight_places FROM airplane WHERE airplane.id = NEW.airplane_id;
                
                IF remaining_places = -1 THEN
                    SET remaining_places = maximum_flight_places;
                END IF;
                
                SET NEW.remaining_places = remaining_places;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_flight');
    }
};
