<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\Event;
use App\Models\Beneficiary;
use App\Models\EventCodeStatus;
use App\Models\EventCode;
use App\Models\EventStatus;
use App\Models\EventCall;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Event();
    }

    public function index(Request $request)
    {
        $params = $request->only(['vehicle_id', 'beneficiary_id', 'event_status_id', 'event_date', 'event_result_id']);
        $from = $request->input('from');
        $to = $request->input('to');

        $dni = $request->input('dni');
        $plateNumber = $request->input('plate_number');
        $name = $request->input('name');
        $status = $request->input('status');

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->where('tenant_id', $this->currentTenantId())
            ->when($from, function($query) use ($from) {
                return $query->where('event_date', '>=' , $from);
            })
            ->when($to, function($query) use ($to) {
                return $query->where('event_date', '<=' , $to);
            })
            ->when($status, function($query) use ($status) {
                return $query->whereIn('event_status_id', $status);
            })
            ->where(function($query) use ($plateNumber, $dni) {
                $query->when($plateNumber, function($query) use ($plateNumber) {
                    return $query->whereHas('vehicle', function($queryVehicle) use ($plateNumber) {
                        return $queryVehicle->where('plate_number', $plateNumber);
                    });
                })->orWhere(function($query) use ($plateNumber, $dni) {
                    $query->when($dni, function($query) use ($dni) {
                        return $query->whereHas('beneficiary', function($queryBeneficiaries) use ($dni) {
                            return $queryBeneficiaries->where('dni', $dni);
                        });
                    });
                });
            })
            /*->when($dni, function($query) use ($dni) {
                return $query->whereHas('beneficiary', function($queryBeneficiaries) use ($dni) {
                    return $queryBeneficiaries->where('dni', $dni);
                });
            })
            ->when($plateNumber, function($query) use ($plateNumber) {
                return $query->whereHas('vehicle', function($queryVehicle) use ($plateNumber) {
                    return $queryVehicle->where('plate_number', $plateNumber);
                });
            })*/
//            ->when($name, function($query) use ($name) {
//                return $query->whereHas('vehicle', function($queryVehicle) use ($name) {
//                    return $queryVehicle->where('plate_number', $name);
//                });
//            })
            ->orderBy('event_date', 'desc')
            ->orderBy('event_time', 'desc')
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Event $event)
    {
        if($event->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $include = $request->input('include', []);
        $event->load($include);
        return GenericResource::make($event);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'vehicle_id' => 'required',
            'event_date' => 'sometimes',
            'event_time' => 'sometimes',
            'received_by' => 'sometimes',
            'detail' => 'sometimes',
            'observation' => 'sometimes',
            'event_status_id' => 'sometimes',
            'origin' => 'sometimes',
            'event_result_id' => 'sometimes',
            'event_start' => 'sometimes',
            'event_end' => 'sometimes',
            'call_start' => 'sometimes'
        ]);

        $include = $request->input('include', []);
        $event_status = EventStatus::where('code', 'created')->first();

        $event = new Event();
        $event->fill($params);
        $event->received_by = $this->myUser()->id;
        $event->event_date = $params['event_date'] ?? Carbon::now();
        $event->event_time = $params['event_time'] ?? Carbon::now()->toTimeString();
        $event->tenant_id = $this->currentTenantId();
        $event->event_status_id = $event_status->id;
        $event->save();
        
        if ($request->has('call_start')) {
            $event->eventCalls()->create([
                'beneficiary_id' => $request->input('beneficiary_id'),
                'user_id' => $this->myUser()->id,
                'call_start' => $request->input('call_start'),
                'call_end' => null
            ]);
        }

        (new AuditHelper())->storeAuditRecord($event, 'created');

        $event->load($include);

        return GenericResource::make($event);
    }

    public function update(Request $request, Event $event)
    {
        if($event->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $auditOld = $event;

        $include = $request->input('include', []);

        $event->fill($request->all());
        $event->save();

        (new AuditHelper())->storeAuditRecord($event, 'updated', $auditOld);

        $event->load($include);
        return GenericResource::make($event);
    }

    public function delete(Request $request, Event $event)
    {
        if($event->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }
        $auditOld = $event;

        $event->delete();
        (new AuditHelper())->storeAuditRecord($event, 'deleted', $auditOld);
        return GenericResource::make($event);
    }

    public function findInProgress(Request $request)
    {
        $params = $request->only(['vehicle_id', 'event_status_id']);

        $include = $request->input('include', []);

        $event = $this->filtrar($params)
            ->where('tenant_id', $this->currentTenantId())
            ->where('vehicle_id', $request->vehicle_id)
            ->whereHas('status', function ($q) {
                $q->where('code', '=', 'created')
                    ->orWhere('code', '=', 'in-progress');
            })
            ->with($include)
            ->first();

        (new AuditHelper())->storeAuditRecord($event, 'event found');

        return GenericResource::make($event);
    }

    public function validateCode(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'event_id' => 'required',
            'code' => 'required|string',
        ]);

        $beneficiary_id = $request->input('beneficiary_id');
        $event_id = $request->input('event_id');
        $code = strtoupper($request->input('code'));

        $beneficiary = Beneficiary::where('id',$beneficiary_id)->firstOrFail();
        $event = Event::where('id', $event_id)->firstOrFail();

        //Set old object for audit record
        $auditOld = $event;

        $status = EventCodeStatus::where('code', 'pending')->first();

        $previous_code = EventCode::where('event_id', $event->id)
                                ->where('beneficiary_id', $beneficiary->id)
                                ->where('status_id', $status->id)
                                ->where('code', $code)->firstOrFail();

        $expirate_date = Carbon::create($previous_code->duration);

        $now_date = Carbon::now();

        if($now_date->greaterThan($expirate_date)) {
            $expired_status = EventCodeStatus::where('code', 'expired')->first();
            $previous_code->status_id = $expired_status->id;
            $previous_code->save();
            return response()->json([
                'is_valid' => false,
                'message' => 'code is invalid'
            ], 200);
        }

        $validated_status = EventCodeStatus::where('code', 'validated')->first();
        $previous_code->status_id = $validated_status->id;
        $previous_code->save();

        $event_status = EventStatus::where('code','validated')->first();
        $event->event_status_id = $event_status->id;
        $event->save();

        (new AuditHelper())->storeAuditRecord($event, 'validated by code', $auditOld);

        return response()->json([
            'is_valid' => true,
            'message' => 'code is valid'
        ], 200);
    }

    public function leaveEvent(Request $request)
    {
        $params = (object)$this->validate($request, [
            'id' => 'required',
        ]);

        $event = Event::whereHas('status', function ($q) {
                            $q->where('code', '=', 'validated')
                                ->orWhere('code', '=', 'in-progress');
                        })
                        ->where('id', $params->id)
                        ->first();

        //Set old object for audit record
        $auditOld = $event;

        if(!$event) {
            return response()->json([
                'message' => 'event not available to leave in progress',
                'finished' => false
            ], 200);
        }

        $event_status = EventStatus::where('code', 'in-progress')->first();
        $event->event_status_id = $event_status->id;
        $event->save();

        (new AuditHelper())->storeAuditRecord($event, 'event in progress', $auditOld);

        return response()->json([
            'message' => 'event state successfully changed to in progress',
            'finished' => true
        ], 200);
    }

    public function finishEvent(Request $request)
    {
        $params = (object)$this->validate($request, [
            'id' => 'required',
        ]);

        $event = Event::whereHas('status', function ($q) {
                            $q->where('code', '!=', 'finished');
                        })
                        ->where('id', $params->id)
                        ->first();

        //Set old object for audit record
        $auditOld = $event;

        if(!$event) {
            return response()->json([
                'message' => 'event not available to finish',
                'finished' => false
            ], 200);
        }

        $event_status = EventStatus::where('code', 'finished')->first();
        $event->event_status_id = $event_status->id;
        $event->event_end = Carbon::now();
        $event->save();

        (new AuditHelper())->storeAuditRecord($event, 'finished', $auditOld);

        return response()->json([
            'message' => 'event successfully finished',
            'finished' => true
        ], 200);
    }
}
