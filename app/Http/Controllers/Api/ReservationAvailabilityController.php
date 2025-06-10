<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationAvailabilityController extends Controller
{
    public function availableTimes(Request $request): JsonResponse
    {
        return response()->json([
            'times' => [
                '10:00', '10:30', '11:00', '11:30', '12:00',
                '12:30', '13:00', '13:30', '14:00', '14:30',
                '15:00', '15:30', '16:00', '16:30', '17:00',
                '17:30', '18:00', '18:30', '19:00', '19:30',
                '20:00', '20:30', '21:00'
            ]
        ]);
    }

    public function availableDurations(Request $request): JsonResponse
    {
        return response()->json([
            'durations' => [
                30, 60, 90, 120
            ]
        ]);
    }

    public function availablePeople(Request $request): JsonResponse
    {
        return response()->json([
            'people' => range(1, 10)
        ]);
    }
}
