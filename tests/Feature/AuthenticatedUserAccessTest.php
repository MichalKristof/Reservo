<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($this->user);
});

test('dashboard is accessible for authenticated user', function () {
    $response = $this->get(route('dashboard'));
    $response->assertStatus(200);
});

test('reservations index is accessible', function () {
    $response = $this->get(route('reservations.index'));
    $response->assertStatus(200);
});

test('reservations create page is accessible', function () {
    $response = $this->get(route('reservations.create'));
    $response->assertStatus(200);
});

test('authenticated user can logout', function () {
    $response = $this->post(route('logout'));
    $response->assertRedirect('/');
    $this->assertGuest();
});

test('admin user can access tables index page', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($admin);

    $response = $this->get(route('tables.index'));
    $response->assertStatus(200);
});
