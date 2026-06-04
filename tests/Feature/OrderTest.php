<?php

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->category = Category::factory()->create();
    $this->product = Product::factory()->create([
        'category_id' => $this->category->id,
        'price' => 100.00,
        'discount' => 10.00, // 90.00 price
        'stock' => 5,
    ]);
});

test('guest is redirected to login when accessing cart or checkout', function () {
    $this->get(route('cart.index'))->assertRedirect(route('login'));
    $this->get(route('checkout'))->assertRedirect(route('login'));
});

test('user can add product to database cart', function () {
    $response = $this->actingAs($this->user)->post(route('cart.add', $this->product->id), [
        'quantity' => 2,
    ]);

    $response->assertRedirect(route('cart.index'));
    $this->assertDatabaseHas('carts', [
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'quantity' => 2,
        'price' => 90.00,
    ]);
});

test('user cannot add product to cart if quantity exceeds stock', function () {
    $response = $this->actingAs($this->user)->post(route('cart.add', $this->product->id), [
        'quantity' => 6, // Stock is 5
    ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('carts', [
        'user_id' => $this->user->id,
    ]);
});

test('user can update cart item quantity', function () {
    $cart = Cart::create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 90.00,
    ]);

    $response = $this->actingAs($this->user)->post(route('cart.update', $cart->id), [
        'quantity' => 3,
    ]);

    $response->assertSessionHas('success');
    expect($cart->fresh()->quantity)->toBe(3);
});

test('user can remove product from database cart', function () {
    $cart = Cart::create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 90.00,
    ]);

    $response = $this->actingAs($this->user)->delete(route('cart.remove', $cart->id));

    $response->assertSessionHas('success');
    $this->assertDatabaseMissing('carts', [
        'id' => $cart->id,
    ]);
});

test('checkout page loads with user cart items', function () {
    Cart::create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'quantity' => 2,
        'price' => 90.00,
    ]);

    $response = $this->actingAs($this->user)->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee($this->product->title);
    $response->assertSee('$180.00'); // 90 * 2 = 180
});

test('user can successfully place order', function () {
    Cart::create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'quantity' => 2,
        'price' => 90.00,
    ]);

    $response = $this->actingAs($this->user)->post(route('place.order'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '123456789',
        'address' => '123 Main St',
        'city' => 'Istanbul',
        'country' => 'Turkey',
        'zip_code' => '34000',
        'shipping_method' => 'Free Shipping',
        'payment_method' => 'Cash on Delivery',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertDatabaseHas('orders', [
        'user_id' => $this->user->id,
        'name' => 'John Doe',
        'total' => 180.00,
        'status' => 'New',
    ]);

    $order = Order::where('user_id', $this->user->id)->first();

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_title' => $this->product->title,
        'price' => 90.00,
        'quantity' => 2,
        'total' => 180.00,
    ]);

    // Cart should be empty in database
    $this->assertDatabaseMissing('carts', [
        'user_id' => $this->user->id,
    ]);

    // Product stock should decrement: 5 - 2 = 3
    expect($this->product->fresh()->stock)->toBe(3);
});

test('order placement fails if stock is depleted after adding to cart', function () {
    Cart::create([
        'user_id' => $this->user->id,
        'product_id' => $this->product->id,
        'quantity' => 3,
        'price' => 90.00,
    ]);

    // Deplete stock manually in the database
    $this->product->update(['stock' => 2]);

    $response = $this->actingAs($this->user)->post(route('place.order'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '123456789',
        'address' => '123 Main St',
        'city' => 'Istanbul',
        'country' => 'Turkey',
        'zip_code' => '34000',
        'shipping_method' => 'Free Shipping',
        'payment_method' => 'Cash on Delivery',
    ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('orders', [
        'user_id' => $this->user->id,
    ]);
});
