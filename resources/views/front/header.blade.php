		@php
    use App\Models\Cart;
    use App\Models\Category;
    $headerCategories = Category::where('status', true)->get();
    $headerCartItems = collect();
    $headerCartCount = 0;
    $headerCartTotal = 0;
    if (auth()->check()) {
        $headerCartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();
        $headerCartCount = $headerCartItems->sum('quantity');
        $headerCartTotal = $headerCartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }
@endphp
<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
						@guest
							<li><a href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Login</a></li>
							<li><a href="{{ route('register') }}"><i class="fa fa-user-plus"></i> Register</a></li>
						@else
							<li><a href="#"><i class="fa fa-user-o"></i> {{ auth()->user()->name }}</a></li>
							<li>
								<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<i class="fa fa-sign-out"></i> Logout
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									@csrf
								</form>
							</li>
						@endguest
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- MAIN HEADER -->
			<div id="header">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="{{ route('home') }}" class="logo">
									<img src="{{ asset('frontend-assets') }}/img/logo.png" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- SEARCH BAR -->
						<div class="col-md-6">
							<div class="header-search">
								<form action="{{ route('store') }}" method="GET">
									<select class="input-select" name="category_id">
										<option value="">All Categories</option>
										@foreach($headerCategories as $cat)
											<option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
										@endforeach
									</select>
									<input class="input" name="search" placeholder="Search here" value="{{ request('search') }}">
									<button type="submit" class="search-btn">Search</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Wishlist -->
								<div class="dropdown" id="wishlist-dropdown-container">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="cursor: pointer;">
										<i class="fa fa-heart-o"></i>
										<span>Your Wishlist</span>
										<div class="qty" id="wishlist-qty">0</div>
									</a>
									<div class="cart-dropdown" id="wishlist-dropdown" style="width: 320px;">
										<div class="cart-list" id="wishlist-list">
											<!-- Dynamically rendered favorites -->
										</div>
										<div class="cart-btns" style="margin: 0px -17px -17px;">
											<a href="#" id="clear-wishlist" style="width: 100%; background-color: #1e1f29; text-align: center;">Clear Wishlist</a>
										</div>
									</div>
								</div>
								<!-- /Wishlist -->

								<!-- Cart -->
								<div class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="cursor: pointer;">
										<i class="fa fa-shopping-cart"></i>
										<span>Your Cart</span>
										<div class="qty">{{ $headerCartCount }}</div>
									</a>
									<div class="cart-dropdown">
										<div class="cart-list">
											@auth
												@forelse($headerCartItems as $item)
													<div class="product-widget">
														<div class="product-img">
															@if($item->product && $item->product->image)
																<img src="{{ asset($item->product->image) }}" alt="{{ $item->product->title }}">
															@else
																<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="">
															@endif
														</div>
														<div class="product-body">
															<h3 class="product-name">
																@if($item->product)
																	<a href="{{ route('product', ['product_id' => $item->product->id]) }}">{{ $item->product->title }}</a>
																@else
																	<a href="#">Product Deleted</a>
																@endif
															</h3>
															<h4 class="product-price"><span class="qty">{{ $item->quantity }}x</span>${{ number_format($item->price, 2) }}</h4>
														</div>
														<form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline;">
															@csrf
															@method('DELETE')
															<button type="submit" class="delete"><i class="fa fa-close"></i></button>
														</form>
													</div>
												@empty
													<p style="padding: 15px 0 0; text-align: center; color: #8D99AE; margin: 0;">Your cart is empty.</p>
												@endforelse
											@else
												<p style="padding: 15px 0 0; text-align: center; color: #8D99AE; margin: 0;">Please login to view your cart.</p>
											@endauth
										</div>
										<div class="cart-summary">
											<small>{{ $headerCartCount }} Item(s) selected</small>
											<h5>SUBTOTAL: ${{ number_format($headerCartTotal, 2) }}</h5>
										</div>
										<div class="cart-btns">
											@auth
												<a href="{{ route('cart.index') }}">View Cart</a>
												<a href="{{ route('checkout') }}">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
											@else
												<a href="{{ route('login') }}" style="width: 100%; text-align: center; background-color: #D10024;">Login <i class="fa fa-arrow-circle-right"></i></a>
											@endauth
										</div>
									</div>
								</div>
								<!-- /Cart -->

								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
							</div>
						</div>
						<!-- /ACCOUNT -->
					</div>
					<!-- row -->
				</div>
				<!-- container -->
			</div>
			<!-- /MAIN HEADER -->
		</header>
		<!-- /HEADER -->
