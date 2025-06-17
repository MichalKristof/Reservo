<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Carbon\Carbon;
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
            'reserved_at' => Carbon::yesterday()->setTime(17, 0),
            "number_of_people" => 1,
            'duration' => 1,
        ]);
        Reservation::create([
            'user_id' => 1,
            'table_id' => 2,
            'reserved_at' => Carbon::yesterday()->setTime(19, 0),
            "number_of_people" => 2,
            'duration' => 1,
        ]);
    }
}
