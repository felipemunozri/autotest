<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            CarBrandSeeder::class,
            ActionTypeSeeder::class,
            DeviceModelSeeder::class,
            DeviceStatusSeeder::class,
            EventStatusSeeder::class,
            EventQuestionSeeder::class,
            TenantUserStatusSeeder::class,
            FuelTypeSeeder::class,
            ActionSeeder::class,
            CarrierSeeder::class,
            EventCodeStatusSeeder::class,
            EventResultSeeder::class,
            EventQuestionSeeder::class,
            VehicleActivationStatusSeeder::class,
            TenantSeeder::class,
            RolsPermissionsSeeder::class,
            TechnicalProfileSeeder::class,
            UserSeeder::class,
            ExampleDataSeeder::class,
            SimCardStatusSeeder::class
        ]);
    }
}
