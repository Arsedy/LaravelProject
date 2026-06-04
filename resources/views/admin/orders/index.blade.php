@extends('layouts.admin')

@section('title', 'Orders - Admin Dashboard')
@section('page-title', 'Orders')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Header Controls -->
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h4 class="mb-0 text-dark fw-semibold">Manage Orders</h4>
        <div class="d-flex flex-column flex-sm-row gap-2">
          <!-- Status Filter -->
          <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-2">
            <select name="status" class="form-select text-white bg-dark border-secondary" style="width: auto;">
              <option value="">All Statuses</option>
              @foreach($statuses as $item)
                <option value="{{ $item }}" {{ $status === $item ? 'selected' : '' }}>
                  {{ $item }}
                </option>
              @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Filter</button>
            @if($status)
              <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-danger" title="Clear Filter">
                <i class="bi bi-x-lg"></i>
              </a>
            @endif
          </form>
        </div>
      </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4 py-3 text-secondary text-uppercase fs-7" style="width: 80px;">ID</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Customer</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Products</th>
                <th class="py-3 text-secondary text-uppercase fs-7">Total</th>
                <th class="py-3 text-secondary text-uppercase fs-7" style="width: 150px;">Status</th>
                <th class="py-3 text-secondary text-uppercase fs-7" style="width: 150px;">Date</th>
                <th class="pe-4 py-3 text-secondary text-uppercase text-end fs-7" style="width: 150px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
                <tr>
                  <td class="ps-4 fw-bold text-white">#{{ $order->id }}</td>
                  <td>
                    <div class="fw-bold text-white">{{ $order->name }}</div>
                    <div class="text-white-50 fs-8">{{ $order->email }}</div>
                    <div class="text-white-50 fs-8">{{ $order->phone }}</div>
                  </td>
                  <td>
                    @forelse($order->items as $item)
                      <div class="fw-semibold text-white">{{ $item->quantity }}x {{ $item->product_title }}</div>
                    @empty
                      <span class="text-danger fw-semibold fs-8">No Items</span>
                    @endforelse
                  </td>
                  <td>
                    <div class="fw-bold text-white">${{ number_format($order->total, 2) }}</div>
                  </td>
                  <td>
                    @if($order->status === 'New')
                      <span class="badge bg-primary px-2.5 py-1.5 rounded-pill border border-primary fw-semibold text-uppercase text-white">
                        New
                      </span>
                    @elseif($order->status === 'Accepted')
                      <span class="badge bg-info px-2.5 py-1.5 rounded-pill border border-info fw-semibold text-uppercase text-white">
                        Accepted
                      </span>
                    @elseif($order->status === 'Onshipping')
                      <span class="badge bg-warning px-2.5 py-1.5 rounded-pill border border-warning fw-semibold text-uppercase text-white">
                        Onshipping
                      </span>
                    @elseif($order->status === 'Completed')
                      <span class="badge bg-success px-2.5 py-1.5 rounded-pill border border-success fw-semibold text-uppercase text-white">
                        Completed
                      </span>
                    @elseif($order->status === 'Cancelled')
                      <span class="badge bg-danger px-2.5 py-1.5 rounded-pill border border-danger fw-semibold text-uppercase text-white">
                        Cancelled
                      </span>
                    @else
                      <span class="badge bg-secondary px-2.5 py-1.5 rounded-pill border border-secondary fw-semibold text-uppercase text-white">
                        {{ $order->status }}
                      </span>
                    @endif
                  </td>
                  <td class="text-white-50 fw-semibold fs-8">
                    {{ $order->created_at->format('M d, Y H:i') }}
                  </td>
                  <td class="pe-4 text-end">
                    <div class="d-inline-flex gap-2">
                      <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info" title="View Order Details">
                        <i class="bi bi-eye"></i>
                      </a>
                      <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->id }}?');" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Order">
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
                      <i class="bi bi-cart-x text-muted mb-3" style="font-size: 3rem;"></i>
                      <h5 class="text-secondary fw-semibold">No Orders Found</h5>
                      <p class="text-muted mb-3">
                        @if($search)
                          No orders match your search "{{ $search }}".
                        @else
                          There are currently no orders placed on the store.
                        @endif
                      </p>
                      @if($search)
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Clear Search</a>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($orders->hasPages())
        <div class="card-footer bg-white border-0 py-3">
          <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
