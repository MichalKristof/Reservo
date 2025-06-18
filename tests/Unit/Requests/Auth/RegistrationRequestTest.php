<?php

use App\Http\Requests\Auth\RegisterRequest;
use App\DTO\RegisterData;
use Illuminate\Support\Facades\Validator;

test('register request validation passes with valid data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new RegisterRequest();
    $request->initialize([], $data);
    $request->setMethod('POST');

    $rules = $request->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->passes())->toBeTrue();

    $dto = $request->toData();
    expect($dto)->toBeInstanceOf(RegisterData::class)
        ->and($dto->name)->toBe($data['name'])
        ->and($dto->email)->toBe($data['email'])
        ->and($dto->password)->toBe($data['password']);
});

test('register request validation fails with missing fields', function () {
    $data = [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
    ];

    $rules = (new RegisterRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});
