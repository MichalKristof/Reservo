<?php

namespace App\DTO;

use DateTimeImmutable;
use Exception;

class ReservationData
{
    public DateTimeImmutable $reservedAt;
    public int $duration;
    public int $numberOfPeople;

    /**
     * @throws Exception
     */
    public function __construct(
        DateTimeImmutable $reservedAt,
        int               $duration,
        int               $numberOfPeople,
    )
    {
        $this->reservedAt = $reservedAt;
        $this->duration = $duration;
        $this->numberOfPeople = $numberOfPeople;
    }

    /**
     * @throws Exception
     */
    public static function from(array $data): self
    {
        $dateObj = new \DateTimeImmutable($data['reserved_at']);
        $date = $dateObj->format('Y-m-d');
        $time = $data['time'];
        $dateTimeString = "{$date} {$time}";

        $reservedAt = new \DateTimeImmutable($dateTimeString);

        return new self(
            reservedAt: $reservedAt,
            duration: (int)$data['duration'],
            numberOfPeople: (int)$data['number_of_people'],
        );
    }

}
