@extends('layouts.home')

@section('title', 'Checkout - Electro')

@section('content')
		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<h3 class="breadcrumb-header">Checkout</h3>
						<ul class="breadcrumb-tree">
							<li><a href="{{ route('home') }}">Home</a></li>
							<li class="active">Checkout</li>
						</ul>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /BREADCRUMB -->

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

				<form action="{{ route('orders.store') }}" method="POST">
					@csrf
					<div class="row">

						<div class="col-md-7">
							<!-- Billing Details -->
							<div class="billing-details">
								<div class="section-title">
									<h3 class="title">Billing address</h3>
								</div>

								@if ($errors->any())
									<div class="alert alert-danger" style="margin-bottom: 20px;">
										<ul style="margin: 0; padding-left: 15px;">
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								@endif

								<div class="form-group">
									<label class="control-label" for="name" style="margin-bottom: 5px; font-weight: 500;">Full Name <span class="text-danger">*</span></label>
									<input class="input" type="text" id="name" name="name" placeholder="Full Name" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" required>
								</div>
								<div class="form-group">
									<label class="control-label" for="email" style="margin-bottom: 5px; font-weight: 500;">Email Address <span class="text-danger">*</span></label>
									<input class="input" type="email" id="email" name="email" placeholder="Email Address" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" required>
								</div>
								<div class="form-group">
									<label class="control-label" for="address" style="margin-bottom: 5px; font-weight: 500;">Address <span class="text-danger">*</span></label>
									<input class="input" type="text" id="address" name="address" placeholder="Delivery Address" value="{{ old('address') }}" required>
								</div>
								<div class="form-group">
									<label class="control-label" for="telephone" style="margin-bottom: 5px; font-weight: 500;">Telephone <span class="text-danger">*</span></label>
									<input class="input" type="tel" id="telephone" name="telephone" placeholder="Telephone Number" value="{{ old('telephone') }}" required>
								</div>
							</div>
							<!-- /Billing Details -->
						</div>

						<!-- Order Details -->
						<div class="col-md-5 order-details">
							<div class="section-title text-center">
								<h3 class="title">Your Order</h3>
							</div>
							<div class="order-summary">
								<div class="order-col">
									<div><strong>PRODUCT</strong></div>
									<div><strong>TOTAL</strong></div>
								</div>
								<div class="order-products">
									@if(isset($product) && $product)
										<div class="order-col">
											<div>{{ $quantity }}x {{ $product->title }}</div>
											<div>
												@php
													$unitPrice = $product->price;
													if ($product->discount > 0) {
														$unitPrice = $product->price - ($product->price * ($product->discount / 100));
													}
													$total = $unitPrice * $quantity;
												@endphp
												${{ number_format($total, 2) }}
											</div>
										</div>
									@else
										<div class="order-col">
											<div>No product selected</div>
											<div>$0.00</div>
										</div>
									@endif
								</div>
								<div class="order-col">
									<div>Shipping</div>
									<div><strong>FREE</strong></div>
								</div>
								<div class="order-col">
									<div><strong>TOTAL</strong></div>
									<div><strong class="order-total">${{ number_format($total ?? 0, 2) }}</strong></div>
								</div>
							</div>
							
							<div class="payment-method" style="margin-top: 25px;">
								<div class="input-radio">
									<input type="radio" name="payment" id="payment-1" checked>
									<label for="payment-1">
										<span></span>
										Cash on Delivery
									</label>
									<div class="caption">
										<p>Pay with cash upon delivery of your products.</p>
									</div>
								</div>
							</div>

							@if(isset($product) && $product)
								<input type="hidden" name="product_id" value="{{ $product->id }}">
								<input type="hidden" name="quantity" value="{{ $quantity }}">
								<button type="submit" class="primary-btn order-submit" style="width: 100%; border: none;">Place order</button>
							@else
								<button type="button" class="primary-btn order-submit" style="width: 100%; border: none;" disabled>No Product Selected</button>
							@endif
						</div>
						<!-- /Order Details -->
					</div>
				</form>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->
@endsection

@section('scripts')
<script>
    @if(isset($product) && $product)
		@php
			$unitPrice = $product->price;
			if ($product->discount > 0) {
				$unitPrice = $product->price - ($product->price * ($product->discount / 100));
			}
		@endphp
        window.checkoutProduct = {
            id: {{ $product->id }},
            title: {!! json_encode($product->title) !!},
            price: {{ $unitPrice }},
            image: "{{ $product->image ? asset($product->image) : asset('frontend-assets/img/product01.png') }}",
            quantity: {{ $quantity }}
        };
    @endif
</script>
@endsection
