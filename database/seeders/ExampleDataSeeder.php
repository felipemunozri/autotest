<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Models\Device;
use App\Models\SimCard;
use App\Models\Beneficiary;
use App\Models\Vehicle;
use App\Models\BeneficiaryVehicle;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Device::query()->truncate();
        SimCard::query()->truncate();
        Beneficiary::query()->truncate();
        Vehicle::query()->truncate();
        BeneficiaryVehicle::query()->truncate();

        $tenant = DB::table('tenants')->where('code', 'pruebas-autoseguro')->first();
        $country = DB::table('countries')->where('code', 'CL')->first();
        $fuelType = DB::table('fuel_types')->where('code', 'gasoline')->first();
        $fuelType2 = DB::table('fuel_types')->where('code', 'diesel')->first();
        $deviceModel = DB::table('device_models')->where('code', 'gps103')->first();
        $carrier = DB::table('carriers')->where('code', 'entel')->first();
        
        $beneficiary = new Beneficiary(['name' => 'Carlos', 'lastname' => 'Henríquez', 'dni' => '16485095-1', 'dni_serial_number' => '512106024', 'phone_number' => '+56950143188', 'email' => 'carlos.henriquez.lara@gmail.com', 'address' => 'Los Notros 097, Pitrufquén']);
        $beneficiary->save();

        $beneficiary2 = new Beneficiary(['name' => 'Sociedad Comercial Villarrica y Pucón Ltda.', 'lastname' => '', 'dni' => '76922123-9', 'dni_serial_number' => '', 'phone_number' => '+56922108987', 'email' => 'alvaro.rosas@autoseguro365.com', 'address' => 'Camino Internacional 4.5 0']);
        $beneficiary2->save();

        $vehicle = new Vehicle(['tenant_id' => $tenant->id, 'country_id' => $country->id, 'color' => 'BLANCO', 'plate_number' => 'DWZG76', 'model' => 'RIO 5', 'year' => '2012', 'owner_name' => 'Carlos Henríquez Lara', 'owner_dni' => '16485095-1', 'owner_adquisition_date' => '2019-02-05', 'fuel_type_id' => $fuelType->id]);
        $vehicle->save();

        $vehicle2 = new Vehicle(['tenant_id' => $tenant->id, 'country_id' => $country->id, 'color' => 'BLANCO', 'plate_number' => 'LWKG62', 'model' => 'APV FURGON 1.6', 'year' => '2020', 'owner_name' => 'Sociedad Comercial Villarrica y Pucón Limitada.', 'owner_dni' => '76922123-9', 'owner_adquisition_date' => '2020-04-13', 'fuel_type_id' => $fuelType2->id]);
        $vehicle2->save();

        $beneficiaryVehicle = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary->id, 'vehicle_id' => $vehicle->id, 'owner' => true]);
        $beneficiaryVehicle->save();

        $beneficiaryVehicle2 = new BeneficiaryVehicle(['beneficiary_id' => $beneficiary2->id, 'vehicle_id' => $vehicle2->id, 'owner' => true]);
        $beneficiaryVehicle2->save();

        $device = new Device(['device_model_id' => $deviceModel->id, 'serial_number' => '#11245125', 'code' => 'GPS103', 'tenant_id' => $tenant->id, 'vehicle_id' => $vehicle->id, 'password' => '123456']);
        $device->save();

        $simCard = new SimCard(['country_id' => $country->id, 'carrier_id' => $carrier->id, 'phone_number' => '+56991534382', 'device_id' => $device->id, 'tenant_id' => $tenant->id]);
        $simCard->save();

        $device2 = new Device(['device_model_id' => $deviceModel->id, 'serial_number' => '#11245333', 'code' => 'GPS103', 'tenant_id' => $tenant->id, 'vehicle_id' => $vehicle2->id, 'password' => '123456']);
        $device2->save();

        $simCard2 = new SimCard(['country_id' => $country->id, 'carrier_id' => $carrier->id, 'phone_number' => '+56982598950', 'device_id' => $device2->id, 'tenant_id' => $tenant->id]);
        $simCard2->save();
    }
}
