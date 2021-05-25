<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleActivation extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'vehicle_activations';

    protected $fillable = [
        'code',
        'user_id',
        'vehicle_id',
        'device_id',
        'status_id',
        'details'
    ];

    public function status()
    {
        return $this->belongsTo(VehicleActivationStatus::class, 'status_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDetailsAttribute()
    {
        return json_decode($this->attributes['details']);
    }

    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }
}
