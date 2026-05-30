@extends('layouts.admin')

@section('title', 'Categories - Admin Dashboard')
@section('page-title', 'Categories')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Header Controls -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h4 class="mb-0 text-dark fw-semibold">Manage Categories</h4>
        <div class="d-flex flex-column flex-sm-row gap-2">
          <!-- Search Form -->
          <form action="{{ route('admin.categories.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group">
              <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                class="form-control" 
                placeholder="Search title, keywords..."
                aria-label="Search categories"
              >
              <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
              </button>
              @if($search)
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-danger" title="Clear Search">
                  <i class="bi bi-x-lg"></i>
                </a>
              @endif
            </div>
          </form>
          <!-- Create Button -->
          <a href="{{ route('admin.categories.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Add Category</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4 py-3 text-secondary text-uppercase fs-7" style="width: 80px;">ID</th>
                <th class="py-3 text-secondary text-uppercase fs-7" style="width: 100px;">Image</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Title</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Parent Category</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Keywords</th>
                <th class="py-3 text-secondary text-uppercase fs-7" style="width: 120px;">Status</th>
                <th class="py-3 text-secondary text-uppercase fs-7" style="width: 150px;">Created At</th>
                <th class="pe-4 py-3 text-secondary text-uppercase text-end fs-7" style="width: 150px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($categories as $category)
                <tr>
                  <td class="ps-4 fw-medium text-secondary">#{{ $category->id }}</td>
                  <td>
                    @if($category->image)
                      <img 
                        src="{{ asset($category->image) }}" 
                        alt="{{ $category->title }}" 
                        class="img-thumbnail rounded-3 shadow-xs" 
                        style="width: 48px; height: 48px; object-fit: cover;"
                      >
                    @else
                      <div class="bg-light text-secondary rounded-3 d-flex align-items-center justify-content-center border" style="width: 48px; height: 48px;">
                        <i class="bi bi-image" style="font-size: 1.2rem;"></i>
                      </div>
                    @endif
                  </td>
                  <td>
                    <div class="fw-semibold text-dark">{{ $category->title }}</div>
                  </td>
                  <td>
                    @if($category->parent)
                      <span class="badge bg-light text-dark border fw-medium">
                        {{ $category->parent->title }}
                      </span>
                    @else
                      <span class="text-muted fs-8">None</span>
                    @endif
                  </td>
                  <td>
                    <span class="text-muted fs-8 text-truncate d-inline-block" style="max-width: 180px;" title="{{ $category->keywords }}">
                      {{ $category->keywords ?: '-' }}
                    </span>
                  </td>
                  <td>
                    @if($category->status)
                      <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill border border-success-subtle fw-semibold">
                        <i class="bi bi-check-circle-fill me-1"></i> Active
                      </span>
                    @else
                      <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 rounded-pill border border-danger-subtle fw-semibold">
                        <i class="bi bi-x-circle-fill me-1"></i> Inactive
                      </span>
                    @endif
                  </td>
                  <td class="text-secondary fs-8">
                    {{ $category->created_at->format('M d, Y') }}
                  </td>
                  <td class="pe-4 text-end">
                    <div class="d-inline-flex gap-2">
                      <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit Category">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the category \'{{ addslashes($category->title) }}\'?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Category">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                      <i class="bi bi-folder-x text-muted mb-3" style="font-size: 3rem;"></i>
                      <h5 class="text-secondary fw-semibold">No Categories Found</h5>
                      <p class="text-muted mb-3">
                        @if($search)
                          No categories match your search "{{ $search }}". Try resetting filters.
                        @else
                          Start by adding a new category to group your products.
                        @endif
                      </p>
                      @if($search)
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">Clear Search</a>
                      @else
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                          <i class="bi bi-plus-circle-fill me-2"></i>Add First Category
                        </a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($categories->hasPages())
        <div class="card-footer bg-white border-0 py-3">
          <div class="d-flex justify-content-center">
            {{ $categories->links('pagination::bootstrap-5') }}
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
