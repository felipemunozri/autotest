<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\EventQuestionApplied;
use Illuminate\Http\Request;

class EventQuestionAppliedController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new EventQuestionApplied();
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

    public function show(Request $request, EventQuestionApplied $eventQuestionApplied)
    {
        $include = $request->input('include', []);
        $eventQuestionApplied->load($include);
        return GenericResource::make($eventQuestionApplied);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'event_id' => 'required',
            'event_question_id' => 'required',
            'alternative_id' => 'sometimes',
            'number_answer' => 'sometimes',
            'answer' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $item = new EventQuestionApplied();
        $item->fill($params);
        $item->save();

        $item->load($include);

        return GenericResource::make($item);
    }

    public function storeList(Request $request)
    {
        $list = $request->input('list');

        foreach ($list as $item) {
            $applied = new EventQuestionApplied([
                'event_id' => $item['event_id'],
                'event_question_id' => $item['event_question_id'],
                'alternative_id' => $item['alternative_id'],
                'number_answer' => $item['number_answer'],
                'answer' => $item['answer']
            ]);
            $applied->save();
        }

        return response()->json(['message' => 'answer registered successfully'], 201);
    }

    public function updateOrStore(Request $request)
    {
        $params = $this->validate($request, [
            'event_id' => 'required',
            'event_question_id' => 'required',
            'alternative_id' => 'required',
        ]);

        $event_id = $request->input('event_id');
        $event_question_id = $request->input('event_question_id');
        $alternative_id = $request->input('alternative_id');

        EventQuestionApplied::updateOrCreate(
            [
                'event_id' => $event_id,
                'event_question_id' => $event_question_id,
            ],
            [
                'alternative_id' => $alternative_id,
                // 'number_answer' => $item['number_answer'],
                // 'answer' => $item['answer']
            ]
            );

        return response()->json(['message' => 'answer registered successfully'], 201);
    }

    public function update(Request $request, EventQuestionApplied $eventQuestionApplied)
    {
        $include = $request->input('include', []);

        $eventQuestionApplied->fill($request->all());
        $eventQuestionApplied->save();

        $eventQuestionApplied->load($include);
        return GenericResource::make($eventQuestionApplied);
    }

    public function delete(Request $request, EventQuestionApplied $eventQuestionApplied)
    {
        $eventQuestionApplied->delete();
        return GenericResource::make($eventQuestionApplied);
    }
}
