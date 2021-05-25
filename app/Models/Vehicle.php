<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'vehicles';

    protected $fillable = [
        'tenant_id',
        'country_id',
        'vehicle_type_id',
        'card_brand_id',
        'plate_number',
        'model',
        'model_id',
        'year',
        'chassis_number',
        'color_code',
        'color',
        'engine_capacity',
        'drive',
        'fuel_type_id',
        'owner_name',
        'owner_dni',
        'owner_adquisition_date',
        'key',
    ];

    public function device()
    {
        return $this->hasOne(Device::class, 'vehicle_id');
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_vehicle', 'vehicle_id', 'beneficiary_id')->withPivot('owner');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function fuelType()
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id');
    }

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class, 'card_brand_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function scopeOwner()
    {
        return $this->beneficiaries()->where('owner', 1)->first();
    }
}
