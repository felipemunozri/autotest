<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimCard extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'sim_cards';

    protected $fillable = [
        'country_id',
        'carrier_id',
        'phone_number',
        'sim_card_status_id',
        'device_id',
        'tenant_id',
        'balance',
        'sms'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function status()
    {
        return $this->belongsTo(SimCardStatus::class, 'sim_card_status_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Carrier::class, 'tenant_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getSmsBalanceAttribute()
    {
        $carrier = Carrier::query()->where('id', $this->attributes['carrier_id'])->firstOrFail();
        $smsCost = $carrier->sms_cost ?? 50;
        if ($carrier) {
            return \number_format($this->attributes['balance']/$smsCost);
        }
    }
}
