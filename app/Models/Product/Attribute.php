<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'products_attributes';

    protected $fillable = [
    	'data_type',
    	'product_id',
    	'name',
    	'value',
    ];
}
