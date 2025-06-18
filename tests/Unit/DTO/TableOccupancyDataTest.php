<?php

use App\DTO\TableOccupancyData;

test('TableOccupancyData can be instantiated correctly', function () {
    $occupancy = [
        "date" => "2025-01-01",
    ];

    $tableOccupancyData = TableOccupancyData::from($occupancy);

    expect($tableOccupancyData)->toBeInstanceOf(TableOccupancyData::class)
        ->and($tableOccupancyData->date->format('Y-m-d'))->toBe('2025-01-01');
});
