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
        Schema::create('fertilizer_applications', function (Blueprint $table) {

            $table->id();

            // Farmer who applied the fertilizer
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Fertilizer selected from admin fertilizer catalog
            $table->foreignId('fertilizer_id')
                ->constrained('fertilizer_regs')
                ->cascadeOnDelete();

            // Actual amount applied
            $table->decimal('quantity', 10, 2);

            // g, kg, ml, L
            $table->string('quantity_unit', 10);

            // Date fertilizer was applied
            $table->date('applied_date');

            // Next planned fertilizer date
            $table->date('next_application_date')
                ->nullable();

            // Optional farmer notes
            $table->text('notes')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizer_applications');
    }
};