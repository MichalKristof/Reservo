<?php

namespace App\Actions;

use App\Models\Table;
use Carbon\Carbon;
use DateTimeImmutable;

class GetAvailableDurationsAction
{
    public function execute(DateTimeImmutable $date, string $time): array
    {
        $reservedDate = Carbon::parse($date)->startOfDay();
        $start = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . $time);
        $durations = config('restaurant.durations');
        $closingTime = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . config('restaurant.closing_time'));

        $tables = Table::with(['reservations' => function ($query) use ($reservedDate) {
            $query->whereBetween('reserved_at', [
                $reservedDate->copy()->startOfDay(),
                $reservedDate->copy()->endOfDay()
            ]);
        }])->get();

        $availableDurations = [];

        foreach ($durations as $duration) {
            $end = $start->copy()->addHours($duration);

            if ($end->gt($closingTime)) {
                continue;
            }

            $hasAvailableTable = $tables->contains(function ($table) use ($start, $end) {
                $hasConflict = $table->reservations->contains(function ($reservation) use ($start, $end) {
                    $resStart = Carbon::parse($reservation->reserved_at);
                    $resEnd = $resStart->copy()->addHours($reservation->duration);
                    return $start->lt($resEnd) && $end->gt($resStart);
                });

                return !$hasConflict;
            });

            if ($hasAvailableTable) {
                $availableDurations[] = $duration;
            }
        }

        return $availableDurations;
    }
}


