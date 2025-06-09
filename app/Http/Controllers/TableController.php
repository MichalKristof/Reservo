<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Inertia\Inertia;

class TableController extends Controller
{
    /**
     * Zobrazí zoznam stolov.
     */
    public function index()
    {
        $tables = Table::where('status', 'active')->get();

        return Inertia::render('Table/TableIndex', [
            'tables' => $tables,
        ]);
    }
}
