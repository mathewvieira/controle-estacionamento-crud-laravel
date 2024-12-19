<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::factory(50)->create();
        Vehicle::factory(20)->create([
            'exit_at' => now()->subHours(rand(1, 48)),
        ]);
    }
}