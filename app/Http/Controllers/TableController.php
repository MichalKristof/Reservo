<?php

namespace App\Http\Controllers;

use App\Actions\GetTableOccupancyForDateAction;
use App\DTO\TableOccupancyData;
use App\Http\Requests\TableOccupancyRequest;
use Inertia\Inertia;
use Inertia\Response;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Table/TableIndex', [
            'selectedDate' => now()->toDateString(),
            'tables' => [],
        ]);
    }

    /**
     * Show the list of active tables with reservations.
     */
    public function show(TableOccupancyRequest $tableOccupancyRequest, GetTableOccupancyForDateAction $action): Response
    {
        $validated = $tableOccupancyRequest->validated();

        $date = TableOccupancyData::from($validated);

        return Inertia::render('Table/TableIndex', [
            'selectedDate' => $date->date->format('Y-m-d'),
            'tables' => $action->execute($date->date),
        ]);
    }
}
