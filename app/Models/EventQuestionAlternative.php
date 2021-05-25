<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQuestionAlternative extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_questions_alternatives';

    protected $fillable = [
        'event_question_id',
        'code',
        'body',
        'order',
    ];

    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo(EventQuestionAlternative::class, 'event_question_id');
    }
}
