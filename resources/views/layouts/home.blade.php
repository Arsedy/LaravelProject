<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <title>@yield('keywords')</title>
        <title>@yield('description')</title>

        @section('header')
            <!-- Google font -->
            <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

            <!-- Bootstrap -->
            <link type="text/css" rel="stylesheet" href="{{asset('assets')}}/css/bootstrap.min.css"/>

            <!-- Slick -->
            <link type="text/css" rel="stylesheet" href="{{asset('assets')}}/css/slick.css"/>
            <link type="text/css" rel="stylesheet" href="{{asset('assets')}}/css/slick-theme.css"/>

            <!-- nouislider -->
            <link type="text/css" rel="stylesheet" href="{{asset('assets')}}/css/nouislider.min.css"/>

            <!-- Font Awesome Icon -->
            <link rel="stylesheet" href="{{asset('assets')}}/css/font-awesome.min.css">

            <!-- Custom stlylesheet -->
            <link type="text/css" rel="stylesheet" href="{{asset('assets')}}/css/style.css"/>

            <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <![endif]-->

        @show

    </head>
    <body>
        @section('header')
            @include('front.header')
        @show
        
        @include('front.menu')
        @yield('slider')

        <div class="container">
            @include('front.slider')
            @yield('content')
        </div>

        @section('footer')
            @include('front.footer')
        @show
        
    </body>
</html>