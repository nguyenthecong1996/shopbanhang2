@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Sửa sản phẩm
                        </header>
                        <?php
						  $message = Session::get('message');
						  if ($message) {
						  	echo '<span class="text-alert">'.$message. '</span>';
						  	$message = Session::put('message',null);
						  }
						?>
                        <div class="panel-body">
                            <div class="position-center">
                                <form role="form" action="{{URL::to('/update-product/'. $data['edit_product']->product_id)}}" method="post" enctype="multipart/form-data">
                                	{{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"  name="product_name" value="{{$data['edit_product']->product_name}}" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea style="resize: none;" rows="5" name="product_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{$data['edit_product']->product_desc}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea style="resize: none;" rows="5" name="product_content" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{$data['edit_product']->product_content}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Ảnh sản phẩm</label>
                                    <input type="file" class="form-control" name="product_image">
                                    <img src="{{URL::to('public/backend/uploads/'.$data['edit_product']->product_image)}}" width="100" height="100s">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá sản phẩm</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"  name="product_price" value="{{$data['edit_product']->product_price}}" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Danh mục sản phẩm</label>
                                   	<select name="category_id" class="form-control input-sm m-bot15">
                                   		@foreach($data['all_category_product'] as $item)
                                   			@if($item->category_id == $data['edit_product']->category_id )
		                                		<option selected value="{{$item->category_id}}">{{ $item->category_name }}</option>
		                                	@else
		                                		<option value="{{$item->category_id}}">{{ $item->category_name }}</option>
		                                	@endif	
		                                @endforeach
                            		</select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Thương hiệu sản phẩm</label>
                                   	<select name="brand_id" class="form-control input-sm m-bot15">
                                   		@foreach($data['all_brand_product'] as $item1)
                                   			@if($item1->brand_id == $data['edit_product']->brand_id )
		                                		<option selected value="{{$item1->brand_id}}">{{ $item1->brand_name }}</option>
		                                	@else
		                                		<option value="{{$item1->brand_id}}">{{ $item1->brand_name }}</option>
		                                	@endif
		                                
		                                @endforeach
                            		</select>
                                </div>
                                <button type="submit" name="add_category_product" class="btn btn-info">Cập nhật</button>
                            </form>
                            </div>

                        </div>
                    </section>

            </div>
@endsection