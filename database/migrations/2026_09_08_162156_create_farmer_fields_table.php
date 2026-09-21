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
        Schema::create('farmer_fields', function (Blueprint $table) {
            $table->id();

            // The farmer/user who owns this field
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Field area entered by the farmer
            $table->decimal('area', 10, 2);

            // Area unit: acre or cent
            $table->enum('area_unit', ['acre', 'cent']);

            // Number of cardamom plants
            $table->unsignedInteger('plant_count');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_fields');
    }
};