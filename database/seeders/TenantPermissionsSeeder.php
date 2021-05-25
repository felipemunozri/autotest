<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Acl\Role;
use App\Models\Acl\Permission;

class TenantPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();
        $data = [
            ['editar organizaciones', 'edit-tenants'],
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

        $roles = Role::whereIn('name', [
            'admin',
        ])->get();
        
        foreach ($data as $value) {
            $permission = Permission::create($value);
            $permission->syncRoles($roles);
        }


        
    }
}
