<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//login facebook
 Route::get('/facebook', 'SocialAuthController@redirectToProvider');
 Route::get('/facebook/callback', 'SocialAuthController@handleProviderCallback');

//sendmail
Route::get('/send-mail', 'HomeController@sendMail');

// font-end
Route::get('/', 'HomeController@index');
Route::get('/trang-chu', 'HomeController@index');

//category product
Route::get('/category/{category_id}', 'HomeController@category_product');
//brand product
Route::get('/brand/{brand_id}', 'HomeController@brand_product');

//product-detail
Route::get('/product-detail/{product_id}', 'HomeController@productDetail');

//cart
Route::post('/save-cart', 'CartController@Cart');
Route::get('/remove-item/{row_id}', 'CartController@removeItem');
Route::get('/show-cart', 'CartController@showCart');
Route::post('/cart-update', 'CartController@cartUpdate');

//checkout
Route::get('/login-checkout', 'CheckOutController@loginCart');
Route::get('/logout-checkout', 'CheckOutController@logoutCart');
Route::get('/checkout', 'CheckOutController@checkOut');
Route::post('/save-checkout-customer', 'CheckOutController@saveCheckoutCustomer');
Route::get('/payment', 'CheckOutController@payment');
Route::post('/order-place', 'CheckOutController@orderPlace');
Route::post('/register-checkout', 'CheckOutController@registerCheckout');



// back-end
Route::group(['prefix' => 'test'], function () {
	Route::get('/admin', 'AdminController@index');

});
Route::get('/admint', 'AdminController@indext');

// Route::get('/dashboard', 'AdminController@show_dashboard');
Route::post('/admin-dashboard', 'AdminController@dashboard');
Route::get('/logout', 'AdminController@logout');


//category product
Route::group(['middleware' => 'adminLogin'], function () {
	Route::get('/add-category-product', 'CategoryProduct@add_category_product');
	Route::post('/save-category-product', 'CategoryProduct@save_category_product');
	Route::get('/edit-category-product/{category_product_id}', 'CategoryProduct@edit_category_product');
	Route::get('/delete-category-product/{category_product_id}', 'CategoryProduct@delete_category_product');
	Route::post('/update-category-product/{category_product_id}', 'CategoryProduct@update_category_product');
	Route::get('/active-category/{category_product_id}', 'CategoryProduct@active_category_product');
	Route::get('/unactive-category/{category_product_id}', 'CategoryProduct@unactive_category_product');
	Route::get('/all-category-product', 'CategoryProduct@all_category_product');

	//brand product
	Route::get('/add-brand-product', 'BrandProduct@add_brand_product');
	Route::post('/save-brand-product', 'BrandProduct@save_brand_product');
	Route::get('/all-brand-product', 'BrandProduct@all_brand_product');

	Route::get('/edit-brand-product/{brand_product_id}', 'BrandProduct@edit_brand_product');
	Route::get('/delete-brand-product/{brand_product_id}', 'BrandProduct@delete_brand_product');
	Route::post('/update-brand-product/{brand_product_id}', 'BrandProduct@update_brand_product');

	Route::get('/active-brand/{brand_product_id}', 'BrandProduct@active_brand_product');
	Route::get('/unactive-brand/{brand_product_id}', 'BrandProduct@unactive_brand_product');

	// product 
	Route::get('/add-product', 'ProductController@add_product');
	Route::post('/save-product', 'ProductController@save_product');
	Route::get('/all-product', 'ProductController@all_product');

	Route::get('/active-product/{product_id}', 'ProductController@active_product');
	Route::get('/unactive-product/{product_id}', 'ProductController@unactive_product');

	Route::get('/edit-product/{product_id}', 'ProductController@edit_product');
	Route::post('/update-product/{product_id}', 'ProductController@update_product');
	Route::get('/delete-product/{product_id}', 'ProductController@delete_product');

	//order
	Route::get('/get-order', 'OrderController@getOrder');
	Route::get('/order-detail/{order_id}', 'OrderController@orderDetail');


});






