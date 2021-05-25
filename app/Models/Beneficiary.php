<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'beneficiaries';

    protected $fillable = [
        'dni',
        'dni_serial_number',
        'name',
        'lastname',
        'phone_number',
        'email',
        'address',
    ];

    public function setDniAttribute($value)
    {
        $this->attributes['dni'] = strtoupper($value);
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'beneficiary_vehicle', 'beneficiary_id', 'vehicle_id')->withPivot('owner');
    }
}
