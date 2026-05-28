<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title', 'Electro - HTML Ecommerce Template')</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="{{ asset('frontend-assets') }}/css/bootstrap.min.css"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="{{ asset('frontend-assets') }}/css/slick.css"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('frontend-assets') }}/css/slick-theme.css"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="{{ asset('frontend-assets') }}/css/nouislider.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="{{ asset('frontend-assets') }}/css/font-awesome.min.css">

		<!-- Custom stylesheet -->
		<link type="text/css" rel="stylesheet" href="{{ asset('frontend-assets') }}/css/style.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script>
			window.Laravel = {
				routes: {
					home: "{{ route('home') }}",
					store: "{{ route('store') }}",
					checkout: "{{ route('checkout') }}",
					product: "{{ route('product') }}",
					blank: "{{ route('blank') }}"
				}
			};
		</script>

		@yield('styles')
	</head>
	<body>
		@include('front.header')
		@include('front.menu')

		@yield('content')

		@include('front.newsletter')
		@include('front.footer')

		@yield('scripts')
	</body>
</html>
