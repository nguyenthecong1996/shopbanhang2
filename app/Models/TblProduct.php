<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblProduct extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $table = 'tbl_product';
    protected  $primaryKey = 'product_id';
    protected $fillable = ['category_id', 'product_id ', 'brand_id', 'product_name', 'product_desc', 'product_content', 'product_image','product_price', 'product_status'];

    public function CategoryProduct()
    {
        return $this->belongsTo('App\Models\CategoryProduct', 'category_id', 'category_id');
    }

    public function Brand()
    {
        return $this->belongsTo('App\Models\TblBrandProduct', 'brand_id', 'brand_id');
    }
}
