<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageText extends Model
{

    protected $fillable = ['page_id', 'language', 'key', 'content'];

    public function pagetextsedit($pageid)
    {
        $languageSession = Session::get('lang', 'nl');
        return PageText::where('page_id', $pageid)->where('language', $languageSession)->get();
    }

    public function updatetext(){

    }
}
