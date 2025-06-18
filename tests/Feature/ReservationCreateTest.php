<?php

use App\Models\Table;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->reserved_at = now()->addDays(1)->format('Y-m-d');
    $this->time = '18:00';
    $this->duration = 1;
    $this->number_of_people = 1;
});

test('available times endpoint returns json data', function () {
    $response = $this->postJson(route('reservations.availableTimes'), [
        'reserved_at' => $this->reserved_at,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['times']);
});

test('available durations endpoint returns json data', function () {
    $response = $this->postJson(route('reservations.availableDurations'), [
        'reserved_at' => $this->reserved_at,
        'time' => $this->time,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['durations']);
});

test('returns available people counts as json', function () {
    $response = $this->postJson(route('reservations.availablePeople'), [
        'reserved_at' => $this->reserved_at,
        'time' => $this->time,
        'duration' => $this->duration,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'available_people_counts',
        ]);
});

test('authenticated user can create a reservation', function () {
    $table = Table::factory()->create();

    $data = [
        'reserved_at' => $this->reserved_at,
        'duration' => $this->duration,
        'time' => $this->time,
        'number_of_people' => $this->number_of_people,
    ];

    $response = $this->post(route('reservations.store'), $data);
    
    $response->assertStatus(200);

    $response->assertInertia(fn($page) => $page
        ->has('flash')
        ->where('flash.type', 'success')
        ->where('flash.message', 'Reservation created successfully.')
        ->has('reservation')
    );
});
