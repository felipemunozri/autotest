<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventQuestionApplied extends CustomModel
{
    use HasFactory;

    protected $connection = 'public';

    protected $table = 'event_questions_applied';

    protected $fillable = [
        'event_id',
        'event_question_id',
        'alternative_id',
        'number_answer',
        'answer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function question()
    {
        return $this->belongsTo(EventQuestion::class, 'event_question_id');
    }

    public function alternative()
    {
        return $this->belongsTo(EventQuestionAlternative::class, 'alternative_id');
    }
}
