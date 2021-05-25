<?php

namespace Database\Seeders;

use App\Models\Action;
use App\Models\ActionType;
use App\Models\Beneficiary;
use App\Models\BeneficiaryVehicle;
use App\Models\CarBrand;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Device;
use App\Models\DeviceModel;
use App\Models\DeviceStatus;
use App\Models\FuelType;
use App\Models\SimCard;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\TenantUserStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();
        Country::query()->truncate();
        Carrier::query()->truncate();
        CarBrand::query()->truncate();
        ActionType::query()->truncate();
        Action::query()->truncate();
        Tenant::query()->truncate();
        DeviceModel::query()->truncate();
        DeviceStatus::query()->truncate();
        Device::query()->truncate();
        Beneficiary::query()->truncate();
        Vehicle::query()->truncate();
        BeneficiaryVehicle::query()->truncate();
        FuelType::query()->truncate();
        TenantUser::query()->truncate();
        DB::connection('public')->table('user_preferences')->truncate();

        $country = new Country(['name' => 'Chile', 'code' => 'CL', 'code_number' => '+56']);
        $country->save();

        $carrier = new Carrier(['name' => 'Entel', 'country_id' => $country->id]);
        $carrier->save();
        $carrier2 = new Carrier(['name' => 'Movistar', 'country_id' => $country->id]);
        $carrier2->save();
        $carrier3 = new Carrier(['name' => 'Claro', 'country_id' => $country->id]);
        $carrier3->save();

        $carBrand = new CarBrand(['name' => 'Kia Motors']);
        $carBrand->save();
        $carBrand2 = new CarBrand(['name' => 'Suzuki']);
        $carBrand2->save();
        $carBrand3 = new CarBrand(['name' => 'Toyota']);
        $carBrand3->save();
        $carBrand4 = new CarBrand(['name' => 'Hyundai']);
        $carBrand4->save();
        $carBrand5 = new CarBrand(['name' => 'Chevrolet']);
        $carBrand5->save();
        $carBrand6 = new CarBrand(['name' => 'Mazda']);
        $carBrand6->save();
        $carBrand7 = new CarBrand(['name' => 'Audi']);
        $carBrand7->save();

        $actionType = new ActionType(['name' => 'Envío SMS']);
        $actionType->save();

        $action = new Action(['code' => 'begin', 'name' => 'Iniciar', 'action_type_id' => $actionType->id]);
        $action->save();
        $action2 = new Action(['code' => 'check', 'name' => 'Status', 'action_type_id' => $actionType->id]);
        $action2->save();
        $action3 = new Action(['code' => 'locate', 'name' => 'Localizar', 'action_type_id' => $actionType->id]);
        $action3->save();
        $action4 = new Action(['code' => 'turn_on', 'name' => 'Encender', 'action_type_id' => $actionType->id]);
        $action4->save();
        $action5 = new Action(['code' => 'turn_off', 'name' => 'Apagar', 'action_type_id' => $actionType->id]);
        $action5->save();

        $tenantUserStatus = new TenantUserStatus(['name' => 'Activo']);
        $tenantUserStatus->save();
        $tenantUserStatus2 = new TenantUserStatus(['name' => 'Inactivo']);
        $tenantUserStatus2->save();

        $tenant2 = new Tenant(['name' => 'AutoSeguro Pruebas', 'dni' => '77109214-4', 'country_id' => $country->id]);
        $tenant2->save();

        $tenant = new Tenant(['name' => 'Ilustre Municipalidad de Villarrica', 'dni' => '12345-4', 'country_id' => $country->id]);
        $tenant->save();

        $user = new User([
            'name' => 'Administrador',
            'lastname' => 'AutoSeguro',
            'dni' => '10-8',
            'email' => 'admin@autoseguro.com',
            'password' => Hash::make('123456'),
        ]);
        $user->save();

        $user2 = new User([
            'name' => 'Operador 1',
            'lastname' => '',
            'dni' => '1-9',
            'email' => 'operador1@autoseguro.com',
            'password' => Hash::make('123456'),
        ]);
        $user2->save();

        $user3 = new User([
            'name' => 'Operador 2',
            'lastname' => '',
            'dni' => '2-7',
            'email' => 'operador2@autoseguro.com',
            'password' => Hash::make('qwerty'),
        ]);
        $user3->save();

        $tenantUser = TenantUser::query()->insert(['user_id' => $user->id, 'tenant_id' => $tenant2->id, 'user_status_id' => $tenantUserStatus->id]);
        DB::connection('public')->table('user_preferences')->insert(['user_id' => $user->id, 'current_tenant_id' => $tenant2->id]);

        $tenantUser = TenantUser::query()->insert(['user_id' => $user2->id, 'tenant_id' => $tenant->id, 'user_status_id' => $tenantUserStatus->id]);
        DB::connection('public')->table('user_preferences')->insert(['user_id' => $user2->id, 'current_tenant_id' => $tenant->id]);

        $tenantUser2 = TenantUser::query()->insert(['user_id' => $user3->id, 'tenant_id' => $tenant->id, 'user_status_id' => $tenantUserStatus->id]);
        DB::connection('public')->table('user_preferences')->insert(['user_id' => $user3->id, 'current_tenant_id' => $tenant->id]);

        $fuelType = new FuelType(['name' => 'Gasolina', 'code' => 'gasoline']);
        $fuelType->save();

        $fuelType2 = new FuelType(['name' => 'Diésel', 'code' => 'diesel']);
        $fuelType2->save();

        $deviceModel = new DeviceModel([
            'name' => 'GPS103',
            'detail' => \json_encode([
                'begin' => 'begin',
                'locate' => 'fix010s001n',
                'turn_on' => 'resume',
                'turn_off' => 'stop',
            ])
        ]);
        $deviceModel->save();

        $beneficiary = new Beneficiary(['name' => 'Carlos', 'lastname' => 'Henríquez', 'dni' => '16485095-1', 'dni_serial_number' => '512106024', 'phone_number' => '+56950143188', 'email' => 'carlos.henriquez.lara@gmail.com', 'address' => 'Los Notros 097, Pitrufquén',]);
        $beneficiary->save();

        $beneficiary3 = new Beneficiary(['name' => 'Jon', 'lastname' => 'Doe', 'dni' => '16960182-8', 'dni_serial_number' => '512106028', 'phone_number' => '+56950143211', 'email' => 'carlos.henriquez@andeslabs.cl', 'address' => 'Los Notros 097, Pitrufquén',]);
        $beneficiary3->save();
        $beneficiary4 = new Beneficiary(['name' => 'Mr', 'lastname' => 'Burns', 'dni' => '1-9', 'dni_serial_number' => '512106030', 'phone_number' => '+56950143222', 'email' => 'burns@andeslabs.cl', 'address' => 'Los Notros 097, Pitrufquén',]);
        $beneficiary4->save();

        $beneficiary2 = new Beneficiary(['name' => 'Sociedad Comercial Villarrica y Pucón Ltda.', 'lastname' => '', 'dni' => '76922123-9', 'dni_serial_number' => '', 'phone_number' => '+56922108987', 'email' => 'alvaro.rosas@autoseguro365.com', 'address' => 'Camino Internacional 4.5 0',]);
        $beneficiary2->save();

        $vehicle = new Vehicle(['tenant_id' => $tenant2->id, 'country_id' => $country->id, 'color' => 'BLANCO', 'plate_number' => 'DWZG76', 'model' => 'RIO 5', 'year' => '2012', 'owner_name' => 'Carlos Henríquez Lara', 'owner_dni' => '16485095-1', 'owner_adquisition_date' => '2019-02-05', 'fuel_type_id' => $fuelType->id,]);
        $vehicle->save();

        $vehicle2 = new Vehicle(['tenant_id' => $tenant->id, 'country_id' => $country->id, 'color' => 'BLANCO', 'plate_number' => 'LWKG62', 'model' => 'APV FURGON 1.6', 'year' => '2020', 'owner_name' => 'Sociedad Comercial Villarrica y Pucón Limitada.', 'owner_dni' => '76922123-9', 'owner_adquisition_date' => '2020-04-13', 'fuel_type_id' => $fuelType2->id,]);
        $vehicle2->save();
        $vehicle3 = new Vehicle(['tenant_id' => $tenant2->id, 'country_id' => $country->id, 'color' => 'NEGRO', 'plate_number' => 'HE5592', 'model' => 'HILYX', 'year' => '2018', 'owner_name' => 'Sociedad Comercial Villarrica y Pucón Limitada.', 'owner_dni' => '76922123-9', 'owner_adquisition_date' => '2020-04-13', 'fuel_type_id' => $fuelType2->id,]);
        $vehicle3->save();
        $vehicle4 = new Vehicle(['tenant_id' => $tenant2->id, 'country_id' => $country->id, 'color' => 'VERDE', 'plate_number' => 'XY1234', 'model' => 'BALENO', 'year' => '2018', 'owner_name' => 'Sociedad Comercial Villarrica y Pucón Limitada.', 'owner_dni' => '76922123-9', 'owner_adquisition_date' => '2020-04-13', 'fuel_type_id' => $fuelType2->id,]);
        $vehicle4->save();
        $vehicle5 = new Vehicle(['tenant_id' => $tenant2->id, 'country_id' => $country->id, 'color' => 'AMARILLO', 'plate_number' => 'AABB12', 'model' => 'CX-3', 'year' => '2019', 'owner_name' => 'Sociedad Comercial Villarrica y Pucón Limitada.', 'owner_dni' => '76922123-9', 'owner_adquisition_date' => '2020-04-13', 'fuel_type_id' => $fuelType2->id,]);
        $vehicle5->save();

        $beneficiaryVehicle = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary->id, 'vehicle_id' => $vehicle->id, 'owner' => true,]);
        $beneficiaryVehicle->save();
        $beneficiaryVehicle3 = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary3->id, 'vehicle_id' => $vehicle->id, 'owner' => false,]);
        $beneficiaryVehicle3->save();
        $beneficiaryVehicle4 = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary3->id, 'vehicle_id' => $vehicle3->id, 'owner' => true,]);
        $beneficiaryVehicle4->save();
        $beneficiaryVehicle5 = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary4->id, 'vehicle_id' => $vehicle4->id, 'owner' => true,]);
        $beneficiaryVehicle5->save();
        $beneficiaryVehicle6 = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary4->id, 'vehicle_id' => $vehicle5->id, 'owner' => true,]);
        $beneficiaryVehicle6->save();

        $beneficiaryVehicle2 = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary2->id, 'vehicle_id' => $vehicle2->id, 'owner' => true,]);
        $beneficiaryVehicle2->save();

        $device = new Device(['device_model_id' => $deviceModel->id, 'serial_number' => '#11245125', 'code' => 'GPS103', 'tenant_id' => $tenant2->id, 'vehicle_id' => $vehicle->id, 'password' => '123456',]);
        $device->save();

        $simCard = new SimCard(['country_id' => $country->id, 'carrier_id' => $carrier->id, 'phone_number' => '+56991534382', 'device_id' => $device->id, 'tenant_id' => $tenant2->id,]);
        $simCard->save();

        $device2 = new Device(['device_model_id' => $deviceModel->id, 'serial_number' => '#11245333', 'code' => 'GPS103', 'tenant_id' => $tenant->id, 'vehicle_id' => $vehicle2->id, 'password' => '123456',]);
        $device2->save();

        $simCard2 = new SimCard(['country_id' => $country->id, 'carrier_id' => $carrier->id, 'phone_number' => '+56982598950', 'device_id' => $device2->id, 'tenant_id' => $tenant->id,]);
        $simCard2->save();
    }
}
