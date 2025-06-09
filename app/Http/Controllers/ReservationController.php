<?php

namespace App\Http\Controllers;

use App\Actions\FindAvailableTablesAction;
use App\DTO\CheckAvailabilityData;
use App\Http\Requests\CheckAvailabilityRequest;
use Exception;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{

    /**
     * Display the reservation creation page.
     */
    public function create(): Response
    {
        $config = config('restaurant');

        return Inertia::render('Reservations/ReservationCreate', [
            'config' => $config,
        ]);
    }

    /**
     * Check the availability of tables based on the reservation data.
     * @throws Exception
     */
    public function availableTables(CheckAvailabilityRequest $request, FindAvailableTablesAction $findAvailableTablesAction): \Illuminate\Http\JsonResponse
    {
        $data = new CheckAvailabilityData(
            new \DateTimeImmutable($request->input('reserved_at')),
            $request->input('time'),
            $request->integer('duration'),
            $request->integer('number_of_people')
        );


        $result = ($findAvailableTablesAction)($data);

        return response()->json([
            'success' => true,
            'data' => [
                'availableTables' => $result->availableTables,
            ],
            'messages' => $result->messages,
        ]);
    }
}
