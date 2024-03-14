<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

   protected $fillable = ['category_id', 'name', 'price', 'stock', 'img_url','product_code','created_by','expire_date'];

}
