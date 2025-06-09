<?php

namespace App\DTO;
class FindAvailableTablesResultData
{
    public function __construct(
        public readonly \Illuminate\Support\Collection $availableTables,
        public readonly array                          $messages = []
    )
    {
    }
}
