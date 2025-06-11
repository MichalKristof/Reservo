<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\Table;

class GetAvailableNumberOfPeople
{
    public function execute(string $date, string $time, int $durationHours): array
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

        $seatOptions = $freeTables->flatMap(function ($table) {
            return range(1, $table->seats);
        });

        return $seatOptions->unique()->sort()->values()->toArray();
    }
}
