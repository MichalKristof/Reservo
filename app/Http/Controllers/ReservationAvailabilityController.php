<?php

namespace App\Http\Controllers;

use App\Actions\GetAvailableDurationsAction;
use App\Actions\GetAvailableTimesAction;
use App\Actions\GetAvailableNumberOfPeople;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationAvailabilityController extends Controller
{
    public function availableTimes(Request $request, GetAvailableTimesAction $getAvailableTimes): JsonResponse
    {
        // TODO: Add validation for the date format and ensure it is a future date.
        $request->validate([
            'reserved_at' => ['required', 'date'],
        ]);

        $times = $getAvailableTimes->execute($request->input('reserved_at'));

        return response()->json([
            'times' => $times,
        ]);
    }

    public function availableDurations(Request $request, GetAvailableDurationsAction $availableDurationsAction): JsonResponse
    {
        $request->validate([
            'reserved_at' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $durations = $availableDurationsAction->execute($request->input('reserved_at'), $request->input('time'));

        return response()->json([
            'durations' => $durations,
        ]);
    }

    public function availablePeople(Request $request, GetAvailableNumberOfPeople $getMaxSeatsForTimeAction): JsonResponse
    {
        $request->validate([
            'reserved_at' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'duration' => ['required', 'integer', 'min:1'],
        ]);

        $peopleOptions = $getMaxSeatsForTimeAction->execute($request->input('reserved_at'), $request->input('time'), $request->input('duration'));

        return response()->json([
            'available_people_counts' => $peopleOptions,
        ]);
    }
}
