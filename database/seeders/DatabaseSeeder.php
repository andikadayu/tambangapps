<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\driver;
use App\Models\fuel;
use App\Models\vehicle;
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
        \App\Models\User::factory(10)->create();

        driver::factory(10)->create();

        fuel::factory()->create([
            'name' => 'Pertalite',
            'price' => 10000
        ]);
        fuel::factory()->create([
            'name' => 'Pertamax',
            'price' => 14500
        ]);
        fuel::factory()->create([
            'name' => 'Bio Solar',
            'price' => 7500
        ]);
        fuel::factory()->create([
            'name' => 'Dex Lite',
            'price' => 17100
        ]);

        vehicle::factory()->create([
            'name' => 'Avanza',
            'fuelid' => 1
        ]);

        vehicle::factory()->create([
            'name' => 'Mitsubishi Subaru',
            'fuelid' => 2
        ]);

        vehicle::factory()->create([
            'name' => 'Pickup Motor 1',
            'fuelid' => 3
        ]);

        vehicle::factory()->create([
            'name' => 'Pickup Motor 2',
            'fuelid' => 4
        ]);
    }
}
