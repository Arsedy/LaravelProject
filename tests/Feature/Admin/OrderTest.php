<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    $this->adminRole = Role::create(['name' => 'admin', 'description' => 'Admin Role']);
    $this->userRole = Role::create(['name' => 'user', 'description' => 'Regular User Role']);

    // Create admin user
    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($this->adminRole);

    // Create a categories, products and order
    $this->category = Category::factory()->create();
    $this->product = Product::factory()->create(['category_id' => $this->category->id]);
    $this->order = Order::factory()->create([
        'product_id' => $this->product->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'telephone' => '555-4321',
        'status' => 'pending',
    ]);
});

test('guest cannot access admin orders routes', function () {
    $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
    $this->get(route('admin.orders.show', $this->order))->assertRedirect(route('login'));
    $this->put(route('admin.orders.update', $this->order), ['status' => 'completed'])->assertRedirect(route('login'));
    $this->delete(route('admin.orders.destroy', $this->order))->assertRedirect(route('login'));
});

test('regular user cannot access admin orders routes', function () {
    $regularUser = User::factory()->create();
    $regularUser->roles()->attach($this->userRole);

    $this->actingAs($regularUser)->get(route('admin.orders.index'))->assertStatus(403);
    $this->actingAs($regularUser)->get(route('admin.orders.show', $this->order))->assertStatus(403);
    $this->actingAs($regularUser)->put(route('admin.orders.update', $this->order), ['status' => 'completed'])->assertStatus(403);
    $this->actingAs($regularUser)->delete(route('admin.orders.destroy', $this->order))->assertStatus(403);
});

test('admin can see orders list with search', function () {
    $otherOrder = Order::factory()->create([
        'product_id' => $this->product->id,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertSee('Jane Smith');

    // Test Search
    $searchResponse = $this->actingAs($this->admin)->get(route('admin.orders.index', ['search' => 'Jane']));
    $searchResponse->assertOk();
    $searchResponse->assertSee('Jane Smith');
    $searchResponse->assertDontSee('John Doe');
});

test('admin can see order details page', function () {
    $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $this->order));

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertSee($this->order->address);
    $response->assertSee($this->product->title);
});

test('admin can update order status', function () {
    $response = $this->actingAs($this->admin)->put(route('admin.orders.update', $this->order), [
        'status' => 'completed',
    ]);

    $response->assertRedirect(route('admin.orders.show', $this->order));
    $response->assertSessionHas('success');

    $this->order->refresh();
    expect($this->order->status)->toBe('completed');
});

test('admin can delete an order', function () {
    $response = $this->actingAs($this->admin)->delete(route('admin.orders.destroy', $this->order));

    $response->assertRedirect(route('admin.orders.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('orders', ['id' => $this->order->id]);
});
