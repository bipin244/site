<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRelated extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product1', 'product2'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'product_related';

}
