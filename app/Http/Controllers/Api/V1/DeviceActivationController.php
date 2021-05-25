<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Device;
use App\Models\User;
use App\Models\Beneficiary;
use App\Models\DeviceStatus;
use App\Models\Vehicle;
use App\Models\VehicleActivation;
use App\Models\VehicleActivationStatus;
use App\Helpers\RandomCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VehicleActivated;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Events\DeviceActivated;

class DeviceActivationController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Device();
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'plate_number' => 'required',
            'device_id' => 'required_without:serial_number',
            'serial_number' => 'required_without:device_id',
        ]);

        $device_id = $request->input('device_id');
        $serial_number = $request->input('serial_number');
        $plate_number = $request->input('plate_number');

        $user = $this->myUser();

        $status = VehicleActivationStatus::where('code','started')->firstOrFail();

        $vehicle_activation = VehicleActivation::create([
            'code' => RandomCode::generate(8),
            'user_id' => $user->id,
            'status_id' => $status->id
        ]);

        $vehicle = Vehicle::query()->where('plate_number', $params['plate_number'])->first();


        $device = Device::query()
                        ->when($device_id, function ($q, $device_id) {
                            return $q->where('id', $device_id);
                        })->when($serial_number, function ($q, $serial_number) {
                            return $q->orWhere('serial_number', $serial_number);
                        })->first();
    
        $messages = [];
        $vehicle_available = true;
        $device_available = true;
        $vehicle_status = 'OK';
        $device_status = 'OK';
        
        if ($vehicle){
            $vehicle_activation->vehicle_id = $vehicle->id;
            $vehicle->load('device.status');
            if($vehicle->device && ($vehicle->device->status && $vehicle->device->status->name === 'activated')) {
                $vehicle_available = false;
                $vehicle_status = 'ALREADY-ACTIVATED';
                array_push($messages, 'the vehicle already has an active device');
            }
        } else {
            $vehicle_status = 'NOT-FOUND';
            array_push($messages, 'Vehicle not found');
        }

        if($device) {
            $vehicle_activation->device_id = $device->id;
            $device->load('status');
            $rejected_device_status = ['activated', 'disabled'];
            if($device->status && in_array($device->status->name, $rejected_device_status)) {
                $device_available = false;
                $device_status = 'INVALID-DEVICE';
                array_push($messages, 'the device does not have a valid status to be activated');
            }
        } else {
            $device_status = 'NOT-FOUND';
            array_push($messages, 'Device not found');
        }

        if($device && $vehicle && $vehicle_available && $device_available) {
            $status = DeviceStatus::query()->where('name', '=', 'activated')->firstOrFail();
            $device->device_status_id = $status ? $status->id : null;
            $device->vehicle_id = $vehicle->id;
            $device->save();
            $messages = ['Device activated successfully'];
            $status = VehicleActivationStatus::where('code','activated')->firstOrFail();
            
        } else {
            $status = VehicleActivationStatus::where('code','failed')->firstOrFail();
            
        }

        $details = [
            'messages' => $messages,
            'vehicle_status' => $vehicle_status,
            'device_status' => $device_status
        ];

        $vehicle_activation->status_id = $status->id;
        $vehicle_activation->details = json_encode($details);

        $vehicle_activation->save();
        $vehicle_activation->load('status');

        // send notification
        if($vehicle_activation->status && $vehicle_activation->status->code === 'activated'){
            $users = User::whereHas('roles', function ($q) {
                $q->whereNotIn('name', ['technical']);
            })->get();
            Notification::send($users, new VehicleActivated($user, $vehicle, $device));

            $beneficiary = $vehicle->owner();
            $tenant = $vehicle->tenant()->first();

            DeviceActivated::dispatch(
                $beneficiary->email,
                $vehicle->plate_number,
                $beneficiary->name.' '.$beneficiary->lastname,
                $tenant->name
            );
        }

        return response()->json([
            'messages' => $messages,
            'data' => $vehicle_activation
        ]);
    }
}
