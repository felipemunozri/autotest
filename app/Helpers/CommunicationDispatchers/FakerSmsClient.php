<?php

namespace App\Helpers\CommunicationDispatchers;

use App\Events\SmsSent;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;
use Ramsey\Uuid\Uuid;
use App\Helpers\DeviceSchemas\ExampleAnswer;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\V1\OperationSmsController;

use App\Models\Action;
use App\Models\Device;
use App\Models\Vehicle;
use App\Models\SimCard;
use App\Models\Task;
use App\Models\TaskAnswer;
use App\Helpers\DeviceSchemas\ExtractSchema;

class FakerSmsClient implements DispatcherInterface
{
    private $phone = "+56900000000";

    public function __construct() {
    }

    public function sendSMS($phone_number, $message)
    {
      return (object) [
        'sid' => Uuid::uuid4(),
        'to' => $phone_number,
        'from' => $this->phone,
        'body' => $message
      ];
    }

    public function callPhone($phone_number)
    {
      return (object) [
        'sid' => Uuid::uuid4(),
        'to' => $phone_number,
        'from' => $this->phone
      ];
    }

    public function sendFakeResponse($from, $device_type, $action_type)
    {
      $message_id = Uuid::uuid4();

      $message = ExampleAnswer::generate($device_type, $action_type);

      $simCard = SimCard::with('device.vehicle', 'device.model')->where('phone_number', '=', $from)->firstOrFail();
        if($simCard) {
            \event(new SmsSent($simCard));
        }

      $device = $simCard->device;
      $vehicle = $device->vehicle;
      $model_code = $device->model->name;

      $schema = ExtractSchema::extract($message, $model_code);
      if(!$schema || !$schema->is_valid) return response()->json('invalid message', 422);

      $action = Action::where('code', $schema->type)->first();
      if(!$action) return response()->json('action not found', 422);

      $task = Task::where('vehicle_id', $vehicle->id)
                    ->where('action_id', $action->id)
                    ->whereHas('event', function ($query) {
                        $query->whereHas('status', function ($sub_query) {
                            $sub_query->where('code', 'validated')->orWhere('code', 'in-progress');
                        });
                    })
                    ->orderByDesc('created_at')
                    ->first();
      \Log::debug(json_encode($task));
      if(!$task) \Log::debug('Task not found');
      $task_answer = TaskAnswer::create([
          'vehicle_id' => $vehicle->id,
          'action_id' => $action->id,
          'task_id' => $task->id,
          'event_id' => $task->event_id,
          'phone_number' => $from,
          'detail' => json_encode($schema),
          'code' => $message_id,
      ]);
      \Log::debug(json_encode($task_answer));
    }
}
