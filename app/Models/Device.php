<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'devices';

    protected $fillable = [
        'device_model_id',
        'serial_number',
        'code',
        'tenant_id',
        'device_status_id',
        'vehicle_id',
        'password',
        'detail',
    ];

    protected static $logFillable = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function getDetailFixAttribute()
    {
        return \json_decode($this->attributes['detail']);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function simCard()
    {
        return $this->hasOne(SimCard::class, 'device_id');
    }

    public function model()
    {
        return $this->belongsTo(DeviceModel::class, 'device_model_id');
    }

    public function status()
    {
        return $this->belongsTo(DeviceStatus::class, 'device_status_id');
    }
}
