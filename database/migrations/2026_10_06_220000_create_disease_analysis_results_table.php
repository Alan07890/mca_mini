<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_analysis_results', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Farmer
            |--------------------------------------------------------------------------
            |
            | user_id is nullable so analysis history is not lost when an
            | administrator deletes a user account.
            |
            | farmer_name and farmer_email preserve the farmer information
            | that existed at the time of analysis.
            |
            */

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('farmer_name')->nullable();
            $table->string('farmer_email')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Analysis type / status
            |--------------------------------------------------------------------------
            */

            $table->string('analysis_type', 30);
            // symptoms | image | image_and_symptoms

            $table->string('status', 30)->default('completed');
            // completed | failed | needs_confirmation


            /*
            |--------------------------------------------------------------------------
            | Farmer field snapshot
            |--------------------------------------------------------------------------
            |
            | These values are copied from farmer_fields at analysis time so
            | old analysis history does not change when field details change.
            |
            */

            $table->decimal('field_area', 10, 2)->nullable();
            $table->string('field_area_unit', 20)->nullable();
            $table->unsignedInteger('field_plant_count')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Uploaded image
            |--------------------------------------------------------------------------
            */

            $table->string('image_path')->nullable();
            $table->string('image_original_name')->nullable();
            $table->string('image_mime_type', 100)->nullable();


            /*
            |--------------------------------------------------------------------------
            | Final AI identification
            |--------------------------------------------------------------------------
            */

            $table->string('disease_name')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('ai_confidence', 50)->nullable();
            $table->decimal('ai_confidence_score', 5, 2)->nullable();

            $table->text('ai_reason')->nullable();


            /*
            |--------------------------------------------------------------------------
            | AI agricultural guidance
            |--------------------------------------------------------------------------
            */

            $table->text('causes')->nullable();
            $table->text('prevention')->nullable();
            $table->text('fertilizer')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Database comparison snapshot
            |--------------------------------------------------------------------------
            */

            $table->boolean('database_match_found')->default(false);

            $table->string('database_disease_name')->nullable();

            $table->decimal(
                'database_match_percentage',
                5,
                2
            )->nullable();

            $table->unsignedInteger(
                'database_matched_count'
            )->nullable();

            $table->unsignedInteger(
                'database_total_symptoms'
            )->nullable();

            $table->json('database_matched_symptoms')->nullable();

            $table->json('database_other_known_symptoms')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Preliminary image analysis
            |--------------------------------------------------------------------------
            */

            $table->text('visual_summary')->nullable();

            $table->json('visible_symptoms')->nullable();

            $table->json('possible_diseases')->nullable();

            $table->string('image_analysis_confidence', 50)->nullable();

            $table->string('image_quality', 50)->nullable();

            $table->boolean('needs_better_image')->default(false);


            /*
            |--------------------------------------------------------------------------
            | Farmer confirmation answers
            |--------------------------------------------------------------------------
            |
            | Example:
            | [
            |   {
            |     "question": "Do you see leaf curling?",
            |     "answer": "YES"
            |   }
            | ]
            |
            */

            $table->json('confirmation_answers')->nullable();


            /*
            |--------------------------------------------------------------------------
            | AI model / analysis time
            |--------------------------------------------------------------------------
            */

            $table->string('ai_model', 100)->nullable();

            $table->timestamp('analyzed_at')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Laravel timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Foreign keys
            |--------------------------------------------------------------------------
            */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('category_id')
                ->references('category_id')
                ->on('diseases_categories')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');
            $table->index('analysis_type');
            $table->index('status');
            $table->index('analyzed_at');
            $table->index('database_match_found');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disease_analysis_results');
    }
};
