<?php

namespace App\Http\Controllers;

use App\CategoryImg;
use App\Color;
use App\Http\Controllers\Controller;
use DB;
use App\Page;
use App\Category;
use App\CartItem;
use URL;
use App\PageText;
use App\Coating;
use App\Product;
use App\ProductParam;
use App\ProductImgRel;
use App\ProductImg;
use App\ProductRelated;
use App\Post;
use Session;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function index()
    {
        return $this->show("index");
    }

    public function show($slug, $lang = null)
    {
        if ($lang != null) {
            Session::put('lang', $lang);
        }

        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if ($slug == 'subcategoryshow') {
            return $this->subCategoryShow(null, null);
        }

        if ($slug == 'productenfiltered') {
            return $this->subCategoryFilter(null);
        }

        $cartItems = Session::get('cartItems', 'null');

        if ($cartItems != 'null') {
            $amountItems = count($cartItems);
            Session::put('cartAmount', $amountItems);
        }

        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $page = Page::where('slug', $slug)->first();
        if ($page == null) abort(404); // pagina niet gevonden

        if($slug == "promotionpage"){
            return PostController::promotionPage();
        }

        if ($slug == "index") {
            $hoofdCategories = Category::where('subcategoryId', NULL)->orderBy('order')->get();
            $subCategories = Category::where('subcategoryId', '!=', NULL)->orderBy('order')->get();


            foreach ($hoofdCategories as $key => $hoofdCategory) {
                $subsTemp = array();
                foreach ($subCategories as $subCategory) {
                    if ($hoofdCategory->id == $subCategory->subcategoryId) {
                        $subsTemp[] = $subCategory;
                    }
                }
                $hoofdCategories[$key]->subCategories = $subsTemp;
                $subCategoriesTemp = $subsTemp;

                if(sizeof($hoofdCategories[$key]->subCategories) > 0){
                    foreach($hoofdCategories[$key]->subCategories as $key2 => $subCategoryfromHoofd){
                        $subsTemp2 = array();
                        foreach ($subCategories as $subCategory) {
                            if ($subCategoryfromHoofd->id == $subCategory->subcategoryId) {
                                $subsTemp2[] = $subCategory;
                            }
                        }
                        $subCategoriesTemp[$key2]->subCategories = $subsTemp2;
                    }
                }
                $hoofdCategories[$key]->subCategories = $subCategoriesTemp;
            }


            $producten = DB::table('products')
                ->join('product_params', 'products.id', '=', 'product_params.productId')
                ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
                ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
                ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
                ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
                ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en','product_params.created_at as creation_time'])
                ->where('headImg', 1)
                ->where('productNrImg', NULL)
                ->where('naam', 'like', '%(small).%')
                ->where('nieuwParam', 1)
                ->orderBy('product_params.created_at','DESC')
                ->get();

            $prevProductNrs = array();

            foreach($producten as $key => $productFilter){
                if(in_array($productFilter->productNr, $prevProductNrs)){
                    unset($producten[$key]);
                }else{
                    $prevProductNrs[] = $productFilter->productNr;
                }
            }

            $productenSingle = DB::table('products')
                ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
                ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
                ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en','products.created_at as creation_time'])
                ->where('headImg', 1)
                ->whereNotNull('products.productNr')
                ->where('naam', 'like', '%(small).%')
                ->where('productNrImg', NULL)
                ->where('nieuw', 1)
                ->orderBy('products.created_at','DESC')
                ->get();

            foreach($producten as $key => $product){
                $producten[$key]->paramProduct = true;
            }

            foreach($productenSingle as $key => $product){
                $productenSingle[$key]->singleProduct = true;
            }

            $allProducts = array();

            $tellerSingle = 0;



            foreach($producten as $product){
                if(sizeof($productenSingle) > $tellerSingle){
                    if($product->creation_time >= $productenSingle[$tellerSingle]->creation_time){
                        $allProducts[] = $productenSingle[$tellerSingle];
                        $tellerSingle++;
                    }else{
                        $allProducts[] = $product;
                    }
                }else{
                    $allProducts[] = $product;
                }
            }




            return view('front.cms.' . $page->template, ['page' => $page, 'categories' => $hoofdCategories, 'lang' => $languageSession, 'tellerFilter' => 0,'tellerFilterSub' => 1000,'allProducts' => $allProducts]);
        }

        if ($slug == "producten") {
            $producten = DB::table('products')
                ->join('product_params', 'products.id', '=', 'product_params.productId', 'left outer')
                ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId', 'left outer')
                ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id', 'left outer')
                ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
                ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
                ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
                ->where('headImg', 1)->where('naam', 'like', '%(small)%')->get();

            $hoofdCategories = Category::where('subcategoryId', NULL)->get();
            $subCategories = Category::where('subcategoryId', '!=', NULL)->get();


            foreach ($hoofdCategories as $key => $hoofdCategory) {
                $subsTemp = array();
                foreach ($subCategories as $subCategory) {
                    if ($hoofdCategory->id == $subCategory->subcategoryId) {
                        $subsTemp[] = $subCategory;
                    }
                }
                $hoofdCategories[$key]->subCategories = $subsTemp;
            }


            return view('front.cms.' . $page->template, ['page' => $page, 'producten' => $producten, 'lang' => $languageSession, 'categories' => $hoofdCategories, 'tellerFilter' => 0]);
        } else {
            return view('front.cms.' . $page->template, ['page' => $page]);
        }
        //return view('front.cms.show', ['page' => Page::where('slug', $slug)->first()]);
    }

    public function filterProducts(Request $request)
    {
        $categoryIds = json_decode(stripslashes($request->input('filterCategoryIds')));

        $lang = Session::get('lang', 'nl');

        if ($lang != 'nl' && $lang != 'de' && $lang != 'en' && $lang != 'fr') {
            $lang = 'nl';
        }

        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->join('categorie_products', 'products.id', '=', 'categorie_products.product_id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)->where('naam', 'like', '%(small)%')->whereIn('categorie_products.category_id', $categoryIds)->get();

        $data["producten"] = $producten;

        $html = "";

        $teller = 1;

        foreach ($producten as $key => $product) {
            $imagebackground = 'background-image: url("' . (URL::asset("uploads/" . $product->directory . "/" . $product->naam)) . '")';
            if ($teller == 1) {
                $html .= '<div class="row">';
            }
            if ($teller > 1) {
                if ($producten[$key - 1]->id != $product->id) {
                    $html .= '<div class="col-md-4">';
                    $html .= '<a href="/productdetails/' . $product->product_id . '">';
                    $html .= '<div class="product-image-wrapper">';
                    $html .= '<div class="single-products">';
                    $html .= '<div class="productinfo text-center">';
                    $html .= '<div class="productimg" style="' . htmlspecialchars($imagebackground) . '"></div>';
                    $html .= '<h2>' . $product->{"naam_" . $lang} . '</h2>';
                    $html .= '<p>' . $product->{"beschrijving_kort_" . $lang} . '</p>';
                    $html .= '<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="choose">';
                    $html .= '<ul class="nav nav-pills nav-justified">';
                    $html .= '<li><a href="/productdetails/' . $product->product_id . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                    $html .= '</ul>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</a>';
                    $html .= '</div>';
                }
            } else {
                $html .= '<div class="col-md-4">';
                $html .= '<a href="/productdetails/' . $product->product_id . '">';
                $html .= '<div class="product-image-wrapper">';
                $html .= '<div class="single-products">';
                $html .= '<div class="productinfo text-center">';
                $html .= "<div class='productimg' style='" . $imagebackground . "'></div>";
                $html .= '<h2>' . $product->{"naam_" . $lang} . '</h2>';
                $html .= '<p>' . $product->{"beschrijving_kort_" . $lang} . '</p>';
                $html .= '<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="choose">';
                $html .= '<ul class="nav nav-pills nav-justified">';
                $html .= '<li><a href="/productdetails/' . $product->product_id . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</div>';
            }
            if ($teller % 3 == 0) {
                $html .= '</div>';
                $html .= '<div class="row">';
            }
            if ($teller % 3 != 0 && $teller == sizeof($producten)) {
                $html .= '</div>';
            }
            $teller++;
        }

        $data["html"] = $html;

        return $html;
    }

    public function productPage($page, $languageSession)
    {
        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)->where('naam', 'like', '%(small)%')->get();

        $hoofdCategories = Category::where('subcategoryId', NULL)->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->get();

        dd($producten);

        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
        }

        return view('front.cms.' . $page->template, ['page' => $page, 'producten' => $producten, 'lang' => $languageSession, 'categories' => $hoofdCategories]);
    }

    public function relatedGet(Request $request, $productNr)
    {

        $product = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('product_params.productNr', $productNr)
            ->first();


        return json_encode($product);
    }

    public function getRelatedItems(Request $request)
    {
        $productNr = $request->input('productNr');

        $productId = $request->input('productId');

        $data["imagesSmall"] = DB::table('product_imgs')
            ->where('productNrImg', $productNr)->where('naam', 'like', '%(small)%')
            ->get();

        $data["imagesXsmall"] = DB::table('product_imgs')
            ->where('productNrImg', $productNr)
            ->where('naam', 'like', '%(xsmall)%')
            ->get();

        $data["relatedProducts"] = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->where('product_related.productNr1', $productNr)
            ->where('product_imgs.naam', 'like', '%(small)%')
            ->get();

        $productNrsFromDB = array();

        foreach ($data["relatedProducts"] as $product) {
            $productNrsFromDB[] = $product->productNr;
        }

        $productImgs = DB::table('product_imgs')
            ->where('naam', 'like', '%(small)%')
            ->whereIn('productNrImg', $productNrsFromDB)
            ->get();

        foreach ($productImgs as $productImg) {
            $key = array_search($productImg->productNrImg, $productNrsFromDB);
            $data["relatedProducts"][$key]->productNrImg[] = $productImg;
        }

        $data["relatedProductsMain"] = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->where('product_related.product1', $productId)
            ->where('product_imgs.naam', 'like', '%(small)%')
            ->get();

        $data['images'] = DB::table('product_imgs')
            ->where('productNrImg', $productNr)
            ->where('naam', 'like', '%(small)%')->get();

        return json_encode($data);
    }

    public function searchRelated(Request $request)
    {
        $filterString = $request->input('keyword');


        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('products.naam_nl', 'like', '%' . $filterString . '%')
            ->orWhere('products.naam_fr', 'like', '%' . $filterString . '%')
            ->orWhere('products.naam_de', 'like', '%' . $filterString . '%')
            ->orWhere('products.naam_en', 'like', '%' . $filterString . '%')
            ->orWhere('products.productNr', 'like', '%' . $filterString . '%')
            ->orWhere('product_params.productNr', 'like', '%' . $filterString . '%')
            ->get();

        /*$producten[] = DB::table('product_params')
             ->join('products', 'product_params.productId', '=', 'products.id')
             ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId','left outer')
             ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id','left outer')
             ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id','left outer')
             ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id','left outer')
             ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en'])
             ->where('headImg', 1)
            ->where('naam', 'like', '%(xsmall)%')
            ->where('product_params.productNr', 'like', '%' . $filterString . '%')
            ->get();*/

        $productenresult = array();


        /*foreach($producten as $key => $product){
            if(empty($product)){

            }else{
                foreach($product as $subproduct){

                    $addToResult = true;

                    if(!$productenresult){

                    }else{
                        foreach($productenresult as $productresult){
                            if($subproduct->product_id == $productresult->product_id){
                                $addToResult = false;
                            }
                        }
                    }

                    if($addToResult){
                        $productenresult[] = $subproduct;
                    }
                }
            }
        }*/

        $productsFinally = array();

        $productsFinally[] = $producten[0];

        if ($request->has('json')) {
            return json_encode($productsFinally);
        } else {
            $html = "";
            $languageSession = Session::get('lang', 'nl');
            if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
                $languageSession = 'nl';
                Session::put('lang', 'nl');
            }

            $resultCounter = 0;

            $productenFinal = array();

            foreach ($producten as $product) {
                if ($resultCounter < 1) {
                    $productenFinal[] = $product;
                    $resultCounter++;
                }
            }


            return json_encode("tand");
        }
    }


    public function subSubCategoryShow($id, $clickedCategory)
    {
        if ($id != null) {
            Session::put('categoryId', $id);
            Session::put('clickedSubCategory', $clickedCategory);
            Session::put('clickedSubCategoryId', $id);
        } else {
            $id = Session::get('categoryId');
            $clickedCategory = Session::get('clickedCategory');
        }

        $categories = DB::table('categories')
            ->where('subcategoryId', $id)
            ->orderBy('order')
            ->get();

        foreach($categories as $key => $category){
            $categories[$key]->image = CategoryImg::where('categoryId',$category->id)
                ->where('naam','like','%(small).%')
                ->orderBy('created_at','DESC')
                ->first();
        }

        $page = Page::where('slug', 'subcategoryshow')->first();

        $languageSession = Session::get('lang', 'nl');
        if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId', 'left outer')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId', 'left outer')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id', 'left outer')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)->where('naam', 'like', '%(small)%')->get();

        $hoofdCategories = Category::where('subcategoryId', NULL)->orderBy('order')->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->orderBy('order')->get();


        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
            $subCategoriesTemp = $subsTemp;

            if(sizeof($hoofdCategories[$key]->subCategories) > 0){
                foreach($hoofdCategories[$key]->subCategories as $key2 => $subCategoryfromHoofd){
                    $subsTemp2 = array();
                    foreach ($subCategories as $subCategory) {
                        if ($subCategoryfromHoofd->id == $subCategory->subcategoryId) {
                            $subsTemp2[] = $subCategory;
                        }
                    }
                    $subCategoriesTemp[$key2]->subCategories = $subsTemp2;
                }
            }
            $hoofdCategories[$key]->subCategories = $subCategoriesTemp;
        }

        return view('front.cms.productensubcategory', ['page' => $page, 'producten' => $producten, 'lang' => $languageSession, 'categories' => $hoofdCategories, 'tellerFilter' => 0, 'tellerFilterSub' => 1000,'clickedSubCategoryId' => $id, 'selectedCategories' => $categories]);
    }


    public function subCategoryShow($id, $clickedCategory)
    {
        if ($id != null) {
            Session::put('categoryId', $id);
            Session::put('clickedCategory', $clickedCategory);
            Session::put('clickedSubCategory', '');
            Session::put('clickedSubCategoryId', '');
        } else {
            $id = Session::get('categoryId');
            $clickedCategory = Session::get('clickedCategory');
        }

        Session::put('clickedCategory', $clickedCategory);
        $categories = DB::table('categories')
            ->where('subcategoryId', $id)
            ->orderBy('order')
            ->get();

        foreach($categories as $key => $category){
            $categories[$key]->image = CategoryImg::where('categoryId',$category->id)
                ->where('naam','like','%(small).%')
                ->orderBy('created_at','DESC')
                ->first();
        }


        Session::put('clickedSubCategory', '');

        $page = Page::where('slug', 'subcategoryshow')->first();

        $languageSession = Session::get('lang', 'nl');
        if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId', 'left outer')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId', 'left outer')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id', 'left outer')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)->where('naam', 'like', '%(small)%')->get();

        $hoofdCategories = Category::where('subcategoryId', NULL)->orderBy('order')->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->orderBy('order')->get();

        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
            $subCategoriesTemp = $subsTemp;

            if(sizeof($hoofdCategories[$key]->subCategories) > 0){
                foreach($hoofdCategories[$key]->subCategories as $key2 => $subCategoryfromHoofd){
                    $subsTemp2 = array();
                    foreach ($subCategories as $subCategory) {
                        if ($subCategoryfromHoofd->id == $subCategory->subcategoryId) {
                            $subsTemp2[] = $subCategory;
                            foreach($categories as $key3 => $category){
                                if($category->id == $subCategory->subcategoryId){
                                    $categories[$key3]->hasChilds = true;
                                }
                            }
                        }
                    }
                    $subCategoriesTemp[$key2]->subCategories = $subsTemp2;

                }
            }
            $hoofdCategories[$key]->subCategories = $subCategoriesTemp;
        }
        return view('front.cms.productensubcategory', ['page' => $page, 'producten' => $producten, 'lang' => $languageSession, 'categories' => $hoofdCategories, 'tellerFilter' => 0, 'tellerFilterSub' => 1000, 'tellerFilterSub2' => 1000, 'selectedCategories' => $categories,'id'=>$id,'clickedCategory'=>$clickedCategory]);
    }

    public function filteredProductsPOST(Request $request)
    {
        $url = url('/');
        $languageSession = Session::get('lang', 'nl');
        if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $filterData = json_decode($request->input('filterData'));

        $hoofdCategories = Category::where('subcategoryId', NULL)->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->get();


        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
        }



        if (sizeof($filterData->selectedCategories) == 0) {
            $html = "";
            /*geen product subcategory geselecteerd*/

            $page = Page::where('slug', 'subcategoryshow')->first();

            $id = Session::get('categoryId');
            $clickedCategory = Session::get('clickedCategory');

            $selectedCategories = DB::table('categories')
                ->where('subcategoryId', $id)
                ->get();

                $teller = 1;
            foreach ($selectedCategories as $selectedCategory) {
                if ($teller > 1) {
                    if ($teller == 1) {
                        $html .= '<div class="row">';
                    }

                        $html .= '<div class="col-md-4">';
                        $html .= '<a href="/subcategoryFilter/' . $selectedCategory->id . '">';
                        $html .= '<div class="product-image-wrapper">';
                        $html .= '<div class="single-products">';
                        $html .= '<div class="productinfo text-center">';
                        $html .= '<div class="productimg"></div>';
                        $html .= '<h2>' . $selectedCategory->{"naam_" . $languageSession} . '</h2>';
                        $html .= '<a href="/subcategoryFilter/' . $selectedCategory->id . '" class="btn btn-default add-to-cart">Bekijk deze categorie</a>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="choose">';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</a>';
                        $html .= '</div>';

                if ($teller % 3 == 0) {
                    $html .= '</div>';
                    $html .= '<div class="row">';
                }
                if ($teller % 3 != 0 && $teller == sizeof($selectedCategories)) {
                    $html .= '</div>';
                }
                 $teller++;
                 $onthoud = $selectedCategory->id;
                 } else {
                        if ($teller == 1) {
                            $html .= '<div class="row">';
                        }
                        $html .= '<div class="col-md-4">';
                        $html .= '<a href="/subcategoryFilter/' . $selectedCategory->id . '">';
                                $html .= '<div class="product-image-wrapper">';
                                    $html .= '<div class="single-products">';
                                        $html .= '<div class="productinfo text-center">';
                                            $html .= '<div class="productimg"></div>';
                                            $html .= '<h2>' . $selectedCategory->{"naam_" . $languageSession} . '</h2>';
                                            $html .= '<a href="/subcategoryFilter/{{$selectedCategory->id}}" class="btn btn-default add-to-cart">Bekijk deze categorie</a>';
                                            $html .= '</div>';
                                        $html .= '</div>';
                                    $html .= '<div class="choose">';
                                        $html .= '</div>';
                                    $html .= '</div>';
                                $html .= '</a>';
                            $html .= '</div>';

                    if ($teller % 3 == 0) {
                        echo '</div>';
                        $html .= '<div class="row">';
                    }
                    if ($teller % 3 != 0 && $teller == sizeof($selectedCategories)) {
                        $html .= '</div>';
                    }
                    $teller++;
                    $onthoud = $selectedCategory->id;
                    }
                }

            $data['htmlout'] = $html;
            $data['nuut'] = "";

            return json_encode($data);
        } else {
            $producten = DB::table('products')
                ->join('product_params', 'products.id', '=', 'product_params.productId', 'left outer')
                ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
                ->join('product_imgs as imgs1', 'product_img_relations.productImgId', '=', 'imgs1.id')
                ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
                ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
                ->join('categorie_products', 'products.id', '=', 'categorie_products.product_id', 'left outer')
                ->select(['*', 'products.id as product_id', 'products.productNr as product_productNr', 'imgs1.id as imgs1_id', 'imgs1.naam as imgs1_naam', 'imgs1.directory as imgs1_directory', 'product_colors.id as color_id', 'product_coatings.id as coating_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
                ->where('imgs1.headImg', 1)
                ->where('imgs1.naam', 'like', '%(small)%')
                ->whereIn('categorie_products.category_id', $filterData->selectedCategories);

            if(sizeof($filterData->selectedColors) > 0){
                $producten = $producten->whereIn('product_params.colorId', $filterData->selectedColors);
            }
            if(sizeof($filterData->selectedCoatings) > 0){
                $producten = $producten->whereIn('product_params.coatingId', $filterData->selectedCoatings);
            }
            if(sizeof($filterData->selectedAfmetingen) > 0){
                $producten = $producten->whereIn('product_params.afmeting', $filterData->selectedAfmetingen);
            }

            $producten = $producten->get();

            $productNrsFromDB = array();

            foreach ($producten as $product) {
                $productNrsFromDB[] = $product->productNr;
            }

            $productImgs = DB::table('product_imgs')
                ->where('naam', 'like', '%(small)%')
                ->whereIn('productNrImg', $productNrsFromDB)
                ->get();

            foreach ($productImgs as $productImg) {
                $key = array_search($productImg->productNrImg, $productNrsFromDB);
                $producten[$key]->productNrImg[] = $productImg;
            }

            $data["producten"] = $producten;

            $html = "";

            $teller = 1;

            $colors = array();

            $coatings = array();

            foreach ($producten as $key => $product) {
                $afmetingen[] = $product->afmeting;
                $color = new Color();
                $color->id = $product->color_id;
                $color->color_naam_nl = $product->color_naam_nl;
                $color->color_naam_fr = $product->color_naam_fr;
                $color->color_naam_de = $product->color_naam_de;
                $color->color_naam_en = $product->color_naam_en;
                $colors[] = $color;

                $coating = new Coating();
                $coating->id = $product->coating_id;
                $coating->coatingnaam_nl = $product->coatingnaam_nl;
                $coating->coatingnaam_fr = $product->coatingnaam_fr;
                $coating->coatingnaam_de = $product->coatingnaam_de;
                $coating->coatingnaam_en = $product->coatingnaam_en;
                $coatings[] = $coating;
                if ($product->productNrImg != null) {
                    $imagebackground = 'background-image: url("' . (URL::asset("uploads/" . $product->productNrImg[0]->directory . "/" . $product->productNrImg[0]->naam)) . '")';
                } else {
                    $imagebackground = 'background-image: url("' . (URL::asset("uploads/" . $product->directory . "/" . $product->naam)) . '")';
                }

                if ($teller == 1) {
                    $html .= '<div class="row">';
                }
                if ($teller > 1) {
                    $sameAsPrev = false;
                    if ($producten[$key - 1]->productNr != null && $product->productNr != null) {

                        if ($producten[$key - 1]->productNr == $product->productNr) {
                            $sameAsPrev = true;
                        }
                    } elseif ($producten[$key - 1]->productNr != null) {
                        if ($producten[$key - 1]->productNr == $product->product_productNr) {
                            $sameAsPrev = true;
                        }
                    } elseif ($product->productNr != null) {
                        if ($producten[$key - 1]->product_productNr == $product->productNr) {
                            $sameAsPrev = true;
                        }
                    }elseif($producten[$key - 1]->product_productNr == $product->product_productNr){
                        $sameAsPrev = true;
                    }
                }


                if ($teller > 1) {
                    if (!$sameAsPrev) {
                        /*if($key = 4){
                            $tempData["html"] = $html;
                            $tempData["product"] = $producten[$key];
                            return json_encode($tempData);
                        }*/
                        $html .= '<div class="col-md-4">';
                        if ($product->productNr == "" || $product->productNr == null) {
                            $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '">';
                        } else {
                            $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '">';
                        }
                        $html .= '<div class="product-image-wrapper">';
                        $html .= '<div class="single-products">';
                        $html .= '<div class="productinfo text-center">';
                        $html .= '<div class="productimg" style="' . htmlspecialchars($imagebackground) . '"></div>';
                        $html .= '<h2>' . $product->{"product_naam_" . $languageSession} . ' ';
                        if ($product->color_id != 0 && $product->color_id != null) {
                            $html .= $product->{"color_naam_" . $languageSession} . ' ';
                        }
                        if ($product->coating_id != 0 && $product->coating_id != null) {
                            $html .= $product->{"coatingnaam_" . $languageSession} . ' ';
                        }
                        if ($product->afmeting != '' && $product->afmeting != null) {
                            $html .= $product->afmeting;
                        }
                        $html .= '</h2>';
                        $html .= '<p>' . $product->{"beschrijving_kort_" . $languageSession} . '</p>';
                        $html .= '<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '<div class="choose">';
                        $html .= '<ul class="nav nav-pills nav-justified">';
                        if ($product->productNr == "" || $product->productNr == null) {
                            $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                        } else {
                            $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                        }
                        $html .= '</ul>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</a>';
                        $html .= '</div>';
                    }
                } else {
                    $html .= '<div class="col-md-4">';
                    if ($product->productNr == "") {
                        $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '">';
                    } else {
                        $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '">';
                    }
                    $html .= '<div class="product-image-wrapper">';
                    $html .= '<div class="single-products">';
                    $html .= '<div class="productinfo text-center">';
                    $html .= "<div class='productimg' style='" . $imagebackground . "'></div>";
                    $html .= '<h2>' . $product->{"product_naam_" . $languageSession} . ' ';
                    if ($product->color_id != 0) {
                        $html .= $product->{"color_naam_" . $languageSession} . ' ';
                    }
                    if ($product->coating_id != 0) {
                        $html .= $product->{"coatingnaam_" . $languageSession} . ' ';
                    }
                    if ($product->afmeting != '' && $product->afmeting != null) {
                        $html .= $product->afmeting;
                    }
                    $html .= '</h2>';
                    $html .= '<p>' . $product->{"beschrijving_kort_" . $languageSession} . '</p>';
                    $html .= '<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="choose">';
                    $html .= '<ul class="nav nav-pills nav-justified">';
                    if ($product->productNr == "") {
                        $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                    } else {
                        $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                    }
                    $html .= '</ul>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</a>';
                    $html .= '</div>';
                }
                if ($teller % 3 == 0) {
                    $html .= '</div>';
                    $html .= '<div class="row">';
                }
                if ($teller % 3 != 0 && $teller == sizeof($producten)) {
                    $html .= '</div>';
                }
                $teller++;
            }

            if (sizeof($producten) == 0) {
                $html = '<div class="row"><h3>Er zijn nog geen producten voor deze subcategory</h3></div>';
            }
            $data["htmlout"] = $html;
            $data["afmetingen"] = array_unique($afmetingen);
            $data["colors"] = array_unique($colors);
            $data["coatings"] = array_unique($coatings);

            return json_encode($data);
            /*return json_encode($data);*/
        }

    }

    public function subCategoryFilter($id)
    {
        Session::put('subcategoryFilter',$id);
        $url = url('/');
        $languageSession = Session::get('lang', 'nl');
        if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $hoofdCategories = Category::where('subcategoryId', NULL)->orderBy('order')->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->orderBy('order')->get();

        $filterDataCurrent = Session::get('filterData', null);

        if ($filterDataCurrent == null) {
            $emptyFilterData = new \stdClass();
            $emptyFilterData->selectedCategories = [$id];
            $emptyFilterData->selectedColors = [];
            $emptyFilterData->selectedCoatings = [];
            $emptyFilterData->selectedAfmetingen = [];
            $filterDataCurrent = $emptyFilterData;
        }


        if ($id != null) {
            Session::put('categoryFilterId', $id);
        } else {
            $id = Session::get('categoryFilterId');
        }


        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
            $subCategoriesTemp = $subsTemp;

            if(sizeof($hoofdCategories[$key]->subCategories) > 0){
                foreach($hoofdCategories[$key]->subCategories as $key2 => $subCategoryfromHoofd){
                    $subsTemp2 = array();
                    foreach ($subCategories as $subCategory) {
                        if ($subCategoryfromHoofd->id == $subCategory->subcategoryId) {
                            $subsTemp2[] = $subCategory;
                        }
                    }
                    $subCategoriesTemp[$key2]->subCategories = $subsTemp2;
                }
            }
            $hoofdCategories[$key]->subCategories = $subCategoriesTemp;
        }

        $categoryIds[] = $id;

        $page = Page::where('slug', 'productenfiltered')->first();


        $lang = Session::get('lang', 'nl');

        if ($lang != 'nl' && $lang != 'de' && $lang != 'en' && $lang != 'fr') {
            $lang = 'nl';
        }

        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId', 'left outer')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs as imgs1', 'product_img_relations.productImgId', '=', 'imgs1.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->join('categorie_products', 'products.id', '=', 'categorie_products.product_id', 'left outer')
            ->select(['*', 'products.id as product_id', 'products.productNr as product_productNr', 'imgs1.id as imgs1_id', 'imgs1.naam as imgs1_naam', 'imgs1.directory as imgs1_directory', 'product_colors.id as color_id', 'product_coatings.id as coating_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('imgs1.headImg', 1)
            ->where('imgs1.naam', 'like', '%(small)%')
            ->whereIn('categorie_products.category_id', $categoryIds)->get();

        $productNrsFromDB = array();

        foreach ($producten as $product) {
            $productNrsFromDB[] = $product->productNr;
        }

        $productImgs = DB::table('product_imgs')
            ->where('naam', 'like', '%(small)%')
            ->whereIn('productNrImg', $productNrsFromDB)
            ->get();

        foreach ($productImgs as $productImg) {
            $key = array_search($productImg->productNrImg, $productNrsFromDB);
            $producten[$key]->productNrImgs[] = $productImg;
        }

        $data["producten"] = $producten;

        $html = "";

        $teller = 1;

        $colors = array();

        $coatings = array();

        $afmetingen = array();

        foreach ($producten as $key => $product) {
            $afmetingen[] = $product->afmeting;
            $color = new Color();
            $color->id = $product->color_id;
            $color->color_naam_nl = $product->color_naam_nl;
            $color->color_naam_fr = $product->color_naam_fr;
            $color->color_naam_de = $product->color_naam_de;
            $color->color_naam_en = $product->color_naam_en;
            $colors[] = $color;

            $coating = new Coating();
            $coating->id = $product->coating_id;
            $coating->coatingnaam_nl = $product->coatingnaam_nl;
            $coating->coatingnaam_fr = $product->coatingnaam_fr;
            $coating->coatingnaam_de = $product->coatingnaam_de;
            $coating->coatingnaam_en = $product->coatingnaam_en;
            $coatings[] = $coating;
            if ($product->productNrImg != null) {
                $imagebackground = 'background-image: url("' . (URL::asset("uploads/" . $product->productNrImg[0]->directory . "/" . $product->productNrImg[0]->naam)) . '")';
            } else {
                $imagebackground = 'background-image: url("' . (URL::asset("uploads/" . $product->directory . "/" . $product->naam)) . '")';
            }

            if ($teller == 1) {
                $html .= '<div class="row">';
            }
            if ($teller > 1) {
                $sameAsPrev = false;
                if ($producten[$key - 1]->productNr != null && $product->productNr != null) {

                    if ($producten[$key - 1]->productNr == $product->productNr) {
                        $sameAsPrev = true;
                    }
                } elseif ($producten[$key - 1]->productNr != null) {
                    if ($producten[$key - 1]->productNr == $product->product_productNr) {
                        $sameAsPrev = true;
                    }
                } elseif ($product->productNr != null) {
                    if ($producten[$key - 1]->product_productNr == $product->productNr) {
                        $sameAsPrev = true;
                    }
                }elseif($producten[$key - 1]->product_productNr == $product->product_productNr){
                    $sameAsPrev = true;
                }
            }


            if ($teller > 1) {
                if (!$sameAsPrev) {
                    /*if($key = 4){
                        $tempData["html"] = $html;
                        $tempData["product"] = $producten[$key];
                        return json_encode($tempData);
                    }*/
                    $html .= '<div class="col-md-4">';
                    if ($product->productNr == "" || $product->productNr == null) {
                        $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '">';
                    } else {
                        $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '">';
                    }
                    $html .= '<div class="product-image-wrapper">';
                    $html .= '<div class="single-products">';
                    $html .= '<div class="productinfo text-center">';
                    $html .= '<div class="productimg" style="' . htmlspecialchars($imagebackground) . '"></div>';
                    $html .= '<h2>' . $product->{"product_naam_" . $languageSession} . ' ';
                    if ($product->color_id != 0 && $product->color_id != null) {
                        $html .= $product->{"color_naam_" . $languageSession} . ' ';
                    }
                    if ($product->coating_id != 0 && $product->coating_id != null) {
                        $html .= $product->{"coatingnaam_" . $languageSession} . ' ';
                    }
                    if ($product->afmeting != '' && $product->afmeting != null) {
                        $html .= $product->afmeting;
                    }
                    $html .= '</h2>';
                    $html .= '<p>' . $product->{"beschrijving_kort_" . $languageSession} . '</p>';
                    $html .= '<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '<div class="choose">';
                    $html .= '<ul class="nav nav-pills nav-justified">';
                    if ($product->productNr == "" || $product->productNr == null) {
                        $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                    } else {
                        $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                    }
                    $html .= '</ul>';
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</a>';
                    $html .= '</div>';
                }
            } else {
                $html .= '<div class="col-md-4">';
                if ($product->productNr == "") {
                    $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '">';
                } else {
                    $html .= '<a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '">';
                }
                $html .= '<div class="product-image-wrapper">';
                $html .= '<div class="single-products">';
                $html .= '<div class="productinfo text-center">';
                $html .= "<div class='productimg' style='" . $imagebackground . "'></div>";
                $html .= '<h2>' . $product->{"product_naam_" . $languageSession} . ' ';
                if ($product->color_id != 0) {
                    $html .= $product->{"color_naam_" . $languageSession} . ' ';
                }
                if ($product->coating_id != 0) {
                    $html .= $product->{"coatingnaam_" . $languageSession} . ' ';
                }
                if ($product->afmeting != '' && $product->afmeting != null) {
                    $html .= $product->afmeting;
                }
                $html .= '</h2>';
                $html .= '<p>' . $product->{"beschrijving_kort_" . $languageSession} . '</p>';
                $html .= '<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="choose">';
                $html .= '<ul class="nav nav-pills nav-justified">';
                if ($product->productNr == "") {
                    $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->product_productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                } else {
                    $html .= '<li><a href="'.$url.'/productdetailsSubProduct/' . $product->product_id . '/' . $product->productNr . '"><i class="fa fa-plus-square"></i> Details bekijken</a></li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</a>';
                $html .= '</div>';
            }
            if ($teller % 3 == 0) {
                $html .= '</div>';
                $html .= '<div class="row">';
            }
            if ($teller % 3 != 0 && $teller == sizeof($producten)) {
                $html .= '</div>';
            }
            $teller++;
        }

        if (sizeof($producten) == 0) {
            $html = '<div class="row"><h3>Er zijn nog geen producten voor deze subcategory</h3></div>';
        }
        $data["html"] = $html;

        $categoriesSelected = array();

        $categoriesSelected[] = $id;

        $afmetingen = array_unique($afmetingen);
        $colors = array_unique($colors);
        $coatings = array_unique($coatings);
        return view('front.cms.' . $page->template, ['page' => $page, 'producten' => $producten, 'lang' => $lang, 'categories' => $hoofdCategories, 'html' => $html, 'tellerFilter' => 0,'tellerFilterSub' => 1000, 'categoryClicked' => $id, 'selectedCategories' => $categoriesSelected, 'afmetingen' => $afmetingen, 'colors' => $colors, 'coatings' => $coatings, 'filterData' => $filterDataCurrent]);
    }

    public function updateAmountCartItem(Request $request){
        $cartId = $request->input('cartId');
        $newAmount = $request->input('newAmount');

        $cartItem = CartItem::where('id', $cartId)->first();

        $cartItem->amount = $newAmount;

        $cartItem->save();

        return "success";
    }


    public function removeCartItem(Request $request){
        $cartId = $request->input('cartId');

        $cartItem = CartItem::where('id', $cartId)->first();
        $cartItem->delete();

        $amountItems = Session::get('cartAmount');
        $amountItems--;

        $cartItems = Session::get('cartItems');
        if(($key = array_search($cartId, $cartItems)) !== false) {
            unset($cartItems[$key]);
        }

        Session::put('cartItems', $cartItems);
        Session::put('cartAmount',$amountItems);

        return $amountItems;
    }

    public function productDetailsSub($productId, $productNr)
    {

        $page = Page::where('slug', 'productdetails')->first();

        $lang = Session::get('lang', 'nl');

        if ($lang != 'nl' && $lang != 'de' && $lang != 'en' && $lang != 'fr') {
            $lang = 'nl';
            Session::put('lang', $lang);
        }

        $product = Product::where('id', $productId)->first();

        $product->productNrSingle = $product->productNr;

        $relatedProducts = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->where('product_related.product1', $productId)
            ->where('product_related.productNr1', '')
            ->where('product_imgs.naam', 'like', '%(small)%')
            ->get();


        $productNrsFromDB = array();

        foreach ($relatedProducts as $productrel) {
            $productNrsFromDB[] = $productrel->productNr;
        }

        $productImgs = DB::table('product_imgs')
            ->where('naam', 'like', '%(small)%')
            ->whereIn('productNrImg', $productNrsFromDB)
            ->get();

        foreach ($productImgs as $productImg) {
            $key = array_search($productImg->productNrImg, $productNrsFromDB);
            $relatedProducts[$key]->productNrImg[] = $productImg;
        }

        $product->params = DB::table('product_params')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->where('productId', $productId)->get();


        $imageIds = ProductImgRel::where('productId', $productId)->pluck('productImgId')->toArray();
        $product->images = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->get();



        $product->headImages = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->where('productNrImg', NULL)->get();

        if ($product->params != null && sizeof($product->params) > 0) {
            foreach ($product->params as $param) {
                if ($param->productNr == $productNr) {
                    $currentProductParam = $param;
                }
            }
            $currentProductParam->images = DB::table('product_imgs')
                ->where('productNrImg', $productNr)->where('naam', 'like', '%(small)%')
                ->get();

            $currentProductParam->imagesXsmall = DB::table('product_imgs')
                ->where('productNrImg', $productNr)->where('naam', 'like', '%(xsmall)%')
                ->get();

            $currentProductParam->relatedProducts = DB::table('product_related')
                ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
                ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
                ->join('products', 'product_params.productId', '=', 'products.id')
                ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
                ->where('product_related.productNr1', $productNr)
                ->where('product_imgs.naam', 'like', '%(small)%')
                ->get();
        }

        $hoofdCategories = Category::where('subcategoryId', NULL)->get();
        $subCategories = Category::where('subcategoryId', '!=', NULL)->get();


        foreach ($hoofdCategories as $key => $hoofdCategory) {
            $subsTemp = array();
            foreach ($subCategories as $subCategory) {
                if ($hoofdCategory->id == $subCategory->subcategoryId) {
                    $subsTemp[] = $subCategory;
                }
            }
            $hoofdCategories[$key]->subCategories = $subsTemp;
        }


        if (sizeof($product->params) <= 0) {
            $productType = "Single";
        } elseif ($product->params[0]->colorId == NULL) {
            $productType = "Afmeting";
        } elseif ($product->params[0]->afmeting == NULL) {
            $productType = "Kleur";
        } elseif ($product->params[0]->afmeting != NULL && $product->params[0]->colorId != NULL) {
            $productType = "KleurEnAfmeting";
        }

        if ($product->params != null && sizeof($product->params) > 0) {
            return view('front.cms.productdetails', ['page' => $page, 'product' => $product, 'productType' => $productType, 'related' => $relatedProducts, 'tellerDataSlide' => 0, 'tellerActive' => 0, 'lang' => $lang, 'currentProductParam' => $currentProductParam,'productId'=>$productId]);
        } else {
            return view('front.cms.productdetails', ['page' => $page, 'product' => $product, 'productType' => $productType, 'related' => $relatedProducts, 'tellerDataSlide' => 0, 'tellerActive' => 0, 'lang' => $lang,'productId'=>$productId]);
        }

    }

    public function search(Request $request)
    {
        $filterString = $request->input('keyword');

        $url = url('/');
        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)
            ->where('naam', 'like', '%(xsmall)%')
            ->where('products.naam_nl', 'like', '%' . $filterString . '%')
            ->orWhere('products.naam_fr', 'like', '%' . $filterString . '%')
            ->orWhere('products.naam_de', 'like', '%' . $filterString . '%')
            ->orWhere('products.naam_en', 'like', '%' . $filterString . '%')
            ->orWhere('products.productNr', 'like', '%' . $filterString . '%')
            ->orWhere('product_params.productNr', 'like', '%' . $filterString . '%')
            ->orWhere('product_params.searchKeywords', 'like', '%' . $filterString . '%')
            ->get();



        $productenSingle = DB::table('products')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)
            ->whereNotNull('products.productNr')
            ->where(function($q) use ($filterString) {
                $q->where('products.naam_nl', 'like', '%' . $filterString . '%')
                    ->orWhere('products.naam_fr', 'like', '%' . $filterString . '%')
                    ->orWhere('products.naam_de', 'like', '%' . $filterString . '%')
                    ->orWhere('products.naam_en', 'like', '%' . $filterString . '%')
                    ->orWhere('products.productNr', 'like', '%' . $filterString . '%');
            })
            ->where('naam', 'like', '%(xsmall)%')
            ->get();

        /*$producten[] = DB::table('product_params')
             ->join('products', 'product_params.productId', '=', 'products.id')
             ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId','left outer')
             ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id','left outer')
             ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id','left outer')
             ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id','left outer')
             ->select(['*', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en'])
             ->where('headImg', 1)
            ->where('naam', 'like', '%(xsmall)%')
            ->where('product_params.productNr', 'like', '%' . $filterString . '%')
            ->get();*/

        $productenresult = array();


        /*foreach($producten as $key => $product){
            if(empty($product)){

            }else{
                foreach($product as $subproduct){

                    $addToResult = true;

                    if(!$productenresult){

                    }else{
                        foreach($productenresult as $productresult){
                            if($subproduct->product_id == $productresult->product_id){
                                $addToResult = false;
                            }
                        }
                    }

                    if($addToResult){
                        $productenresult[] = $subproduct;
                    }
                }
            }
        }*/

        if ($request->has('json')) {
            return json_encode($producten);
        } else {
            $html = "";
            $languageSession = Session::get('lang', 'nl');
            if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
                $languageSession = 'nl';
                Session::put('lang', 'nl');
            }

            $resultCounter = 0;
            $prevProductNrs = array();

            foreach ($productenSingle as $productresult) {
                   /* $html .= '<li>
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="' . URL::asset("uploads/" . $productresult->directory . "/" . $productresult->naam) . '" width="45px" height="45px" alt="NL">
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4 class="single">' . $productresult->{"naam_" . $languageSession} . '</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <p>' . $productresult->{"beschrijving_kort_" . $languageSession} . '</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </li>';*/
                if ($resultCounter >= 7 || in_array($productresult->productNr, $prevProductNrs)) {

                } else {

                    $html .= "<li><a href='".$url."/productdetailsSubProduct/" . $productresult->product_id . "/" . $productresult->productNr . "' class='listItemSearch'><div class='row'><div class='col-md-2'><img src='" . URL::asset('uploads/' . $productresult->directory . '/' . $productresult->naam) . "' width='45px' height='45px' alt='NL'></div><div class='col-md-10'><div class='row'><div class='col-md-10'><p>" . $productresult->{'product_naam_' . $languageSession} . "</p></div></div><div class='row'><div class='col-md-10'> <p class='fontDescSearch'>" . $productresult->{'beschrijving_kort_' . $languageSession} . "</p></div></div></div></div></a></li>";
                }
                $prevProductNrs[] = $productresult->productNr;
                $resultCounter++;
            }


            foreach ($producten as $productresult) {
                /*$html .= '<li>
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="' . URL::asset("uploads/" . $productresult->directory . "/" . $productresult->naam) . '" width="45px" height="45px" alt="NL">
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h4>' . $productresult->{"naam_" . $languageSession} . '</h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <p>' . $productresult->{"beschrijving_kort_" . $languageSession} . '</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </li>';*/
                if ($resultCounter >= 7 || in_array($productresult->productNr, $prevProductNrs)) {

                } else {
                    $html .= "<li><a href='".$url."/productdetailsSubProduct/" . $productresult->product_id . "/" . $productresult->productNr . "' class='listItemSearch'><div class='row'><div class='col-md-2'><img src='" . URL::asset('uploads/' . $productresult->directory . '/' . $productresult->naam) . "' width='45px' height='45px' alt='NL'></div><div class='col-md-10'><div class='row'><div class='col-md-10'><p>" . $productresult->{'product_naam_' . $languageSession} . " " . $productresult->afmeting . " " . $productresult->{'color_naam_' . $languageSession} . " " . $productresult->{'coatingnaam_' . $languageSession} . "</p></div></div><div class='row'><div class='col-md-10'> <p class='fontDescSearch'>" . $productresult->{'beschrijving_kort_' . $languageSession} . "</p></div></div></div></div></a></li>";
                }
                $prevProductNrs[] = $productresult->paramProductNr;
                $resultCounter++;
            }


            return $html;
        }
    }

    public function cartOpen()
    {
        $page = Page::where('slug', 'cart')->first();

        $cartIds = Session::get('cartItems', null);

        $cartIdString = "";
        $teller = 1;
        if ($cartIds != null) {
            foreach ($cartIds as $cartId) {
                if ($teller == 1) {
                    $cartIdString .= $cartId;
                } else {
                    $cartIdString .= "," . $cartId;
                }
                $teller++;
            }
        }


        $cartItems = DB::table('cart_items')->whereIn('id', $cartIds)->get();


        /*$cartItems1 = DB::table('cart_items')
            ->join('products', 'cart_items.productNr', '=', 'products.productNr')
            ->selectRaw('*, "nothing" as productId, "nothing" as colorId, "nothing" as afmeting, "nothing" as beschrijving_nl')
            ->whereIn('cart_items.id', $cartIds)
            ->get();

        dd($cartItems1);

        $cartItems2 = DB::table('cart_items')
            ->join('product_params', 'cart_items.productNr', '=', 'product_params.productNr')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->whereIn('cart_items.id', $cartIds);

        $results = $cartItems1->union($cartItems2)->get();

        dd($results);*/
        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        if ($cartIds != null) {
            $cartItems2 = DB::table('cart_items')
                ->join('product_params', 'cart_items.productNr', '=', 'product_params.productNr')
                ->join('products', 'product_params.productId', '=', 'products.id')
                ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
                ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
                ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
                ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
                ->select(['*','cart_items.id as cart_item_id', 'products.id as product_id', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
                ->where('headImg', 1)->where('naam', 'like', '%(xsmall)%')
                ->whereIn('cart_items.id', $cartIds)->get();

            $cartItems3 = DB::table('cart_items')
                ->join('products', 'cart_items.productNr', '=', 'products.productNr')
                ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
                ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
                ->select(['*', 'cart_items.id as cart_item_id','products.id as product_id', 'product_imgs.id as image_id', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
                ->where('headImg', 1)->where('naam', 'like', '%(xsmall)%')
                ->whereIn('cart_items.id', $cartIds)->get();
        } else {
            $cartItems2 = null;
            $cartItems3 = null;
        }

        $allProductNrs2 = array();

        if($cartItems2 != null){
            foreach($cartItems2 as $key => $cartItem2){
                if(in_array($cartItem2->paramProductNr, $allProductNrs2)){
                    unset($cartItems2[$key]);
                }else{
                    $allProductNrs2[] = $cartItem2->paramProductNr;
                }
            }
        }



        $allProductNrs3 = array();

        if($cartItems3 != null){
            foreach($cartItems3 as $key => $cartItem3){
                if(in_array($cartItem3->productNr, $allProductNrs3)){
                    unset($cartItems3[$key]);
                }else{
                    $allProductNrs3[] = $cartItem3->productNr;
                }
            }
        }

        return view('front.cms.cart', ['page' => $page, 'producten' => $cartItems2, 'productenSingle' => $cartItems3, 'lang' => $languageSession, 'tellerDataSlide' => 0, 'tellerActive' => 0]);

    }

    public function productDetails(Request $request, $id)
    {

        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $page = Page::where('slug', 'productdetails')->first();

        $product = Product::where('id', $id)->first();


        $product->params = DB::table('product_params')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->where('productId', $id)->get();

        if (sizeof($product->params) <= 0) {
            $productType = "Single";
        } elseif ($product->params[0]->colorId == NULL) {
            $productType = "Afmeting";
        } elseif ($product->params[0]->afmeting == NULL) {
            $productType = "Kleur";
        } elseif ($product->params[0]->afmeting != NULL && $product->params[0]->colorId != NULL) {
            $productType = "KleurEnAfmeting";
        }

        $relatedIds = ProductRelated::where('product1', $id)->pluck('product2')->toArray();
        $relatedProducts = Product::whereIn('id', $relatedIds)->get();

        $productNr = $request->input('productNr');

        $data["imagesSmall"] = DB::table('product_imgs')
            ->where('productNrImg', $productNr)->where('naam', 'like', '%(small)%')
            ->get();

        $data["imagesXsmall"] = DB::table('product_imgs')
            ->where('productNrImg', $productNr)
            ->where('naam', 'like', '%(xsmall)%')
            ->get();

        $data["relatedProducts"] = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->where('product_related.productNr1', $productNr)
            ->where('product_imgs.naam', 'like', '%(small)%')
            ->get();

        $data['images'] = DB::table('product_imgs')
            ->where('productNrImg', $productNr)
            ->where('naam', 'like', '%(small)%')->get();


        $imageIds = ProductImgRel::where('productId', $id)->pluck('productImgId')->toArray();
        $product->images = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->get();

        $product->headImages = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->where('productNrImg', NULL)->get();

        if ($request->has('json')) {
            return json_encode($product);
        } else {
            return view('front.cms.productdetails', ['page' => $page, 'product' => $product, 'productType' => $productType, 'related' => $relatedProducts, 'tellerDataSlide' => 0, 'tellerActive' => 0, 'lang' => $languageSession]);
        }
    }

    public function addToCart(Request $request)
    {
        $productNr = $request->input('productNr');
        $amount = $request->input('amount');

        $cartItem = new CartItem();

        $cartItem->productNr = $productNr;
        $cartItem->amount = $amount;
        $cartItem->type = "";

        $cartItem->save();

        $cartItems = array();

        $cartItemsDone = array();

        $cartItemsfinal = Session::get('cartItems', $cartItems);

        foreach ($cartItemsfinal as $cartItemfinal) {
            $cartItemsDone[] = $cartItemfinal;
        }

        $cartItemsDone[] = $cartItem->id;
        $amountItems = count($cartItemsDone);

        Session::put('cartItems', $cartItemsDone);
        Session::put('cartAmount', $amountItems);


        echo $amountItems;
    }

    public function addToCartSingle(Request $request)
    {
        $productNr = $request->input('productNr');
        $amount = $request->input('amount');

        $cartItem = new CartItem();

        $cartItem->productNr = $productNr;
        $cartItem->amount = $amount;
        $cartItem->type = "Single";

        $cartItem->save();

        $cartItems = array();

        $cartItemsDone = array();

        $cartItemsfinal = Session::get('cartItems', $cartItems);

        foreach ($cartItemsfinal as $cartItemfinal) {
            $cartItemsDone[] = $cartItemfinal;
        }

        $cartItemsDone[] = $cartItem->id;
        $amountItems = count($cartItemsDone);

        Session::put('cartItems', $cartItemsDone);
        Session::put('cartAmount', $amountItems);


        echo $amountItems;
    }

    public function updateText(Request $request)
    {
        $pageTextId = $request->input('pageTextId');
        $pageText = $request->input('pageText');

        $page = new \stdClass();
        $page->id = $pageTextId;
        $page->text = $pageText;

        PageText::where('id', $pageTextId)->update(['content' => $pageText]);

        echo json_encode($page);
    }
}
