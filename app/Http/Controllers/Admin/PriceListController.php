<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\PageImg;
use App\PageText;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\PriceList;
use App\ProductParam;
use Validation;
use Auth;
use Session;

class PriceListController extends Controller
{
    public function index()
    {
        return view('admin.priceList.list', ['pages' => PriceList::paginate(10) ]);
    }
    public function create()
    {
        $productNrData = ProductParam::get(['Id','productNr']);
        return view('admin.priceList.create')->with('productNr', $productNrData );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request){
        $input = $request->all();
        echo "<pre>";
        print_r($input);exit;
    }
}
