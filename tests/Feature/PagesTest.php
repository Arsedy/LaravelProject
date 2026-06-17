<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('front pages return a successful response', function () {
    // Create a product so the product page has data to display
    $category = Category::factory()->create();
    Product::factory()->create(['category_id' => $category->id]);

    $routes = ['home', 'store', 'product', 'blank'];

    foreach ($routes as $routeName) {
        $response = $this->get(route($routeName));
        $response->assertStatus(200);
    }
});

test('checkout page redirects to login for guests', function () {
    $this->get(route('checkout'))->assertRedirect(route('login'));
});

test('admin page returns a successful response for admin user', function () {
    $adminRole = Role::create(['name' => 'admin', 'description' => 'Admin Role']);
    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);

    $response = $this->actingAs($admin)->get(route('admin.home'));
    $response->assertStatus(200);
});

test('storefront search and category filter returns a successful response', function () {
    $category1 = Category::factory()->create(['title' => 'Laptops', 'status' => true]);
    $category2 = Category::factory()->create(['title' => 'Cameras', 'status' => true]);
    Product::factory()->create([
        'category_id' => $category1->id,
        'title' => 'MacBook Pro',
        'price' => 1200,
        'status' => true,
    ]);
    Product::factory()->create([
        'category_id' => $category2->id,
        'title' => 'Sony Alpha',
        'price' => 2000,
        'status' => true,
    ]);

    // Test search filter
    $response = $this->get(route('store', ['search' => 'MacBook']));
    $response->assertStatus(200);
    $response->assertSee('MacBook Pro');
    $response->assertDontSee('Sony Alpha');

    // Test category filter
    $response = $this->get(route('store', ['category_id' => $category2->id]));
    $response->assertStatus(200);
    $response->assertSee('Sony Alpha');
    $response->assertDontSee('MacBook Pro');
});
