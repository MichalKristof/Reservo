<?php

namespace App\Actions;

use App\Models\Table;
use Carbon\Carbon;
use DateTimeImmutable;

class GetAvailableTimesAction
{
    public function execute(DateTimeImmutable $date): array
    {
        $reservedDate = Carbon::parse($date)->startOfDay();
        $availableTimes = collect(config('restaurant.times'));
        $durations = collect(config('restaurant.durations'));
        $closingTime = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . config('restaurant.closing_time'));

        $tables = Table::with(['reservations' => function ($query) use ($reservedDate) {
            $query->whereBetween('reserved_at', [
                $reservedDate->copy()->startOfDay(),
                $reservedDate->copy()->endOfDay(),
            ]);
        }])->get();

        $availableSlots = [];

        foreach ($availableTimes as $timeStr) {
            $start = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . $timeStr);

            $fitsAnyDuration = $durations->contains(function ($duration) use ($start, $closingTime) {
                return $start->copy()->addHours($duration)->lte($closingTime);
            });

            if (!$fitsAnyDuration) {
                continue;
            }

            $isAvailableForSomeDuration = $tables->every(fn($table) => $table->reservations->isEmpty()) || $durations->contains(function ($duration) use ($start, $tables) {
                    $end = $start->copy()->addHours($duration);

                    return $tables->contains(function ($table) use ($start, $end) {
                        $hasConflict = $table->reservations->contains(function ($reservation) use ($start, $end) {
                            $resStart = Carbon::parse($reservation->reserved_at);
                            $resEnd = $resStart->copy()->addHours($reservation->duration);

                            return $start->lt($resEnd) && $end->gt($resStart);
                        });

                        return !$hasConflict;
                    });
                });


            if ($isAvailableForSomeDuration) {
                $availableSlots[] = $timeStr;
            }
        }

        return $availableSlots;

    }
}

