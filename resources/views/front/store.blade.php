@extends('layouts.home')

@section('title', 'Store - Electro')

@section('content')
		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb-tree">
							<li><a href="{{ route('home') }}">Home</a></li>
							<li><a href="{{ route('store') }}">All Categories</a></li>
							<li><a href="#">Accessories</a></li>
							<li class="active">Headphones (227,490 Results)</li>
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
					<!-- ASIDE -->
					<div id="aside" class="col-md-3">
						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Categories</h3>
							<div class="checkbox-filter">
								@foreach($categories as $cat)
									@php
										$count = $cat->products()->where('status', true)->count();
									@endphp
									<div class="input-checkbox">
										<input type="checkbox" id="category-{{ $cat->id }}" class="category-filter-checkbox" value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'checked' : '' }} onchange="window.location.href='{{ route('store') }}?category_id=' + (this.checked ? this.value : '') + '&search={{ request('search') }}'">
										<label for="category-{{ $cat->id }}">
											<span></span>
											{{ $cat->title }}
											<small>({{ $count }})</small>
										</label>
									</div>
								@endforeach
							</div>
						</div>
						<!-- /aside Widget -->

						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Price</h3>
							<div class="price-filter">
								<div id="price-slider"></div>
								<div class="input-number price-min">
									<input id="price-min" type="number">
									<span class="qty-up">+</span>
									<span class="qty-down">-</span>
								</div>
								<span>-</span>
								<div class="input-number price-max">
									<input id="price-max" type="number">
									<span class="qty-up">+</span>
									<span class="qty-down">-</span>
								</div>
							</div>
						</div>
						<!-- /aside Widget -->

						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Brand</h3>
							<div class="checkbox-filter">
								<div class="input-checkbox">
									<input type="checkbox" id="brand-1">
									<label for="brand-1">
										<span></span>
										SAMSUNG
										<small>(578)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-2">
									<label for="brand-2">
										<span></span>
										LG
										<small>(125)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-3">
									<label for="brand-3">
										<span></span>
										SONY
										<small>(755)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-4">
									<label for="brand-4">
										<span></span>
										SAMSUNG
										<small>(578)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-5">
									<label for="brand-5">
										<span></span>
										LG
										<small>(125)</small>
									</label>
								</div>
								<div class="input-checkbox">
									<input type="checkbox" id="brand-6">
									<label for="brand-6">
										<span></span>
										SONY
										<small>(755)</small>
									</label>
								</div>
							</div>
						</div>
						<!-- /aside Widget -->

						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Top selling</h3>
							<div class="product-widget">
								<div class="product-img">
									<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="">
								</div>
								<div class="product-body">
									<p class="product-category">Category</p>
									<h3 class="product-name"><a href="{{ route('product') }}">product name goes here</a></h3>
									<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								</div>
							</div>

							<div class="product-widget">
								<div class="product-img">
									<img src="{{ asset('frontend-assets') }}/img/product02.png" alt="">
								</div>
								<div class="product-body">
									<p class="product-category">Category</p>
									<h3 class="product-name"><a href="{{ route('product') }}">product name goes here</a></h3>
									<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								</div>
							</div>

							<div class="product-widget">
								<div class="product-img">
									<img src="{{ asset('frontend-assets') }}/img/product03.png" alt="">
								</div>
								<div class="product-body">
									<p class="product-category">Category</p>
									<h3 class="product-name"><a href="{{ route('product') }}">product name goes here</a></h3>
									<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								</div>
							</div>
						</div>
						<!-- /aside Widget -->
					</div>
					<!-- /ASIDE -->

					<!-- STORE -->
					<div id="store" class="col-md-9">
						<!-- store top filter -->
						<div class="store-filter clearfix">
							<div class="store-sort">
								<label>
									Sort By:
									<select class="input-select">
										<option value="0">Popular</option>
										<option value="1">Position</option>
									</select>
								</label>

								<label>
									Show:
									<select class="input-select">
										<option value="0">20</option>
										<option value="1">50</option>
									</select>
								</label>
							</div>
							<ul class="store-grid">
								<li class="active"><i class="fa fa-th"></i></li>
								<li><a href="#"><i class="fa fa-th-list"></i></a></li>
							</ul>
						</div>
						<!-- /store top filter -->

						<!-- store products -->
						<div class="row">
							@forelse($products as $product)
								<!-- product -->
								<div class="col-md-4 col-xs-6" style="margin-bottom: 30px; height: 450px;">
									<div class="product">
										<div class="product-img">
											@if($product->image)
												<img src="{{ asset($product->image) }}" alt="{{ $product->title }}" style="height: 180px; width: 100%; object-fit: cover;">
											@else
												<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="{{ $product->title }}" style="height: 180px; width: 100%; object-fit: cover;">
											@endif
											<div class="product-label">
												@if($product->discount > 0)
													<span class="sale">-{{ round($product->discount) }}%</span>
												@endif
												@if($product->created_at->gt(now()->subDays(7)))
													<span class="new">NEW</span>
												@endif
											</div>
										</div>
										<div class="product-body">
											<p class="product-category">{{ $product->category->title ?? 'General' }}</p>
											<h3 class="product-name" style="height: 40px; overflow: hidden;"><a href="{{ route('product', ['product_id' => $product->id]) }}">{{ $product->title }}</a></h3>
											@if($product->discount > 0)
												@php
													$discountedPrice = $product->price - ($product->price * ($product->discount / 100));
												@endphp
												<h4 class="product-price">${{ number_format($discountedPrice, 2) }} <del class="product-old-price">${{ number_format($product->price, 2) }}</del></h4>
											@else
												<h4 class="product-price">${{ number_format($product->price, 2) }}</h4>
											@endif
											<div class="product-rating">
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
												<i class="fa fa-star"></i>
											</div>
											<div class="product-btns">
												<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
												<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
												<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
											</div>
										</div>
										<div class="add-to-cart">
											<form action="{{ route('cart.add', $product->id) }}" method="POST">
												@csrf
												<button type="submit" class="add-to-cart-btn" style="width: 100%; border: none; background: none; color: inherit; height: 40px; display: block; text-align: center; line-height: 40px;"><i class="fa fa-shopping-cart"></i> add to cart</button>
											</form>
										</div>
									</div>
								</div>
								<!-- /product -->
							@empty
								<div class="col-md-12 text-center" style="padding: 50px 0;">
									<h3>No Products Found</h3>
									<p>Please seed the database or add products from the admin panel.</p>
								</div>
							@endforelse
						</div>
						<!-- /store products -->

						<!-- store bottom filter -->
						@if($products->hasPages())
							<div class="store-filter clearfix text-center">
								{{ $products->links('pagination::bootstrap-4') }}
							</div>
						@endif
					</div>
					<!-- /STORE -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->
@endsection
