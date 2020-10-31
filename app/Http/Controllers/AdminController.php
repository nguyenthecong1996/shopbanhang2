<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Redirect;
session_start();
class AdminController extends Controller
{
    public function index(){
    	return view('admin_login');
	}
	// public function show_dashboard(){
 //    	return view('admin.dashboard');
	// }
	public function dashboard(Request $request) {
		$arr = [
			'email' => $request->email,
			'password' => $request->password
		];
		if (Auth::attempt($arr)){
			return view('admin.dashboard');
		} else {
			return Redirect::to('/admin')->with('message', 'Mật khẩu hoặc tài khoản không chính xác');
		}
		// $admin_email = $request->admin_email;
		// $admin_password = md5($request->admin_password);

		// $resutl = DB::table('tbl_admin')->where('admin_email', $admin_email)->where('admin_password', $admin_password)->first();
		// if ($resutl) {
		// 	Session::put('admin_name', $resutl->admin_name);
		// 	Session::put('admin_id', $resutl->admin_id);
		// 	return Redirect::to('/dashboard');
		// } else {
		// 	Session::put('message', 'Mật khẩu hoặc tài khoản không chính xác');
		// 	return Redirect::to('/admin');
		// }
		// return view('admin.dashboard');

	}

	public function logout() {
		Auth::logout();
		return Redirect::to('/admin');
	}
}
