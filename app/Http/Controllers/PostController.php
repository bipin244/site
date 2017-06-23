<?php

namespace App\Http\Controllers;

use App\CategoryImg;
use App\Color;
use App\Config;
use App\Http\Controllers\Controller;
use App\OrderCartItems;
use App\Visitor;
use App\Order;
use App\VisitorAddress;
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

class PostController extends Controller
{

    function activateUser($id, $key, $rawpass){
        $visitor = Visitor::where('id', $id)
            ->where('activationkey', $key)
            ->where('activated', 0)
            ->first();

        $uri = URL::asset('/');

        if($visitor != null && $visitor != ""){
            $visitor->activated = 1;
            $visitor->save();
            $htmlMail = "<p>Beste gebruiker,</p></br></br>
                        <p>Uw account is geactiveerd op de website van Hermic BVBA.</p></br>
                        <p><b>E-mail:</b> " . $visitor->email . "</p>
                        <p><b>Wachtwoord:</b> " . $rawpass . "</p></br></br>
                        <p>U kan nu inloggen op onze website via deze link <a href='" . $uri . "#loginLoginDialog'>" . $uri . "#loginLoginDialog </a></br></p>";
            $onderwerpOwner = "Hermic BVBA - Registratie geactiveerd";

            $headers = array('From: no-reply@hermicdev.be',"Reply-To: no-reply@hermicdev.be", "Content-Type: text/html; charset=ISO-8859-1");
            $headers = implode("\r\n", $headers);
            mail($visitor->email, $onderwerpOwner, $htmlMail, $headers);

            dd($visitor->bedrijfsnaam . " is geactiveerd.");
        }else{
            dd("Deze account is al geactiveerd of u heeft de foute URL ingevoerd.");
        }
    }

    function getOrderHistory(){
        $page = Page::where('slug', 'productdetails')->first();
        $user = Session::get('loggedin',null);

        if($user != null){
            $orders = Order::where('visitorId',$user->id)->get();

            foreach($orders as $key => $order){
                $orders[$key]->cartItems = DB::table('order_cartitems')
                ->join('cart_items', 'order_cartitems.cartId', '=', 'cart_items.id')
                ->where('orderId',$order->id)
                ->get();
            }

            return view('front.cms.orderhistory', ['orders' => $orders, 'page' => $page]);
        }else{
            return Redirect::back();
        }
    }

    function loginUser(Request $request){
        $email = $request->input('email');
        $passwordHashed = sha1($request->input('password'));
        $user = Visitor::where('email', $email)
            ->where('password',$passwordHashed)
            ->first();

        $url = $request->input('url');

        if($user != null && $user != ""){
            Session::put('loggedin',$user);
        }else{
            Session::put('loggedin',null);
        }

        return redirect($url);
    }

    function getDefaultAddress(Request $request){
        $visitorId = $request->input('visitorId');

        $visitorAddress = VisitorAddress::where('visitorId',$visitorId)
            ->where('isdefault',1)
            ->orderBy('created_at','desc')
            ->first();

        if($visitorAddress == null){
            return 0;
        }else{
            return json_encode($visitorAddress);
        }
    }

    static function promotionPage(){
        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
        }

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
        $page = Page::where('slug', 'promotionpage')->first();

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
            ->where('promotionParam', 1)
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
            ->where('promotion', 1)
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

        return view('front.cms.promotionpage', ['page' => $page, 'categories' => $hoofdCategories, 'lang' => $languageSession, 'tellerFilter' => 0,'tellerFilterSub' => 1000,'allProducts' => $allProducts]);
    }

    function configUpdate(Request $request){
        $adminemail = $request->input('adminemail');

        $adminemailConfig = Config::where('key','admin_email')->first();

        $adminemailConfig->value = $adminemail;
        $adminemailConfig->save();

        return "success";
    }

    function adminConfiguration(){
        $adminemailConfig = Config::where('key','admin_email')->first();

        $adminemailValue = $adminemailConfig->value;

        $configValues = Config::all();

        $valuearray = array();

        foreach($configValues as $configValue){
            $valuearray[$configValue->key] = $configValue->value;
        }


        return view('admin.config.main', ['adminmail' => $adminemailValue, 'values' => $valuearray]);
    }

    function langUpdate(Request $request){
        $lang = $request->input('lang');

        $value = $request->input('value');

        $configvalue = Config::where('key', $lang)->first();
        $configvalue->value = $value;
        $configvalue->save();

        return "ready";
    }

    function newTextKey(Request $request){
        $langArray = array("nl", "fr", "de", "en");
        $pageId = $request->input('pageId');
        $key = $request->input('key');
        $content = $request->input('content');
        foreach($langArray as $lang){
            $pageTextRule = new PageText();

            $pageTextRule->page_id = $pageId;
            $pageTextRule->language = $lang;
            $pageTextRule->key = $key;
            $pageTextRule->content = $content;

            $pageTextRule->save();
        }

        return route('admin.page.edit', $pageId);
    }



    function registerUser(Request $request){

        $adminmailConfig = Config::where('key','admin_email')->first();

        $admin_email = $adminmailConfig->value;

        $page = Page::where('slug', 'productdetails')->first();

        $email = $request->input('email');
        $url = $request->input('url');
        $visitorAlreadyExist = Visitor::where('email',$email)->first();
        if($visitorAlreadyExist != null && $visitorAlreadyExist != ""){
            return redirect($url . "/#userAlreadyExist");
        }
        $bedrijfsnaam = $request->input('bedrijfsnaam');
        $btwNr = $request->input('btwnr');
        $gsm = $request->input('gsm');


        $activationKey = $this->rand_char(50);
        $randCijfer = rand(1, 999);
        $bedrijfsnaamForPass = preg_replace("/[^A-Za-z0-9]/", "", $bedrijfsnaam);

        $newVisitor = new Visitor();

        $newVisitor->email = $email;
        $newVisitor->bedrijfsnaam = $bedrijfsnaam;
        $newVisitor->btwnr = $btwNr;
        $newVisitor->telnr = $gsm;
        $newVisitor->activationkey = $activationKey;
        $newVisitor->password = sha1(ucfirst(strtolower($bedrijfsnaamForPass)) . $randCijfer);

        $newVisitor->save();

        $tableUserInfo = "";
        $tableUserInfo .= '<tr>
                                <td>
                                <b>Bedrijfsnaam</b>
                                </td>
                                <td>'
            . $bedrijfsnaam .
            '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>E-mail</b>
                                </td>
                                <td>'
            . $email .
            '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>BTW Nr.</b>
                                </td>
                                <td>'
            . $btwNr .
            '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>Tel. Nr / GSM</b>
                                </td>
                                <td>'
            . $gsm .
            '</td>
                            </tr>';

        $urlToActivate = URL::asset('/user/activateUser/' . $newVisitor->id . '/' . $newVisitor->activationkey . '/' . ucfirst(strtolower($bedrijfsnaamForPass)) . $randCijfer);

        $html = '<h2>Geregistreerd Bedrijf</h2>' . $tableUserInfo . '<table style="border:none;"><thead style="border:none;"><tr height="20px" style="border:none;"><td style="border:none;"></td></tr><tr height="20px" style="border:none;"><td style="border:none;"></td></tr></thead></table>' . '<h2>Gebruiker activeren</h2>' . $urlToActivate;
        $onderwerpOwner = "Nieuwe registratie Hermic Website";

        $headers = array('From: no-reply@hermicdev.be',"Reply-To: no-reply@hermicdev.be", "Content-Type: text/html; charset=ISO-8859-1");
        $headers = implode("\r\n", $headers);

        //mail to owner
        mail($admin_email, $onderwerpOwner, $html, $headers);



        return redirect($url . "/#registrationComplete");
    }

    function rand_char($length) {
        $required_length = $length;
        $limit_one = rand();
        $limit_two = rand();
        $randomID = substr(uniqid(sha1(crypt(md5(rand(min($limit_one, $limit_two), max($limit_one, $limit_two)))))), 0, $required_length);
        return $randomID;
    }

    function contactFormSend(Request $request){
        $adminmailConfig = Config::where('key','admin_email')->first();

        $admin_email = $adminmailConfig->value;

        $onderwerpOwner = $request->input('subject');

        $naam = $request->input('name');

        $messageSender = $request->input('tekst');

        $emailSender = $request->input('email');

        $tableUserInfo = "";

        $tableUserInfo .= '<div style="overflow-x:auto;">
                        <table style="max-width:680px;" border="1px gray solid">
                            <tbody>';

        $tableUserInfo .= '<tr>
                               <td>Naam: </td>
                               <td>' . $naam . '</td>
                           </tr>
                           <tr>
                                <td>E-mail: </td>
                                <td>' . $emailSender . '</td>
                            </tr>
                            <tr>
                                <td>Bericht: </td>
                                <td>' . $messageSender . '</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>';

        $headers = array('From: no-reply@hermicdev.be',"Reply-To: " . $emailSender, "Content-Type: text/html; charset=ISO-8859-1");
        $headers = implode("\r\n", $headers);
        Session::put('cartItems', null);
        Session::put('cartAmount', null);
        //mail to owner
        mail($admin_email, $onderwerpOwner, $tableUserInfo, $headers);

        $page = Page::where('slug', 'contact')->first();

        return view('front.cms.contact', ['page' => $page]);
    }

    function sendOrder(Request $request){
        $adminmailConfig = Config::where('key','admin_email')->first();

        $admin_email = $adminmailConfig->value;

        $page = Page::where('slug', 'productdetails')->first();

        $cartIds = Session::get('cartItems', null);

        $visitorId = $request->input('visitorId');

        //Check if Visitor is logged in
        if($visitorId != 0){

            //store Order
            $newOrder = new Order();
            $newOrder->visitorId = $visitorId;
            $newOrder->save();

            //store OrderCartItems
            foreach($cartIds as $cartId){
                $newOrderCartItem = new OrderCartItems();
                $newOrderCartItem->cartId = $cartId;
                $newOrderCartItem->orderId = $newOrder->id;
                $newOrderCartItem->save();
            }

            //store Address Default
            $visitorAddress = VisitorAddress::where('visitorId',$visitorId)
                ->where('isdefault',1)
                ->first();

            if($request->has('isdefault') && $request->has('isdefault') == 1){
                $newVisitorAddress = new VisitorAddress();
                $newVisitorAddress->visitorId = $visitorId;
                $newVisitorAddress->straat = $request->input('straatnaam');
                $newVisitorAddress->huisnummer = $request->input('huisnummer');
                $newVisitorAddress->stad = $request->input('plaats');
                $newVisitorAddress->postcode = $request->input('postcode');
                $newVisitorAddress->land = $request->input('land');
                if($request->has('isdefault') && $request->has('isdefault') == 1){
                    $newVisitorAddress->isdefault = 1;
                }
                $newVisitorAddress->save();
            }
        }

        $languageSession = Session::get('lang', 'nl');

        if ($languageSession != 'nl' && $languageSession != 'de' && $languageSession != 'en' && $languageSession != 'fr') {
            $languageSession = 'nl';
        }

        $lang = $languageSession;

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


        foreach($cartItems2 as $key => $product){
            if($product->EANNumber == null || $product->EANNumber == ""){
                $cartItems2[$key]->EANNumber = "Geen";
            }
            if($product->{"product_naam_" . $lang} == null || $product->{"product_naam_" . $lang} == ""){
                $cartItems2[$key]->{"product_naam_" . $lang} = $cartItems2[$key]->product_naam_nl;
            }
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


        $html = '';
        $tableUserInfo = '';

        $tableUserInfo .= '<div style="overflow-x:auto;">
                        <table style="max-width:680px;" border="1px gray solid">
                            <tbody>';

        $tableUserInfo .= '<tr>
                                <td>
                                <b>Bedrijfsnaam</b>
                                </td>
                                <td>'
                                    . $request->input('bedrijfsnaam') .
                                '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>E-mail</b>
                                </td>
                                <td>'
                            . $request->input('email') .
                            '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>Straat + huisnummer</b>
                                </td>
                                <td>'
                                . $request->input('straatnaam') . " " . $request->input('huisnummer') .
                                '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>Plaats</b>
                                </td>
                                <td>'
                                . $request->input('plaats') .
                                '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>Provincie</b>
                                </td>
                                <td>'
                            . $request->input('provincie') .
                            '</td>
                            </tr>
                            <tr>
                                <td>
                                <b>Land</b>
                                </td>
                                <td>'
                            . $request->input('land') .
                            '</td>
                            </tr>';




        $tableOrder = "";
        //start table lay-out order confirmation
        $tableOrder .= '<div style="overflow-x:auto;">
                        <table style="max-width:680px;" border="1px gray solid">
                            <thead>
                            <tr>
                                <th width="55%">Naam</th>
                                <th width="20%">Barcode</th>
                                <th width="15%">ProductNr</th>
                                <th width="10%">Aantal</th>
                            </tr>
                            </thead>
                            <tbody>';

        $producten = '';

        foreach($cartItems2 as $product)
        {
            $producten .= ' <tr>
                                    <td style="padding:10px;" >' . $product->{"product_naam_" . $lang} . " " . $product->{"color_naam_" . $lang} . " " . $product->{"coatingnaam_" . $lang} . " (" . $product->afmeting . ")" . '</td>
                                    <td style="padding:10px;">' . $product->EANNumber . '</td>
                                    <td style="padding:10px;">' . $product->paramProductNr . '</td>
                                    <td style="padding:10px;">' . $product->amount . '</td>
                                </tr>';
        }

        $productenSingle = '';

        foreach($cartItems3 as $productSingle)
        {
            $productenSingle .= ' <tr>
                                    <td style="padding:10px;" colspan="3">' . $productSingle->{"product_naam_" . $lang} . '</td>
                                    <td style="padding:10px;">' . $productSingle->EANNumber . '</td>
                                    <td style="padding:10px;" >' . $productSingle->productNr . '</td>
                                    <td style="padding:10px;">' . $productSingle->amount . '</td>
                                </tr>';
        }


        $tableOrder .= $producten;

        $tableOrder .= $productenSingle;

        $tableOrder .= '</tbody>
                    </table>
                    </div>';

        $html = '<h2>Persoonlijke gegevens</h2>' . $tableUserInfo . '<table style="border:none;"><thead style="border:none;"><tr height="20px" style="border:none;"><td style="border:none;"></td></tr><tr height="20px" style="border:none;"><td style="border:none;"></td></tr></thead></table><h2>Productoverzicht</h2>' . $tableOrder;


        $htmlVisitor = '<p>Bedankt ' . $request->input('bedrijfsnaam') . ' voor uw aanvraag bij Hermic BVBA.</p><p>Hieronder uw gegevens van de bestelling:</p>';

        $htmlVisitor .= $html . '<p></p><p> Wij reageren zo snel mogelijk met een prijsofferte op maat voor uw bestelling.</p>';

        $onderwerpOwner = "Nieuwe offerte / bestelling";
        $onderwerpVisitor = 'Offerte aanvraag Hermic BVBA';

        $visitorMail = $request->input('email');



        $headers = array('From: no-reply@hermicdev.be',"Reply-To: no-reply@hermicdev.be", "Content-Type: text/html; charset=ISO-8859-1");
        $headers = implode("\r\n", $headers);
        Session::put('cartItems', null);
        Session::put('cartAmount', null);
        //mail to owner
        mail($admin_email, $onderwerpOwner, $html, $headers);

        //mail to user
        mail($visitorMail, $onderwerpVisitor, $htmlVisitor, $headers);



        return view('front.cms.tempTableExample', ['html' => $html, 'page' => $page]);
    }


}