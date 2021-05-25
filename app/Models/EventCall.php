<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCall extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_calls';

    protected $fillable = [
        'beneficiary_id',
        'user_id',
        'event_id',
        'call_start',
        'call_end'
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
