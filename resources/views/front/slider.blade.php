@php
    $dbProducts = \App\Models\Product::all();
    $getProductUrl = function($title) use ($dbProducts) {
        $product = $dbProducts->first(function($p) use ($title) {
            return stripos($p->title, $title) !== false || stripos($title, $p->title) !== false;
        });
        return $product ? route('product', ['product_id' => $product->id]) : route('product');
    };
@endphp
		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">New Products</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
									<li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
									<li><a data-toggle="tab" href="#tab1">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab1">Accessories</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /section title -->

					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab1" class="tab-pane active">
									<div class="products-slick" data-nav="#slick-nav-1">
										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('MacBook Pro 16-inch M3') }}">
													<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="MacBook Pro 16-inch M3">
												</a>
												<div class="product-label">
													<span class="sale">-10%</span>
													<span class="new">NEW</span>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category">Laptops</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('MacBook Pro 16-inch M3') }}">MacBook Pro 16-inch M3</a></h3>
												<h4 class="product-price">$1999.00 <del class="product-old-price">$2199.00</del></h4>
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
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('Sony WH-1000XM5 Headphones') }}">
													<img src="{{ asset('frontend-assets') }}/img/product02.png" alt="Sony WH-1000XM5 Headphones">
												</a>
												<div class="product-label">
													<span class="new">NEW</span>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category">Headphones</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('Sony WH-1000XM5 Headphones') }}">Sony WH-1000XM5 Headphones</a></h3>
												<h4 class="product-price">$349.00 <del class="product-old-price">$399.00</del></h4>
												<div class="product-rating">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-o"></i>
												</div>
												<div class="product-btns">
													<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
													<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
													<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
												</div>
											</div>
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('ASUS ROG Zephyrus G14') }}">
													<img src="{{ asset('frontend-assets') }}/img/product03.png" alt="ASUS ROG Zephyrus G14">
												</a>
												<div class="product-label">
													<span class="sale">-20%</span>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category">Laptops</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('ASUS ROG Zephyrus G14') }}">ASUS ROG Zephyrus G14</a></h3>
												<h4 class="product-price">$1399.00 <del class="product-old-price">$1599.00</del></h4>
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
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('iPad Pro 11-inch M2') }}">
													<img src="{{ asset('frontend-assets') }}/img/product04.png" alt="iPad Pro 11-inch M2">
												</a>
											</div>
											<div class="product-body">
												<p class="product-category">Tablets</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('iPad Pro 11-inch M2') }}">iPad Pro 11-inch M2</a></h3>
												<h4 class="product-price">$799.00 <del class="product-old-price">$849.00</del></h4>
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
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('Logitech MX Master 3S') }}">
													<img src="{{ asset('frontend-assets') }}/img/product05.png" alt="Logitech MX Master 3S">
												</a>
											</div>
											<div class="product-body">
												<p class="product-category">Accessories</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('Logitech MX Master 3S') }}">Logitech MX Master 3S</a></h3>
												<h4 class="product-price">$99.00 <del class="product-old-price">$109.00</del></h4>
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
										</div>
										<!-- /product -->
									</div>
									<div id="slick-nav-1" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- HOT DEAL SECTION -->
		<div id="hot-deal" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="hot-deal">
							<ul class="hot-deal-countdown">
								<li>
									<div>
										<h3>02</h3>
										<span>Days</span>
									</div>
								</li>
								<li>
									<div>
										<h3>10</h3>
										<span>Hours</span>
									</div>
								</li>
								<li>
									<div>
										<h3>34</h3>
										<span>Mins</span>
									</div>
								</li>
								<li>
									<div>
										<h3>60</h3>
										<span>Secs</span>
									</div>
								</li>
							</ul>
							<h2 class="text-uppercase">hot deal this week</h2>
							<p>New Collection Up to 50% OFF</p>
							<a class="primary-btn cta-btn" href="#">Shop now</a>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /HOT DEAL SECTION -->

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">Top selling</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab2">Laptops</a></li>
									<li><a data-toggle="tab" href="#tab2">Smartphones</a></li>
									<li><a data-toggle="tab" href="#tab2">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab2">Accessories</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /section title -->

					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab2" class="tab-pane fade in active">
									<div class="products-slick" data-nav="#slick-nav-2">
										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('Samsung Galaxy S23 Ultra') }}">
													<img src="{{ asset('frontend-assets') }}/img/product06.png" alt="Samsung Galaxy S23 Ultra">
												</a>
												<div class="product-label">
													<span class="sale">-10%</span>
													<span class="new">NEW</span>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category">Smartphones</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('Samsung Galaxy S23 Ultra') }}">Samsung Galaxy S23 Ultra</a></h3>
												<h4 class="product-price">$1199.00 <del class="product-old-price">$1299.00</del></h4>
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
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('Dell XPS 13 Plus') }}">
													<img src="{{ asset('frontend-assets') }}/img/product07.png" alt="Dell XPS 13 Plus">
												</a>
												<div class="product-label">
													<span class="new">NEW</span>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category">Laptops</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('Dell XPS 13 Plus') }}">Dell XPS 13 Plus</a></h3>
												<h4 class="product-price">$999.00 <del class="product-old-price">$1099.00</del></h4>
												<div class="product-rating">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-o"></i>
												</div>
												<div class="product-btns">
													<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
													<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
													<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
												</div>
											</div>
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('Sony Alpha 7 IV Camera') }}">
													<img src="{{ asset('frontend-assets') }}/img/product08.png" alt="Sony Alpha 7 IV Camera">
												</a>
												<div class="product-label">
													<span class="sale">-5%</span>
												</div>
											</div>
											<div class="product-body">
												<p class="product-category">Cameras</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('Sony Alpha 7 IV Camera') }}">Sony Alpha 7 IV Camera</a></h3>
												<h4 class="product-price">$2199.00 <del class="product-old-price">$2299.00</del></h4>
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
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('iPhone 15 Pro') }}">
													<img src="{{ asset('frontend-assets') }}/img/product09.png" alt="iPhone 15 Pro">
												</a>
											</div>
											<div class="product-body">
												<p class="product-category">Smartphones</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('iPhone 15 Pro') }}">iPhone 15 Pro</a></h3>
												<h4 class="product-price">$999.00 <del class="product-old-price">$1099.00</del></h4>
												<div class="product-rating">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-o"></i>
												</div>
												<div class="product-btns">
													<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
													<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
													<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
												</div>
											</div>
										</div>
										<!-- /product -->

										<!-- product -->
										<div class="product">
											<div class="product-img">
												<a href="{{ $getProductUrl('HP Spectre x360') }}">
													<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="HP Spectre x360">
												</a>
											</div>
											<div class="product-body">
												<p class="product-category">Laptops</p>
												<h3 class="product-name"><a href="{{ $getProductUrl('HP Spectre x360') }}">HP Spectre x360</a></h3>
												<h4 class="product-price">$1149.00 <del class="product-old-price">$1249.00</del></h4>
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
										</div>
										<!-- /product -->
									</div>
									<div id="slick-nav-2" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- /Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-4 col-xs-6">
						<div class="section-title">
							<h4 class="title">Top selling</h4>
							<div class="section-nav">
								<div id="slick-nav-3" class="products-slick-nav"></div>
							</div>
						</div>

						<div class="products-widget-slick" data-nav="#slick-nav-3">
							<div>
								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Dell XPS 13 Plus') }}">
											<img src="{{ asset('frontend-assets') }}/img/product07.png" alt="Dell XPS 13 Plus">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Laptops</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Dell XPS 13 Plus') }}">Dell XPS 13 Plus</a></h3>
										<h4 class="product-price">$999.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Sony Alpha 7 IV Camera') }}">
											<img src="{{ asset('frontend-assets') }}/img/product08.png" alt="Sony Alpha 7 IV Camera">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Cameras</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Sony Alpha 7 IV Camera') }}">Sony Alpha 7 IV Camera</a></h3>
										<h4 class="product-price">$2199.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('iPhone 15 Pro') }}">
											<img src="{{ asset('frontend-assets') }}/img/product09.png" alt="iPhone 15 Pro">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Smartphones</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('iPhone 15 Pro') }}">iPhone 15 Pro</a></h3>
										<h4 class="product-price">$999.00</h4>
									</div>
								</div>
								<!-- product widget -->
							</div>

							<div>
								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('MacBook Pro 16-inch M3') }}">
											<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="MacBook Pro 16-inch M3">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Laptops</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('MacBook Pro 16-inch M3') }}">MacBook Pro 16-inch M3</a></h3>
										<h4 class="product-price">$1999.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Sony WH-1000XM5 Headphones') }}">
											<img src="{{ asset('frontend-assets') }}/img/product02.png" alt="Sony WH-1000XM5 Headphones">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Headphones</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Sony WH-1000XM5 Headphones') }}">Sony WH-1000XM5 Headphones</a></h3>
										<h4 class="product-price">$349.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('ASUS ROG Zephyrus G14') }}">
											<img src="{{ asset('frontend-assets') }}/img/product03.png" alt="ASUS ROG Zephyrus G14">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Laptops</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('ASUS ROG Zephyrus G14') }}">ASUS ROG Zephyrus G14</a></h3>
										<h4 class="product-price">$1399.00</h4>
									</div>
								</div>
								<!-- product widget -->
							</div>
						</div>
					</div>

					<div class="col-md-4 col-xs-6">
						<div class="section-title">
							<h4 class="title">Top selling</h4>
							<div class="section-nav">
								<div id="slick-nav-4" class="products-slick-nav"></div>
							</div>
						</div>

						<div class="products-widget-slick" data-nav="#slick-nav-4">
							<div>
								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('iPad Pro 11-inch M2') }}">
											<img src="{{ asset('frontend-assets') }}/img/product04.png" alt="iPad Pro 11-inch M2">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Tablets</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('iPad Pro 11-inch M2') }}">iPad Pro 11-inch M2</a></h3>
										<h4 class="product-price">$799.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Logitech MX Master 3S') }}">
											<img src="{{ asset('frontend-assets') }}/img/product05.png" alt="Logitech MX Master 3S">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Accessories</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Logitech MX Master 3S') }}">Logitech MX Master 3S</a></h3>
										<h4 class="product-price">$99.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Samsung Galaxy S23 Ultra') }}">
											<img src="{{ asset('frontend-assets') }}/img/product06.png" alt="Samsung Galaxy S23 Ultra">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Smartphones</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Samsung Galaxy S23 Ultra') }}">Samsung Galaxy S23 Ultra</a></h3>
										<h4 class="product-price">$1199.00</h4>
									</div>
								</div>
								<!-- product widget -->
							</div>

							<div>
								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Dell XPS 13 Plus') }}">
											<img src="{{ asset('frontend-assets') }}/img/product07.png" alt="Dell XPS 13 Plus">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Laptops</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Dell XPS 13 Plus') }}">Dell XPS 13 Plus</a></h3>
										<h4 class="product-price">$999.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Sony Alpha 7 IV Camera') }}">
											<img src="{{ asset('frontend-assets') }}/img/product08.png" alt="Sony Alpha 7 IV Camera">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Cameras</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Sony Alpha 7 IV Camera') }}">Sony Alpha 7 IV Camera</a></h3>
										<h4 class="product-price">$2199.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('iPhone 15 Pro') }}">
											<img src="{{ asset('frontend-assets') }}/img/product09.png" alt="iPhone 15 Pro">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Smartphones</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('iPhone 15 Pro') }}">iPhone 15 Pro</a></h3>
										<h4 class="product-price">$999.00</h4>
									</div>
								</div>
								<!-- product widget -->
							</div>
						</div>
					</div>

					<div class="col-md-4 col-xs-6">
						<div class="section-title">
							<h4 class="title">Top selling</h4>
							<div class="section-nav">
								<div id="slick-nav-5" class="products-slick-nav"></div>
							</div>
						</div>

						<div class="products-widget-slick" data-nav="#slick-nav-5">
							<div>
								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('MacBook Pro 16-inch M3') }}">
											<img src="{{ asset('frontend-assets') }}/img/product01.png" alt="MacBook Pro 16-inch M3">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Laptops</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('MacBook Pro 16-inch M3') }}">MacBook Pro 16-inch M3</a></h3>
										<h4 class="product-price">$1999.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Sony WH-1000XM5 Headphones') }}">
											<img src="{{ asset('frontend-assets') }}/img/product02.png" alt="Sony WH-1000XM5 Headphones">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Headphones</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Sony WH-1000XM5 Headphones') }}">Sony WH-1000XM5 Headphones</a></h3>
										<h4 class="product-price">$349.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('ASUS ROG Zephyrus G14') }}">
											<img src="{{ asset('frontend-assets') }}/img/product03.png" alt="ASUS ROG Zephyrus G14">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Laptops</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('ASUS ROG Zephyrus G14') }}">ASUS ROG Zephyrus G14</a></h3>
										<h4 class="product-price">$1399.00</h4>
									</div>
								</div>
								<!-- product widget -->
							</div>

							<div>
								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('iPad Pro 11-inch M2') }}">
											<img src="{{ asset('frontend-assets') }}/img/product04.png" alt="iPad Pro 11-inch M2">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Tablets</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('iPad Pro 11-inch M2') }}">iPad Pro 11-inch M2</a></h3>
										<h4 class="product-price">$799.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Logitech MX Master 3S') }}">
											<img src="{{ asset('frontend-assets') }}/img/product05.png" alt="Logitech MX Master 3S">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Accessories</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Logitech MX Master 3S') }}">Logitech MX Master 3S</a></h3>
										<h4 class="product-price">$99.00</h4>
									</div>
								</div>
								<!-- /product widget -->

								<!-- product widget -->
								<div class="product-widget">
									<div class="product-img">
										<a href="{{ $getProductUrl('Samsung Galaxy S23 Ultra') }}">
											<img src="{{ asset('frontend-assets') }}/img/product06.png" alt="Samsung Galaxy S23 Ultra">
										</a>
									</div>
									<div class="product-body">
										<p class="product-category">Smartphones</p>
										<h3 class="product-name"><a href="{{ $getProductUrl('Samsung Galaxy S23 Ultra') }}">Samsung Galaxy S23 Ultra</a></h3>
										<h4 class="product-price">$1199.00</h4>
									</div>
								</div>
								<!-- product widget -->
							</div>
						</div>
					</div>

				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->
