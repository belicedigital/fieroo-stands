<?php

namespace Fieroo\Stands\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Stands\Models\StandsTypeTranslation;
use Fieroo\Exhibitors\Models\StandTypeCategory;

class StandsType extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function translations()
    {
        return $this->hasMany(StandsTypeTranslation::class, 'stand_type_id');
    }

    public function categories()
    {
        return $this->hasMany(StandTypeCategory::class, 'stand_type_id');
    }
}
