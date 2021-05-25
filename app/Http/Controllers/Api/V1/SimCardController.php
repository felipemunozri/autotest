<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\SimCard;
use App\Models\SimCardStatus;
use Illuminate\Http\Request;

class SimCardController extends BaseApiController
{
    public const SIMCARD_STATUS_ENABLED = 'enabled';

    public function __construct()
    {
        $this->model = new SimCard();
    }

    public function index(Request $request)
    {
        $params = $request->only(['phone_number', 'carrier_id', 'country_id', 'sim_card_status_id']);

        $include = $request->input('include', []);

        $filteredbyStatus = $request->has('status_code');
        $status_code = $request->input('status_code', null);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->where('tenant_id', $this->currentTenantId())
            ->orderBy('updated_at', 'desc')
            ->when($filteredbyStatus, function($query) use ($status_code) {
                return $query->whereHas('status', function($queryStatus) use ($status_code) {
                    return $queryStatus->where('name', $status_code);
                });
            })
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, SimCard $simCard)
    {
        if($simCard->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $include = $request->input('include', []);
        $simCard->load($include);
        return GenericResource::make($simCard);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'country_id' => 'sometimes',
            'carrier_id' => 'required',
            'phone_number' => 'sometimes',
            'sim_card_status_id' => 'sometimes',
            'device_id' => 'sometimes',
            'balance' => 'sometimes',
            'sms' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $simCard = new SimCard();
        $simCard->fill($params);
        $simCard->tenant_id = $this->currentTenantId();
        if(!$request->input('sim_card_status_id')) {
            $status = SimCardStatus::query()->where('name', '=',self::SIMCARD_STATUS_ENABLED)->first();
            $simCard->sim_card_status_id = $status->id;
        }
        $simCard->save();

        (new AuditHelper())->storeAuditRecord($simCard, 'Sim card created');

        $simCard->load($include);

        return GenericResource::make($simCard);
    }

    public function update(Request $request, SimCard $simCard)
    {
        if($simCard->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $auditOld = $simCard;

        $include = $request->input('include', []);

        $simCard->fill($request->all());
        $simCard->save();

        (new AuditHelper())->storeAuditRecord($simCard, 'Sim card created', $auditOld);

        $simCard->load($include);
        return GenericResource::make($simCard);
    }

    public function delete(Request $request, SimCard $simCard)
    {
        if($simCard->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }
        $simCard->delete();

        (new AuditHelper())->storeAuditRecord($simCard, 'Sim card deleted');

        return GenericResource::make($simCard);
    }
}
