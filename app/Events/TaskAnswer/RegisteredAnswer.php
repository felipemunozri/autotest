<?php

namespace App\Events\TaskAnswer;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\TaskAnswer;

class RegisteredAnswer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task_answer;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TaskAnswer $task_answer)
    {
        $this->task_answer = $task_answer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("task-answer.{$this->task_answer->id}");
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'registered-answer';
    }
}
