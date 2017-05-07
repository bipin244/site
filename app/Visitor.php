<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Visitor extends Model
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
    protected $table = 'visitors';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getAllProducts()
    {
        return Product::all();
    }

    public function productName($productId)
    {
        $languageSession = Session::get('lang', 'nl');
        $menuitem = 'naam_' . $languageSession;
        return Product::where('id', $productId)->first()->{$menuitem};
    }

    public function getVoorraad($productId){

    }



    public function shortDesc($productId)
    {
        $languageSession = Session::get('lang', 'nl');
        $menuitem = 'beschrijving_kort_' . $languageSession;
        return Product::where('id', $productId)->first()->{$menuitem};
    }

    public function getMainImage($productId)
    {
        $relations = ProductImgRel::where('productId', $productId)->get();

        $url = "";
        $images = array();

        foreach($relations as $relation){
            if(ProductImg::where('id', $relation->productImgId)->where('headImg', 1)->first() !== null){
                $images[] = ProductImg::where('id', $relation->productImgId)->where('headImg', 1)->first();
            }
        }

        foreach($images as $image){
            if(strpos($image->naam, "(small)") !== false){
                $url = "uploads/" . $image->directory . "/" . $image->naam;
            }
        }

        return $url;
    }
}
