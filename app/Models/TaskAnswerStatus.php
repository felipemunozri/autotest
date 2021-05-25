<?php

namespace App\Models;

use App\Traits\AutoGenerateUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAnswerStatus extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'task_answer_status';

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = false;
}
