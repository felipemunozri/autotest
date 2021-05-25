<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBrand extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'car_brands';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}
