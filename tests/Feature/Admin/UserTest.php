<?php

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
});

test('guest cannot access users index page', function () {
    $response = $this->get(route('admin.users.index'));
    $response->assertRedirect(route('login'));
});

test('regular user cannot access users index page', function () {
    $regularUser = User::factory()->create();
    $regularUser->roles()->attach($this->userRole);

    $response = $this->actingAs($regularUser)->get(route('admin.users.index'));
    $response->assertStatus(403);
});

test('admin can see users index page with search', function () {
    $user1 = User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
    $user2 = User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);

    $response = $this->actingAs($this->admin)->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertSee('Alice');
    $response->assertSee('Bob');

    // Test search
    $searchResponse = $this->actingAs($this->admin)->get(route('admin.users.index', ['search' => 'Alice']));
    $searchResponse->assertOk();
    $searchResponse->assertSee('Alice');
    $searchResponse->assertDontSee('Bob');
});

test('admin can see edit user page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->admin)->get(route('admin.users.edit', $user));

    $response->assertOk();
    $response->assertSee($user->name);
});

test('admin can update user details and roles', function () {
    $user = User::factory()->create();
    $user->roles()->attach($this->userRole);

    $response = $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'roles' => [$this->adminRole->id],
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success');

    $user->refresh();
    expect($user->name)->toBe('Updated Name')
        ->and($user->email)->toBe('updated@example.com')
        ->and($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasRole('user'))->toBeFalse();
});

test('admin cannot delete themselves', function () {
    $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
});

test('admin can delete other users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $user));

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
