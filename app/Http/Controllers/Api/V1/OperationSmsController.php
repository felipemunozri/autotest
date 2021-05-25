<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Domain\Helpers\OperationHelper;
use App\Domain\Helpers\VehicleHelper;
use App\Events\SmsSent;
use App\Helpers\CommunicationDispatchers\DispatcherInterface;
use App\Http\Controllers\Api\BaseApiController;
use App\Models\Action;
use App\Models\Carrier;
use App\Models\Device;
use App\Models\Vehicle;
use App\Models\SimCard;
use App\Models\Beneficiary;
use App\Models\Event;
use App\Models\EventCode;
use App\Models\EventCodeStatus;
use App\Helpers\RandomCode;
use App\Models\Task;
use App\Models\TaskAnswer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\DeviceSchemas\ExtractSchema;

class OperationSmsController extends BaseApiController
{
    public function send(Request $request, Vehicle $vehicle)
    {
        $actionCode = $request->input('action');
        $action = Action::query()->where('code', $actionCode)->first();

        if($action) {
            $simCard = (new VehicleHelper())->getDevicePhoneNumber($vehicle->id);
            $device = Device::query()->where('vehicle_id', $vehicle->id)->first();
            $password = $device->password ?? '123456';

            if($simCard) {
                $phoneNumber = $simCard->data->phone_number;
                $actionDetail = $action->code;
                $deviceModel = $device->model()->first();
                $statement = $deviceModel->detail->$actionDetail;
                $body = $statement.$password;
                $message = (new OperationHelper())->sendSms($phoneNumber, $body);

                $taskData = [
                    'tenant_id' => $this->currentTenantId(),
                    'event_id' => $request->input('event_id'),
                    'vehicle_id' => $vehicle->id,
                    'action_id' => $action->id,
                    'executed_by' => \auth()->user()->id,
                    'detail' => \json_encode((object) ['body' => $body]),
                    'observation' => $request->input('observation'),
                    //'code' => 'foo-bar-123',
                    'code' => $message->sid,
                ];

                $task = (new OperationHelper())->storeTask($taskData);
                $task->load('action');

                return response()->json(['message' => 'sms sended', 'data' => $task]);
            }

            return response()->json('No se ha encontrado SIM registrada para el vehículo');
        }

        return response()->json('Acción no encontrada');

    }

    private function sendSMS(DispatcherInterface $dispatcher, $phoneNumber, $message)
    {
        $dispatcher->sendSMS($phoneNumber, $message);
    }

    public function receive(Request $request)
    {
        \Log::info("incoming message");
        $this->validate($request,
            [
                'To' => 'required|string',
                'From' => 'required|string',
                'Body' => 'required|string',
                'MessageSid' => 'required|string',
            ]
        );

        $params = $request->only('To', 'From', 'Body', 'MessageSid');

        $to_number = $params['To'];
        $from_number = $params['From'];
        $response_message = $params['Body'];
        $message_id = $params['MessageSid'];

        $simCard = SimCard::with('device.vehicle', 'device.model')->where('phone_number', '=', $from_number)->firstOrFail();

        if($simCard) {
            \event(new SmsSent($simCard));
        }

        $device = $simCard->device;
        $vehicle = $device->vehicle;
        $model_code = $device->model->name;

        $schema = ExtractSchema::extract($response_message, $model_code);
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
        if(!$task) return response()->json('task not found', 422);
        $task_answer = TaskAnswer::create([
            'vehicle_id' => $vehicle->id,
            'action_id' => $action->id,
            'task_id' => $task->id,
            'event_id' => $task->event_id,
            'phone_number' => $from_number,
            'detail' => json_encode($schema),
            'code' => $message_id,
        ]);

        (new AuditHelper())->storeAuditRecord($task_answer, 'created');

        \Log::info(json_encode($task_answer));
        \Log::info("message received");

        //return response()->json('message received', 200);
    }

    public function sendConfirmationCode(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'event_id' => 'required',
        ]);

        $beneficiary_id = $request->input('beneficiary_id');
        $event_id = $request->input('event_id');
        $beneficiary = Beneficiary::where('id',$beneficiary_id)->firstOrFail();
        $event = Event::where('id', $event_id)->firstOrFail();

        $status = EventCodeStatus::where('code', 'pending')->first();

        $previous_code = EventCode::where('event_id', $event->id)
                                ->where('beneficiary_id', $beneficiary->id)
                                ->where('status_id', $status->id)->first();

        if ($previous_code) {
            $expired_status = EventCodeStatus::where('code', 'expired')->first();
            $previous_code->status_id = $expired_status->id;
            $previous_code->save();
        }

        $date = Carbon::now();
        $minutes = 5;
        $date->addMinutes($minutes);
        $duration = $date->toDateTimeString();
        $code = strtoupper(RandomCode::generate());


        $event_code = EventCode::create([
            'event_id' => $event->id,
            'code' => $code,
            'beneficiary_id' => $beneficiary->id,
            'duration' => $duration,
            'status_id' => $status->id
        ]);

        (new AuditHelper())->storeAuditRecord($event_code, 'created');

        $body = "AutoSeguro365\nTu código de verificación es: $code\n";

        try {
            $message = (new OperationHelper())->sendSms($beneficiary->phone_number, $body);
        } catch (Exception $e) {
            \Log::error($e);
        }


        return response()->json(['sent'=>true], 200);
    }
}
