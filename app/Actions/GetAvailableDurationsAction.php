<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Reservation;

class GetAvailableDurationsAction
{
    public function execute(string $date, string $time): array
    {
        $reservedDate = Carbon::parse($date)->startOfDay();
        $start = Carbon::parse($reservedDate->format('Y-m-d') . ' ' . $time);
        $durations = config('restaurant.durations');
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

        $availableDurations = [];

        foreach ($durations as $duration) {
            $end = $start->copy()->addHours($duration);

            if ($end->gt($closingTime)) {
                continue;
            }

            $overlaps = $reservedTimes->contains(function ($reserved) use ($start, $end) {
                return $start->lt($reserved['end']) && $end->gt($reserved['start']);
            });

            if (!$overlaps) {
                $availableDurations[] = $duration;
            }
        }

        return $availableDurations;
    }
}

