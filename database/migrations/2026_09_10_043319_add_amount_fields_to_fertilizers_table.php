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
            $table->decimal('amount_per_plant', 10, 2)
                ->nullable()
                ->after('category');

            $table->string('amount_unit', 10)
                ->nullable()
                ->after('amount_per_plant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fertilizer_regs', function (Blueprint $table) {
            $table->dropColumn([
                'amount_per_plant',
                'amount_unit',
            ]);
        });
    }
};