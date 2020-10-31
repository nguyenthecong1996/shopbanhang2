<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Helper;
use DB;
use Cart;

class CartController extends Controller
{
    public function Cart(Request $request)
    {	
    	$data = array();
    	$getProduct =  DB::table('tbl_product')->where('product_id', $request->id_hidden)->first();
    	$data['id'] = $getProduct->product_id;
    	$data['qty'] = $request->quantity;
    	$data['name'] = $getProduct->product_content;
    	$data['price'] = $getProduct->product_price;
    	$data['weight'] = 0;
    	$data['options']['image'] = $getProduct->product_image;
    	Cart::add($data);
    	return Redirect::to('/show-cart');
    }

    public function showCart(){
    	$test = Helper::getCatagoryAndBrand();
    	$all_brand_product = $test['all_brand_product'];
    	$all_category_product =  $test['all_category_product'];
    	$show_cart = Cart::content();
    	return view('pages.cart')->with(compact('all_brand_product', 'all_category_product', 'show_cart'));
    }

    public function removeItem($row_id) {
    	// dd($row_id);
    	Cart::remove($row_id);
    	return Redirect::to('/show-cart');

    }

    public function cartUpdate(Request $request) {
        Cart::update($request->rowId, $request->quantity_update);
        return Redirect::to('/show-cart');
    }
}
