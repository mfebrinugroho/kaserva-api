<?php

test('hello api returns correct response', function () {

  $response = $this->getJson('/api/example');

  $response
    ->assertStatus(200)
    ->assertJson([
      'message' => 'Example Test',
    ]);
});
