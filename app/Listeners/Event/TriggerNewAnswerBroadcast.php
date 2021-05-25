<?php

namespace App\Listeners\Event;

use App\Events\TaskAnswer\RegisteredAnswer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Event;
use App\Models\TaskAnswer;

use App\Events\Event\NewAnswer;


class TriggerNewAnswerBroadcast
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RegisteredAnswer  $event
     * @return void
     */
    public function handle(RegisteredAnswer $event)
    {
        $task_answer = $event->task_answer;
        $event = Event::where('id', $task_answer->event_id)->first();
        if($event) {
            event(new NewAnswer($event, $task_answer));
        }
    }
}
