<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->category = Category::factory()->create();
    $this->product = Product::factory()->create([
        'category_id' => $this->category->id,
        'price' => 100.00,
        'discount' => 10.00, // 10% discount -> unit price 90.00
        'stock' => 5,
    ]);
});

test('checkout page loads with a default product when no product_id is provided', function () {
    $response = $this->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee($this->product->title);
});

test('checkout page loads with the specified product and quantity', function () {
    $otherProduct = Product::factory()->create([
        'category_id' => $this->category->id,
        'title' => 'Specific Tablet',
        'price' => 200.00,
        'discount' => 0.00,
    ]);

    $response = $this->get(route('checkout', ['product_id' => $otherProduct->id, 'quantity' => 2]));

    $response->assertStatus(200);
    $response->assertSee('Specific Tablet');
    $response->assertSee('2x Specific Tablet');
    $response->assertSee('$400.00'); // 2 * 200.00 = 400.00
});

test('guest can successfully place an order', function () {
    $response = $this->post(route('orders.store'), [
        'name' => 'Guest Customer',
        'email' => 'guest@example.com',
        'address' => '123 Fake Street',
        'telephone' => '555-1234',
        'product_id' => $this->product->id,
        'quantity' => 2,
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('success');

    // Expected total: (100.00 - 10.00) * 2 = 180.00
    $this->assertDatabaseHas('orders', [
        'user_id' => null,
        'product_id' => $this->product->id,
        'name' => 'Guest Customer',
        'email' => 'guest@example.com',
        'address' => '123 Fake Street',
        'telephone' => '555-1234',
        'quantity' => 2,
        'total' => 180.00,
        'status' => 'pending',
    ]);

    // Product stock should decrement: 5 - 2 = 3
    $this->product->refresh();
    expect($this->product->stock)->toBe(3);
});

test('authenticated user can successfully place an order', function () {
    $user = User::factory()->create([
        'name' => 'Auth Customer',
        'email' => 'auth@example.com',
    ]);

    // Check if name and email are pre-filled on checkout page
    $viewResponse = $this->actingAs($user)->get(route('checkout', ['product_id' => $this->product->id]));
    $viewResponse->assertStatus(200);
    $viewResponse->assertSee('value="Auth Customer"', false);
    $viewResponse->assertSee('value="auth@example.com"', false);

    // Place order
    $response = $this->actingAs($user)->post(route('orders.store'), [
        'name' => 'Auth Customer',
        'email' => 'auth@example.com',
        'address' => '456 User Lane',
        'telephone' => '555-9876',
        'product_id' => $this->product->id,
        'quantity' => 1,
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'product_id' => $this->product->id,
        'name' => 'Auth Customer',
        'email' => 'auth@example.com',
        'address' => '456 User Lane',
        'telephone' => '555-9876',
        'quantity' => 1,
        'total' => 90.00,
        'status' => 'pending',
    ]);
});

test('order placement fails if requested quantity exceeds product stock', function () {
    $response = $this->post(route('orders.store'), [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'address' => 'Test Address',
        'telephone' => '555-0000',
        'product_id' => $this->product->id,
        'quantity' => 6, // Stock is 5
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');

    // Confirm order was not created
    $this->assertDatabaseMissing('orders', [
        'name' => 'Test Customer',
    ]);

    // Confirm stock remained the same
    $this->product->refresh();
    expect($this->product->stock)->toBe(5);
});

test('validation errors are caught when placing order', function () {
    $response = $this->post(route('orders.store'), [
        'name' => '', // Required
        'email' => 'not-an-email', // Email validation
        'address' => 'Address',
        'telephone' => '555-1234',
        'product_id' => 9999, // Non-existent product
        'quantity' => 0, // Min 1
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'product_id', 'quantity']);
});
