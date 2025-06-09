<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            Table::create([
                'name' => 'Table ' . $i,
                'seats' => rand(2, 6),
                'status' => 'active',
            ]);
        }
    }
}
