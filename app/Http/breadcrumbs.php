<?php

use App\Category;
use App\CategoryProduct;
use App\Product;
// Home
Breadcrumbs::register('Home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('/home'));
});

// Breadcrumbs::register('SubCategory', function($breadcrumbs)
// {
//     $breadcrumbs->parent('Home');
//     $breadcrumbs->push('SubCategory');
// });
Breadcrumbs::register('Category', function($breadcrumbs,$id)
{
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('Product',route('subcategoryFilter',['id'=>$id]));
});

// Home > Test2
Breadcrumbs::register('subcategoryFilter', function($breadcrumbs,$id)
{
    $Cat = Category::find($id);
    if($Cat['subcategoryId']){
        $subCat = Category::find($Cat['subcategoryId']);
    }else{
        $subCat = $Cat; 
    }
    
    $breadcrumbs->parent('Home');
    $breadcrumbs->push($subCat->naam_nl, route('subcategoryFilter', $id));
});

// Home > poortframes
Breadcrumbs::register('subCategoryShow', function($breadcrumbs, $id, $clickedCategory)
{
    $subCat = Category::find($id);
    $breadcrumbs->parent('Home');
    $breadcrumbs->push($subCat->naam_nl, route('subCategoryShow',['id'=>$id,'clickedCategory'=> $clickedCategory]));
});

//Home > poortframes 
//Home > category > Gebogen ijzerss
Breadcrumbs::register('productDetailsSub', function($breadcrumbs, $productId, $productNr)
{
    $subCat = Product::find($productId);
    $allCats = Category::all('subcategoryId');
    $allCatIds = array();
    foreach($allCats as $currentCat){
        if($currentCat['subcategoryId'] != null){
            array_push($allCatIds,$currentCat['subcategoryId']);
        }
    }
    $getCat = DB::table('categorie_products')
        ->select('category_id')
        ->where('product_id',$productId)
        ->whereNotIn('category_id',$allCatIds)
        ->get();

    $showCat = false;
    if(sizeof($getCat) == 0){
        $getCat = DB::table('categorie_products')
            ->select('category_id')
            ->where('product_id',$productId)
            ->get();
        $showCat = true;
    }
    $selectedCats = Session::get('selectedCategories');
    $keyOfCat = "";
    if(sizeof($selectedCats) > 0){
        foreach($getCat as $key => $currentCat){
            if(in_array($currentCat->category_id,$selectedCats)){
                $keyOfCat = $key;

            }
        }
        if($keyOfCat == ""){
            $keyOfCat = 0;
        }
    }else{
        $keyOfCat = 0;
    }
    
    $subcategoryFilter = Session::get('subcategoryFilter')?Session::get('subcategoryFilter'):$getCat[$keyOfCat]->category_id;
    if($showCat){
        $breadcrumbs->parent('subCategoryShow',$subcategoryFilter,0);
    }else{
        $breadcrumbs->parent('subcategoryFilter',$subcategoryFilter);
    }

    $breadcrumbs->push($subCat->naam_nl, route('productdetailsSubProduct',['productId'=>$productId,'productNr'=> $productNr]));
    
    

    /*$subCat = Product::find($productId);
    $subcategoryFilter = Session::get('subcategoryFilter');
    if($subcategoryFilter == null){
        $categoryId = CategoryProduct::where('product_id',$productId)->first(['category_id']);
        $subcategoryFilter = $categoryId['category_id'];
        Session::put('subcategoryFilter',$subcategoryFilter);
    }
    $breadcrumbs->parent('subcategoryFilter',$subcategoryFilter);
    $breadcrumbs->push($subCat->naam_nl, route('productdetailsSubProduct',['productId'=>$productId,'productNr'=> $productNr]));*/
});

//Home > Promotions
Breadcrumbs::register('promotionPage', function($breadcrumbs)
{
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('Promotions');
});

//Home > Contact
Breadcrumbs::register('contact', function($breadcrumbs)
{
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('Contact');
});

//Home > Cart

Breadcrumbs::register('cart', function($breadcrumbs)
{
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('Offertemand');
});
?>