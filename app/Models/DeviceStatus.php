<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'device_status';

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;
}
