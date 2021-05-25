<?php

namespace App\Listeners\FakerSmsClient;

use App\Events\Task\RegisteredTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Helpers\CommunicationDispatchers\FakerSmsClient;
use Illuminate\Support\Facades\Config;

use App\Models\Task;
use App\Models\TaskAnswer;

class CreateFakeAnswer
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
     * @param  RegisteredTask  $event
     * @return void
     */
    public function handle(RegisteredTask $event)
    {
        $sms_operator = Config::get('sms-operator.service');
        if($sms_operator === 'fakerClient'){
            $task = $event->task;
            $task->load(['action', 'vehicle.device.simCard']);
            $device = $task->vehicle->device;
            $from = $device->simCard->phone_number;
            $device_type = $device->code;
            $action_type = $task->action->code;

            $faker_client = new FakerSmsClient();
            $faker_client->sendFakeResponse($from, $device_type, $action_type);
        }
        
    }
}
