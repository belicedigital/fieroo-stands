<?php

namespace Fieroo\Stands\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Stands\Models\StandsTypeTranslation;

class StandsType extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function translations()
    {
        return $this->hasMany(StandsTypeTranslation::class);
    }
}
