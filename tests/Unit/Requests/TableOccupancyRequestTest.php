<?php

use App\Http\Requests\TableOccupancyRequest;
use Illuminate\Support\Facades\Validator;

test('table occupancy request validation passes with valid date', function () {
    $data = ['date' => '2025-06-18'];

    $rules = (new TableOccupancyRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->passes())->toBeTrue();

    $request = TableOccupancyRequest::create('/table-occupancy', 'GET', $data);
    $request->merge($data);

    expect($request->input('date'))->toBe($data['date']);
});


test('table occupancy request validation passes without date', function () {
    $data = [];

    $rules = (new TableOccupancyRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->passes())->toBeTrue();

    $request = TableOccupancyRequest::create('/table-occupancy', 'GET', $data);
    $request->initialize([], $data);

    expect($request->input('date'))->toBeNull();
});

test('table occupancy request validation fails with invalid date', function () {
    $data = ['date' => 'not-a-date'];

    $rules = (new TableOccupancyRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('date'))->toBeTrue();

});
