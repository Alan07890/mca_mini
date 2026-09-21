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
      Schema::create('symptom_regs', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('category_id');
    $table->unsignedBigInteger('symptom_id');

    $table->foreign('category_id')
          ->references('category_id')
          ->on('diseases_categories')
          ->onDelete('cascade');

    $table->foreign('symptom_id')
          ->references('symptom_id')
          ->on('diseases_symptoms')
          ->onDelete('cascade');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptom_regs');
    }
};
