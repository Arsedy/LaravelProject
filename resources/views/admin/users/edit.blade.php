@extends('layouts.admin')

@section('title', 'Edit User Roles - Admin Dashboard')
@section('page-title', 'Edit User')

@section('content')
<div class="row">
  <div class="col-lg-8 mx-auto">
    <!-- Back Button -->
    <div class="mb-3">
      <a href="{{ route('admin.users.index') }}" class="btn btn-link text-decoration-none p-0 d-inline-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span>Back to users</span>
      </a>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm bg-dark text-white">
      <div class="card-header border-bottom border-secondary py-3 bg-dark">
        <h4 class="mb-0 text-white fw-bold">Edit User: <span class="text-info">{{ $user->name }}</span></h4>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
          @csrf
          @method('PUT')

          <!-- User Name -->
          <div class="mb-3">
            <label for="name" class="form-label fw-bold text-white">Full Name <span class="text-danger">*</span></label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              value="{{ old('name', $user->name) }}" 
              class="form-control bg-secondary text-white border-secondary @error('name') is-invalid @enderror" 
              placeholder="e.g. John Doe" 
              required 
              autofocus
            >
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- User Email -->
          <div class="mb-3">
            <label for="email" class="form-label fw-bold text-white">Email Address <span class="text-danger">*</span></label>
            <input 
              type="email" 
              name="email" 
              id="email" 
              value="{{ old('email', $user->email) }}" 
              class="form-control bg-secondary text-white border-secondary @error('email') is-invalid @enderror" 
              placeholder="e.g. john@example.com" 
              required
            >
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password (Optional) -->
          <div class="mb-3">
            <label for="password" class="form-label fw-bold text-white">New Password <span class="text-white-50 fs-8">(Leave blank to keep current)</span></label>
            <input 
              type="password" 
              name="password" 
              id="password" 
              class="form-control bg-secondary text-white border-secondary @error('password') is-invalid @enderror" 
              placeholder="Minimum 8 characters"
            >
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password Confirmation -->
          <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-bold text-white">Confirm New Password</label>
            <input 
              type="password" 
              name="password_confirmation" 
              id="password_confirmation" 
              class="form-control bg-secondary text-white border-secondary" 
              placeholder="Repeat new password"
            >
          </div>

          <!-- User Roles -->
          <div class="mb-4 border-top border-secondary pt-3">
            <label class="form-label fw-bold text-white d-block">User Roles <span class="text-danger">*</span></label>
            <div class="d-flex flex-column gap-2 mt-2">
              @foreach($roles as $role)
                <div class="form-check">
                  <input 
                    class="form-check-input @error('roles') is-invalid @enderror" 
                    type="checkbox" 
                    name="roles[]" 
                    value="{{ $role->id }}" 
                    id="role_{{ $role->id }}"
                    {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}
                  >
                  <label class="form-check-label text-white-50 fw-semibold" for="role_{{ $role->id }}">
                    <span class="text-white">{{ ucfirst($role->name) }}</span> - {{ $role->description }}
                  </label>
                </div>
              @endforeach
            </div>
            @error('roles')
              <div class="text-danger mt-1 fs-8">{{ $message }}</div>
            @enderror
          </div>

          <!-- Submit Buttons -->
          <div class="d-flex justify-content-end gap-2 border-top border-secondary pt-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light px-4">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-1"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
