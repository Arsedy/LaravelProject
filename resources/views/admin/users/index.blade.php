@extends('layouts.admin')

@section('title', 'Users - Admin Dashboard')
@section('page-title', 'Users')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Header Controls -->
    <div class="card mb-4 border-0 shadow-sm bg-dark text-white">
      <div class="card-header bg-dark border-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h4 class="mb-0 text-white fw-bold">Manage Users</h4>
        <div class="d-flex flex-column flex-sm-row gap-2">
          <!-- Search Form -->
          <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2">
            <div class="input-group">
              <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                class="form-control bg-secondary text-white border-secondary" 
                placeholder="Search name, email..."
                aria-label="Search users"
              >
              <button class="btn btn-outline-light border-secondary bg-secondary" type="submit">
                <i class="bi bi-search text-white"></i>
              </button>
              @if($search)
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger" title="Clear Search">
                  <i class="bi bi-x-lg"></i>
                </a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm bg-dark text-white">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0 table-dark">
            <thead class="table-dark border-bottom border-secondary">
              <tr>
                <th class="ps-4 py-3 text-secondary text-uppercase fs-7" style="width: 80px;">ID</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Name</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Email</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Roles</th>
                <th class="py-3 text-secondary text-uppercase fs-7" style="width: 150px;">Created At</th>
                <th class="pe-4 py-3 text-secondary text-uppercase text-end fs-7" style="width: 150px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr class="border-bottom border-secondary">
                  <td class="ps-4 fw-bold text-white">#{{ $user->id }}</td>
                  <td>
                    <div class="fw-bold text-white">{{ $user->name }}</div>
                  </td>
                  <td>
                    <span class="text-white-50 fw-semibold fs-8">{{ $user->email }}</span>
                  </td>
                  <td>
                    <div class="d-flex flex-wrap gap-1">
                      @forelse($user->roles as $role)
                        @if($role->name === 'admin')
                          <span class="badge bg-danger text-white border border-danger fw-semibold px-2 py-1">
                            <i class="bi bi-shield-lock-fill me-1"></i>Admin
                          </span>
                        @else
                          <span class="badge bg-primary text-white border border-primary fw-semibold px-2 py-1">
                            <i class="bi bi-person-fill me-1"></i>User
                          </span>
                        @endif
                      @empty
                        <span class="text-white-50 fw-semibold fs-8">No roles assigned</span>
                      @endforelse
                    </div>
                  </td>
                  <td class="text-white-50 fw-semibold fs-8">
                    {{ $user->created_at->format('M d, Y') }}
                  </td>
                  <td class="pe-4 text-end">
                    <div class="d-inline-flex gap-2">
                      <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit User Roles">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the user \'{{ addslashes($user->name) }}\'?');" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete User">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <div class="d-flex flex-column align-items-center justify-content-center py-4 bg-dark">
                      <i class="bi bi-people text-muted mb-3" style="font-size: 3rem;"></i>
                      <h5 class="text-secondary fw-semibold">No Users Found</h5>
                      <p class="text-muted mb-3">
                        @if($search)
                          No users match your search "{{ $search }}". Try resetting search filter.
                        @else
                          No registered users found in database.
                        @endif
                      </p>
                      @if($search)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Clear Search</a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($users->hasPages())
        <div class="card-footer bg-dark border-0 py-3">
          <div class="d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-5') }}
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
