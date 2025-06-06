<?php

namespace App\DTO;

class CheckAvailabilityData
{
    public function __construct(
        public \DateTimeInterface $reservedAt,
        public string             $time,
        public ?int               $duration = null,
        public ?int               $numberOfPeople = null
    )
    {
    }
}
