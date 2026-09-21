<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fertilizer_reg extends Model
{
    protected $table = 'fertilizer_regs';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
    'fertilizer_name',
    'description',
    'category',
    'dosage_amount',
    'dosage_unit',
    'reference_quantity',
    'reference_unit',
];
}