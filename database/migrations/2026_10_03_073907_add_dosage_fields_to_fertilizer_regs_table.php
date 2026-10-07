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
        /*
        |--------------------------------------------------------------------------
        | Add only columns that do not already exist
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn(
            'fertilizer_regs',
            'dosage_amount'
        )) {

            Schema::table(
                'fertilizer_regs',
                function (Blueprint $table) {
                    $table->decimal(
                        'dosage_amount',
                        10,
                        2
                    )->nullable()->after('category');
                }
            );
        }


        if (!Schema::hasColumn(
            'fertilizer_regs',
            'dosage_unit'
        )) {

            Schema::table(
                'fertilizer_regs',
                function (Blueprint $table) {
                    $table->string(
                        'dosage_unit',
                        20
                    )->nullable()->after('dosage_amount');
                }
            );
        }


        if (!Schema::hasColumn(
            'fertilizer_regs',
            'reference_quantity'
        )) {

            Schema::table(
                'fertilizer_regs',
                function (Blueprint $table) {
                    $table->decimal(
                        'reference_quantity',
                        10,
                        2
                    )->nullable()->after('dosage_unit');
                }
            );
        }


        if (!Schema::hasColumn(
            'fertilizer_regs',
            'reference_unit'
        )) {

            Schema::table(
                'fertilizer_regs',
                function (Blueprint $table) {
                    $table->string(
                        'reference_unit',
                        20
                    )->nullable()->after('reference_quantity');
                }
            );
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Remove only columns that exist
        |--------------------------------------------------------------------------
        */

        $columns = [
            'dosage_amount',
            'dosage_unit',
            'reference_quantity',
            'reference_unit',
        ];

        foreach ($columns as $column) {

            if (
                Schema::hasColumn(
                    'fertilizer_regs',
                    $column
                )
            ) {

                Schema::table(
                    'fertilizer_regs',
                    function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    }
                );
            }
        }
    }
};