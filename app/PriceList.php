<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class PriceList extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'Priority'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'priceList';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    
}
