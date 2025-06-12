<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\Table;
use DateTimeImmutable;

class GetAvailableNumberOfPeople
{
    public function execute(DateTimeImmutable $date, string $time, int $durationHours): array
    {
        $reservedDate = Carbon::parse($date)->startOfDay();
        $start = Carbon::parse("{$reservedDate->format('Y-m-d')} {$time}");
        $end = $start->copy()->addHours($durationHours);

        $tables = Table::where('status', 'active')->get();

        $reservations = Reservation::whereBetween('reserved_at', [
            $reservedDate->copy()->startOfDay(),
            $reservedDate->copy()->endOfDay()
        ])->get();

        $reservedTimes = $reservations->map(function ($reservation) {
            $resStart = Carbon::parse($reservation->reserved_at);
            $resEnd = $resStart->copy()->addHours($reservation->duration);
            return [
                'start' => $resStart,
                'end' => $resEnd,
                'table_id' => $reservation->table_id,
            ];
        });

        $freeTables = $tables->filter(function ($table) use ($reservedTimes, $start, $end) {
            return !$reservedTimes->contains(function ($res) use ($table, $start, $end) {
                return $res['table_id'] === $table->id &&
                    $start->lt($res['end']) && $end->gt($res['start']);
            });
        });

        $maxSeats = $freeTables->pluck('seats')->max();

        if (!$maxSeats) {
            return [0];
        }

        return range(1, $maxSeats);
    }
}
