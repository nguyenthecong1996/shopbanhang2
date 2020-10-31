<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Helper;
use DB;
use Session;
use Auth;
use Cart;
use Illuminate\Support\Facades\Redirect;
session_start();
class CheckOutController extends Controller
{
    public function loginCart(){
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];
    	return view('pages.login_checkout')->with(compact('all_brand_product', 'all_category_product'));
    }

    public function registerCheckout(Request $request) {
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];
    	$req = $request->all();
    	$data = array();
    	if ($req['param'] == 'register') {
    		$data['name'] = $req['name'];
    		$data['email'] = $req['email'];	
    		$data['password'] = bcrypt($req['password']);
    		DB::table('tbl_admin')->insert($data);
    		return view('pages.login_checkout')->with(compact('all_brand_product', 'all_category_product'));
    	}

    	if ($req['param'] == 'login') {
    		$arr = [
			'email' => $request->email,
			'password' => $request->password
			];
			if (Auth::attempt($arr)){
				return view('pages.checkout')->with(compact('all_brand_product', 'all_category_product'));
			} else {
				return Redirect::to('/pages.login_checkout')->with(compact('all_brand_product', 'all_category_product'));
			}
    	}
    }

    public function checkOut() {
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];
		return view('pages.checkout')->with(compact('all_brand_product', 'all_category_product'));

    }

    public function saveCheckoutCustomer(Request $request) {
    	$data = array();
    	$data['shipping_email'] = $request->shipping_email;
    	$data['shipping_name'] = $request->shipping_name;
    	$data['shipping_address'] = $request->shipping_address;
    	$data['shipping_phone'] = $request->shipping_phone;
    	$data['shipping_content'] = $request->shipping_content;
    	$getId = DB::table('tbl_shipping')->insertGetId($data);
    	Session::put('shipping_id', $getId);
    	return Redirect::to('/payment');
    }

    public function payment() {
        $test = Helper::getCatagoryAndBrand();
        $all_brand_product = $test['all_brand_product'];
        $all_category_product =  $test['all_category_product'];
        return view('pages.payment')->with(compact('all_brand_product', 'all_category_product'));
    }

    public function orderPlace(Request $request) {
        //insert payment 
        $payment = array();
        $payment['payment_method'] = $request->payment_option;
        $payment['payment_status'] = 'Đang chờ xử lý';
        $getPaymentId = DB::table('tbl_payment')->insertGetId($payment);

        //insert ordertbl_order_details
        $order = array();
        $order['customer_id'] = Auth::user()->id;
        $order['shipping_id'] = Session::get('shipping_id');
        $order['payment_id'] = $getPaymentId;
        $order['order_total'] = Cart::total();
        $order['order_status'] = 'Đang chờ xử lý';
        $getOrderId = DB::table('tbl_order')->insertGetId($order);

        // insert order detail
        $orderDetail = array();
        foreach (Cart::content() as $value) {
            $orderDetail['order_id'] = $getOrderId;
            $orderDetail['product_id'] = $value->id;
            $orderDetail['product_name'] = $value->name;
            $orderDetail['product_price'] = $value->price;
            $orderDetail['product_sales_quantity'] = $value->qty;
            DB::table('tbl_order_details')->insert($orderDetail);        
        }
        if ($payment['payment_method'] == 1) {
            echo "1";
        } elseif ($payment['payment_method'] == 2) {
            Cart::destroy();
            $test = Helper::getCatagoryAndBrand();
            $all_brand_product = $test['all_brand_product'];
            $all_category_product =  $test['all_category_product'];
            return view('pages.handcash')->with(compact('all_brand_product', 'all_category_product'));
        } else {
            echo "3";
        }
        

        // Session::put('shipping_id', $getId);
        // return Redirect::to('/payment');
    }
    public function logoutCart() {
    	Auth::logout();
    	Redirect::to('/login-checkout');
    }
}
