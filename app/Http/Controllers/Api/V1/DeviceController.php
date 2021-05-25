<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\Device;
use App\Models\SimCard;
use App\Models\SimCardStatus;
use App\Models\DeviceModel;
use App\Models\DeviceStatus;
use Illuminate\Http\Request;

class DeviceController extends BaseApiController
{
    public const DEVICE_STATUS_ENABLED = 'enabled';

    public function __construct()
    {
        $this->model = new Device();
    }

    public function index(Request $request)
    {
        $params = $request->only(['device_model_id', 'device_status_id', 'vehicle_id', 'serial_number']);

        $filteredByPhoneNumber = $request->has('phone_number');
        $phoneNumber = $request->input('phone_number');

        $status = $request->input('status');

        $filteredByStatus = $request->has('status_code');
        $status_code = $request->input('status_code', null);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $vehicle_id = $request->input('vehicle_id', null);

        $data = $this->filtrar($params)
            ->where('tenant_id', $this->currentTenantId())
            ->orderBy('updated_at', 'desc')
            ->when($filteredByPhoneNumber, function($query) use ($phoneNumber) {
                return $query->whereHas('simCard', function($querySimCard) use ($phoneNumber) {
                    return $querySimCard->where('phone_number', 'ILIKE', $phoneNumber);
                });
            })
            ->when($status, function($query) use ($status) {
                return $query->whereIn('device_status_id', $status);
            })
            ->when($filteredByStatus, function($query) use ($status_code) {
                return $query->whereHas('status', function($queryStatus) use ($status_code) {
                    return $queryStatus->where('name', $status_code);
                });
            })
            ->with($include);

        if($vehicle_id) {
            $data = $data->whereHas('vehicle', function ($q) use ($vehicle_id) {
                $q->where('id', $vehicle_id);
            });
        }

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        $data->transform(function ($item, $key) use ($request, $perPage, $paginate) {
            $item->position = $paginate ? (intval($request->input('page', 1)) - 1) * $perPage + ($key + 1) : $key + 1;
            //dd($item->qr);
            // $item->qr = QrCode::format('svg')->generate($item->id);
            return $item;
        });

        return GenericResource::collection($data);
    }

    public function show(Request $request, Device $device)
    {
        if($device->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $include = $request->input('include', []);
        $device->load($include);
        return GenericResource::make($device);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'device_model_id' => 'required',
            'password' => 'required',
            'vehicle_id' => 'sometimes',
            'serial_number' => 'required',
            'code' => 'sometimes',
            'device_status_id' => 'sometimes',
            'detail' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $device_model = DeviceModel::where('id', $request->device_model_id)->first();
        $device = new Device();
        $device->fill($params);
        $device->tenant_id = $this->currentTenantId();
        $device->code = $device_model->name;
        if(!$request->input('device_status_id')) {
            $status = DeviceStatus::query()->where('name', '=',self::DEVICE_STATUS_ENABLED)->first();
            $device->device_status_id = $status->id;
        }

        $device->save();

        (new AuditHelper())->storeAuditRecord($device, 'Device created');

        if($request->has('simcard_id')){
            $new_simcard = SimCard::where('id', $request->input('simcard_id'))
                                    ->where('device_id', null)
                                    ->whereHas('status', function ($query) {
                                        return $query->where('name', 'enabled');
                                    })
                                    ->first();
            if ($new_simcard) {
                $active_sim_status = SimCardStatus::where('name', 'installed')->first();
                $new_simcard->sim_card_status_id = $active_sim_status->id;
                $new_simcard->device_id = $device->id;
                $new_simcard->save();
                (new AuditHelper())->storeAuditRecord($new_simcard, 'Sim card created');
            }
        }

        $device->load($include);

        return GenericResource::make($device);
    }

    public function update(Request $request, Device $device)
    {
        if($device->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $deviceOld = $device;

        $include = $request->input('include', []);

        $device->fill($request->all());
        $device->save();

        (new AuditHelper())->storeAuditRecord($device, 'Device updated', $deviceOld);

        if($request->has('simcard_id')){
            $enable_sim_status = SimCardStatus::where('name', 'enabled')->first();
            $new_simcard = SimCard::where('id', $request->input('simcard_id'))
                                    ->where('device_id', null)
                                    ->where('sim_card_status_id', $enable_sim_status->id)
                                    ->first();

            if ($new_simcard) {
                $old_simcard = SimCard::where('device_id', $device->id)
                                        ->first();

                if ($old_simcard) {
                    $oldSim = $old_simcard;
                    $old_simcard->sim_card_status_id = $enable_sim_status->id;
                    $old_simcard->device_id = null;
                    $old_simcard->save();

                    (new AuditHelper())->storeAuditRecord($old_simcard, 'Sim card updated', $oldSim);
                }

                $oldNewSim = $new_simcard;

                $active_sim_status = SimCardStatus::where('name', 'installed')->first();
                $new_simcard->sim_card_status_id = $active_sim_status->id;
                $new_simcard->device_id = $device->id;
                $new_simcard->save();

                (new AuditHelper())->storeAuditRecord($new_simcard, 'Sim card updated', $oldNewSim);

            }
        }

        $device->load($include);
        return GenericResource::make($device);
    }

    public function delete(Request $request, Device $device)
    {
        if($device->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }
        $device->delete();

        (new AuditHelper())->storeAuditRecord($device, 'Device deleted');
        return GenericResource::make($device);
    }
}
