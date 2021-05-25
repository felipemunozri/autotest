<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Events\Task\RegisteredTask;

class Task extends CustomModel
{
    use HasFactory;

    protected $dispatchesEvents = [
        'created' => RegisteredTask::class,
    ];

    protected $connection = 'public';

    protected $table = 'tasks';

    protected $fillable = [
        'tenant_id',
        'event_id',
        'vehicle_id',
        'executed_by',
        'action_id',
        'code',
        'detail',
        'observation',
    ];

    public function getDetailAttribute()
    {
        return json_decode($this->attributes['detail']);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function answers()
    {
        return $this->hasMany(TaskAnswer::class, 'task_id');
    }

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executed_by');
    }
}
