<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class diseases_categories  extends Model
{
    protected $table = 'diseases_categories';

    protected $primaryKey = 'category_id';

    public $incrementing = true;

    protected $keyType = 'int';
}