<?php
namespace App\Helpers;
use DB;

class Helper{
    public static function getCatagoryAndBrand() {
		$data = array();
		$data['all_brand_product'] = DB::table('tbl_brand_product')->where('brand_status', '1')->orderBy('brand_id', 'desc')->get();
		$data['all_category_product'] =  DB::table('category_product')->where('category_status', '1')->orderBy('category_id', 'desc')->get();
		return $data;
	}
}