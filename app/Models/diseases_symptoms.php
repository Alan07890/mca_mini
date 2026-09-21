<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class diseases_symptoms extends Model
{
    protected $table = 'diseases_symptoms';

    protected $primaryKey = 'symptom_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'symptom_name',
        'symptom_image',
    ];
}