<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\PageImg;
use App\PageText;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\PriceList;
use App\Visitor;
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
            return redirect()->route('admin.showAllPriceLists.index')->with('success', 'Successfully added price list!');
        }
    }
    public function getUser($id){
        $priceList = PriceList::where('Id',$id)->get(['Id','name']);
        $data = Visitor::where('activated','1')->get(['bedrijfsnaam','priceListId','id']);
        return view('admin.priceList.addUser', ['data' => $data, 'priceList' => $priceList[0]]);
    }
    public function addUser(Request $request){
        $input = $request->all();
        $success = "false";
        $userIds = explode(',', $input['selectedUserId'][0]);

        if($input['selectedUserId'][0]){
            foreach ($userIds as $key => $value) {
                $data = Visitor::where('id',$value)->get(['priceListId']);
                $oldPriceListId = $data[0]['priceListId'];
                if($oldPriceListId == ""){
                    $newPriceListId = $input['priceId'];
                }else{
                    if (!preg_match('/\b' . $input['priceId'] . '\b/', $oldPriceListId)) { 
                        $newPriceListId = $oldPriceListId.','.$input['priceId'];
                    }else{
                        $newPriceListId = $oldPriceListId;
                    }
                }
                $updateData = Visitor::where('id',$value)->update(['priceListId'=>$newPriceListId,'updated_at'=>date('Y-m-d H:i:s') ]);
                if(!$updateData){
                    $success = "true";
                }
            }
        }else{
            return redirect()->route('admin.showAllPriceLists.user',$input['priceId'])->with('error', 'Something wrong!');
        }
        if($success == "true"){
            return redirect()->route('admin.priceList.addUser',$input['priceId'])->with('error', 'Something wrong!');
        }else{
            return redirect()->route('admin.showAllPriceLists.index')->with('success', 'Successfully added user!');
        }

    }
    public function edit($id){
        $priceList = PriceList::find($id);
        $productpriceList = ProductPriceList::where('price_list_id',$id)->get();
        $productNr  = ProductParam::get(['Id','productNr']);
        return view('admin.priceList.edit')->with('priceList', $priceList )->with('productpriceList', $productpriceList )->with('productNr', $productNr  );
    }

    public function update(Request $request,$id){
        $input = $request->all();
        $Priority = '0';
        if($input['PriorityCheckbox'] == 'true'){
            $Priority = '1';  
        }
        $priceList = PriceList::where('Id',$id)->update(['name' => $input['name'],'Priority' => $Priority]);
        if($priceList){
            $error = false;
            if(isset($input['price'])){
                foreach ($input['price'] as $key => $value) {
                    $ProductPriceList = ProductPriceList::where('price_list_id',$id)->where('productNr',$key)->count();
                    if($ProductPriceList == 0){
                        $ProductPriceList = new ProductPriceList();
                        $ProductPriceList->price_list_id = $id;
                        $ProductPriceList->productNr = $key;
                        $ProductPriceList->priority = $Priority;
                        $ProductPriceList->price = $value;
                        if(!$ProductPriceList->save()){
                            $error = true;
                        }
                    }else{
                        ProductPriceList::where('price_list_id',$id)->where('productNr',$key)->update(['price' => $value,'priority' => $Priority]);
                    }
                    
                }
            }
        }else{
            return redirect()->route('admin.showAllPriceLists.edit')->with('error', 'Something wrong!');
        }
        if($error){
            return redirect()->route('admin.showAllPriceLists.edit')->with('error', 'Something wrong!');
        }else{
            return redirect()->route('admin.showAllPriceLists.index')->with('success', 'Successfully edited price list!');
        }
    }
}
