<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_analysis_symptoms', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Parent analysis
            |--------------------------------------------------------------------------
            |
            | Deleting one analysis should remove its symptom records.
            |
            */

            $table->unsignedBigInteger('analysis_id');


            /*
            |--------------------------------------------------------------------------
            | Original symptom reference
            |--------------------------------------------------------------------------
            |
            | symptom_id is nullable because an administrator may later delete
            | the symptom from diseases_symptoms. The historical symptom_name
            | remains preserved below.
            |
            */

            $table->unsignedBigInteger('symptom_id')->nullable();

            $table->string('symptom_name');


            /*
            |--------------------------------------------------------------------------
            | Source
            |--------------------------------------------------------------------------
            |
            | farmer   -> directly selected by farmer
            | confirmed -> confirmed through image-analysis questions
            | ai       -> identified by Gemini during visual analysis
            |
            */

            $table->string('source', 30)->default('farmer');


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Foreign keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('analysis_id')
                ->references('id')
                ->on('disease_analysis_results')
                ->cascadeOnDelete();

            $table->foreign('symptom_id')
                ->references('symptom_id')
                ->on('diseases_symptoms')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('analysis_id');
            $table->index('symptom_id');
            $table->index('source');


            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate symptom entries for the same analysis/source
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'analysis_id',
                'symptom_id',
                'source'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_analysis_symptoms');
    }
};
