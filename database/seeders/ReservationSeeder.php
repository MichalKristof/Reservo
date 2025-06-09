<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::create([
            'user_id' => 1,
            'table_id' => 1,
            'reserved_at' => now()->addDays(1),
            "number_of_people" => 1,
            'duration' => 1,
        ]);
        Reservation::create([
            'user_id' => 1,
            'table_id' => 2,
            'reserved_at' => now()->addDays(1),
            "number_of_people" => 1,
            'duration' => 1,
        ]);
    }
}
