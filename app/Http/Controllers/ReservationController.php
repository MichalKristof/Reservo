<?php

namespace App\Http\Controllers;

use App\Actions\CreateReservationAction;
use App\Actions\GetUserReservationsAction;
use App\DTO\ReservationData;
use App\Http\Requests\StoreReservationRequest;
use App\Jobs\SendReservationCreatedMail;
use App\Mail\ReservationCreateMail;
use Exception;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{

    /**
     * Show the list of reservations.
     */
    public function index(GetUserReservationsAction $getUserReservationsAction): Response
    {
        $reservations = $getUserReservationsAction->execute();

        return Inertia::render('Reservations/ReservationIndex', [
            'reservations' => $reservations,
        ]);
    }

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
     * Create a new reservation.
     * @throws Exception
     */
    public function store(StoreReservationRequest $storeReservationRequest, CreateReservationAction $createReservationAction): Response
    {
        $validated = $storeReservationRequest->validated();

        try {
            $reservationData = ReservationData::from($validated);

            $reservation = $createReservationAction->execute(
                $reservationData->reservedAt,
                $reservationData->duration,
                $reservationData->numberOfPeople
            );

            dispatch(new SendReservationCreatedMail($reservation));

            return Inertia::render('Reservations/ReservationCreate', [
                'flash' => [
                    'type' => 'success',
                    'message' => 'Reservation created successfully.',
                ],
                'reservation' => $reservation,
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
