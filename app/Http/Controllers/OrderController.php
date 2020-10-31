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


class OrderController extends Controller
{
    public function getOrder(){
    	$getOrder = DB::table('tbl_order')->join('tbl_admin', 'tbl_order.customer_id', '=', 'tbl_admin.id')->get();
    	return view('admin.all_order')->with('getOrder', $getOrder);
    }

    public function orderDetail($order_id) {
    	$getOrderById = DB::table('tbl_order')
    	->join('tbl_admin', 'tbl_order.customer_id', '=','tbl_admin.id')
    	->join('tbl_shipping', 'tbl_order.shipping_id', '=','tbl_shipping.shipping_id')
    	->where('tbl_order.order_id', $order_id )->first();
    	 $test = (array) $getOrderById;	
    	 $test['product_detail'] = DB::table('tbl_order')
    	 ->join('tbl_order_details', 'tbl_order_details.order_id', '=','tbl_order.order_id')
    	 ->select('tbl_order_details.*')
    	 ->where('tbl_order.order_id', $order_id )->get();
    	return view('admin.order_detail')->with('getOrderById', $test);
    }
}
