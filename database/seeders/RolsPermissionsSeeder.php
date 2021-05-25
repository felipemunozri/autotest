<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Acl\Role;
use App\Models\Acl\Permission;

class RolsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        Role::truncate();
        
        DB::table('acl.role_has_permissions')->truncate();
        $timestamp = Carbon::now();
        $data = [
            ['listar vehículos', 'list-vehicles'],
            ['crear vehículos', 'create-vehicles'],
            ['ver Vehículos', 'show-vehicles'],
            ['editar vehículos', 'edit-vehicles'],
            ['eliminar vehículos', 'delete-vehicles'],
            ['asignar beneficiarios vehículos', 'assign-beneficiaries-vehicles'],
            ['asignar dispositivos vehículos', 'assign-devices-vehicles'],
            ['cambiar dueños vehículos', 'change-owners-vehicles'],
            ['listar beneficiarios', 'list-beneficiaries'],
            ['crear beneficiarios', 'create-beneficiaries'],
            ['ver beneficiarios', 'show-beneficiaries'],
            ['editar beneficiarios', 'edit-beneficiaries'],
            ['eliminar beneficiarios', 'delete-beneficiaries'],
            ['listar dispositivos', 'list-devices'],
            ['crear dispositivos', 'create-devices'],
            ['ver dispositivos', 'show-devices'],
            ['editar dispositivos', 'edit-devices'],
            ['eliminar dispositivos', 'delete-devices'],
            ['listar eventos', 'list-events'],
            ['crear eventos', 'create-events'],
            ['ver eventos', 'show-events'],
            ['editar eventos', 'edit-events'],
            ['eliminar eventos', 'delete-events'],
            ['listar usuarios', 'list-users'],
            ['crear usuarios', 'create-users'],
            ['ver usuarios', 'show-users'],
            ['editar usuarios', 'edit-users'],
            ['eliminar usuarios', 'delete-users'],
        ];

        $data = array_map(function($element) use ($timestamp) {
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[1],
               'description' => $element[0],
               'guard_name' => 'web',
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        Permission::insert($data);

        $tenant = DB::table('tenants')->where('code', 'pruebas-autoseguro')->first();

        /* $super_admin = Role::create([
            'id' => Uuid::uuid4(),
            'name' => 'super-admin',
            'description' => 'super administrador',
            'guard_name' => 'web',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'tenant_id' => $tenant->id
        ]);
        $permissions = Permission::all();
        $super_admin->syncPermissions($permissions); */

        $admin = Role::create([
            'id' => Uuid::uuid4(),
            'name' => 'admin',
            'description' => 'administrador',
            'guard_name' => 'web',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'tenant_id' => $tenant->id
        ]);

        $permissions = Permission::all();
        $admin->syncPermissions($permissions);

        $operator = Role::create([
            'id' => Uuid::uuid4(),
            'name' => 'operator',
            'description' => 'operador',
            'guard_name' => 'web',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'tenant_id' => $tenant->id
        ]);

        $permissions = Permission::whereIn('name', [
            'create-vehicles',
            'show-vehicles',
            'edit-vehicles',
            'assign-beneficiaries-vehicles',
            'assign-devices-vehicles',
            'create-beneficiaries',
            'show-beneficiaries',
            'edit-beneficiaries',
            'create-devices',
            'show-devices',
            'edit-devices',
            'create-events',
            'show-events',
            'edit-events',
        ])->get();
        $operator->syncPermissions($permissions);
    }
}
