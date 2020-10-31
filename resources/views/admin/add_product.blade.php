@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm sản phẩm
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
                                <form role="form" action="{{URL::to('/save-product')}}" method="post" enctype="multipart/form-data">
                                	{{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"  name="product_name" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea style="resize: none;" rows="5" name="product_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea style="resize: none;" rows="5" name="product_content" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Ảnh sản phẩm</label>
                                    <input type="file" class="form-control" name="product_image">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá sản phẩm</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1"  name="product_price" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Danh mục sản phẩm</label>
                                   	<select name="category_id" class="form-control input-sm m-bot15">
                                   		@foreach($data['all_category_product'] as $item)
		                                <option value="{{$item->category_id}}">{{ $item->category_name }}</option>
		                                @endforeach
                            		</select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Thương hiệu sản phẩm</label>
                                   	<select name="brand_id" class="form-control input-sm m-bot15">
                                   		@foreach($data['all_brand_product'] as $item1)
		                                	<option value="{{$item1->brand_id}}">{{ $item1->brand_name }}</option>
		                                @endforeach
                            		</select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Hiện thị</label>
                                   	<select name="product_status" class="form-control input-sm m-bot15">
		                                <option value="0">Ẩn</option>
		                                <option value="1">Hiện</option>
                            		</select>
                                </div>
                                <button type="submit" name="add_product" class="btn btn-info">Thêm sản phẩm</button>
                            </form>
                            </div>

                        </div>
                    </section>

            </div>
@endsection