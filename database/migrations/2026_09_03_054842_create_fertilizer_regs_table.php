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
        Schema::create('fertilizer_regs', function (Blueprint $table) {
            $table->id();
            $table->string('fertilizer_name');
            $table->text('description');
            $table->enum('category', ['Organic', 'Inorganic']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fertilizer_regs');
    }
};