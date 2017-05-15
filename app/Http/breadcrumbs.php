<?php

use App\Category;
// Home
Breadcrumbs::register('Home', function($breadcrumbs)
{
    $breadcrumbs->push('Home', route('/home'));
});

// Home > Blog
Breadcrumbs::register('SubCategory', function($breadcrumbs)
{
    $breadcrumbs->parent('Home');
    $breadcrumbs->push('SubCategory');
});

Breadcrumbs::register('subcategoryFilter', function($breadcrumbs,$id)
{
	$subCat = Category::find($id);
    $breadcrumbs->parent('Home');
    $breadcrumbs->push($subCat->naam_nl, route('subcategoryFilter', $id));
});

// Home > About
// Breadcrumbs::register('subCategoryShow', function($breadcrumbs, $id, $clickedCategory)
// {
// 	echo " Id : ".$id;
//     $breadcrumbs->parent('SubCategory');
//     $breadcrumbs->push('SubCategory', route('subCategoryShow',$id,$clickedCategory));
// });
?>