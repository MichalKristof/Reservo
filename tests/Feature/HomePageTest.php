<?php

namespace Tests\Feature;

use Tests\TestCase;

test('the homepage returns a successful response', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
