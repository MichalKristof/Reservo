<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Reservation;

class GetAvailableTimesAction
{
    public function execute(string $date): array
    {
        $reservedDate = Carbon::parse($date)->startOfDay();
        $availableTimes = collect(config('restaurant.times'));
        $durations = collect(config('restaurant.durations'));
        $closingTime = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . config('restaurant.closing_time'));
        $reservations = Reservation::whereBetween('reserved_at', [
            $reservedDate->copy()->startOfDay(),
            $reservedDate->copy()->endOfDay()
        ])->get();
        
        $reservedTimes = $reservations->map(function ($reservation) {
            $start = Carbon::parse($reservation->reserved_at);
            $end = $start->copy()->addHours($reservation->duration);
            return ['start' => $start, 'end' => $end];
        });

        $availableSlots = [];

        foreach ($availableTimes as $timeStr) {
            $start = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . $timeStr);

            $fitsAnyDuration = collect($durations)->contains(function ($duration) use ($start, $closingTime) {
                return $start->copy()->addHours($duration)->lte($closingTime);
            });

            if (!$fitsAnyDuration) {
                continue;
            }

            $isAvailableForSomeDuration = collect($durations)->contains(function ($duration) use ($start, $reservedTimes) {
                $end = $start->copy()->addHours($duration);

                $overlaps = $reservedTimes->contains(function ($reserved) use ($start, $end) {
                    return $start->lt($reserved['end']) && $end->gt($reserved['start']);
                });

                return !$overlaps;
            });

            if ($isAvailableForSomeDuration) {
                $availableSlots[] = $timeStr;
            }
        }

        return $availableSlots;
    }
}

