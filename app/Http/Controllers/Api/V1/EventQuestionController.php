<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\EventQuestion;
use Illuminate\Http\Request;

class EventQuestionController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new EventQuestion();
    }

    public function index(Request $request)
    {
        $params = $request->only(['name', 'code']);

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

    public function show(Request $request, EventQuestion $eventQuestion)
    {
        $include = $request->input('include', []);
        $eventQuestion->load($include);
        return GenericResource::make($eventQuestion);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'name' => 'required',
            'code' => 'sometimes',
            'description' => 'sometimes',
            'order' => 'sometimes',
            'question_type_id' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $item = new EventQuestion();
        $item->fill($params);
        $item->save();

        $item->load($include);

        return GenericResource::make($item);
    }

    public function update(Request $request, EventQuestion $eventQuestion)
    {
        $include = $request->input('include', []);

        $eventQuestion->fill($request->all());
        $eventQuestion->save();

        $eventQuestion->load($include);
        return GenericResource::make($eventQuestion);
    }

    public function delete(Request $request, EventQuestion $eventQuestion)
    {
        $eventQuestion->delete();
        return GenericResource::make($eventQuestion);
    }
}
