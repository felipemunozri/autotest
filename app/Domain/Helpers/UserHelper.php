<?php


namespace App\Domain\Helpers;


use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Collection\Collection;
use App\Models\Acl\Role;
use App\Models\Acl\Permission;

class UserHelper
{
    public static function myTenant()
    {
        return UserHelper::currentTenant(auth()->user()->id);
    }

    public function currentTenantId($userId)
    {
        $userPreferences = DB::table('public.user_preferences')->where('user_id', $userId)->first();
        return $userPreferences->current_tenant_id;
    }

    public static function currentTenant($userId)
    {
        $userPreferences = DB::table('public.user_preferences')->where('user_id', $userId)->first();
        return Tenant::query()->find($userPreferences->current_tenant_id);
    }

    public function profilesByUser($userId)
    {
        $tenantId = $this->currentTenantId($userId);

        $profiles = Role::whereHas('users', function ($query) use ($userId, $tenantId) {
            $query->where('id', $userId);
            $query->where('model_has_roles.tenant_id', $tenantId);
        })->get();

        if (!$profiles) {
            return new Collection([]);
        }

        return $profiles;
    }

    public function profileNamesByUser($userId)
    {
        $tenantId = $this->currentTenantId($userId);

        $profiles = Role::whereHas('users', function ($query) use ($userId, $tenantId) {
            $query->where('id', $userId);
            $query->where('model_has_roles.tenant_id', $tenantId);
        })->pluck('name');

        if (!$profiles) {
            return new Collection([]);
        }

        return $profiles;
    }

    public function permissions($userId)
    {
        $roles = $this->profilesByUser($userId);

        $permissions = DB::table('acl.permissions')
            ->join('acl.role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->whereIn('role_has_permissions.role_id', $roles->pluck('id'))
            ->select('permissions.id', 'permissions.code', 'permissions.name', 'permissions.uuid')
            ->distinct()
            ->orderBy('permissions.name')
            ->get();

        if (!$permissions) {
            return [];
        }

        return $permissions;
    }

    public function getQueryUsersByRoles($tenantId, $roles = [])
    {
        $users = User::query()->whereHas('tenants', function($query) use ($tenantId) {
            return $query->where('id', $tenantId);
        })->whereHas('profiles', function($query) use ($roles, $tenantId) {
            return $query->where('model_has_roles.tenant_id', $tenantId)->whereIn('name', $roles);
        })->orderBy('lastname')
        ->orderBy('name');

        return $users;
    }

    public function storeUserWithProfile($tenantId, array $roles, array $props)
    {
        $user = new User();
        $user->fill($props);

        $password = $props['password'] ?? $props['dni'];
        $user->password = Hash::make($password);

        $user->save();

        $this->saveUserProfiles($tenantId, $user, $roles);

        DB::table('public.user_preferences')->insert(['user_id' => $user->id, 'current_tenant_id' => $tenantId]);

        return $user;
    }

    public function saveUserProfiles($tenantId, $user, array $roles)
    {
        /* foreach ($roles as $role) {
            $roleFound = Role::where('name', $role)->first();
            DB::table('acl.model_has_roles')->updateOrInsert(['tenant_id' => $tenantId, 'model_id' => $user->id, 'role_id' => $roleFound->id]);
            //User::find($user->id)->roles()->save($roleFound, ['tenant_id' => $tenantId]);
        } */

        $roles = Role::whereIn('id', $roles)->get();

        $user->syncRoles($roles);
    }

    public function storeTenantUser($tenantId, $userId, $disabledWeb = false, $disabledMobile = false, $userStatusId)
    {
        $tenantsUser = TenantUser::query()->updateOrCreate(
            ['tenant_id' => $tenantId, 'user_id' => $userId],
            ['disabled_web' => $disabledWeb, 'disabled_mobile' => $disabledMobile, 'user_status_id' => $userStatusId]
        );
        return $tenantsUser;
    }
}
