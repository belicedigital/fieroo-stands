<?php

namespace Fieroo\Stands\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandsTypeTranslation extends Model
{
    use HasFactory;

    protected $table = 'stands_types_translations';

    public $timestamps = false;

    protected $fillable = [
        'stand_type_id',
        'name',
        'locale',
        'price',
        'size',
        'max_number_modules',
        'description'
    ];
}
