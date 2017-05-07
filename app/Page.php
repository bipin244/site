<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title_nl', 'title_fr', 'title_en', 'title_de', 'template', 'slug', 'status'];

    /**
     * Vertaling ophalen voor bepaald tekstveld van de pagina
     */
    public function veld($key)
    {

        $languageSession = Session::get('lang', 'nl');
        if($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr'){
            $languageSession = 'nl';
        }
        return PageText::where('key', $key)->where('language', $languageSession)->first()->content;
    }

    public function image($key)
    {
        return PageImg::where('key', $key)->first()->url;
    }

    public function productColorHex($id){
        return Color::where('id', $id)->first()->hex;
    }

    public function productColorText($id){
        $languageSession = Session::get('lang', 'nl');
        if($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr'){
            $languageSession = 'nl';
        }
        $colortext = 'naam_' . $languageSession;
        return Color::where('id', $id)->first()->{$colortext};
    }

    public function imagePath($id){
        $image = ProductImg::where('id',$id)->first();
        $url = "uploads/" . $image->directory . "/" . $image->naam;
        return $url;
    }

    public function getKleur($colorId){
        return Color::where('id', $colorId)->first()->naam_nl;
    }



    /**
     * Haalt de titel, vertaald, op vanuit de databank, of vanuit dit
     * Page-object indien geen slug meegegeven.
     */
    public function getTitle($slug = null)
    {
        $languageSession = Session::get('lang', 'nl');
        if($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr'){
            $languageSession = 'nl';
        }
        $menuitem = 'title_' . $languageSession;
        if($slug == null){
            return $this->{$menuitem};
        } else {
            return Page::where('slug', $slug)->first()->{$menuitem};
        }
    }
}
