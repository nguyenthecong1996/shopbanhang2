<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    // use HasFactory;
    public $timestamps = false;
    protected $table = 'category_product';
    protected $fillable = ['category_id', 'product_id', 'brand_id', 'brand_status'];

    public function product()
    {
        return $this->hasMany('App\Models\TblProduct', 'category_id', 'category_id');
    }
}
