<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
    	return Socialite::driver('facebook')->redirect(); 
    }

    public function handleProviderCallback()
    {
    	dd(1);
	            // Sau khi xác thực Facebook chuyển hướng về đây cùng với một token
            // Các xử lý liên quan đến đăng nhập bằng mạng xã hội cũng đưa vào đây.  
    	$provider =  Socialite::driver('facebook')->user();
            dd($provider);  
    }
}
