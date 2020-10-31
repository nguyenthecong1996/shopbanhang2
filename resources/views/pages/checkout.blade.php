@extends('layout')
@section('content')
	<section id="cart_items">
		<div class="container" style="width: 950px">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Check out</li>
				</ol>
			</div><!--/breadcrums-->
			<div class="register-req">
				<p>Please use Register And Checkout to easily get access to your order history, or use Checkout as Guest</p>
			</div><!--/register-req-->

			<div class="shopper-informations">
				<div class="row">
					<div class="col-sm-12 clearfix">
						<div class="bill-to">
							<p>Điền thông tin gửi hàng</p>
							<div class="form-one">
								<form action="{{url('/save-checkout-customer')}}" method="post">
									{{ csrf_field() }}
									<input type="text" placeholder="Email*" name="shipping_email">
									<input type="text" placeholder="Name*" name="shipping_name">
									<input type="text" placeholder="Address" name="shipping_address">
									<input type="text" placeholder="Phone *" name="shipping_phone">
									<textarea name="shipping_content"  placeholder="Notes about your order, Special Notes for Delivery" rows="16"></textarea>
									<input  type="submit" class="btn btn-primary btn-sm" value="Gửi">
								</form>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</section>
@endsection