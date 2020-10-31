@extends('layout')
@section('content')
<div class="features_items"><!--features_items-->
    <h2 class="title text-center">Sản phẩm mới nhất</h2>
    @foreach($all_product as $item)
    <a href="{{URL::to('/product-detail/'.$item->product_id)}}">
        <div class="col-sm-4">
        <div class="product-image-wrapper">
            <div class="single-products">
                    <div class="productinfo text-center">
                        <img src="{{('public/backend/uploads/'.$item->product_image)}}" alt="" width="100" height="200" />
                        <h2>{{number_format($item->product_price)}}</h2>
                        <p>{{$item->product_name}}</p>
                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                    </div>
            </div>
            <div class="choose">
                <ul class="nav nav-pills nav-justified">
                    <li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
                    <li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
                </ul>
            </div>
        </div>
        </div>
    </a>
    @endforeach                        
</div><!--features_items-->

@endsection
