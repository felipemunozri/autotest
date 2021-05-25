<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCode extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_codes';

    protected $fillable = [
        'event_id',
        'code',
        'beneficiary_id',
        'duration',
        'status_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'beneficiary_id');
    }

    public function status()
    {
        return $this->belongsTo(EventCodesStatus::class, 'status_id');
    }
}