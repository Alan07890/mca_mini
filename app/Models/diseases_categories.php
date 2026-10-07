<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class diseases_categories  extends Model
{
    protected $table = 'diseases_categories';

    protected $primaryKey = 'category_id';

    public $incrementing = true;

    protected $keyType = 'int';
    public function fertilizers(): HasMany
{
    return $this->hasMany(
        DiseaseFertilizer::class,
        'category_id',
        'category_id'
    );
}
}