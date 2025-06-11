<?php

namespace App\Actions;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class GetUserReservationsAction
{
    public function execute()
    {
        $userId = Auth::id();

        return Reservation::with('table')
            ->where('user_id', $userId)
            ->orderBy('reserved_at', 'desc')
            ->get();
    }
}
