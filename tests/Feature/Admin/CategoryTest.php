<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

beforeEach(function () {
    File::deleteDirectory(public_path('uploads/categories'));
});

afterAll(function () {
    File::deleteDirectory(public_path('uploads/categories'));
});

test('admin can see categories list page with search', function () {
    Category::factory()->create([
        'title' => 'Electronics',
        'keywords' => 'electronics, gadgets',
    ]);
    Category::factory()->create([
        'title' => 'Clothing',
        'keywords' => 'apparel, garments',
    ]);

    $response = $this->get(route('admin.categories.index'));

    $response->assertOk();
    $response->assertSee('Electronics');
    $response->assertSee('Clothing');

    // Test Search
    $searchResponse = $this->get(route('admin.categories.index', ['search' => 'gadgets']));
    $searchResponse->assertOk();
    $searchResponse->assertSee('Electronics');
    $searchResponse->assertDontSee('Clothing');
});

test('admin can see create category page', function () {
    $response = $this->get(route('admin.categories.create'));

    $response->assertOk();
    $response->assertSee('Create New Category');
});

test('admin can create a category with image upload', function () {
    $parent = Category::factory()->create(['title' => 'Top Category']);
    $file = UploadedFile::fake()->create('category.jpg', 100, 'image/jpeg');

    $response = $this->post(route('admin.categories.store'), [
        'title' => 'New Category',
        'parent_id' => $parent->id,
        'keywords' => 'new, phone',
        'description' => 'Description here',
        'image' => $file,
        'status' => '1',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('categories', [
        'title' => 'New Category',
        'parent_id' => $parent->id,
        'keywords' => 'new, phone',
        'description' => 'Description here',
        'status' => true,
    ]);

    $category = Category::where('title', 'New Category')->first();
    $this->assertNotNull($category->image);
    $this->assertFileExists(public_path($category->image));
});

test('validation fails when title is missing', function () {
    $response = $this->post(route('admin.categories.store'), [
        'keywords' => 'some-keywords',
        'status' => '1',
    ]);

    $response->assertSessionHasErrors(['title']);
});

test('validation fails when parent_id does not exist', function () {
    $response = $this->post(route('admin.categories.store'), [
        'title' => 'Category',
        'parent_id' => 999,
        'status' => '1',
    ]);

    $response->assertSessionHasErrors(['parent_id']);
});

test('admin can see edit category page', function () {
    $category = Category::factory()->create();

    $response = $this->get(route('admin.categories.edit', $category));

    $response->assertOk();
    $response->assertSee('Edit Category');
});

test('admin can update a category and replace image', function () {
    $category = Category::factory()->create([
        'title' => 'Old Title',
        'keywords' => 'old',
    ]);

    $oldFile = UploadedFile::fake()->create('old_image.jpg', 100, 'image/jpeg');
    $this->put(route('admin.categories.update', $category), [
        'title' => 'Old Title',
        'image' => $oldFile,
        'status' => '1',
    ]);

    $category->refresh();
    $oldImagePath = $category->image;
    $this->assertFileExists(public_path($oldImagePath));

    $newFile = UploadedFile::fake()->create('new_image.jpg', 100, 'image/jpeg');
    $response = $this->put(route('admin.categories.update', $category), [
        'title' => 'Updated Title',
        'keywords' => 'updated',
        'image' => $newFile,
        'status' => '0',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $response->assertSessionHas('success');

    $category->refresh();

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'title' => 'Updated Title',
        'keywords' => 'updated',
        'status' => false,
    ]);

    $this->assertFileExists(public_path($category->image));
    $this->assertFileDoesNotExist(public_path($oldImagePath));
});

test('admin can delete a category and its image', function () {
    $category = Category::factory()->create();
    $file = UploadedFile::fake()->create('cat.png', 100, 'image/png');

    $this->put(route('admin.categories.update', $category), [
        'title' => $category->title,
        'image' => $file,
        'status' => '1',
    ]);

    $category->refresh();
    $imagePath = $category->image;
    $this->assertFileExists(public_path($imagePath));

    $response = $this->delete(route('admin.categories.destroy', $category));

    $response->assertRedirect(route('admin.categories.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);

    $this->assertFileDoesNotExist(public_path($imagePath));
});
