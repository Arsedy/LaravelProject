@extends('layouts.admin')

@section('title', 'Add Product - Admin Dashboard')
@section('page-title', 'Add Product')

@section('content')
<div class="row">
  <div class="col-lg-10 mx-auto">
    <!-- Back Button -->
    <div class="mb-3">
      <a href="{{ route('admin.products.index') }}" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Back to products</span>
      </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm bg-dark text-white">
      <div class="card-header border-bottom border-secondary py-3 bg-dark">
        <h4 class="mb-0 text-white fw-bold">Create New Product</h4>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
          @csrf

          <div class="row">
            <!-- Left Column: Title, Category, Keywords, Detail, Description -->
            <div class="col-md-7 border-end border-secondary pe-md-4">
              <!-- Category select -->
              <div class="mb-3">
                <label for="category_id" class="form-label fw-bold text-white">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" class="form-select bg-secondary text-white border-secondary @error('category_id') is-invalid @enderror" required>
                  <option value="" class="bg-dark">Select Category</option>
                  @foreach($categories as $category)
                    <option value="{{ $category->id }}" class="bg-dark" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                      {{ $category->title }}
                    </option>
                  @endforeach
                </select>
                @error('category_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Title -->
              <div class="mb-3">
                <label for="title" class="form-label fw-bold text-white">Product Title <span class="text-danger">*</span></label>
                <input 
                  type="text" 
                  name="title" 
                  id="title" 
                  value="{{ old('title') }}" 
                  class="form-control bg-secondary text-white border-secondary @error('title') is-invalid @enderror" 
                  placeholder="e.g. iPhone 15 Pro Max" 
                  required 
                  autofocus
                >
                @error('title')
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
                  placeholder="e.g. mobile, apple, smart phone" 
                >
                @error('keywords')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Description -->
              <div class="mb-3">
                <label for="description" class="form-label fw-bold text-white">Short Description</label>
                <textarea 
                  name="description" 
                  id="description" 
                  rows="3" 
                  class="form-control bg-secondary text-white border-secondary @error('description') is-invalid @enderror" 
                  placeholder="Brief summary of the product..."
                >{{ old('description') }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Detail -->
              <div class="mb-3">
                <label for="detail" class="form-label fw-bold text-white">Product Detail Specifications</label>
                <textarea 
                  name="detail" 
                  id="detail" 
                  rows="6" 
                  class="form-control bg-secondary text-white border-secondary @error('detail') is-invalid @enderror" 
                  placeholder="Technical specifications, dimensions, features, etc..."
                >{{ old('detail') }}</textarea>
                @error('detail')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <!-- Right Column: Price, Stock, Image, Status -->
            <div class="col-md-5 ps-md-4">
              <!-- Price -->
              <div class="mb-3">
                <label for="price" class="form-label fw-bold text-white">Price ($) <span class="text-danger">*</span></label>
                <input 
                  type="number" 
                  name="price" 
                  id="price" 
                  value="{{ old('price') }}" 
                  step="0.01" 
                  min="0" 
                  class="form-control bg-secondary text-white border-secondary @error('price') is-invalid @enderror" 
                  placeholder="e.g. 999.99" 
                  required
                >
                @error('price')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Discount -->
              <div class="mb-3">
                <label for="discount" class="form-label fw-bold text-white">Discount (%)</label>
                <input 
                  type="number" 
                  name="discount" 
                  id="discount" 
                  value="{{ old('discount', 0) }}" 
                  step="0.01" 
                  min="0" 
                  max="100" 
                  class="form-control bg-secondary text-white border-secondary @error('discount') is-invalid @enderror" 
                  placeholder="e.g. 10" 
                >
                @error('discount')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Stock & Min Stock Row -->
              <div class="row mb-3">
                <div class="col-6">
                  <label for="stock" class="form-label fw-bold text-white">Stock <span class="text-danger">*</span></label>
                  <input 
                    type="number" 
                    name="stock" 
                    id="stock" 
                    value="{{ old('stock', 0) }}" 
                    min="0" 
                    class="form-control bg-secondary text-white border-secondary @error('stock') is-invalid @enderror" 
                    required
                  >
                  @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-6">
                  <label for="min_stock" class="form-label fw-bold text-white">Min. Stock <span class="text-danger">*</span></label>
                  <input 
                    type="number" 
                    name="min_stock" 
                    id="min_stock" 
                    value="{{ old('min_stock', 5) }}" 
                    min="0" 
                    class="form-control bg-secondary text-white border-secondary @error('min_stock') is-invalid @enderror" 
                    required
                  >
                  @error('min_stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Image Upload -->
              <div class="mb-3">
                <label for="image" class="form-label fw-bold text-white">Product Image</label>
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
              </div>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="d-flex justify-content-end gap-2 border-top border-secondary pt-3 mt-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light px-4">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-1"></i> Save Product
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
