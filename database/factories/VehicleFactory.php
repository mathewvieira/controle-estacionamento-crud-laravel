<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'plate_number' => strtoupper($this->faker->unique()->regexify('[A-Z]{3}-[0-9]{4}')),
            'spot_number' => $this->faker->unique()->numberBetween(1, 500),
            'model' => $this->faker->lastName(),
            'color' => $this->faker->safeColorName(),
            'entry_at' => $this->faker->dateTimeBetween('-2 day', 'now'),
            'exit_at' => null
        ];
    }
}