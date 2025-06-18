<?php

namespace Database\Factories;

use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $durations = config('restaurant.durations', [1]);
        $numberOfPeople = config('restaurant.number_of_people', [2, 4, 6]);

        return [
            'user_id' => User::factory(),
            'table_id' => Table::factory(),
            'reserved_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'duration' => $this->faker->randomElement($durations),
            'number_of_people' => $this->faker->randomElement($numberOfPeople),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
