<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TenantController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new Tenant();
    }

    public function index(Request $request)
    {
        $paginate = $request->has('page');
        $perPage = $request->input('per_page', 30);

        $include = $request->input('include', []);
        $filters = $request->only(['code', 'name', 'country_id', 'location_id']);

        $data = $this->filtrar($filters)
            ->orderBy('name')
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, Tenant $tenant)
    {
        $include = $request->input('include', []);
        $tenant->load($include);

        return GenericResource::make($tenant);
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'dni' => 'sometimes',
            'code' => 'sometimes',
            'name' => 'required',
            'address' => 'sometimes',
            'contact' => 'sometimes',
            'country_id' => 'sometimes',
            'location_id' => 'sometimes',
            'uuid' => 'sometimes',
        ]);

        $tenant = new Tenant();
        $tenant->fill($params);
        $tenant->save();

        return GenericResource::make($tenant);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $auditOld = $tenant;

        $tenant->fill($request->all());
        $tenant->save();

        (new AuditHelper())->storeAuditRecord($tenant, 'Tenant updated', $auditOld);

        return GenericResource::make($tenant);
    }

    public function delete(Tenant $tenant)
    {
        $tenant->delete();
        return response()->json('deleted tenant resource');
    }

    public function uploadProfilePhoto(Request $request, Tenant $tenant) {
        //dd($request->file('photo'));
        $request->validate([
            'photo' => 'required|file|image|max:1024|dimensions:max_width=1080,max_height=1080',
        ]);

        $ext = $request->file('photo')->getClientOriginalExtension();

        $filePath = "organization/$tenant->id/profile.$ext";

        $path = $request->file('photo')->storePubliclyAs(
            'public', $filePath
        );

        $url = Storage::url($filePath);

        $tenant->logo_url = $url;
        $tenant->save();

        return GenericResource::make($tenant);
    }

}
