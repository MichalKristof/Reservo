<?php

use App\DTO\RegisterData;

test('RegisterData from method creates instance correctly', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret',
    ];

    $register = RegisterData::from($data);

    expect($register)->toBeInstanceOf(RegisterData::class)
        ->and($register->name)->toBe('John Doe')
        ->and($register->email)->toBe('john@example.com')
        ->and($register->password)->toBe('secret');
});
