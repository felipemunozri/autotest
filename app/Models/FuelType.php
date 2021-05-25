<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelType extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'fuel_types';

    protected $fillable = [
        'name',
        'code',
    ];

    public $timestamps = false;
}
