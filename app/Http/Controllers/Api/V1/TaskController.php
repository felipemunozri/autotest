<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Task();
    }

    public function index(Request $request)
    {
        $params = $request->only(['action_id', 'executed_by', 'event_id']);

        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->where('tenant_id', $this->currentTenantId())
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Task $task)
    {
        if($task->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $include = $request->input('include', []);
        $task->load($include);
        return GenericResource::make($task);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'event_id' => 'required',
            'executed_by' => 'required',
            'action_id' => 'sometimes',
            'observation' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $task = new Task();
        $task->fill($params);
        $task->tenant_id = $this->currentTenantId();
        $task->save();

        (new AuditHelper())->storeAuditRecord($task, 'Task created');

        $task->load($include);

        return GenericResource::make($task);
    }

    public function update(Request $request, Task $task)
    {
        if($task->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $auditOld = $task;

        $include = $request->input('include', []);

        $task->fill($request->all());
        $task->save();

        (new AuditHelper())->storeAuditRecord($task, 'Task updated', $auditOld);

        $task->load($include);
        return GenericResource::make($task);
    }

    public function delete(Request $request, Task $task)
    {
        if($task->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }
        $task->delete();

        (new AuditHelper())->storeAuditRecord($task, 'Task deleted');

        return GenericResource::make($task);
    }
}
