<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'carriers';

    protected $fillable = [
        'name',
        'country_id',
        'sms_cost',
        'currency_id',
    ];

    public $timestamps = false;

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
