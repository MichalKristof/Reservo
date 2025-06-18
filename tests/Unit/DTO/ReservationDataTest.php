<?php

use App\DTO\ReservationData;

test('ReservationData constructor assigns properties correctly', function () {
    $reservedAt = new DateTimeImmutable('2025-06-18 18:00:00');
    $duration = 2;
    $people = 4;

    $reservationData = new ReservationData($reservedAt, $duration, $people);

    expect($reservationData->reservedAt)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($reservationData->reservedAt->format('Y-m-d H:i:s'))->toBe('2025-06-18 18:00:00')
        ->and($reservationData->duration)->toBe($duration)
        ->and($reservationData->numberOfPeople)->toBe($people);
});

test('ReservationData::from() creates an instance from array', function () {
    $data = [
        'reserved_at' => '2025-06-18',
        'time' => '18:00',
        'duration' => '2',
        'number_of_people' => '4',
    ];

    $reservationData = ReservationData::from($data);

    expect($reservationData)->toBeInstanceOf(ReservationData::class)
        ->and($reservationData->reservedAt->format('Y-m-d H:i'))->toBe('2025-06-18 18:00')
        ->and($reservationData->duration)->toBe(2)
        ->and($reservationData->numberOfPeople)->toBe(4);
});
