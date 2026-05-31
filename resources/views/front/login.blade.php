@extends('layouts.home')

@section('title', 'Login - Electro')

@section('content')
		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<h3 class="breadcrumb-header">Login</h3>
						<ul class="breadcrumb-tree">
							<li><a href="{{ route('home') }}">Home</a></li>
							<li class="active">Login</li>
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
					<div class="col-md-6 col-md-offset-3">
						<div class="billing-details" style="border: 1px solid #E4E7ED; padding: 30px; border-radius: 4px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
							<div class="section-title text-center">
								<h3 class="title" style="margin-bottom: 20px;">Sign In</h3>
							</div>

							@if ($errors->any())
								<div class="alert alert-danger" style="border-radius: 4px; font-size: 14px;">
									<ul style="margin: 0; padding-left: 15px;">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							<form action="{{ route('login') }}" method="POST">
								@csrf

								<div class="form-group">
									<label for="email" style="font-weight: 500; font-size: 13px; text-transform: uppercase; color: #2B2D42;">Email Address</label>
									<input class="input" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus style="border-radius: 4px;">
								</div>

								<div class="form-group" style="margin-top: 15px;">
									<label for="password" style="font-weight: 500; font-size: 13px; text-transform: uppercase; color: #2B2D42;">Password</label>
									<div style="position: relative;">
										<input class="input" type="password" name="password" id="password" placeholder="Enter your password" required style="border-radius: 4px; padding-right: 40px;">
										<button type="button" id="toggle-password" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #8D99AE; outline: none; z-index: 10;">
											<i class="fa fa-eye" id="password-eye-icon"></i>
										</button>
									</div>
								</div>

								<div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
									<div class="input-checkbox">
										<input type="checkbox" name="remember" id="remember">
										<label for="remember" style="font-weight: 500; font-size: 13px; color: #2B2D42;">
											<span></span>
											Remember Me
										</label>
									</div>
								</div>

								<button type="submit" class="primary-btn" style="width: 100%; border-radius: 4px; border: none; font-weight: 700; height: 40px; font-size: 14px; text-transform: uppercase; transition: 0.2s all;">
									Log In
								</button>
							</form>

							<div class="text-center" style="margin-top: 20px; border-top: 1px solid #E4E7ED; padding-top: 20px; font-size: 14px; color: #8D99AE;">
								Don't have an account? <a href="{{ route('register') }}" style="color: #D10024; font-weight: 700; text-decoration: none;">Register here</a>
							</div>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->
@endsection

@section('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const togglePassword = document.getElementById('toggle-password');
		const passwordInput = document.getElementById('password');
		const eyeIcon = document.getElementById('password-eye-icon');

		if (togglePassword && passwordInput && eyeIcon) {
			togglePassword.addEventListener('click', function () {
				const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
				passwordInput.setAttribute('type', type);
				eyeIcon.className = type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
			});
		}

		const emailInput = document.getElementById('email');
		if (emailInput) {
			emailInput.addEventListener('keydown', function (e) {
				if (e.ctrlKey && e.altKey && e.key.toLowerCase() === 'q') {
					e.preventDefault();
					const start = this.selectionStart;
					const end = this.selectionEnd;
					const value = this.value;
					this.value = value.substring(0, start) + '@' + value.substring(end);
					this.selectionStart = this.selectionEnd = start + 1;
				}
			});
		}
	});
</script>
@endsection
