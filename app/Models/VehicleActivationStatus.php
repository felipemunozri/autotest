<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleActivationStatus extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'vehicle_activation_status';

    protected $fillable = [
        'code',
        'name'
    ];
}
