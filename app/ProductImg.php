<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImg extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'status'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'product_imgs';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getAllCategory()
    {
        return Category::all();
    }
}
