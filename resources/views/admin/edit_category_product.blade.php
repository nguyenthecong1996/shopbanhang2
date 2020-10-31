@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Sửa danh mục sản phẩm
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
                                <form role="form" action="{{URL::to('/update-category-product/'. $edit_category_product->category_id)}}" method="post">
                                	{{ csrf_field() }}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên danh mục</label>
                                    <input type="text" value="{{ $edit_category_product->category_name }}" class="form-control" id="exampleInputEmail1"  name="category_product_name" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả danh mục</label>
                                    <textarea style="resize: none;"  rows="5" name="category_product_desc" class="form-control" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{ $edit_category_product->category_desc }}</textarea>
                                </div>
                                <button type="submit" name="add_category_product" class="btn btn-info">Cập nhật danh mục</button>
                            </form>
                            </div>

                        </div>
                    </section>

            </div>
@endsection