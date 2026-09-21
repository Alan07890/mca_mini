<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diseases_symptoms', function (Blueprint $table) {
            $table->string('symptom_image')->nullable()->after('symptom_name');
        });
    }

    public function down(): void
    {
        Schema::table('diseases_symptoms', function (Blueprint $table) {
            $table->dropColumn('symptom_image');
        });
    }
};