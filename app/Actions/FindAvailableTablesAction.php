<?php

namespace App\Actions;

use App\DTO\CheckAvailabilityData;
use App\Models\Table;
use App\Models\Reservation;
use Carbon\Carbon;
use Exception;

class FindAvailableTablesAction
{
    /**
     * @throws Exception
     */
    public function __invoke(CheckAvailabilityData $data)
    {
        $reservedAt = $data->reservedAt->format('Y-m-d');
        $time = $data->time;

        $start = Carbon::createFromFormat('Y-m-d H:i', "$reservedAt $time");

        $duration = ($data->duration ?? 1) * 60;
        $end = (clone $start)->addMinutes($duration);

        $closingTimeStr = config('restaurant.closing_time', '22:00');
        $closingTime = Carbon::createFromFormat('Y-m-d H:i', "$reservedAt $closingTimeStr");

        // TODO: return only render inertia or JSON response or find another solution
        if ($end->greaterThan($closingTime)) {
            throw new Exception('Reservation cannot extend past closing time (22:00).');
        }

        $reservedTableIds = Reservation::where(function ($query) use ($start, $end) {
            $query->where('reserved_at', '<', $end)
                ->whereRaw('DATE_ADD(reserved_at, INTERVAL duration MINUTE) > ?', [$start]);
        })->pluck('table_id');

        $query = Table::where('status', 'active');

        if ($data->numberOfPeople !== null) {
            $query->where('seats', '>=', $data->numberOfPeople);
        }

        return $query->whereNotIn('id', $reservedTableIds)->get();
    }
}
