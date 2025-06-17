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
     * Show the list of active tables with reservations.
     */
    public function index(TableOccupancyRequest $tableOccupancyRequest, GetTableOccupancyForDateAction $action): Response
    {
        $validated = $tableOccupancyRequest->validated();

        if (isset($validated['date'])) {
            $date = TableOccupancyData::from($validated);

            return Inertia::render('Table/TableIndex', [
                'selectedDate' => $date->date->format('Y-m-d'),
                'tables' => $action->execute($date->date),
            ]);
        }

        return Inertia::render('Table/TableIndex');
    }
}
