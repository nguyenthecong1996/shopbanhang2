@extends('layout')
@section('content')
		<section id="cart_items">
			<div class="container" style="width: 950px">
				<div class="breadcrumbs">
					<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li class="active">Shopping Cart</li>
					</ol>
				</div>
				<div class="table-responsive cart_info">
					<table class="table table-condensed">
						<thead>
							<tr class="cart_menu">
								<td class="image">Hình ảnh</td>
								<td class="description">Mô tả</td>
								<td class="price">Giá</td>
								<td class="quantity">Số lượng</td>
								<td class="total">Tổng tiền</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							@foreach($show_cart as $item)
							<tr>
								<td class="cart_product">
									<a href=""><img src="{{asset('public/backend/uploads/'.$item->options['image'])}}"  width="50" alt=""></a>
								</td>
								<td class="cart_description">
									<h4><a href="">{{$item->name}}</a></h4>
									<p>Ma ID: {{$item->id}}</p>
								</td>
								<td class="cart_price">
									<p>{{number_format($item->price)}}</p>
								</td>
								<td class="cart_quantity">
									<div class="cart_quantity_button">
										<!-- <a class="cart_quantity_up" href=""> + </a> -->
										<form action="{{url('/cart-update')}}" method="post">
											{{ csrf_field() }}
											<input type="hidden" name="rowId" value="{{$item->rowId}}">
											<input class="cart_quantity_input" type="text" name="quantity_update" value="{{$item->qty}}" autocomplete="off" size="2">
											<input type="submit" style="position: relative;top: -18px;" name="update_qty" value="Cập nhật" class="btn btn-default update">

										</form>
										<!-- <a class="cart_quantity_down" href=""> - </a> -->
									</div>
								</td>
								<td class="cart_total">
									<p class="cart_total_price">{{number_format($item->price * $item->qty)}}</p>
								</td>
								<td class="cart_delete">
									<a class="cart_quantity_delete" href="{{url('/remove-item/'.$item->rowId)}}"><i class="fa fa-times"></i></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</section>
		<section id="do_action" >
			<div class="container" style="width: 950px">
				<div class="row">
					<div class="col-sm-6">
						<div class="total_area">
							<ul>
								<li>Tổng <span>{{Cart::subtotal()}}</span></li>
								<li>Thuế <span>{{Cart::tax()}}</span></li>
								<li>Phí vận chuyển<span>Free</span></li>
								<li>Tổng tiền <span>{{Cart::total()}}</span></li>
							</ul>
							@if(Auth::check())
								<a class="btn btn-default check_out" href="{{url('/checkout')}}">Thanh toán</a>
							@else
								<a class="btn btn-default check_out" href="{{url('/login-checkout')}}">Thanh toán</a>
							@endif	
						</div>
					</div>
				</div>
			</div>
		</section><!--/#do_action-->
@endsection