<?php

use App\DTO\LoginData;

test('LoginData can be instantiated correctly', function () {
    $login = new LoginData('test@example.com', 'password123', true);

    expect($login->email)->toBe('test@example.com')
        ->and($login->password)->toBe('password123')
        ->and($login->remember)->toBeTrue();
});
