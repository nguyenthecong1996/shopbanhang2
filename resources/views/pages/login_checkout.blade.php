@extends('layout')
@section('content')
	<section id="form"><!--form-->
		<div class="container">
			<div class="row">
				<div class="col-sm-4 col-sm-offset-1">
					<div class="login-form"><!--login form-->
						<h2>Đăng nhập tài khoản</h2>
						<form action="{{url('/register-checkout?param=login')}}" method="post">
							{{ csrf_field() }}
							<input type="email" placeholder="Email" name="email" />
							<input type="password" placeholder="Password" name="password" />
							<span>
								<input type="checkbox" class="checkbox"> 
								Keep me signed in
							</span>
							<button type="submit" class="btn btn-default">Login</button>
						</form>
					</div><!--/login form-->
				</div>
				<div class="col-sm-1">
					<h2 class="or">OR</h2>
				</div>
				<div class="col-sm-4">
					<div class="signup-form"><!--sign up form-->
						<h2>Đăng kí tài khoản</h2>
						<form action="{{url('/register-checkout?param=register')}}" method="post">
							{{ csrf_field() }}
							<input type="text" placeholder="Name"  name="name"/>
							<input type="email" placeholder="Email Address" name="email"/>
							<input type="password" placeholder="Password" name="password" />
							<button type="submit" class="btn btn-default">Signup</button>
						</form>
					</div><!--/sign up form-->
				</div>
			</div>
		</div>
	</section><!--/form-->
@endsection