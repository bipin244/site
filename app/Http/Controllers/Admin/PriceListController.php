<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\PageImg;
use App\PageText;
use App\Color;
use App\Coating;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Page;
use Validation;
use Auth;
use Session;

class PriceListController extends Controller
{
    public function index()
    {
        return view('admin.page.list', ['pages' => Page::paginate(10) ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function allPriceLists(){

    }
}
