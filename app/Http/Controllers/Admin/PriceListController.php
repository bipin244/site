<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\PageImg;
use App\PageText;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\PriceList;
use App\ProductPriceList;
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
        $Priority = '0';
        if($input['PriorityCheckbox'] == true){
            $Priority = '1';  
        }
        $priceList = new PriceList();
        $priceList->name = $input['name'];
        $priceList->Priority = $Priority;
        if($priceList->save()){
            $error = false;
            $priceListId =$priceList->id;
            if(isset($input['price'])){

                foreach ($input['price'] as $key => $value) {
                    $ProductPriceList = new ProductPriceList();
                    $ProductPriceList->price_list_id = $priceListId;
                    $ProductPriceList->productNr = $key;
                    $ProductPriceList->priority = $Priority;
                    $ProductPriceList->price = $value;
                    if(!$ProductPriceList->save()){
                        $error = true;
                    }
                }
            }
        }else{
            return redirect()->route('admin.showAllPriceLists.create')->with('error', 'Something wrong!');
        }
        if($error){
            return redirect()->route('admin.showAllPriceLists.create')->with('error', 'Something wrong!');
        }else{
            session()->flash('msg','Hey, You have a message to read');
            return redirect()->route('admin.showAllPriceLists.index')->with('success', 'Successfully added!');
        }
    }
}
