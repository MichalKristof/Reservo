<?php

use App\Models\User;

test('user can register successfully', function () {
    $userData = User::factory()->make()->toArray();

    $userData['password'] = 'password123';
    $userData['password_confirmation'] = 'password123';

    $response = $this->post(route('register'), $userData);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
});
