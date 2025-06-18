<?php

use App\Models\Reservation;
use App\Models\User;

test('authenticated user sees only their reservations on the index page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Reservation::factory()->count(2)->create([
        'user_id' => $user->id,
        'duration' => 123,
    ]);

    $otherUser = User::factory()->create();
    Reservation::factory()->count(2)->create([
        'user_id' => $otherUser->id,
        'duration' => 999,
    ]);

    $response = $this->get(route('reservations.index'));

    $response->assertSee('123');

    $response->assertDontSee('999');
});

