<?php

namespace App\Actions;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GetUserReservationsAction
{
    public function execute()
    {
        $userId = Auth::id();

        return Reservation::with('table')
            ->where('user_id', $userId)
            ->orderBy('reserved_at', 'desc')
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'table_id' => $reservation->table_id,
                    'duration' => $reservation->duration,
                    'number_of_people' => $reservation->number_of_people,
                    'reserved_at' => Carbon::parse($reservation->reserved_at)->translatedFormat('Y-m-d H:i'),
                    'table' => $reservation->table ? [
                        'name' => $reservation->table->name,
                        'id' => $reservation->table->id,
                    ] : null,
                ];
            });
    }
}
