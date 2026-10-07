<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiseaseFertilizer extends Model
{
    protected $table = 'disease_fertilizers';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'category_id',
        'fertilizer_id',
    ];

    public function disease(): BelongsTo
    {
        return $this->belongsTo(
            diseases_categories::class,
            'category_id',
            'category_id'
        );
    }

    public function fertilizer(): BelongsTo
    {
        return $this->belongsTo(
            fertilizer_reg::class,
            'fertilizer_id',
            'id'
        );
    }
}