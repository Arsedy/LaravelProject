<?php

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guest can see login page', function () {
    $response = $this->get(route('login'));
    $response->assertStatus(200);
    $response->assertSee('Sign In');
});

test('guest can see register page', function () {
    $response = $this->get(route('register'));
    $response->assertStatus(200);
    $response->assertSee('Create Account');
});

test('user can register successfully', function () {
    $response = $this->post(route('register'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);
    $this->assertTrue(auth()->check());
    $this->assertEquals('John Doe', auth()->user()->name);
});

test('registration requires valid input', function () {
    $response = $this->post(route('register'), []);
    $response->assertSessionHasErrors(['name', 'email', 'password']);

    $response = $this->post(route('register'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different',
    ]);
    $response->assertSessionHasErrors(['password']);

    User::factory()->create(['email' => 'john@example.com']);
    $response = $this->post(route('register'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertSessionHasErrors(['email']);
});

test('user can login successfully', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertTrue(auth()->check());
    $this->assertEquals($user->id, auth()->id());
});

test('user cannot login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'john@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertFalse(auth()->check());
});

test('authenticated user can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));
    $this->assertFalse(auth()->check());
});

test('guest accessing admin panel is redirected to login', function () {
    $response = $this->get(route('admin.home'));
    $response->assertRedirect(route('login'));
});

test('non-admin user accessing admin panel gets aborted with 403', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.home'));
    $response->assertStatus(403);
});

test('admin user can access admin panel successfully', function () {
    $adminRole = Role::create(['name' => 'admin', 'description' => 'Admin']);
    $user = User::factory()->create();
    $user->roles()->attach($adminRole);

    $response = $this->actingAs($user)->get(route('admin.home'));
    $response->assertStatus(200);
});

test('admin user can create products and it saves their user_id', function () {
    $adminRole = Role::create(['name' => 'admin', 'description' => 'Admin']);
    $admin = User::factory()->create();
    $admin->roles()->attach($adminRole);

    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.products.store'), [
        'category_id' => $category->id,
        'title' => 'Gaming Phone',
        'keywords' => 'phone, gaming',
        'description' => 'Best gaming phone',
        'price' => '899.99',
        'stock' => '15',
        'min_stock' => '2',
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'title' => 'Gaming Phone',
        'user_id' => $admin->id,
    ]);
});
