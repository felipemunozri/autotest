<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Acl\Permission;
use App\Models\Acl\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('tenant_user')->truncate();
        DB::table('user_preferences')->truncate();
        $timestamp = Carbon::now();
        
        $data = [
            ['Administrador','AutoSeguro', '20645279-k','admin@autoseguro.com','123456'],
        ];
        $data = array_map(function($element) use ($timestamp) {
            return [
               'id' => Uuid::uuid4(),
               'name' => $element[0],
               'lastname' => $element[1],
               'dni' => $element[2],
               'email' => $element[3],
               'password' => Hash::make($element[4]),
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        DB::table('users')->insert($data);

        $tenant = DB::table('tenants')->where('code', 'pruebas-autoseguro')->first();
        $tenant_user_status = DB::table('tenant_user_status')->where('code', 'active')->first();

        $data2 = array_map(function($element) use ($timestamp, $tenant, $tenant_user_status) {
            return [
               'user_id' => $element['id'],
               'tenant_id' => $tenant->id,
               'user_status_id' => $tenant_user_status->id,
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        DB::table('tenant_user')->insert($data2);

        $data3 = array_map(function($element) use ($timestamp, $tenant) {
            return [
               'user_id' => $element['id'],
               'current_tenant_id' => $tenant->id,
               'created_at' => $timestamp,
               'updated_at' => $timestamp
            ];
        }, $data);

        DB::table('user_preferences')->insert($data3);

        $user = User::where('id', $data[0]['id'])->first();

        $role = Role::where('name', 'admin')->first();

        $user->assignRole($role);
    }
}
