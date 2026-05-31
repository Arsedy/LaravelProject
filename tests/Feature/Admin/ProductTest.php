<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

beforeEach(function () {
    File::deleteDirectory(public_path('uploads/products'));

    // Create admin role and user
    $adminRole = Role::create(['name' => 'admin', 'description' => 'Admin Role']);
    $this->admin = User::factory()->create();
    $this->admin->roles()->attach($adminRole);
    $this->actingAs($this->admin);
});

afterAll(function () {
    File::deleteDirectory(public_path('uploads/products'));
});

test('admin can see products list page with search', function () {
    $category = Category::factory()->create();
    Product::factory()->create([
        'title' => 'iPhone 15 Pro',
        'keywords' => 'apple, phone',
        'category_id' => $category->id,
    ]);
    Product::factory()->create([
        'title' => 'Samsung Galaxy Ultra',
        'keywords' => 'samsung, android',
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('admin.products.index'));

    $response->assertOk();
    $response->assertSee('iPhone 15 Pro');
    $response->assertSee('Samsung Galaxy Ultra');

    // Test Search
    $searchResponse = $this->get(route('admin.products.index', ['search' => 'android']));
    $searchResponse->assertOk();
    $searchResponse->assertSee('Samsung Galaxy Ultra');
    $searchResponse->assertDontSee('iPhone 15 Pro');
});

test('admin can see create product page', function () {
    $response = $this->get(route('admin.products.create'));

    $response->assertOk();
    $response->assertSee('Create New Product');
});

test('admin can create a product with image upload', function () {
    $category = Category::factory()->create();
    $file = UploadedFile::fake()->create('product.jpg', 100, 'image/jpeg');

    $response = $this->post(route('admin.products.store'), [
        'category_id' => $category->id,
        'title' => 'Gaming Laptop',
        'keywords' => 'laptop, gaming',
        'description' => 'A high-end laptop',
        'detail' => 'Specs: 32GB RAM, RTX 4080',
        'price' => '1499.99',
        'stock' => '25',
        'min_stock' => '2',
        'discount' => '10.00',
        'image' => $file,
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('products', [
        'title' => 'Gaming Laptop',
        'category_id' => $category->id,
        'price' => '1499.99',
        'stock' => 25,
        'min_stock' => 2,
        'discount' => '10.00',
        'status' => true,
    ]);

    $product = Product::where('title', 'Gaming Laptop')->first();
    $this->assertNotNull($product->image);
    $this->assertFileExists(public_path($product->image));
});

test('validation fails when required fields are missing', function () {
    $response = $this->post(route('admin.products.store'), [
        'keywords' => 'some-keywords',
    ]);

    $response->assertSessionHasErrors(['category_id', 'title', 'price', 'stock', 'min_stock', 'status']);
});

test('admin can see edit product page', function () {
    $product = Product::factory()->create();

    $response = $this->get(route('admin.products.edit', $product));

    $response->assertOk();
    $response->assertSee('Edit Product');
});

test('admin can update a product and replace image', function () {
    $product = Product::factory()->create([
        'title' => 'Old Title',
        'price' => '500.00',
    ]);

    $oldFile = UploadedFile::fake()->create('old_prod.jpg', 100, 'image/jpeg');
    $this->put(route('admin.products.update', $product), [
        'category_id' => $product->category_id,
        'title' => 'Old Title',
        'price' => '500.00',
        'stock' => '10',
        'min_stock' => '2',
        'image' => $oldFile,
        'status' => '1',
    ]);

    $product->refresh();
    $oldImagePath = $product->image;
    $this->assertFileExists(public_path($oldImagePath));

    $newFile = UploadedFile::fake()->create('new_prod.jpg', 100, 'image/jpeg');
    $response = $this->put(route('admin.products.update', $product), [
        'category_id' => $product->category_id,
        'title' => 'Updated Title',
        'price' => '450.00',
        'stock' => '8',
        'min_stock' => '2',
        'image' => $newFile,
        'status' => '0',
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $response->assertSessionHas('success');

    $product->refresh();

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'title' => 'Updated Title',
        'price' => '450.00',
        'stock' => 8,
        'status' => false,
    ]);

    $this->assertFileExists(public_path($product->image));
    $this->assertFileDoesNotExist(public_path($oldImagePath));
});

test('admin can delete a product and its image', function () {
    $product = Product::factory()->create();
    $file = UploadedFile::fake()->create('prod.png', 100, 'image/png');

    $this->put(route('admin.products.update', $product), [
        'category_id' => $product->category_id,
        'title' => $product->title,
        'price' => $product->price,
        'stock' => $product->stock,
        'min_stock' => $product->min_stock,
        'image' => $file,
        'status' => '1',
    ]);

    $product->refresh();
    $imagePath = $product->image;
    $this->assertFileExists(public_path($imagePath));

    $response = $this->delete(route('admin.products.destroy', $product));

    $response->assertRedirect(route('admin.products.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);

    $this->assertFileDoesNotExist(public_path($imagePath));
});
