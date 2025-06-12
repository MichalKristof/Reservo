<?php

namespace App\Http\Controllers;

use App\Actions\GetAvailableDurationsAction;
use App\Actions\GetAvailableTimesAction;
use App\Actions\GetAvailableNumberOfPeople;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AvailableReservationRequest;

class ReservationAvailabilityController extends Controller
{
    public function availableTimes(AvailableReservationRequest $request, GetAvailableTimesAction $getAvailableTimes): JsonResponse
    {
        $reservedAt = $request->getReservedAt();

        return response()->json([
            'times' => $getAvailableTimes->execute($reservedAt),
        ]);
    }

    public function availableDurations(AvailableReservationRequest $request, GetAvailableDurationsAction $availableDurationsAction): JsonResponse
    {
        return response()->json([
            'durations' => $availableDurationsAction->execute(
                $request->getReservedAt(),
                $request->getTime()
            ),
        ]);
    }

    public function availablePeople(AvailableReservationRequest $request, GetAvailableNumberOfPeople $getMaxSeatsForTimeAction): JsonResponse
    {
        return response()->json([
            'available_people_counts' => $getMaxSeatsForTimeAction->execute(
                $request->getReservedAt(),
                $request->getTime(),
                $request->getDuration()
            ),
        ]);
    }
}
