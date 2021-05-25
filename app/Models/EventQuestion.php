<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQuestion extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_questions';

    protected $fillable = [
        'name',
        'code',
        'description',
        'order',
        'question_type_id',
    ];

    public function alternatives()
    {
        return $this->hasMany(EventQuestionAlternative::class, 'event_question_id');
    }
}
