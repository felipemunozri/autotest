<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'vehicle_types';

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;
}
