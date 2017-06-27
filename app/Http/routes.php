<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['uses' => 'PageController@index', 'as' => '/home']);
Route::get('/subCategoryShow/{id}/{clickedCategory}', ['uses' => 'PageController@subCategoryShow', 'as' => 'subCategoryShow']);

Route::get('/subcategoryFilter/{id}', ['uses' => 'PageController@subCategoryFilter', 'as' => 'subcategoryFilter']);
Route::get('/subSubCategoryShow/{id}/{clickedCategory}','PageController@subSubCategoryShow');
Route::get('/subCategory/{id}',['uses'=>'PageController@subCategoryClick','as'=>'subCategory']);
Route::get('/filteredProductsPOST', ['uses' => 'PageController@filteredProductsPOST', 'as' => 'filteredProductsPOST']);
Route::get('/productdetailsSubProduct/{productId}/{productNr}', ['uses' => 'PageController@productDetailsSub', 'as' => 'productdetailsSubProduct']);
Route::get('/updateText', 'PageController@updateText');
Route::get('/getRelatedItems', 'PageController@getRelatedItems');
Route::get('/relatedGet/{productNr}', 'PageController@relatedGet');
Route::get('/update/category/ajax', 'Admin\CategoryController@update');
Route::get('/searchingRelated', 'PageController@searchRelated');
Route::get('/search', 'PageController@search');
Route::get('/setProductOfNew', 'Admin\ProductController@setProductOfNew');
Route::get('/setProductToNew', 'Admin\ProductController@setProductToNew');
Route::get('/setProductToSale', 'Admin\ProductController@setProductToSale');
Route::get('/setProductOfSale', 'Admin\ProductController@setProductOfSale');
Route::get('/cart/open', 'PageController@cartOpen');
Route::get('/updateAmountCartItem', 'PageController@updateAmountCartItem');
Route::get('/removeCartItem','PageController@removeCartItem');
Route::get('/addtocart/single', 'PageController@addToCartSingle');
Route::get('/visitor/getDefaultAddress', 'PostController@getDefaultAddress');
Route::get('/user/orderHistoryList','PostController@getOrderHistory');
Route::get('/addtocart/afmeting', 'PageController@addToCart');
Route::get('/newCategory', 'Admin\ProductController@addCategory');
Route::get('/filterProducts', 'PageController@filterProducts');
Route::get('/newColor', 'Admin\ProductController@addColor');
Route::get('/newCoating', 'Admin\ProductController@addCoating');
Route::get('/updateColor', 'Admin\ProductController@updateColor');
Route::get('/promotionPage', ['uses' => 'PostController@promotionPage', 'as' => 'promotionPage']);
Route::get('/admin/configUpdate', 'PostController@configUpdate');

Route::get('/admin/langupdate', 'PostController@langUpdate');
//Route::get('/admin/showAllPriceLists','Admin\PriceListController@allPriceLists');
Route::get('/user/activateUser/{id}/{key}/{rawpass}', 'PostController@activateUser');
Route::get('/updateCoating', 'Admin\ProductController@updateCoating');
Route::get('/updateCategory', 'Admin\ProductController@updateCategory');
Route::get('/checkDeleteColor', 'Admin\ProductController@checkDeleteColor');
Route::get('/checkDeleteCoating', 'Admin\ProductController@checkDeleteCoating');
Route::get('/checkDeleteCategory', 'Admin\ProductController@checkDeleteCategory');
Route::get('/deleteColor', 'Admin\ProductController@deleteColor');
Route::get('/edit/{lang}', 'Admin\PageController@editLang');
Route::get('/color/{lang}', 'Admin\PageController@colorLang');
Route::get('/productdetails/{id}', 'PageController@productDetails');
Route::get('/admin/showAllProducts', 'Admin\ProductController@getAllProductsDataTable');
Route::get('/editMainProduct/{id}', 'Admin\ProductController@editMainProduct');
Route::get('/addSubProduct/{id}', 'Admin\ProductController@addSubProduct');
Route::get('/editSubProduct/{id}/{productNr}', 'Admin\ProductController@editSubProduct');
Route::post('/admin/product/updateMain', 'Admin\ProductController@updateMain');
Route::post('/user/loginUser','PostController@loginUser');
Route::post('/user/registerUser', 'PostController@registerUser');
Route::post('/admin/product/updateSub', 'Admin\ProductController@updateSub');
Route::get('/admin/pages/newTextKey/{id}', 'PostController@newTextKey');
Route::post('/send/sendOrder', 'PostController@sendOrder');
Route::get('/editMainProductSingle/{id}/{productNr}', 'Admin\ProductController@editMainProductSingle');
Route::post('/admin/product/updateMainSingle', 'Admin\ProductController@updateMainSingle');
Route::post('/admin/product/addSubtoHead', 'Admin\ProductController@addSubtoHead');
Route::post('/contact/send','PostController@contactFormSend');



Route::group(['middleware' => ['web'] ], function () {

    Route::get('login', 	['as'  	=> 'login', 	'uses' => 'Auth\AuthController@getLogin']);
    Route::post('login', 	['as' 	=> 'login', 	'uses' => 'Auth\AuthController@postLogin']);
    Route::get('logout', 	['as'  	=> 'logout', 	'uses' => 'Auth\AuthController@getLogout']);

    Route::group(['prefix' => 'admin', 'middleware' => ['auth'] ], function () {

    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth'] ], function () {
    Route::resource('showAllPriceLists','Admin\PriceListController');
    Route::get('showAllPriceLists/User/{id}', [ 'as' => 'admin.showAllPriceLists.user', 'uses' => 'Admin\PriceListController@getUser']);
    Route::post('showAllPriceLists/addUser', [ 'as' => 'admin.showAllPriceLists.addUser', 'uses' => 'Admin\PriceListController@addUser']);
    Route::get('/', 			'Admin\AdminController@index');
    Route::resource('user', 	'Admin\UserController');
    Route::resource('post', 	'Admin\PostController');
    Route::resource('page', 	'Admin\PageController');
    Route::resource('product',  'Admin\ProductController');

    Route::get('/clients', 'Admin\UserController@clients');
    Route::get('/saveKlantNr', 'Admin\UserController@saveKlantNr');
    Route::get('/passReset', 'Admin\UserController@passReset');
    Route::get('/setProductInactive/{productId}/{productNr}', 'Admin\ProductController@setProductInactive');
    Route::get('/configuration', 'PostController@adminConfiguration');
    Route::get('/category', 'Admin\CategoryController@index');
    Route::post('/category/update', 'Admin\CategoryController@update');
    Route::post('/category/order', 'Admin\CategoryController@updateOrder');
    Route::post('/clientDelete/{visitorId}','Admin\UserController@clientDelete');
    Route::get('/clientDelete/{visitorId}','Admin\UserController@clientDelete');
    Route::resource('upload', 	'Admin\UploadController');
    Route::delete('uploadDelete/{id}', 'Admin\UploadController@uploadDelete');
    Route::get('editColor', 'Admin\PageController@colors');
    Route::get('editCoating', 'Admin\PageController@coatings');
});
Route::resource('/{slug}/{lang?}', 'PageController@show');
