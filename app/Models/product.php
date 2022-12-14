<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'detail',
        'description',
        'price',
        'product_cats_id'
    ];

    function product_cat(){
        return $this->belongsTo(product_cat::class,'product_cats_id');

    }
}
