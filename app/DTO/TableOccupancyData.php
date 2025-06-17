<?php

namespace App\DTO;

use DateTimeImmutable;
use Exception;

class TableOccupancyData
{
    public DateTimeImmutable $date;

    /**
     * @throws Exception
     */
    public function __construct(
        DateTimeImmutable $date,
    )
    {
        $this->date = $date->setTime(0, 0);
    }

    /**
     * @throws Exception
     */
    public static function from(array $data): self
    {
        $dateObj = new \DateTimeImmutable($data['date'] ?? 'today');

        return new self($dateObj->setTime(0, 0));
    }

}
