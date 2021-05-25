<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\EventStatus;
use Illuminate\Http\Request;

class EventStatusController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new EventStatus();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name', 'code']);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, EventStatus $eventStatus)
    {
        $include = $request->input('include', []);
        $eventStatus->load($include);
        return GenericResource::make($eventStatus);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            //'code' => 'sometimes',
            'description' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $item = new EventStatus();
        $item->fill($params);
        $item->save();

        $item->load($include);

        return GenericResource::make($item);
    }

    public function update(Request $request, EventStatus $eventStatus)
    {
        $include = $request->input('include', []);

        $eventStatus->fill($request->all());
        $eventStatus->save();

        $eventStatus->load($include);
        return GenericResource::make($eventStatus);
    }

    public function delete(Request $request, EventStatus $eventStatus)
    {
        $eventStatus->delete();
        return GenericResource::make($eventStatus);
    }
}
