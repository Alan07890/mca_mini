<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiseaseAnalysisSymptom extends Model
{
    protected $table = 'disease_analysis_symptoms';

    protected $primaryKey = 'id';

    protected $fillable = [
        'analysis_id',
        'symptom_id',
        'symptom_name',
        'source',
    ];


    /*
    |--------------------------------------------------------------------------
    | Parent Analysis
    |--------------------------------------------------------------------------
    */

    public function analysis(): BelongsTo
    {
        return $this->belongsTo(
            DiseaseAnalysisResult::class,
            'analysis_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Original Symptom
    |--------------------------------------------------------------------------
    |
    | symptom_id points to diseases_symptoms.symptom_id.
    | It is nullable because the original symptom may be deleted later,
    | while symptom_name remains available for historical analysis.
    |
    */

    public function symptom(): BelongsTo
    {
        return $this->belongsTo(
            diseases_symptoms::class,
            'symptom_id',
            'symptom_id'
        );
    }
}