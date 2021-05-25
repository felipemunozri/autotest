<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Acl\Role;
use App\Models\Acl\Permission;

class TechnicalProfileSeeder extends Seeder
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
            ['activar vehículos', 'activate-vehicles'],
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

        $technical = Role::create([
            'id' => Uuid::uuid4(),
            'name' => 'technical',
            'description' => 'técnico',
            'guard_name' => 'web',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
            'tenant_id' => $tenant->id
        ]);

        $permissions = Permission::whereIn('name', [
            'activate-vehicles',
        ])->get();
        $technical->syncPermissions($permissions);
    }
}
