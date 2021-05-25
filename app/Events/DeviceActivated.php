<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceActivated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tenant;
    public $email;
    public $plateNumber;
    public $name;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email, $plateNumber, $name, $tenant = null)
    {
        $this->email = $email;
        $this->plateNumber = $plateNumber;
        $this->name = $name;
        $this->tenant = $tenant;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
