<?php

namespace App\Actions;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateReservationAction
{
    /**
     * Create a reservation by finding the first available table
     *
     * @throws Exception
     */
    public function execute(DateTimeImmutable $reservedAt, int $duration, int $numberOfPeople): Reservation
    {
        $start = Carbon::instance($reservedAt);
        $end = (clone $start)->addHours($duration);
        $userId = Auth::id();

        return DB::transaction(function () use ($start, $end, $numberOfPeople, $duration, $userId) {
            $tables = Table::where('seats', '>=', $numberOfPeople)
                ->orderBy('seats')
                ->get();

            foreach ($tables as $table) {
                $hasConflict = $table->reservations()
                    ->where(function ($query) use ($start, $end) {
                        $query
                            ->whereBetween('reserved_at', [$start, $end->copy()->subSecond()])
                            ->orWhere(function ($q) use ($start, $end) {
                                $q->where('reserved_at', '<=', $start)
                                    ->whereRaw('DATE_ADD(reserved_at, INTERVAL duration HOUR) > ?', [$start]);
                            });
                    })
                    ->exists();

                if (!$hasConflict) {
                    return Reservation::create([
                        'user_id' => $userId,
                        'table_id' => $table->id,
                        'reserved_at' => $start,
                        'duration' => $duration,
                        'number_of_people' => $numberOfPeople,
                    ]);
                }
            }

            throw new Exception('No available tables for selected time.');
        });
    }
}
