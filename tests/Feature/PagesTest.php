<?php

test('front pages return a successful response', function () {
    $routes = ['home', 'store', 'product', 'checkout', 'blank'];

    foreach ($routes as $routeName) {
        $response = $this->get(route($routeName));
        $response->assertStatus(200);
    }
});

test('admin page returns a successful response', function () {
    $response = $this->get(route('admin.home'));
    $response->assertStatus(200);
});
