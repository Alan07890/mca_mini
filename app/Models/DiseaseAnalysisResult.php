<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiseaseAnalysisResult extends Model
{
    protected $table = 'disease_analysis_results';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'farmer_name',
        'farmer_email',

        'analysis_type',
        'status',

        'field_area',
        'field_area_unit',
        'field_plant_count',

        'image_path',
        'image_original_name',
        'image_mime_type',

        'disease_name',
        'category_id',

        'ai_confidence',
        'ai_confidence_score',
        'ai_reason',

        'causes',
        'prevention',
        'fertilizer',

        'database_match_found',
        'database_disease_name',
        'database_match_percentage',
        'database_matched_count',
        'database_total_symptoms',
        'database_matched_symptoms',
        'database_other_known_symptoms',

        'visual_summary',
        'visible_symptoms',
        'possible_diseases',
        'image_analysis_confidence',
        'image_quality',
        'needs_better_image',

        'confirmation_answers',

        'ai_model',
        'analyzed_at',
    ];

    protected $casts = [
        'field_area' => 'decimal:2',

        'ai_confidence_score' => 'decimal:2',

        'database_match_found' => 'boolean',

        'database_match_percentage' => 'decimal:2',

        'database_matched_count' => 'integer',
        'database_total_symptoms' => 'integer',

        'database_matched_symptoms' => 'array',
        'database_other_known_symptoms' => 'array',

        'visible_symptoms' => 'array',
        'possible_diseases' => 'array',
        'confirmation_answers' => 'array',

        'needs_better_image' => 'boolean',

        'analyzed_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Farmer
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Disease Category
    |--------------------------------------------------------------------------
    |
    | category_id in this table points to:
    | diseases_categories.category_id
    |
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            diseases_categories::class,
            'category_id',
            'category_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Analysis Symptoms
    |--------------------------------------------------------------------------
    */

    public function symptoms(): HasMany
    {
        return $this->hasMany(
            DiseaseAnalysisSymptom::class,
            'analysis_id',
            'id'
        );
    }
}