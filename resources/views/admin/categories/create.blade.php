@extends('layouts.admin')

@section('title', 'Add Category - Admin Dashboard')
@section('page-title', 'Add Category')

@section('content')
<div class="row">
  <div class="col-lg-8 mx-auto">
    <!-- Back Button -->
    <div class="mb-3">
      <a href="{{ route('admin.categories.index') }}" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Back to categories</span>
      </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm bg-dark text-white">
      <div class="card-header border-bottom border-secondary py-3 bg-dark">
        <h4 class="mb-0 text-white fw-bold">Create New Category</h4>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
          @csrf

          <!-- Category Title -->
          <div class="mb-3">
            <label for="title" class="form-label fw-bold text-white">Category Title <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="title" 
              id="title" 
              value="{{ old('title') }}" 
              class="form-control bg-secondary text-white border-secondary @error('title') is-invalid @enderror" 
              placeholder="e.g. Mobile Phones" 
              required 
              autofocus
            >
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Parent Category -->
          <div class="mb-3">
            <label for="parent_id" class="form-label fw-bold text-white">Parent Category</label>
            <select name="parent_id" id="parent_id" class="form-select bg-secondary text-white border-secondary @error('parent_id') is-invalid @enderror">
              <option value="" class="bg-dark">None (Top Level Category)</option>
              @foreach($parentCategories as $parent)
                <option value="{{ $parent->id }}" class="bg-dark" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                  {{ $parent->title }}
                </option>
              @endforeach
            </select>
            @error('parent_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Keywords -->
          <div class="mb-3">
            <label for="keywords" class="form-label fw-bold text-white">Keywords (SEO)</label>
            <input 
              type="text" 
              name="keywords" 
              id="keywords" 
              value="{{ old('keywords') }}" 
              class="form-control bg-secondary text-white border-secondary @error('keywords') is-invalid @enderror" 
              placeholder="e.g. phones, smartphones, Apple, Samsung" 
            >
            @error('keywords')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text text-white-50 fs-8">Comma-separated search keywords for SEO optimization.</div>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label for="description" class="form-label fw-bold text-white">Description</label>
            <textarea 
              name="description" 
              id="description" 
              rows="4" 
              class="form-control bg-secondary text-white border-secondary @error('description') is-invalid @enderror" 
              placeholder="Describe what kind of products are in this category..."
            >{{ old('description') }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Image Upload with JS Preview -->
          <div class="mb-3">
            <label for="image" class="form-label fw-bold text-white">Category Image</label>
            <input 
              type="file" 
              name="image" 
              id="image" 
              class="form-control bg-secondary text-white border-secondary @error('image') is-invalid @enderror" 
              accept="image/*"
            >
            @error('image')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!-- Image Preview Box -->
            <div class="mt-3 d-none" id="previewContainer">
              <label class="form-label text-white-50 fs-8">Image Preview:</label>
              <div>
                <img id="imagePreview" src="#" alt="Preview" class="img-thumbnail rounded" style="max-height: 150px; object-fit: cover;">
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="mb-4">
            <label for="status" class="form-label fw-bold text-white">Status</label>
            <select name="status" id="status" class="form-select bg-secondary text-white border-secondary @error('status') is-invalid @enderror">
              <option value="1" class="bg-dark" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
              <option value="0" class="bg-dark" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text text-white-50 fs-8">
              Inactive categories and their products will be hidden from the front-end store.
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="d-flex justify-content-end gap-2 border-top border-secondary pt-3">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light px-4">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-1"></i> Save Category
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          previewContainer.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
      } else {
        previewContainer.classList.add('d-none');
        imagePreview.src = '#';
      }
    });
  });
</script>
@endsection
