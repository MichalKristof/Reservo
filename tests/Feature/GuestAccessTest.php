<?php

use function Pest\Laravel\get;

test('guest user can access the homepage', function () {
    get('/')->assertStatus(200);
});

test('guest user can access the login page', function () {
    get('/login')->assertStatus(200);
});

test('guest user can access the register page', function () {
    get('/register')->assertStatus(200);
});
