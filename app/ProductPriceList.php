<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class ProductPriceList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['price_list_id','productNr','priority','price'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'productPriceList';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    
}
