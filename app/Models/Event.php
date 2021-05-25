<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'events';

    protected $fillable = [
        'tenant_id',
        'beneficiary_id',
        'vehicle_id',
        'received_by',
        'detail',
        'event_date',
        'event_time',
        'observation',
        'event_status_id',
        'origin',
        'event_start',
        'event_end',
        'event_result_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'event_end',
        'event_start'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'event_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function status()
    {
        return $this->belongsTo(EventStatus::class, 'event_status_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class,'received_by');
    }

    public function answers()
    {
        return $this->hasMany(EventQuestionApplied::class, 'event_id');
    }

    public function eventCalls()
    {
        return $this->hasMany(EventCall::class, 'event_id');
    }

    public function getOriginAttribute()
    {
        return json_decode($this->attributes['origin']);
    }

    public function result()
    {
        return $this->belongsTo(EventResult::class, 'event_result_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
