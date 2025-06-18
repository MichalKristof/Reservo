<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user can login with correct credentials', function () {
    $password = 'secret1234';
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('user cannot login with invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});
