<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class FertilizerApplication extends Model
{
    protected $fillable = [
        'user_id',
        'fertilizer_id',
        'quantity',
        'quantity_unit',
        'applied_date',
        'next_application_date',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'applied_date' => 'date',
        'next_application_date' => 'date',
    ];

    /**
     * Farmer who applied the fertilizer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fertilizer selected from fertilizer_regs.
     */
    public function fertilizer(): BelongsTo
    {
        return $this->belongsTo(
            fertilizer_reg::class,
            'fertilizer_id'
        );
    }
    public function applications(): HasMany
{
    return $this->hasMany(
        FertilizerApplication::class,
        'fertilizer_id'
    );
}

}