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
              <th class="ps-0 text-white-50">Phone:</th>
              <td class="text-white">{{ $order->phone }}</td>
            </tr>
            <tr>
              <th class="ps-0 text-white-50">Delivery Address:</th>
              <td class="text-white font-monospace">{{ $order->address }}</td>
            </tr>
            @if($order->city)
              <tr>
                <th class="ps-0 text-white-50">City:</th>
                <td class="text-white">{{ $order->city }}</td>
              </tr>
            @endif
            @if($order->country)
              <tr>
                <th class="ps-0 text-white-50">Country:</th>
                <td class="text-white">{{ $order->country }}</td>
              </tr>
            @endif
            @if($order->zip_code)
              <tr>
                <th class="ps-0 text-white-50">ZIP Code:</th>
                <td class="text-white font-monospace">{{ $order->zip_code }}</td>
              </tr>
            @endif
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
              @foreach($statuses as $status)
                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
              @endforeach
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
        <div class="table-responsive">
          <table class="table table-borderless align-middle mb-0">
            <thead>
              <tr class="border-bottom border-secondary-subtle">
                <th class="text-secondary text-uppercase fs-8 ps-0">Product</th>
                <th class="text-center text-secondary text-uppercase fs-8" style="width: 100px;">Price</th>
                <th class="text-center text-secondary text-uppercase fs-8" style="width: 80px;">Qty</th>
                <th class="text-end text-secondary text-uppercase fs-8 pe-0" style="width: 120px;">Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse($order->items as $item)
                <tr class="border-bottom border-dark-subtle">
                  <td class="ps-0 py-3">
                    <div class="fw-semibold text-white">{{ $item->product_title }}</div>
                    @if($item->product && $item->product->category)
                      <div class="text-white-50 fs-8">{{ $item->product->category->title }}</div>
                    @endif
                  </td>
                  <td class="text-center text-white">${{ number_format($item->price, 2) }}</td>
                  <td class="text-center text-white fw-bold">{{ $item->quantity }}</td>
                  <td class="text-end text-white fw-bold pe-0">${{ number_format($item->total, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-white-50 py-3">No Items found for this order.</td>
                </tr>
              @endforelse
              
              <tr>
                <td colspan="2" class="border-0"></td>
                <td class="text-white-50 ps-0 pt-3">Subtotal:</td>
                <td class="text-end text-white pt-3 pe-0">${{ number_format($order->subtotal, 2) }}</td>
              </tr>
              <tr>
                <td colspan="2" class="border-0"></td>
                <td class="text-white-50 ps-0">Shipping:</td>
                <td class="text-end text-white pe-0">${{ number_format($order->shipping_price, 2) }}</td>
              </tr>
              <tr class="border-top border-secondary-subtle">
                <td colspan="2" class="border-0"></td>
                <td class="ps-0 fs-5 text-white fw-bold">Grand Total:</td>
                <td class="text-end fs-5 text-white fw-bold pe-0">${{ number_format($order->total, 2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
