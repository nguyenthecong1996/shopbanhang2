@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Sửa thương hiệu sản phẩm
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
                                <form role="form" action="{{URL::to('/update-brand-product/'. $edit_brand_product->brand_id)}}" method="post">
                                	{{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên thương hiệu</label>
                                    <input type="text" value="{{ $edit_brand_product->brand_name }}" class="form-control" id="exampleInputEmail1"  name="brand_product_name" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả thương hiệu</label>
                                    <textarea style="resize: none;"  rows="5" name="brand_product_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{ $edit_brand_product->brand_desc }}</textarea>
                                </div>
                                <button type="submit" name="add_brand_product" class="btn btn-info">Cập nhật thương hiệu</button>
                            </form>
                            </div>

                        </div>
                    </section>

            </div>
@endsection