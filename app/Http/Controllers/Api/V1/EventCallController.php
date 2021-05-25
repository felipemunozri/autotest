<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\EventCall;
use Illuminate\Http\Request;

class EventCallController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new EventCall();
    }

    public function index(Request $request)
    {
        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            //->where('tenant_id', $this->currentTenantId())
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, EventCall $eventCall)
    {
        $include = $request->input('include', []);
        $eventCall->load($include);
        return GenericResource::make($eventCall);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'beneficiary_id' => 'required',
            'event_id' => 'required',
            'call_start' => 'sometimes',
            'call_end' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $eventCall = new EventCall();
        $eventCall->fill($params);
        $eventCall->user_id = $this->myUser()->id;
        $eventCall->save();
        $eventCall->load($include);

        return GenericResource::make($eventCall);
    }

    public function update(Request $request, EventCall $eventCall)
    {
        $include = $request->input('include', []);

        $eventCall->fill($request->all());
        $eventCall->save();

        $eventCall->load($include);
        return GenericResource::make($eventCall);
    }

    public function finishCall(Request $request, EventCall $eventCall)
    {   
        $include = $request->input('include', []);
        $params = $this->validate($request, [
            'call_end' => 'required',
        ]);
        
        $eventCall->call_end = $request->input('call_end');
        $eventCall->save();
        
        $eventCall->load($include);
        return GenericResource::make($eventCall);
    }

    public function delete(Request $request, EventCall $eventCall)
    {
        $eventCall->delete();
        return GenericResource::make($eventCall);
    }
}