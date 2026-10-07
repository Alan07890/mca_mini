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
        Schema::create('disease_fertilizers', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('category_id');

            $table->unsignedBigInteger('fertilizer_id');

            $table->foreign('category_id')
                ->references('category_id')
                ->on('diseases_categories')
                ->onDelete('cascade');

            $table->foreign('fertilizer_id')
                ->references('id')
                ->on('fertilizer_regs')
                ->onDelete('cascade');

            $table->unique([
                'category_id',
                'fertilizer_id'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disease_fertilizers');
    }
};