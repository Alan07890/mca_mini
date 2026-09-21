<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\diseases_categories;
use App\Models\diseases_symptoms;

class SymptomReg extends Model
{
    protected $table = 'symptom_regs';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'category_id',
        'symptom_id'
    ];

    public function category()
    {
        return $this->belongsTo(
            diseases_categories::class,
            'category_id',
            'category_id'
        );
    }

    public function symptom()
    {
        return $this->belongsTo(
            diseases_symptoms::class,
            'symptom_id',
            'symptom_id'
        );
    }
}