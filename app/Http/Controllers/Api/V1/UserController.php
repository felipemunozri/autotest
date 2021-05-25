<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Helpers\AuditHelper;
use App\Domain\Helpers\UserHelper;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenericResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\TenantUserStatus;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseApiController
{
    public function __construct()
    {
        $this->model = new User();
    }

    public function index(Request $request)
    {
        $tenantId = $this->currentTenantId();

        $paginate = $request->has('page');
        $perPage = $request->input('per_page', 30);

        $include = $request->input('include', []);
        $filters = $request->only(['dni', 'name', 'second_name', 'lastname', 'second_lastname', 'username', 'email', 'disabled_web', 'disabled_mobile', 'user_status_id']);

        $data = $this->filtrar($filters)->whereHas('tenants', function($query) use ($tenantId){
            return $query->where('tenants.id', $tenantId);
        })
            ->orderBy('lastname')
            ->orderBy('second_lastname')
            ->orderBy('name')
            ->orderBy('second_name')
            ->with($include);

        $data = $paginate ? $data->paginate($perPage) : $data->get();

        return GenericResource::collection($data);
    }

    public function show(Request $request, User $user)
    {
        $include = $request->input('include', []);
        $user->load($include);

        return GenericResource::make($user);
    }

    public function search(Request $request)
    {
        $filters = $request->only(['dni', 'name', 'second_name', 'lastname', 'second_lastname', 'username', 'email', 'disabled_web', 'disabled_mobile', 'user_status_id']);

        $data = $this->filtrar($filters)->get();

        return GenericResource::collection($data);
    }

    public function store(Request $request)
    {
        $tenantId = $this->currentTenantId();
        $params = $this->getInputsUser($request);

        $profiles = $request->input('roles', []);

        $user = (new UserHelper())->storeUserWithProfile($tenantId, $profiles, $params);
        $tenant_user_status = TenantUserStatus::where('code', 'active')->first();
        $userStatusId = $tenant_user_status->id;
        (new UserHelper())->storeTenantUser($tenantId, $user->id, $request->input('disabled_web', false), $request->input('disabled_mobile', false), $userStatusId);

        (new AuditHelper())->storeAuditRecord($user, 'User created');

        return GenericResource::make($user);
    }

    public function update(Request $request, User $user)
    {
        $roles = $request->input('roles', []);

        $auditOld = $user;

        $user->fill($request->except(['password']));
        $password = $request->input('password');
        if ($password) {
            $user->password = Hash::make($password);
        }

        (new UserHelper())->saveUserProfiles($this->currentTenantId(), $user, $roles);
        $user->save();

        (new AuditHelper())->storeAuditRecord($user, 'User updated', $auditOld);

        return GenericResource::make($user);
    }

    public function createPersonalAccessToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 200);
        }
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token
        ], 200);
    }

    public function currentTenant() {
        return (new UserHelper())->myTenant();
    }

    public function uploadProfilePhoto(Request $request, User $user) {
        //dd($request->file('photo'));
        $request->validate([
            'photo' => 'required|file|image|max:1024|dimensions:max_width=1080,max_height=1080',
        ]);

        $path = $request->file('photo')->storePubliclyAs(
            'public', $user->id.'/profile.jpg'
        );

        $url = Storage::url($user->id.'/profile.jpg');

        $user->profile_photo = $url;
        $user->save();

        return GenericResource::make($user);
    }
}
