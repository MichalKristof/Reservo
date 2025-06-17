<?php

namespace App\Actions;

use DateTimeImmutable;
use App\Models\Table;
use Carbon\Carbon;

class GetTableOccupancyForDateAction
{
    public function execute(DateTimeImmutable $date): array
    {
        $dateStart = Carbon::parse($date)->startOfDay();
        $dateEnd = Carbon::parse($date)->endOfDay();

        $tables = Table::with(['reservations' => function ($query) use ($dateStart, $dateEnd) {
            $query->whereBetween('reserved_at', [$dateStart, $dateEnd])
                ->orderBy('reserved_at');
        }])->get();

        return $tables->map(function ($table) {
            return [
                'id' => $table->id,
                'name' => $table->name,
                'seats' => $table->seats,
                'reservations' => $table->reservations->map(function ($res) {
                    return [
                        'id' => $res->id,
                        'reserved_at' => $res->reserved_at,
                        'duration' => $res->duration,
                        'number_of_people' => $res->number_of_people,
                        'user_email' => $res->user->email,
                    ];
                }),
            ];
        })->toArray();
    }
}
