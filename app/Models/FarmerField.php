<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FarmerField extends Model
{
    protected $fillable = [
        'user_id',
        'area',
        'area_unit',
        'plant_count',
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'plant_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}