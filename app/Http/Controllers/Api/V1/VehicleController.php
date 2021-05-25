<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\GenericResource;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VehicleController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Vehicle();
    }

    public function index(Request $request)
    {
        $params = $request->only(['plate_number' ,'card_brand_id', 'vehicle_type_id', 'country_id', 'model', 'fuel_type_id']);
        $dni = $request->input('dni');
        $include = $request->input('include', []);

        $paginate = $request->has('page');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 30);

        $data = $this->filtrar($params)
            ->where('tenant_id', $this->currentTenantId())
            ->when($dni, function(Builder $queryDni, $dni) {
                return $queryDni
                    ->whereHas('beneficiaries', function($queryBeneficiaries) use ($dni) {
                        return $queryBeneficiaries->where('dni', $dni);
                    });
            })
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Vehicle $vehicle)
    {
        if($vehicle->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }
        $include = $request->input('include', []);
        $vehicle->load($include);
        return GenericResource::make($vehicle);

    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'country_id' => 'required',
            'vehicle_type_id' => 'sometimes',
            'card_brand_id' => 'required',
            'plate_number' => 'required',
            'model' => 'sometimes',
            'model_id' => 'sometimes',
            'year' => 'sometimes',
            'color_code' => 'sometimes',
            'color' => 'sometimes',
            'engine_capacity' => 'sometimes',
            'drive' => 'sometimes',
            'chassis_number' => 'sometimes',
            'fuel_type_id' => 'sometimes',
            'owner_name' => 'sometimes',
            'owner_dni' => 'sometimes',
            'owner_adquisition_date' => 'sometimes',
            'key' => 'sometimes',
        ]);

        $include = $request->input('include', []);

        $vehicle = new Vehicle();
        $vehicle->fill($params);
        $vehicle->tenant_id = $this->currentTenantId();
        $vehicle->save();

        (new AuditHelper())->storeAuditRecord($vehicle, 'Vehicle created');

        $vehicle->load($include);

        return GenericResource::make($vehicle);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if($vehicle->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }

        $auditOld = $vehicle;

        $include = $request->input('include', []);

        $vehicle->fill($request->all());
        $vehicle->save();

        (new AuditHelper())->storeAuditRecord($vehicle, 'Vehicle updated', $auditOld);

        $vehicle->load($include);
        return GenericResource::make($vehicle);
    }

    public function delete(Request $request, Vehicle $vehicle)
    {
        if($vehicle->tenant_id != $this->currentTenantId()) {
            return response()->json([
                'data' => 'No autorizado'
            ], 401);
        }
        $vehicle->delete();
        (new AuditHelper())->storeAuditRecord($vehicle, 'Vehicle deleted');

        return GenericResource::make($vehicle);
    }
}
