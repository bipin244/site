<?php

namespace App\Http\Controllers\Admin;
use App\ProductRelated;
use DB;

use App\Category;
use App\CategoryProduct;
use App\PageImg;
use App\Coating;
use App\PageText;
use App\VPEProduct;
use App\Product;
use App\VPE;
use App\ProductImg;
use App\ProductImgRel;
use App\ProductParam;
use App\Color;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Page;
use Validation;
use Auth;
use Session;

class ProductController extends Controller
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
    public function create()
    {
        $categories = Category::where('subcategoryId', null)->get();
        $subcategories = Category::where('subcategoryId', '!=', null)->get();

        $allCats = Category::all('subcategoryId');
        $allCatIds = array();
        foreach($allCats as $currentCat){
            if($currentCat['subcategoryId'] != null){
                array_push($allCatIds,$currentCat['subcategoryId']);
            }
        }

        foreach($categories as $key => $category){
            $tempcats = array();
            foreach($subcategories as $subcategory){
                $subSubfound = false;
                if($subcategory->subcategoryId == $category->id){
                    foreach($subcategories as $subSubcategory){
                        if($subSubcategory->subcategoryId == $subcategory->id){
                            $tempcats[] = $subSubcategory;
                            $subSubfound = true;
                        }
                    }
                    if($subSubfound == false){
                        $tempcats[] = $subcategory;
                    }
                }
            }
            $categories[$key]->subcategories = $tempcats;
        }


        $categoriesToPickFrom = Category::whereNotIn('id',$allCatIds)->get();

        $colors = Color::all();
        $coatings = Coating::all();
        $veenheden = DB::table('verpakkingseenheden')->get();
        return view('admin.product.create', ['categories' => $categories,'subcategories' => $subcategories, 'colors' => $colors, 'coatings' => $coatings,'veenheden' => $veenheden,'categoriesToPickFrom' => $categoriesToPickFrom]);


    }

    public function addSubProduct($id){
        $categories = Category::where('subcategoryId', null)->get();
        $subcategories = Category::where('subcategoryId', '!=', null)->get();

        $product = Product::where('id', $id)->first();

        foreach($categories as $key => $category){
            $tempcats = array();
            foreach($subcategories as $subcategory){
                $subSubfound = false;
                if($subcategory->subcategoryId == $category->id){
                    foreach($subcategories as $subSubcategory){
                        if($subSubcategory->subcategoryId == $subcategory->id){
                            $tempcats[] = $subSubcategory;
                            $subSubfound = true;
                        }
                    }
                    if($subSubfound == false){
                        $tempcats[] = $subcategory;
                    }
                }
            }
            $categories[$key]->subcategories = $tempcats;
        }

        $colors = Color::all();
        $coatings = Coating::all();
        $veenheden = DB::table('verpakkingseenheden')->get();
        return view('admin.product.addSub', ['categories' => $categories,'subcategories' => $subcategories, 'colors' => $colors, 'coatings' => $coatings,'veenheden' => $veenheden,'productId' => $id,'product' => $product]);
    }

    public function addSubtoHead(Request $request){
        $productNrNew = $request->input('productNr');

        $productId = $request->input('productId');

        $newProductParam = new ProductParam();

        $newProductParam->productId = $productId;
        $newProductParam->productNr = $productNrNew;
        $newProductParam->EANNumber = $request->input('EANNumber');
        $newProductParam->colorId = $request->input('color');
        $newProductParam->coatingId = $request->input('coating');

        $newProductParam->afmeting = $request->input('productSize');
        $newProductParam->dikte = $request->input('productDikte');
        $newProductParam->searchKeywords = $request->input('searchIndexes');

        $newProductParam->beschrijving_nl = $request->input('description_short_nl');
        $newProductParam->beschrijving_fr = $request->input('description_short_fr');
        $newProductParam->beschrijving_de = $request->input('description_short_de');
        $newProductParam->beschrijving_en = $request->input('description_short_en');

        $newProductParam->voorraad = $request->input('voorraad');


        $jsonimgs = $request->input('mainimages');

        $imgarraymain = json_decode($jsonimgs);

        $newProductParam->levertermijn = "";
        $newProductParam->voorraad = 0;

        $newProductParam->save();

        $imgids = array();

        $hoofdimgteller = 1;

        if (sizeof($imgarraymain) > 0) {
            foreach ($imgarraymain as $imgsubproduct) {
                $productImg = new ProductImg;
                $productImg->internalId = $imgsubproduct->id;
                $productImg->naam = $imgsubproduct->name;
                $productImg->directory = $imgsubproduct->uuid;

                $productImg->groupHash = $imgsubproduct->proxyGroupId;
                $productImg->productNrImg = $productNrNew;
                if ($hoofdimgteller == 1 || $hoofdimgteller == 2 || $hoofdimgteller == 3 || $hoofdimgteller == 4) {
                    $productImg->headImg = 1;
                } else {
                    $productImg->headImg = 0;
                }

                if (isset($imgsubproduct->parentId)) {
                    $productImg->parentId = $imgsubproduct->parentId;
                } else {
                    $productImg->parentId = null;
                }
                $imagepath = asset("uploads/" . $imgsubproduct->uuid . "/" . $imgsubproduct->name);
                //            $imagedetails = getimagesize($imagepath);
                //            $width = $imagedetails[0];
                //            $height = $imagedetails[1];
                $productImg->width = 1;
                $productImg->height = 1;
                $productImg->save();
                $imgids[] = $productImg->id;
                $hoofdimgteller++;
            }
        }

        for ($i = 0; $i < sizeof($imgids); $i++) {
            $productImgRel = new ProductImgRel;
            $productImgRel->productId = $newProductParam->productId;
            $productImgRel->productImgId = $imgids[$i];
            $productImgRel->save();
        }


        if(!empty($request->input('relatedProductsMain'))){
            $relatedProducts = $request->input('relatedProductsMain');
            $relatedProductsFormat = json_decode($relatedProducts);
            foreach($relatedProductsFormat as $p) {
                $relatedProduct = new ProductRelated();
                $relatedProduct->product1 = $newProductParam->productId;
                $relatedProduct->product2 = $p->product_id;
                $relatedProduct->productNr1 = $productNrNew;
                $relatedProduct->productNr2 = $p->paramProductNr;
                $relatedProduct->save();
            }
        }

        return redirect("/admin/showAllProducts");
    }

    public function setProductInactive($productId, $productNr){
        $productParam = ProductParam::where('productNr', $productNr)->first();
        if($productParam != null){
            $productParam->active = 0;
            $productParam->save();
        }else{
            $product = Product::where('productNr', $productNr)->first();
            if($product != null){
                $product->active = 0;
                $product->save();
            }
        }
        return $this->getAllProductsDataTable();
    }

    public function getAllProductsDataTable(){
        $producten = DB::table('products')
            ->join('product_params', 'products.id', '=', 'product_params.productId')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->join('product_colors', 'product_params.colorId', '=', 'product_colors.id', 'left outer')
            ->join('product_coatings', 'product_params.coatingId', '=', 'product_coatings.id', 'left outer')
            ->select(['*', 'products.id as product_id','product_params.created_at as product_created_at','product_params.updated_at as product_updated_at', 'product_imgs.id as image_id', 'product_colors.naam_nl as color_naam_nl', 'product_colors.naam_fr as color_naam_fr', 'product_colors.naam_de as color_naam_de', 'product_colors.naam_en as color_naam_en', 'product_params.productNr as paramProductNr', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('products.active',1)
            ->where('product_params.active',1)
            ->where('headImg', 1)
            ->where('naam', 'like', '%(xsmall)%')
            ->get();

        $productenSingle = DB::table('products')
            ->join('product_img_relations', 'products.id', '=', 'product_img_relations.productId')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->select(['*', 'products.id as product_id', 'products.created_at as product_created_at', 'products.updated_at as product_updated_at', 'product_imgs.id as image_id', 'products.naam_nl as product_naam_nl', 'products.naam_fr as product_naam_fr', 'products.naam_de as product_naam_de', 'products.naam_en as product_naam_en'])
            ->where('headImg', 1)
            ->where('products.active',1)
            ->whereNotNull('products.productNr')
            ->where('naam', 'like', '%(xsmall)%')
            ->get();

            $html = "";
            $languageSession = Session::get('lang', 'nl');
            if ($languageSession != 'nl' || $languageSession != 'de' || $languageSession != 'en' || $languageSession != 'fr') {
                $languageSession = 'nl';
                Session::put('lang', 'nl');
            }

            $resultCounter = 0;
            $prevProductNrs = array();

            foreach ($productenSingle as $productresult) {
                if ($resultCounter >= 7 || in_array($productresult->productNr, $prevProductNrs)) {

                } else {

                    $html .= "<tr>";
                    $html .= "<td>" . $productresult->productNr . "</td>";
                    $html .= "<td>" . $productresult->{'product_naam_' . $languageSession} . "</td>";
                    $html .= "<td>/</td>";
                    $html .= "<td>/</td>";
                    $html .= "<td>/</td>";
                    $html .= "<td><a href='/editMainProductSingle/" . $productresult->product_id . "/" . $productresult->productNr . "' class='btn btn-default'>Bewerk</a></td>";
                    $html .= "<td>/</td>";
                    $html .= "<td>/</td>";
                    if($productresult->nieuw == 1){
                        $html .= "<td><input type='checkbox' class='nieuwproduct' data-product-nr='" . $productresult->productNr . "' checked/></td>";
                    }else{
                        $html .= "<td><input type='checkbox' class='nieuwproduct' data-product-nr='" . $productresult->productNr . "' /></td>";
                    }
                    $html .= "<td><input type='checkbox' class='sale' data-product-nr='" . $productresult->productNr . "' /></td>";
                    $html .= "<td><a href='/admin/unique/" . $productresult->product_id . "/" . $productresult->productNr . "' class='btn btn-default'>Uniek</a></td>";
                    $html .= "<td>" . $productresult->product_created_at . "</td>";
                    $html .= "<td>" . $productresult->product_updated_at . "</td>";
                    $html .= "<td><a href='/admin/setProductInactive/" . $productresult->product_id . "/" . $productresult->productNr . "' onClick=\"return confirm('BEN JE ZEKER DAT JE HET PRODUCT WIL VERWIJDEREN ? Deze zal hierna niet meer zichtbaar / bestelbaar zijn.')\"><i class='glyphicon glyphicon-remove-sign' style='margin-top:6px;'></i></a></td>";
                    $html .= "</tr>";

                    //$html .= "<li><a href='/productdetailsSubProduct/" . $productresult->product_id . "/" . $productresult->productNr . "' class='listItemSearch'><div class='row'><div class='col-md-2'><img src='" . URL::asset('uploads/' . $productresult->directory . '/' . $productresult->naam) . "' width='45px' height='45px' alt='NL'></div><div class='col-md-10'><div class='row'><div class='col-md-10'><p>" . $productresult->{'product_naam_' . $languageSession} . "</p></div></div><div class='row'><div class='col-md-10'> <p class='fontDescSearch'>" . $productresult->{'beschrijving_kort_' . $languageSession} . "</p></div></div></div></div></a></li>";
                }
                $prevProductNrs[] = $productresult->productNr;
                $resultCounter++;
            }


            foreach ($producten as $productresult) {
                if (in_array($productresult->productNr, $prevProductNrs)) {

                } else {

                    $html .= "<tr>";
                    $html .= "<td>" . $productresult->productNr . "</td>";
                    $html .= "<td>" . $productresult->{'product_naam_' . $languageSession} . "</td>";
                    $html .= "<td>" . $productresult->{'color_naam_' . $languageSession} . "</td>";
                    $html .= "<td>" . $productresult->afmeting . "</td>";
                    $html .= "<td>" . $productresult->{'coatingnaam_' . $languageSession} . "</td>";
                    $html .= "<td><a href='/editSubProduct/" . $productresult->product_id . "/" . $productresult->productNr . "' class='btn btn-default'>Bewerk</a></td>";
                    $html .= "<td><a href='/editMainProduct/" . $productresult->product_id . "' class='btn btn-default'>Bewerk gemeenschappelijke info</a></td>";
                    $html .= "<td><a href='/addSubProduct/" . $productresult->product_id . "' class='btn btn-default'>Subproduct toevoegen</a></td>";
                    if($productresult->nieuwParam == 1){
                        $html .= "<td><input type='checkbox' class='nieuwproduct' data-product-nr='" . $productresult->productNr . "' checked/></td>";
                    }else{
                        $html .= "<td><input type='checkbox' class='nieuwproduct' data-product-nr='" . $productresult->productNr . "' /></td>";
                    }
                    $html .= "<td><input type='checkbox' class='sale' data-product-nr='" . $productresult->productNr . "' /></td>";
                    $html .= "<td><a href='/admin/unique/" . $productresult->product_id . "/" . $productresult->productNr . "' class='btn btn-default'>Uniek</a></td>";
                    $html .= "<td>" . $productresult->product_created_at . "</td>";
                    $html .= "<td>" . $productresult->product_updated_at . "</td>";
                    $html .= "<td><a href='/admin/setProductInactive/" . $productresult->product_id . "/" . $productresult->productNr . "' onClick=\"return confirm('BEN JE ZEKER DAT JE HET PRODUCT WIL VERWIJDEREN ? Deze zal hierna niet meer zichtbaar / bestelbaar zijn.')\"><i class='glyphicon glyphicon-remove-sign' style='margin-top:6px;'></i></a></td>";
                    $html .= "</tr>";
                }
                $prevProductNrs[] = $productresult->paramProductNr;
                $resultCounter++;
            }


        return view('admin.product.list', ['html' => $html]);
    }

    public function editMainProductSingle($id, $productNr){
        $categories = Category::where('subcategoryId', null)->get();
        $subcategories = Category::where('subcategoryId', '!=', null)->get();

        foreach($categories as $key => $category){
            $tempcats = array();
            foreach($subcategories as $subcategory){
                $subSubfound = false;
                if($subcategory->subcategoryId == $category->id){
                    foreach($subcategories as $subSubcategory){
                        if($subSubcategory->subcategoryId == $subcategory->id){
                            $tempcats[] = $subSubcategory;
                            $subSubfound = true;
                        }
                    }
                    if($subSubfound == false){
                        $tempcats[] = $subcategory;
                    }
                }
            }
            $categories[$key]->subcategories = $tempcats;
        }

        $colors = Color::all();
        $coatings = Coating::all();
        $veenheden = DB::table('verpakkingseenheden')->get();

        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
            Session::put('lang', $languageSession);
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
        $relatedProducts = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->select(['*', 'product_related.id as product_related_id'])
            ->where('product_related.product1', $id)
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

        $imageIds = ProductImgRel::where('productId', $id)->pluck('productImgId')->toArray();
        $product->images = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->get();

        $product->headImages = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->where('productNrImg', NULL)->get();

        $product->categories =  DB::table('categories')
            ->join('categorie_products', 'categories.id', '=', 'categorie_products.category_id')
            ->where('product_id', $id)
            ->get();


        return view('admin.product.editMainSingle', ['categories' => $categories,'subcategories' => $subcategories, 'colors' => $colors, 'coatings' => $coatings,'veenheden' => $veenheden,'page' => $page, 'product' => $product, 'productType' => $productType, 'related' => $relatedProducts, 'tellerDataSlide' => 0, 'tellerActive' => 0, 'lang' => $languageSession]);

    }

    public function updateMainSingle(Request $request){
        $productId = $request->input('productId');

        $product = Product::where('id', $productId)->first();

        $productNrOld = $request->input('productNrOld');

        $productNrNew = $request->input('productNrSingle');

        $imgsToRemove = $request->input('imageToRemove');

        $catsToRemove = $request->input('catsToRemove');

        $relatedToRemove = $request->input('relatedToRemove');


        if($imgsToRemove != "" && $imgsToRemove != null){
            $imgsToRemove = explode(',', $request->input('imageToRemove'));
            ProductImgRel::whereIn('productImgId',$imgsToRemove)->where('productId', $productId)->delete();
        }

        if($catsToRemove != "" && $catsToRemove != null){
            $catsToRemove = explode(',',$request->input('catsToRemove'));
            CategoryProduct::whereIn('category_id', $catsToRemove)->where('product_id', $productId)->delete();
        }

        if($relatedToRemove != "" && $relatedToRemove != null){
            $relatedToRemove = explode(',',$request->input('relatedToRemove'));
            ProductRelated::whereIn('id',$relatedToRemove)->delete();
        }

        $product->naam_nl = $request->input('title_nl');
        $product->naam_fr = $request->input('title_fr');
        $product->naam_de = $request->input('title_de');
        $product->naam_en = $request->input('title_en');

        $product->beschrijving_kort_nl = $request->input('description_short_nl');
        $product->beschrijving_kort_fr = $request->input('description_short_fr');
        $product->beschrijving_kort_de = $request->input('description_short_de');
        $product->beschrijving_kort_en = $request->input('description_short_en');

        $product->beschrijving_lang_nl = $request->input('description_long_nl');
        $product->beschrijving_lang_fr = $request->input('description_long_fr');
        $product->beschrijving_lang_de = $request->input('description_long_de');
        $product->beschrijving_lang_en = $request->input('description_long_en');

        $product->verpakkingsEenheid = $request->input('verpakkingsEenheid');

        $jsonimgs = $request->input('mainimages');

        $imgarraymain = json_decode($jsonimgs);

        $categorySelections = $request->input('categoryselection');

        $product->productNr = $request->input('productNrSingle');
        $product->EANNumber = $request->input('EANNumberSingle');
        $product->levertermijn = "";
        $product->voorraad = 0;

        $product->save();

        $imgids = array();

        $hoofdimgteller = 1;

        if(sizeof($imgarraymain) > 0) {
            foreach ($imgarraymain as $img) {
                $productImg = new ProductImg;
                $productImg->internalId = $img->id;
                $productImg->naam = $img->name;
                $productImg->directory = $img->uuid;

                $productImg->groupHash = $img->proxyGroupId;

                if ($hoofdimgteller == 1 || $hoofdimgteller == 2 || $hoofdimgteller == 3 || $hoofdimgteller == 4) {
                    $productImg->headImg = 1;
                } else {
                    $productImg->headImg = 0;
                }

                if (isset($img->parentId)) {
                    $productImg->parentId = $img->parentId;
                } else {
                    $productImg->parentId = null;
                }
                $imagepath = asset("uploads/" . $img->uuid . "/" . $img->name);
//            $imagedetails = getimagesize($imagepath);
//            $width = $imagedetails[0];
//            $height = $imagedetails[1];
                $productImg->width = 1;
                $productImg->height = 1;
                $productImg->save();
                $imgids[] = $productImg->id;
                $hoofdimgteller++;
            }
        }

        ProductRelated::where('productNr1', '=', $productNrOld)
            ->update(['productNr1' => $productNrNew]);

        ProductRelated::where('productNr2', '=', $productNrOld)
            ->update(['productNr2' => $productNrNew]);

        ProductImg::where('productNrImg', '=', $productNrOld)
            ->update(['productNrImg' => $productNrNew]);

        for ($i = 0; $i < sizeof($imgids); $i++) {
            $productImgRel = new ProductImgRel;
            $productImgRel->productId = $product->id;
            $productImgRel->productImgId = $imgids[$i];
            $productImgRel->save();
        }

        if(!empty($request->input('relatedProductsMain'))){
            $relatedProducts = $request->input('relatedProductsMain');
            $relatedProductsFormat = json_decode($relatedProducts);
            foreach($relatedProductsFormat as $p) {
                DB::insert("INSERT INTO product_related (product1, product2,productNr1, productNr2) VALUES(?,?,'',?)", [$product->id, $p->product_id,$p->productNr]);
            }
        }


        if($categorySelections != null){
            foreach($categorySelections as $categorySelection){
                $categoryProduct = new CategoryProduct();

                $categoryProduct->product_id = $product->id;
                $categoryProduct->category_id = $categorySelection;
                $categoryProduct->save();
            }
        }
        return redirect("/admin/showAllProducts");
    }

    public function editSubProduct($id, $productNr){
        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
            Session::put('lang', $languageSession);
        }

        $page = Page::where('slug', 'productdetails')->first();

        $product = DB::table('product_params')
            ->where('productNr', $productNr)->first();

        $product->images = DB::table('product_imgs')
            ->where('productNrImg', $productNr)
            ->where('naam', 'like', '%(xsmall)%')
            ->get();

        $product->relatedProducts = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->select(['*', 'product_related.id as product_related_id'])
            ->where('product_related.productNr1', $productNr)
            ->where('product_imgs.naam', 'like', '%(small)%')
            ->get();

        $veenheden = DB::table('verpakkingseenheden')->get();
        $colors = Color::all();
        $coatings = Coating::all();



        return view('admin.product.editSub', ['product' => $product, 'colors' => $colors, 'coatings' => $coatings,'veenheden' => $veenheden,'page' => $page, 'product' => $product, 'tellerDataSlide' => 0, 'tellerActive' => 0, 'lang' => $languageSession]);

    }

    public function setProductOfNew(Request $request){
        $productNr = $request->input('productNr');

        $product = Product::where('productNr', $productNr)->first();

        if($product != null && $product != ""){
            $product->nieuw = null;
            $product->save();
        }else{
            $productParam = ProductParam::where('productNr', $productNr)->first();
            $productParam->nieuwParam = null;
            $productParam->save();
        }
        return "success";
    }

    public function setProductToNew(Request $request){
        $productNr = $request->input('productNr');

        $nieuweProductenSingle = Product::where('nieuw',1)->get();

        $nieuweProductenParams = ProductParam::where('nieuwParam', 1)->get();

        $totaalNieuwe = count($nieuweProductenSingle) + count($nieuweProductenParams);

        if($totaalNieuwe == 9){
            return "max";
        }else{
            $product = Product::where('productNr', $productNr)->first();

            if($product != null && $product != ""){
                $product->nieuw = 1;
                $product->save();
            }else{
                $productParam = ProductParam::where('productNr', $productNr)->first();
                $productParam->nieuwParam = 1;
                $productParam->save();
            }
            return "success";
        }
    }

    public function setProductToSale(Request $request){
        $productNr = $request->input('productNr');

        $nieuweProductenSingle = Product::where('nieuw',1)->get();

        $nieuweProductenParams = ProductParam::where('nieuwParam', 1)->get();

        $totaalNieuwe = count($nieuweProductenSingle) + count($nieuweProductenParams);

        if($totaalNieuwe == 9){
            return "max";
        }else{
            $product = Product::where('productNr', $productNr)->first();

            if($product != null && $product != ""){
                $product->promotion = 1;
                $product->save();
            }else{
                $productParam = ProductParam::where('productNr', $productNr)->first();
                $productParam->promotionParam = 1;
                $productParam->save();
            }
            return "success";
        }
    }

    public function setProductOfSale(Request $request){
        $productNr = $request->input('productNr');

        $product = Product::where('productNr', $productNr)->first();

        if($product != null && $product != ""){
            $product->promotion = null;
            $product->save();
        }else{
            $productParam = ProductParam::where('productNr', $productNr)->first();
            $productParam->promotionParam = null;
            $productParam->save();
        }
        return "success";
    }

    public function editMainProduct($id){

        $categories = Category::where('subcategoryId', null)->get();
        $subcategories = Category::where('subcategoryId', '!=', null)->get();

        foreach($categories as $key => $category){
            $tempcats = array();
            foreach($subcategories as $subcategory){
                $subSubfound = false;
                if($subcategory->subcategoryId == $category->id){
                    foreach($subcategories as $subSubcategory){
                        if($subSubcategory->subcategoryId == $subcategory->id){
                            $tempcats[] = $subSubcategory;
                            $subSubfound = true;
                        }
                    }
                    if($subSubfound == false){
                        $tempcats[] = $subcategory;
                    }
                }
            }
            $categories[$key]->subcategories = $tempcats;
        }

        $colors = Color::all();
        $coatings = Coating::all();
        $veenheden = DB::table('verpakkingseenheden')->get();

        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
            Session::put('lang', $languageSession);
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
        $relatedProducts = DB::table('product_related')
            ->join('product_params', 'product_related.productNr2', '=', 'product_params.productNr')
            ->join('product_img_relations', 'product_params.productId', '=', 'product_img_relations.productId')
            ->join('products', 'product_params.productId', '=', 'products.id')
            ->join('product_imgs', 'product_img_relations.productImgId', '=', 'product_imgs.id')
            ->select(['*', 'product_related.id as product_related_id'])
            ->where('product_related.product1', $id)
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

        $imageIds = ProductImgRel::where('productId', $id)->pluck('productImgId')->toArray();
        $product->images = ProductImg::whereIn('id', $imageIds)
            ->where('productNrImg', NULL)
            ->where('naam', 'like', '%(small)%')->get();

        $product->headImages = ProductImg::whereIn('id', $imageIds)
            ->where('naam', 'like', '%(small)%')->where('productNrImg', NULL)->get();

        $product->categories =  DB::table('categories')
            ->join('categorie_products', 'categories.id', '=', 'categorie_products.category_id')
            ->where('product_id', $id)
            ->get();


        return view('admin.product.editMain', ['categories' => $categories,'subcategories' => $subcategories, 'colors' => $colors, 'coatings' => $coatings,'veenheden' => $veenheden,'page' => $page, 'product' => $product, 'productType' => $productType, 'related' => $relatedProducts, 'tellerDataSlide' => 0, 'tellerActive' => 0, 'lang' => $languageSession]);

    }



    public function updateMain(Request $request){
        $productId = $request->input('productId');

        $product = Product::where('id', $productId)->first();

        $imgsToRemove = $request->input('imageToRemove');

        $catsToRemove = $request->input('catsToRemove');

        $relatedToRemove = $request->input('relatedToRemove');

        $categorySelections = $request->input('categoryselection');


        if($imgsToRemove != "" && $imgsToRemove != null){
            $imgsToRemove = explode(',', $request->input('imageToRemove'));
            ProductImgRel::whereIn('productImgId',$imgsToRemove)->where('productId', $productId)->delete();
        }

        if($catsToRemove != "" && $catsToRemove != null){
            $catsToRemove = explode(',',$request->input('catsToRemove'));
            CategoryProduct::whereIn('category_id', $catsToRemove)->where('product_id', $productId)->delete();
        }

        if($relatedToRemove != "" && $relatedToRemove != null){
            $relatedToRemove = explode(',',$request->input('relatedToRemove'));
            ProductRelated::whereIn('id',$relatedToRemove)->delete();
        }

        $product->naam_nl = $request->input('title_nl');
        $product->naam_fr = $request->input('title_fr');
        $product->naam_de = $request->input('title_de');
        $product->naam_en = $request->input('title_en');

        $product->beschrijving_kort_nl = $request->input('description_short_nl');
        $product->beschrijving_kort_fr = $request->input('description_short_fr');
        $product->beschrijving_kort_de = $request->input('description_short_de');
        $product->beschrijving_kort_en = $request->input('description_short_en');

        $product->beschrijving_lang_nl = $request->input('description_long_nl');
        $product->beschrijving_lang_fr = $request->input('description_long_fr');
        $product->beschrijving_lang_de = $request->input('description_long_de');
        $product->beschrijving_lang_en = $request->input('description_long_en');

        $product->verpakkingsEenheid = $request->input('verpakkingsEenheid');

        $jsonimgs = $request->input('mainimages');

        $imgarraymain = json_decode($jsonimgs);

        $categorySelections = $request->input('categoryselection');

        $product->productNr = $request->input('productNrSingle');
        $product->EANNumber = $request->input('EANNumberSingle');
        $product->levertermijn = "";
        $product->voorraad = 0;

        $product->save();

        $imgids = array();

        $hoofdimgteller = 1;

        if(sizeof($imgarraymain) > 0) {
            foreach ($imgarraymain as $img) {
                $productImg = new ProductImg;
                $productImg->internalId = $img->id;
                $productImg->naam = $img->name;
                $productImg->directory = $img->uuid;

                $productImg->groupHash = $img->proxyGroupId;

                if ($hoofdimgteller == 1 || $hoofdimgteller == 2 || $hoofdimgteller == 3 || $hoofdimgteller == 4) {
                    $productImg->headImg = 1;
                } else {
                    $productImg->headImg = 0;
                }

                if (isset($img->parentId)) {
                    $productImg->parentId = $img->parentId;
                } else {
                    $productImg->parentId = null;
                }
                $imagepath = asset("uploads/" . $img->uuid . "/" . $img->name);
//            $imagedetails = getimagesize($imagepath);
//            $width = $imagedetails[0];
//            $height = $imagedetails[1];
                $productImg->width = 1;
                $productImg->height = 1;
                $productImg->save();
                $imgids[] = $productImg->id;
                $hoofdimgteller++;
            }
        }

        for ($i = 0; $i < sizeof($imgids); $i++) {
            $productImgRel = new ProductImgRel;
            $productImgRel->productId = $product->id;
            $productImgRel->productImgId = $imgids[$i];
            $productImgRel->save();
        }

        if(!empty($request->input('relatedProductsMain'))){
            $relatedProducts = $request->input('relatedProductsMain');
            $relatedProductsFormat = json_decode($relatedProducts);
            foreach($relatedProductsFormat as $p) {
                DB::insert("INSERT INTO product_related (product1, product2,productNr1, productNr2) VALUES(?,?,'',?)", [$product->id, $p->product_id,$p->productNr]);
            }
        }


        if($categorySelections != null){
            foreach($categorySelections as $categorySelection){
                $categoryProduct = new CategoryProduct();

                $categoryProduct->product_id = $product->id;
                $categoryProduct->category_id = $categorySelection;
                $categoryProduct->save();
            }
        }
        return redirect("/admin/showAllProducts");
    }

    public function updateSub(Request $request){
        $productNrOld = $request->input('productNrOld');

        $productNrNew = $request->input('productNr');

        $product = ProductParam::where('productNr', $productNrOld)->first();

        $imgsToRemove = $request->input('imageToRemove');

        $relatedToRemove = $request->input('relatedToRemove');


        if($imgsToRemove != "" && $imgsToRemove != null){
            $imgsToRemove = explode(',', $request->input('imageToRemove'));
            ProductImg::whereIn('id',$imgsToRemove)->where('productNrImg', $productNrOld)->delete();
        }

        if($relatedToRemove != "" && $relatedToRemove != null){
            $relatedToRemove = explode(',',$request->input('relatedToRemove'));
            ProductRelated::whereIn('id',$relatedToRemove)->delete();
        }

        $product->productNr = $productNrNew;
        $product->EANNumber = $request->input('EANNumber');
        $product->colorId = $request->input('color');
        $product->coatingId = $request->input('coating');

        $product->afmeting = $request->input('productSize');
        $product->dikte = $request->input('productDikte');
        $product->searchKeywords = $request->input('searchIndexes');

        $product->beschrijving_nl = $request->input('description_short_nl');
        $product->beschrijving_fr = $request->input('description_short_fr');
        $product->beschrijving_de = $request->input('description_short_de');
        $product->beschrijving_en = $request->input('description_short_en');

        $product->voorraad = $request->input('voorraad');


        $jsonimgs = $request->input('mainimages');

        $imgarraymain = json_decode($jsonimgs);

        $categorySelections = $request->input('categoryselection');


        $product->EANNumber = $request->input('EANNumberSingle');
        $product->levertermijn = "";
        $product->voorraad = 0;

        $product->save();

        $imgids = array();

        $hoofdimgteller = 1;

        if (sizeof($imgarraymain) > 0) {
            foreach ($imgarraymain as $imgsubproduct) {
                $productImg = new ProductImg;
                $productImg->internalId = $imgsubproduct->id;
                $productImg->naam = $imgsubproduct->name;
                $productImg->directory = $imgsubproduct->uuid;

                $productImg->groupHash = $imgsubproduct->proxyGroupId;
                $productImg->productNrImg = $productNrNew;
                if ($hoofdimgteller == 1 || $hoofdimgteller == 2 || $hoofdimgteller == 3 || $hoofdimgteller == 4) {
                    $productImg->headImg = 1;
                } else {
                    $productImg->headImg = 0;
                }

                if (isset($imgsubproduct->parentId)) {
                    $productImg->parentId = $imgsubproduct->parentId;
                } else {
                    $productImg->parentId = null;
                }
                $imagepath = asset("uploads/" . $imgsubproduct->uuid . "/" . $imgsubproduct->name);
                //            $imagedetails = getimagesize($imagepath);
                //            $width = $imagedetails[0];
                //            $height = $imagedetails[1];
                $productImg->width = 1;
                $productImg->height = 1;
                $productImg->save();
                $imgids[] = $productImg->id;
                $hoofdimgteller++;
            }
        }

        for ($i = 0; $i < sizeof($imgids); $i++) {
            $productImgRel = new ProductImgRel;
            $productImgRel->productId = $product->productId;
            $productImgRel->productImgId = $imgids[$i];
            $productImgRel->save();
        }

        ProductRelated::where('productNr1', '=', $productNrOld)
            ->update(['productNr1' => $productNrNew]);

        ProductRelated::where('productNr2', '=', $productNrOld)
            ->update(['productNr2' => $productNrNew]);

        ProductImg::where('productNrImg', '=', $productNrOld)
            ->update(['productNrImg' => $productNrNew]);

        if(!empty($request->input('relatedProductsMain'))){
            $relatedProducts = $request->input('relatedProductsMain');
            $relatedProductsFormat = json_decode($relatedProducts);
            foreach($relatedProductsFormat as $p) {
                $relatedProduct = new ProductRelated();
                $relatedProduct->product1 = $product->productId;
                $relatedProduct->product2 = $p->product_id;
                $relatedProduct->productNr1 = $productNrNew;
                $relatedProduct->productNr2 = $p->paramProductNr;
                $relatedProduct->save();
            }
        }

        return redirect("/admin/showAllProducts");
    }

    public function updateColor(Request $request){
        $colorhex = $request->input('colorhex');
        $colornamenl = $request->input('colornamenl');
        $colornamefr = $request->input('colornamefr');
        $colornamede = $request->input('colornamede');
        $colornameen = $request->input('colornameen');
        $colorral = $request->input('colorral');
        $colorId = $request->input('colorId');

        $newcolor = new Color();

        $newcolor = Color::where('id', $colorId)->first();

        $newcolor->naam_nl = $colornamenl;
        $newcolor->naam_fr = $colornamefr;
        $newcolor->naam_de = $colornamede;
        $newcolor->naam_en = $colornameen;
        $newcolor->hex = $colorhex;
        $newcolor->ral = $colorral;

        $newcolor->save();

        $newcolor->naamnl = $newcolor->naam_nl;

        return json_encode($newcolor);
    }

    public function updateCoating(Request $request){
        $coatingnaamnl = $request->input('naamNL');
        $coatingnaamfr = $request->input('naamFR');
        $coatingnaamde = $request->input('naamDE');
        $coatingnaamen = $request->input('naamEN');
        $coatingId = $request->input('coatingId');

        $newcoating = Coating::where('id', $coatingId)->first();

        $newcoating->coatingnaam_nl = $coatingnaamnl;
        $newcoating->coatingnaam_fr = $coatingnaamfr;
        $newcoating->coatingnaam_de = $coatingnaamde;
        $newcoating->coatingnaam_en = $coatingnaamen;

        $newcoating->save();

        return json_encode($newcoating);
    }

    public function updateCategory(Request $request){
        $categorynamenl = $request->input('naamNL');
        $categorynamefr = $request->input('naamFR');
        $categorynamede = $request->input('naamDE');
        $categorynameen = $request->input('naamEN');
        $categoryId = $request->input('categoryId');


        $newCategory = Category::where('id', $categoryId)->first();

        $newCategory->naam_nl = $categorynamenl;
        $newCategory->naam_fr = $categorynamefr;
        $newCategory->naam_de = $categorynamede;
        $newCategory->naam_en = $categorynameen;

        if($request->has('hoofdcategory')){
            $newCategory->subcategoryId = $request->input('hoofdcategory');
        }

        $newCategory->save();

        return json_encode($newCategory);
    }

    public function addCoating(Request $request){
        $coatingnaamnl = $request->input('coatingnamenl');
        $coatingnaamfr = $request->input('coatingnamefr');
        $coatingnaamde = $request->input('coatingnamede');
        $coatingnaamen = $request->input('coatingnameen');

        $newcoating = new Coating();

        $newcoating->coatingnaam_nl = $coatingnaamnl;
        $newcoating->coatingnaam_fr = $coatingnaamfr;
        $newcoating->coatingnaam_de = $coatingnaamde;
        $newcoating->coatingnaam_en = $coatingnaamen;

        $newcoating->save();

        $newcoating->naamnl = $coatingnaamnl;

        return json_encode($newcoating);
    }

    public function addCategory(Request $request){
        $categorynl = $request->input('coatingnamenl');
        $categoryfr = $request->input('coatingnamefr');
        $categoryde = $request->input('coatingnamede');
        $categoryen = $request->input('coatingnameen');
        $hoofdcategory = $request->input('hoofdcategory');



        $category = new Category();

        $category->naam_nl = $categorynl;
        $category->naam_fr = $categoryfr;
        $category->naam_de = $categoryde;
        $category->naam_en = $categoryen;

        if($request->input('hoofdcategory') != 0){
            $category->subcategoryId = $hoofdcategory;
        }

        $category->save();

        return json_encode($category);
    }

    public function checkDeleteColor(Request $request){
        $colorId = $request->input('colorId');
        $params = ProductParam::where('colorId', $colorId)->get();
        if(sizeof($params) > 0){
            return "false";
        }else{
            $colorId = $request->input('colorId');
            Color::where('id', $colorId)->delete();

            return "true";
        }
    }

    public function checkDeleteCategory(Request $request){
        $categoryId = $request->input('categoryId');
        $params = CategoryProduct::where('category_id', $categoryId)->get();
        if(sizeof($params) > 0){
            return "false";
        }else{
            $categoryId = $request->input('categoryId');
            Category::where('id', $categoryId)->delete();

            return "true";
        }
    }

    public function checkDeleteCoating(Request $request){
        $coatingId = $request->input('coatingId');
        $params = ProductParam::where('coatingId', $coatingId)->get();
        if(sizeof($params) > 0){
            return "false";
        }else{
            Coating::where('id', $coatingId)->delete();
            return "true";
        }
    }

    public function addColor(Request $request){
        $colorhex = $request->input('colorhex');
        $colornamenl = $request->input('colornamenl');
        $colornamefr = $request->input('colornamefr');
        $colornamede = $request->input('colornamede');
        $colornameen = $request->input('colornameen');
        $colorral = $request->input('colorral');

        $newcolor = new Color();

        $newcolor->naam_nl = $colornamenl;
        $newcolor->naam_fr = $colornamefr;
        $newcolor->naam_de = $colornamede;
        $newcolor->naam_en = $colornameen;
        $newcolor->hex = $colorhex;
        $newcolor->ral = $colorral;

        $newcolor->save();

        $newcolor->naamnl = $newcolor->naam_nl;

        return json_encode($newcolor);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $keyformat = str_replace(' ', '', $request->input('title_nl'));

        $keyformat = strtolower($keyformat);

        $product = new Product;

        $product->key = $keyformat;

        $product->naam_nl = $request->input('title_nl');
        $product->naam_fr = $request->input('title_fr');
        $product->naam_de = $request->input('title_de');
        $product->naam_en = $request->input('title_en');

        $product->beschrijving_kort_nl = $request->input('description_short_nl');
        $product->beschrijving_kort_fr = $request->input('description_short_fr');
        $product->beschrijving_kort_de = $request->input('description_short_de');
        $product->beschrijving_kort_en = $request->input('description_short_en');

        $product->beschrijving_lang_nl = $request->input('description_long_nl');
        $product->beschrijving_lang_fr = $request->input('description_long_fr');
        $product->beschrijving_lang_de = $request->input('description_long_de');
        $product->beschrijving_lang_en = $request->input('description_long_en');

        $product->verpakkingsEenheid = $request->input('verpakkingsEenheid');


        if($request->has('active')){
            $product->active = 0;
        }else{
            $product->active = 1;
        }


        $jsonimgs = $request->input('mainimages');
        $jsonimgssub = $request->input('imgvalues');
        $imgarraymain = json_decode($jsonimgs);

        $imgarraysubproducts = array();


        $categorySelections = $request->input('categoryselection');

        $imgids = array();

        $hoofdimgteller = 1;

        if($request->has('productNr')){
            foreach($jsonimgssub as $jsonimgssubsingle){
                $imgarraysubproducts[] = json_decode($jsonimgssubsingle);
            }
        }else{
            $product->productNr = $request->input('productNrSingle');
            $product->EANNumber = $request->input('EANNumberSingle');
            $product->levertermijn = "";
            $product->voorraad = 0;

            $allVerpakkingsEenheden = VPE::all();
            $verpakkingsEenhedenSingle = $request->input('verpakkingsEenhedenSingle');
            $verpakkingsEenhedenSingleAmount = $request->input('verpakkingsEenhedenSingleAmount');

            foreach($verpakkingsEenhedenSingle as $key => $verpakkingsEenheidSingle){
                foreach($allVerpakkingsEenheden as $allVerpakkingsEenheid){
                    if($verpakkingsEenheidSingle == $allVerpakkingsEenheid->id){
                        $newVPEProduct = new VPEProduct();
                        $newVPEProduct->verpakkingseenheid_id = $allVerpakkingsEenheid->id;
                        $newVPEProduct->productNr = $product->productNr;
                        $newVPEProduct->hoeveelheid = $verpakkingsEenhedenSingleAmount[$key];
                        $newVPEProduct->save();
                    }
                }
            }
        }

        $product->save();


        foreach($categorySelections as $categorySelection){
            $categoryProduct = new CategoryProduct();

            $categoryProduct->product_id = $product->id;
            $categoryProduct->category_id = $categorySelection;
            $categoryProduct->save();
        }




        if($request->input('productNrSingle') == ""){
            if($request->has('productNr')) {
                $colors = array();
                $productSizes = array();
                $productVoorraad = array();
                $productNrs = array();
                $EANNumbers = $request->input('EANNumbers');
                $verpakkingsAantallen = $request->input('verpakkingsaantal');
                $verpakkingsEenheden = $request->input('verpakkingsEenheden');
                $productNrs = $request->input('productNr');
                $productVoorraad = $request->input('voorraad');
                $searchKeywords = $request->input('searchIndexes');
                $beschrijving = $request->input('descriptions');
                $beschrijvingFR = $request->input('descriptionsFR');
                $beschrijvingDE = $request->input('descriptionsDE');
                $beschrijvingEN = $request->input('descriptionsEN');
                $colors = $request->input('colors');
                $productSizes = $request->input('productSize');
                $coatings = $request->input('coatings');

                $relatedProductsSubs = $request->input('relatedProducts');
                $relatedProductSelected = $request->input('selectedRelated');
                /*Meerdere productnrs*/

                $tellerVPE = 0;
                for ($i = 0; $i < sizeof($colors); $i++) {
                    $newProductParam = new ProductParam();
                    $newProductParam->productId = $product->id;
                    $newProductParam->productNr = $productNrs[$i];
                    $newProductParam->EANNumber = $EANNumbers[$i];
                    $newProductParam->colorId = $colors[$i];
                    $newProductParam->coatingId = $coatings[$i];
                    $newProductParam->afmeting = $productSizes[$i];
                    $newProductParam->voorraad = $productVoorraad[$i];
                    $newProductParam->levertermijn = "";
                    $newProductParam->beschrijving_nl = $beschrijving[$i];
                    $newProductParam->beschrijving_fr = $beschrijvingFR[$i];
                    $newProductParam->beschrijving_de = $beschrijvingDE[$i];
                    $newProductParam->beschrijving_en = $beschrijvingEN[$i];
                    $found = false;

                    do {
                        if($request->has('verpakkingsEenheden' . $tellerVPE)){
                            $amounts = $request->input('verpakkingsEenhedenAmount' . $tellerVPE);
                            $VPEs = $request->input('verpakkingsEenheden' . $tellerVPE);
                            if($amounts[0] != "" && $amounts[0] != null){
                                foreach($amounts as $key => $amount){
                                    $newVerpakkingsEenheid = new VPEProduct();

                                    $newVerpakkingsEenheid->verpakkingseenheid_id = $VPEs[$key];
                                    $newVerpakkingsEenheid->productNr = $productNrs[$i];
                                    $newVerpakkingsEenheid->hoeveelheid = $amount;

                                    $newVerpakkingsEenheid->save();
                                }
                                $tellerVPE++;
                                $found = true;
                            }else{
                                $found = true;
                            }
                        }else{
                            $tellerVPE++;
                        }
                    } while ($found == false);

                    if(sizeof($relatedProductsSubs[$i]) > 0 && $relatedProductsSubs[$i] != null && $relatedProductsSubs[$i] != ""){

                        foreach(json_decode($relatedProductsSubs[$i]) as $p) {
                            $newRelatedProduct = new ProductRelated();
                            $newRelatedProduct->product1 = $product->id;
                            $newRelatedProduct->product2 = $p->product_id;
                            $newRelatedProduct->productNr1 = $productNrs[$i];
                            $newRelatedProduct->productNr2 = $p->paramProductNr;
                            $newRelatedProduct->save();
                        }
                    }elseif(sizeof(json_decode($relatedProductSelected[$i])) > 0){

                        foreach(json_decode($relatedProductSelected[$i]) as $p){
                            $relatedProduct = new ProductRelated();
                            $relatedProduct->product1 = $product->id;
                            $relatedProduct->product2 = $p->product_id;
                            $relatedProduct->productNr1 = $productNrs[$i];
                            $relatedProduct->productNr2 = $p->paramProductNr;
                            $relatedProduct->save();
                        }
                }


                    $newProductParam->searchKeywords = $searchKeywords[$i];
                    $newProductParam->save();
                }
            }
        }


        if(sizeof($imgarraymain) > 0) {
            foreach ($imgarraymain as $img) {
                $productImg = new ProductImg;
                $productImg->internalId = $img->id;
                $productImg->naam = $img->name;
                $productImg->directory = $img->uuid;

                $productImg->groupHash = $img->proxyGroupId;

                if ($hoofdimgteller == 1 || $hoofdimgteller == 2 || $hoofdimgteller == 3 || $hoofdimgteller == 4) {
                    $productImg->headImg = 1;
                } else {
                    $productImg->headImg = 0;
                }

                if (isset($img->parentId)) {
                    $productImg->parentId = $img->parentId;
                } else {
                    $productImg->parentId = null;
                }
                $imagepath = asset("uploads/" . $img->uuid . "/" . $img->name);
//            $imagedetails = getimagesize($imagepath);
//            $width = $imagedetails[0];
//            $height = $imagedetails[1];
                $productImg->width = 1;
                $productImg->height = 1;
                $productImg->save();
                $imgids[] = $productImg->id;
                $hoofdimgteller++;
            }
        }


        if($request->has('productNr')) {
            if (sizeof($imgarraysubproducts) > 0) {
                foreach ($imgarraysubproducts as $key => $imgsubproducts) {
                    if (sizeof($imgsubproducts) > 0) {
                        foreach ($imgsubproducts as $imgsubproduct) {
                            $productImg = new ProductImg;
                            $productImg->internalId = $imgsubproduct->id;
                            $productImg->naam = $imgsubproduct->name;
                            $productImg->directory = $imgsubproduct->uuid;

                            $productImg->groupHash = $imgsubproduct->proxyGroupId;
                            $productImg->productNrImg = $productNrs[$key];
                            if ($hoofdimgteller == 1 || $hoofdimgteller == 2 || $hoofdimgteller == 3 || $hoofdimgteller == 4) {
                                $productImg->headImg = 1;
                            } else {
                                $productImg->headImg = 0;
                            }

                            if (isset($img->parentId)) {
                                $productImg->parentId = $imgsubproduct->parentId;
                            } else {
                                $productImg->parentId = null;
                            }
                            $imagepath = asset("uploads/" . $imgsubproduct->uuid . "/" . $imgsubproduct->name);
                            //            $imagedetails = getimagesize($imagepath);
                            //            $width = $imagedetails[0];
                            //            $height = $imagedetails[1];
                            $productImg->width = 1;
                            $productImg->height = 1;
                            $productImg->save();
                            $imgids[] = $productImg->id;
                            $hoofdimgteller++;
                        }
                    }
                }
            }
        }

        for ($i = 0; $i < sizeof($imgids); $i++) {
            $productImgRel = new ProductImgRel;
            $productImgRel->productId = $product->id;
            $productImgRel->productImgId = $imgids[$i];
            $productImgRel->save();
        }


        /*DB::delete('DELETE FROM product_related WHERE product1 = ?', [$product->id]);*/
        if(!empty($request->input('relatedProductsMain'))){
            $relatedProducts = $request->input('relatedProductsMain');
            $relatedProductsFormat = json_decode($relatedProducts);
            foreach($relatedProductsFormat as $p) {
                DB::insert("INSERT INTO product_related (product1, product2,productNr1, productNr2) VALUES(?,?,'',?)", [$product->id, $p->product_id,$p->productNr]);
            }
        }








//        $data = [
//            'key'           => $keyformat,
//        	'naam_nl' 		                => $request->input('title_nl'),
//        	'naam_fr' 		                => $request->input('title_fr'),
//        	'naam_de'  		                => $request->input('title_de'),
//        	'naam_en' 		                => $request->input('title_en'),
//            'beschrijving_kort_nl' 		    => $request->input('description_short_nl'),
//            'beschrijving_kort_fr' 		    => $request->input('description_short_fr'),
//            'beschrijving_kort_de'  		=> $request->input('description_short_de'),
//            'beschrijving_kort_en' 		    => $request->input('description_short_en'),
//            'beschrijving_lang_nl' 		    => $request->input('description_long_nl'),
//            'beschrijving_lang_fr' 		    => $request->input('description_long_fr'),
//            'beschrijving_lang_de'  		=> $request->input('description_long_de'),
//            'beschrijving_lang_en' 		    => $request->input('description_long_en')
//        ];
//
//        Page::create($data);

        return redirect()->route('admin.product.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Session::put('pageId', $id);
        $languageSession = Session::get('lang', 'nl');
        return view('admin.page.edit', ['posts' => PageText::where('page_id', $id)->where('language', $languageSession)->get()]);
    }

    public function editLang($lang){
        Session::put('lang',$lang);
        $pageId = Session::get('pageId');
        return view('admin.page.edit', ['posts' => PageText::where('page_id', $pageId)->where('language', $lang)->get()]);
    }

    public function editimg($id)
    {
        $languageSession = Session::get('lang', 'nl');
        return view('admin.page.editimg', ['posts' => PageImg::where('page_id', $id)->get()]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        	'title'		=> 'required'
        ]);

        $user = Auth::user();

        $data = [
        	'title' 		=> $request->input('title'),
        	'slug'  		=> str_slug($request->input('title')),
        	'content' 		=> $request->input('content'),
        	'status' 		=> $request->input('status')
        ];

        Page::find($id)->update($data);

        return redirect()->route('admin.page.index')->with('success', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::find($id)->delete();

        return redirect()->route('admin.page.index')->with('success', 'Successfully deleted!');
    }
}
