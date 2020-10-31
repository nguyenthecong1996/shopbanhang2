<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use App\Models\TblProduct;
use App\Models\CategoryProduct;
use App\Models\TblBrandProduct;


class ProductController extends Controller
{
    public function add_product()
    {
    	$data= array();
    	$data['all_brand_product'] = TblBrandProduct::all();
    	$data['all_category_product'] = CategoryProduct::all();
    	return view('admin.add_product', ['data' => $data]);
    }
    public function all_product()
    {
        // $user = TblProduct::find('product_id', 1);
        // dd($user);
        // $te = TblProduct::with('CategoryProduct')->get();
    	$all_product = DB::table('tbl_product')->
    	join('category_product', 'category_product.category_id','=', 'tbl_product.category_id')
    	->join('tbl_brand_product', 'tbl_brand_product.brand_id','=', 'tbl_product.brand_id')->orderBy('tbl_product.product_id', 'desc')->get();
     //    dd($all_product);
    	return view('admin.all_product')->with('get_product', $all_product);
    }

    public function save_product(Request $request)
    {
    	$data = array();
    	$data['product_image'] = null;
    	$product_image = $request->file('product_image');
    	if($product_image) {
    		$get_name = current(explode('.', $product_image->getClientOriginalName()));
    		$get_type_image = $product_image->getClientOriginalExtension();
    		$new_name = time().$get_name.'.'.$get_type_image;
    		$product_image->move('public/backend/uploads/', $new_name);
    		$data['product_image'] = $new_name;
    	}
    	$data['product_name'] = $request->product_name;
    	$data['product_desc'] = $request->product_desc;
    	$data['product_content'] = $request->product_content;
    	$data['product_price'] = $request->product_price;
    	$data['category_id'] = $request->category_id;
    	$data['brand_id'] = $request->brand_id;
    	$data['product_status'] = $request->product_status;
    	DB::table('tbl_product')->insert($data);
    	Session::put('message', 'Thêm sản phẩm thành công');
    	return Redirect::to('/add-product');
    }

    public function unactive_product($product_id)
    {	
    	DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status'=>1]);
    	Session::put('message', 'kích hoạt sản phẩm');
    	return Redirect::to('all-product');
    }
    public function active_product($product_id)
    {
    	DB::table('tbl_product')->where('product_id', $product_id)->update(['product_status'=>0]);
    	Session::put('message', 'Không kích hoạt sản phẩm');
    	return redirect('all-product');
    }

    public function edit_product($product_id){
    	$data= array();
        $user = TblProduct::find($product_id);
        dd($user->Brand);
    	$data['all_brand_product'] = DB::table('tbl_brand_product')->orderBy('brand_id', 'desc')->get();
    	$data['all_category_product'] = DB::table('category_product')->orderBy('category_id', 'desc')->get();
    	$data['edit_product'] = DB::table('tbl_product')->where('product_id',$product_id )->first();
    	// return view('admin.edit_product')->with(compact('user', 'action'));
    	return view('admin.edit_product', ['data' => $data]);
    }

    public function update_product(Request $request, $product_id) {
    	$data = array();
    	$data['product_name'] = $request->product_name;
    	$data['product_desc'] = $request->product_desc;
    	$data['product_content'] = $request->product_content;
    	$data['product_price'] = $request->product_price;
    	$data['category_id'] = $request->category_id;
    	$data['brand_id'] = $request->brand_id;
    	$product_image = $request->file('product_image');
    	if($product_image) {
    		$get_name = current(explode('.', $product_image->getClientOriginalName()));
    		$get_type_image = $product_image->getClientOriginalExtension();
    		$new_name = time().$get_name.'.'.$get_type_image;
    		$product_image->move('public/backend/uploads/', $new_name);
    		$data['product_image'] = $new_name;
    	}
    	DB::table('tbl_product')->where('product_id', $product_id)->update($data);
    	Session::put('message', 'Cập nhật thành công');
    	return Redirect::to('all-product');
    }

     public function delete_product($product_id){
    	DB::table('tbl_product')->where('product_id', $product_id)->delete();
    	Session::put('message', 'Xóa thành công');
    	return Redirect::to('all-product');
    }
}
