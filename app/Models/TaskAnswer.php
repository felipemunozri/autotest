<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Events\TaskAnswer\RegisteredAnswer;

class TaskAnswer extends CustomModel
{
    use HasFactory;

    protected $dispatchesEvents = [
        'created' => RegisteredAnswer::class,
    ];

    protected $connection = 'public';

    protected $table = 'task_answers';

    protected $fillable = [
        'event_id',
        'task_id',
        'vehicle_id',
        'action_id',
        'phone_number',
        'detail',
        'task_answer_status_id',
        'code',
    ];

    public function getDetailAttribute()
    {
        return json_decode($this->attributes['detail']);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_id');
    }

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function status()
    {
        return $this->belongsTo(TaskAnswerStatus::class, 'task_answer_status_id');
    }
}
