@extends('layouts.admin')

@section('title', 'Order Details - Admin Dashboard')
@section('page-title', 'Order #' . $order->id)

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
      <i class="bi bi-arrow-left"></i>
      <span>Back to Orders</span>
    </a>
  </div>
</div>

<div class="row">
  <!-- Customer & Order details column -->
  <div class="col-md-6 col-12">
    <div class="card mb-4 border-0 shadow-sm">
      <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 text-dark fw-bold">Customer Details</h5>
      </div>
      <div class="card-body">
        <table class="table table-borderless align-middle">
          <tbody>
            <tr>
              <th class="ps-0 text-white-50" style="width: 150px;">Customer Name:</th>
              <td class="text-white fw-bold">{{ $order->name }}</td>
            </tr>
            <tr>
              <th class="ps-0 text-white-50">Email Address:</th>
              <td class="text-white">{{ $order->email }}</td>
            </tr>
            <tr>
              <th class="ps-0 text-white-50">Telephone:</th>
              <td class="text-white">{{ $order->telephone }}</td>
            </tr>
            <tr>
              <th class="ps-0 text-white-50">Delivery Address:</th>
              <td class="text-white font-monospace">{{ $order->address }}</td>
            </tr>
            <tr>
              <th class="ps-0 text-white-50">Account Status:</th>
              <td>
                @if($order->user)
                  <span class="badge bg-primary text-white border border-primary fw-semibold">
                    <i class="bi bi-person-fill"></i> Registered User (ID: #{{ $order->user->id }})
                  </span>
                @else
                  <span class="badge bg-secondary text-white border border-secondary fw-semibold">
                    <i class="bi bi-person-dash-fill"></i> Guest Customer
                  </span>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Status modification form -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 text-dark fw-bold">Update Order Status</h5>
      </div>
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success border-0 shadow-sm mb-3">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
          </div>
        @endif

        <form action="{{ route('admin.orders.update', $order) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label for="status" class="form-label text-white-50">Order Status</label>
            <select name="status" id="status" class="form-select text-white bg-dark border-secondary">
              <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
              <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
              <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
              <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
          </div>
          
          <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-save-fill"></i>
            <span>Save Status</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Product item breakdown column -->
  <div class="col-md-6 col-12">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 text-dark fw-bold">Order Items</h5>
      </div>
      <div class="card-body">
        @if($order->product)
          <div class="d-flex align-items-center mb-4 p-3 bg-dark-subtle rounded-3 border border-secondary-subtle">
            <div class="me-3">
              @if($order->product->image)
                <img 
                  src="{{ asset($order->product->image) }}" 
                  alt="{{ $order->product->title }}" 
                  class="img-thumbnail rounded-3 shadow-sm" 
                  style="width: 80px; height: 80px; object-fit: cover;"
                >
              @else
                <div class="bg-dark text-white-50 rounded-3 d-flex align-items-center justify-content-center border" style="width: 80px; height: 80px;">
                  <i class="bi bi-box" style="font-size: 2rem;"></i>
                </div>
              @endif
            </div>
            <div class="flex-grow-1">
              <h6 class="mb-1 text-white fw-bold">{{ $order->product->title }}</h6>
              <p class="mb-0 text-white-50 fs-8">Category: {{ $order->product->category->title ?? 'General' }}</p>
              @if($order->product->discount > 0)
                <p class="mb-0 text-danger fs-8 fw-semibold">Discount: {{ $order->product->discount }}% Off</p>
              @endif
            </div>
          </div>

          <table class="table table-borderless">
            <tbody>
              <tr>
                <td class="text-white-50 ps-0">Unit Price:</td>
                <td class="text-end text-white">
                  @php
                    $unitPrice = $order->product->price;
                    if ($order->product->discount > 0) {
                      $unitPrice = $order->product->price - ($order->product->price * ($order->product->discount / 100));
                    }
                  @endphp
                  ${{ number_format($unitPrice, 2) }}
                  @if($order->product->discount > 0)
                    <del class="text-muted fs-8">${{ number_format($order->product->price, 2) }}</del>
                  @endif
                </td>
              </tr>
              <tr>
                <td class="text-white-50 ps-0">Quantity ordered:</td>
                <td class="text-end text-white fw-bold">{{ $order->quantity }}</td>
              </tr>
              <tr class="border-top border-secondary-subtle">
                <td class="ps-0 fs-5 text-white fw-bold">Grand Total:</td>
                <td class="text-end fs-5 text-white fw-bold">${{ number_format($order->total, 2) }}</td>
              </tr>
            </tbody>
          </table>
        @else
          <div class="alert alert-danger mb-0">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> The product associated with this order has been deleted.
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
