<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiaryVehicle extends Model
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'beneficiary_vehicle';

    protected $fillable = [
        'beneficiary_id',
        'vehicle_id',
        'owner',
    ];

    protected $primaryKey = ['beneficiary_id', 'vehicle_id'];
    public $incrementing = false;

    protected $casts = [
        'beneficiary_id' => 'string',
        'vehicle_id' => 'string'
    ];

    protected function setKeysForSaveQuery($query)
    {
        return $query->where('beneficiary_id', $this->getAttribute('beneficiary_id'))
            ->where('vehicle_id', $this->getAttribute('vehicle_id'));
    }
}
