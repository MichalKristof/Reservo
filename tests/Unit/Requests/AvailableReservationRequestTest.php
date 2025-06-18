<?php

use App\Http\Requests\AvailableReservationRequest;
use Illuminate\Support\Facades\Validator;

test('available reservation request validation passes with valid data', function () {
    $data = [
        'reserved_at' => now()->format('Y-m-d'),
        'time' => '14:30',
        'duration' => 1,
    ];

    $rules = (new AvailableReservationRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->passes())->toBeTrue();

    $request = AvailableReservationRequest::create('/available-reservation', 'POST', $data);
    $request->merge($data);

    expect($request->input('reserved_at'))->toBe($data['reserved_at'])
        ->and($request->input('time'))->toBe($data['time'])
        ->and($request->input('duration'))->toBe($data['duration']);
});

test('available reservation request validation fails with past date', function () {
    $data = [
        'reserved_at' => '2000-01-01',
        'time' => '14:30',
        'duration' => 1,
    ];

    $rules = (new AvailableReservationRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('reserved_at'))->toBeTrue();
});

test('getDateTime returns combined datetime or null', function () {
    $dataWithTime = [
        'reserved_at' => '2025-06-18',
        'time' => '15:45',
    ];

    $requestWithTime = AvailableReservationRequest::create('/available-reservation', 'POST', $dataWithTime);
    $requestWithTime->merge($dataWithTime);

    $dateTime = $requestWithTime->getDateTime();
    expect($dateTime)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($dateTime->format('Y-m-d H:i'))->toBe('2025-06-18 15:45');

    $dataWithoutTime = [
        'reserved_at' => '2025-06-18',
    ];

    $requestWithoutTime = AvailableReservationRequest::create('/available-reservation', 'POST', $dataWithoutTime);
    $requestWithoutTime->merge($dataWithoutTime);

    expect($requestWithoutTime->getDateTime())->toBeNull();
});

test('getReservedAt returns start of day datetime immutable', function () {
    $data = [
        'reserved_at' => '2025-06-18',
    ];

    $request = AvailableReservationRequest::create('/available-reservation', 'POST', $data);
    $request->merge($data);

    $reservedAt = $request->getReservedAt();

    expect($reservedAt)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($reservedAt->format('Y-m-d H:i:s'))->toBe('2025-06-18 00:00:00');
});

test('getTime returns time string or null', function () {
    $data = [
        'time' => '12:00',
    ];

    $request = AvailableReservationRequest::create('/available-reservation', 'POST', $data);
    $request->merge($data);

    expect($request->getTime())->toBe('12:00');

    $requestNoTime = AvailableReservationRequest::create('/available-reservation', 'POST', []);
    expect($requestNoTime->getTime())->toBeNull();
});

test('getDuration returns integer or null', function () {
    $data = [
        'duration' => '2',
    ];

    $request = AvailableReservationRequest::create('/available-reservation', 'POST', $data);
    $request->merge($data);

    expect($request->getDuration())->toBeInt()->toBe(2);

    $requestNoDuration = AvailableReservationRequest::create('/available-reservation', 'POST', []);
    expect($requestNoDuration->getDuration())->toBeNull();
});
