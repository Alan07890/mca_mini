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
        Schema::table('fertilizer_regs', function (Blueprint $table) {

            // Remove the old fields
            $table->dropColumn([
                'amount_per_plant',
                'amount_unit',
            ]);

            // Add the new flexible dosage fields
            $table->decimal('dosage_amount', 10, 2)
                ->nullable()
                ->after('category');

            $table->string('dosage_unit', 10)
                ->nullable()
                ->after('dosage_amount');

            $table->decimal('reference_quantity', 10, 2)
                ->nullable()
                ->after('dosage_unit');

            $table->string('reference_unit', 10)
                ->nullable()
                ->after('reference_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizer_regs', function (Blueprint $table) {

            // Remove the new fields
            $table->dropColumn([
                'dosage_amount',
                'dosage_unit',
                'reference_quantity',
                'reference_unit',
            ]);

            // Restore the previous fields
            $table->decimal('amount_per_plant', 10, 2)
                ->nullable()
                ->after('category');

            $table->string('amount_unit', 10)
                ->nullable()
                ->after('amount_per_plant');
        });
    }
};