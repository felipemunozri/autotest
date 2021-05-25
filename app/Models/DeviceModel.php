<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceModel extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'device_models';

    protected $fillable = [
        'name',
        'description',
        'detail',
    ];

    public function getDetailAttribute()
    {
        return \json_decode($this->attributes['detail']);
    }
}
