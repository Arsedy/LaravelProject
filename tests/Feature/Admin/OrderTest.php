<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
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

    // Create categories, products and order
    $this->category = Category::factory()->create();
    $this->product = Product::factory()->create(['category_id' => $this->category->id]);
    $this->order = Order::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '555-4321',
        'status' => 'New',
    ]);

    $this->orderItem = OrderItem::create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'product_title' => $this->product->title,
        'price' => $this->product->price,
        'quantity' => 1,
        'total' => $this->product->price,
    ]);
});

test('guest cannot access admin orders routes', function () {
    $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
    $this->get(route('admin.orders.show', $this->order))->assertRedirect(route('login'));
    $this->put(route('admin.orders.update', $this->order), ['status' => 'Completed'])->assertRedirect(route('login'));
    $this->delete(route('admin.orders.destroy', $this->order))->assertRedirect(route('login'));
});

test('regular user cannot access admin orders routes', function () {
    $regularUser = User::factory()->create();
    $regularUser->roles()->attach($this->userRole);

    $this->actingAs($regularUser)->get(route('admin.orders.index'))->assertStatus(403);
    $this->actingAs($regularUser)->get(route('admin.orders.show', $this->order))->assertStatus(403);
    $this->actingAs($regularUser)->put(route('admin.orders.update', $this->order), ['status' => 'Completed'])->assertStatus(403);
    $this->actingAs($regularUser)->delete(route('admin.orders.destroy', $this->order))->assertStatus(403);
});

test('admin can see orders list with status filter', function () {
    $acceptedOrder = Order::factory()->create([
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'status' => 'Accepted',
    ]);

    OrderItem::create([
        'order_id' => $acceptedOrder->id,
        'product_id' => $this->product->id,
        'product_title' => $this->product->title,
        'price' => $this->product->price,
        'quantity' => 1,
        'total' => $this->product->price,
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.orders.index'));

    $response->assertOk();
    $response->assertSee('John Doe');
    $response->assertSee('Jane Smith');

    // Test Status Filter
    $filterResponse = $this->actingAs($this->admin)->get(route('admin.orders.index', ['status' => 'Accepted']));
    $filterResponse->assertOk();
    $filterResponse->assertSee('Jane Smith');
    $filterResponse->assertDontSee('John Doe');
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
        'status' => 'Completed',
    ]);

    $response->assertRedirect(route('admin.orders.show', $this->order));
    $response->assertSessionHas('success');

    $this->order->refresh();
    expect($this->order->status)->toBe('Completed');
});

test('admin can delete an order', function () {
    $response = $this->actingAs($this->admin)->delete(route('admin.orders.destroy', $this->order));

    $response->assertRedirect(route('admin.orders.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('orders', ['id' => $this->order->id]);
});
