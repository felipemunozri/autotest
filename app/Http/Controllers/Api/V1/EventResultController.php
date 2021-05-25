<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GenericResource;
use App\Models\EventResult;

class EventResultController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new eventResult();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name']);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, EventResult $eventResult)
    {
        $include = $request->input('include', []);
        $eventResult->load($include);
        return GenericResource::make($eventResult);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'description' => 'sometimes',
            'order' => 'required',
            'visible' => 'required',
        ]);

        $include = $request->input('include', []);

        $item = new EventResult();
        $item->fill($params);
        $item->save();

        $item->load($include);

        return GenericResource::make($item);
    }

    public function update(Request $request, EventResult $eventResult)
    {
        $include = $request->input('include', []);

        $eventResult->fill($request->all());
        $eventResult->save();

        $eventResult->load($include);
        return GenericResource::make($eventResult);
    }

    public function delete(Request $request, EventResult $eventResult)
    {
        $eventResult->delete();
        return GenericResource::make($eventResult);
    }
}
