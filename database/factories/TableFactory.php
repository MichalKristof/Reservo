<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Table>
 */
class TableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seats = config('restaurant.number_of_people', [2, 4, 6]);
        
        return [
            'name' => $this->faker->unique()->word(),
            'seats' => $this->faker->randomElement($seats),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
