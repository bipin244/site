<?php

use App\Category;
use App\Product;
use App\CategoryProduct;
// Home
Breadcrumbs::register('Home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('/home'));
});

Breadcrumbs::register('SubCategory', function($breadcrumbs)
{
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('SubCategory');
});
Breadcrumbs::register('Category', function($breadcrumbs,$id)
{
    $subCat = Category::find($id);
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('Product',route('subcategoryFilter',$id));
});

// Home > Test2 
Breadcrumbs::register('subcategoryFilter', function($breadcrumbs,$id)
{
	$subCat = Category::find($id);
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

//Home > category > Gebogen ijzerss
Breadcrumbs::register('productDetailsSub', function($breadcrumbs, $productId, $productNr)
{
	$subCat = Product::find($productId);
    $getCat = CategoryProduct::where('product_id',$productId)->get(['category_id'])->first();
    $subcategoryFilter = Session::get('subcategoryFilter')?Session::get('subcategoryFilter'):$getCat['category_id'];
    $breadcrumbs->parent('subcategoryFilter',$subcategoryFilter);
    $breadcrumbs->push($subCat->naam_nl, route('productdetailsSubProduct',['productId'=>$productId,'productNr'=> $productNr]));
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
    $breadcrumbs->push('Cart');
});
?>