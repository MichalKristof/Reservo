<?php

use App\Http\Requests\Auth\LoginRequest;
use App\DTO\LoginData;
use Illuminate\Support\Facades\Validator;


test('login request validation passes with valid data', function () {
    $data = [
        'email' => 'user@example.com',
        'password' => 'secret123',
        'remember' => true,
    ];

    $request = LoginRequest::create('/login', 'POST');
    $request->request->add($data);

    $rules = (new LoginRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->passes())->toBeTrue();

    $dto = $request->toData();
    expect($dto)->toBeInstanceOf(LoginData::class)
        ->and($dto->email)->toBe($data['email'])
        ->and($dto->password)->toBe($data['password'])
        ->and($dto->remember)->toBeTrue();
});

test('login request validation fails with invalid email', function () {
    $data = [
        'email' => 'not-an-email',
        'password' => 'secret123',
    ];

    $rules = (new LoginRequest())->rules();

    $validator = Validator::make($data, $rules);
    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});

test('login request remember defaults to false', function () {
    $data = [
        'email' => 'user@example.com',
        'password' => 'secret123',
    ];

    $request = LoginRequest::create('/login', 'POST');
    $request->request->add($data);

    $dto = $request->toData();
    expect($dto->remember)->toBeFalse();
});
