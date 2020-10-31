<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
use Helper;
session_start();
use Mail;
class HomeController extends Controller
{

    public function handleProviderCallback()
    {
        dd(1);
                // Sau khi xác thực Facebook chuyển hướng về đây cùng với một token
            // Các xử lý liên quan đến đăng nhập bằng mạng xã hội cũng đưa vào đây.  
        $provider =  Socialite::driver('facebook')->user();
            dd($provider);  
    }
    public function index(){
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];

    	$all_product = DB::table('tbl_product')->where('product_status', '1')->orderBy('product_id', 'desc')->limit(4)->get();
    	return view('pages.home')->with(compact('all_brand_product', 'all_category_product', 'all_product'));
    }

    public function category_product($category_id){
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];

    	$getName = DB::table('category_product')->where('category_id', $category_id)->pluck('category_name');
    	// dd($getName[0]);
    	$product_category = DB::table('tbl_product')->join('category_product', 'tbl_product.category_id', '=', 'category_product.category_id')->where('tbl_product.category_id', $category_id )->orderBy('tbl_product.product_id', 'desc')->limit(4)->get();
    	// dd($product_category );
    	return view('pages.category')->with(compact('all_brand_product', 'all_category_product', 'product_category', 'getName'));
    }

    public function brand_product($brand_id){
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];

    	$getName = DB::table('tbl_brand_product')->where('brand_id', $brand_id)->pluck('brand_name');

    	$product_brand = DB::table('tbl_product')->join('tbl_brand_product', 'tbl_product.brand_id', '=', 'tbl_brand_product.brand_id')->where('tbl_product.brand_id', $brand_id )->orderBy('tbl_product.product_id', 'desc')->limit(4)->get();
    	return view('pages.brand')->with(compact('all_brand_product', 'all_category_product', 'product_brand', 'getName'));
    }

    public function productDetail($product_id) {
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];
    	// lấy chỉ tiết sản phẩm 
    	$product_detail = DB::table('tbl_product')->join('category_product', 'tbl_product.category_id', '=', 'category_product.category_id')->join('tbl_brand_product', 'tbl_product.brand_id', '=', 'tbl_brand_product.brand_id')->where('tbl_product.product_id', $product_id )->get();
    	// dd($product_detail );
    	// sản phẩm liên quan
    	$recommeds_product_id = 0;
    	foreach ($product_detail as $key => $value) {
    		$recommeds_product_id = $value->category_id;
    	}
    	$recommeds_product =  DB::table('tbl_product')->join('category_product', 'tbl_product.category_id', '=', 'category_product.category_id')->join('tbl_brand_product', 'tbl_product.brand_id', '=', 'tbl_brand_product.brand_id')->where('category_product.category_id', $recommeds_product_id )->whereNotIn('tbl_product.product_id', [$product_id])->get();
    	return view('pages.product_detail')->with(compact('all_brand_product', 'all_category_product', 'product_detail', 'recommeds_product'));
    }

    //send mail

    public function sendMail()
    {
        $to_name = "thecong96";
        $to_email = "thecong1996@gmail.com";//send to this email

        $data = array("name"=>"noi dung ten","body"=>"noi dung body"); //body of mail.blade.php

       Mail::send('send_mail', $data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
        ->subject('Laravel Test Mail');
        $message->from($to_email, 'gietgg');
        });
       Redirect::to('/trang-chu');
        //--send mail
    }

}
