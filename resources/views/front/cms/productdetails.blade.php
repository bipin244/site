@extends('front.template.main')
@section('content')
    <div class="row spacerbig"></div>

    <div class="container">
    {!! Breadcrumbs::render('productDetailsSub',$productId,'d') !!}
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div id="productDetailsCarousel" class="carousel slide" data-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div id="carouselProductDetailsInner" class="carousel-inner" role="listbox">
                                @foreach($product->images as $image)
                                    <?php if(strpos($image->naam, "(small)")){?>
                                    <div class="item <?php if($tellerActive == 0){echo "active";}?>" style="height:100% !important;">
                                        <img src="{{ URL::asset("uploads/" . $image->directory . "/" . $image->naam )}}" style="height:100% !important; object-fit: contain; font-family: 'object-fit: contain;'" alt="Chania">
                                    </div>
                                    <?php $tellerActive++;}?>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row spacersmall"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="itemslider-zoom" class="thumbnails show-all auto-adjust-width">
                            @foreach($product->images as $image)
                                <div class="item">
                                    <a data-target="#productDetailsCarousel" data-slide-to="{{$tellerDataSlide}}" class="active">
                                        <img src="{{ URL::asset("uploads/" . $image->directory . "/" . $image->naam )}}" alt="Paalhoudergroot" />
                                    </a>
                                </div>
                                <?php $tellerDataSlide++;?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h3><?php echo $product->{'naam_' . $lang}?></h3>
                        <p><?php echo $product->{'beschrijving_kort_' . $lang}?></p>
                        <div class="spacersmallest"></div>
                        <p id="artikelnummer">
                            <b>Artikelnummer:</b>
                            @if($product->productNrSingle != null && $product->productNrSingle != "")
                                <span id="productNrValueSingle">{{$product->productNrSingle}}</span>
                            @else
                                Kies eerst een kleur en/of afmeting en/of coating.
                            @endif
                        </p>
                        <div class="spacersmallest"></div>
                        <p id="verpakkingseenheid"><b>Verpakkingseenheid:</b> {{$product->verpakkingsEenheid}}</p>
                        <div class="spacersmallest"></div>
                        @if($product->productNrSingle == null && $product->productNrSingle == "")
                        <h4 class="optionsproducts">Options</h4>
                        @endif
                        <?php if(count($product->params) != 0 && $product->params[0]->afmeting != "NULL" && $product->params[0]->afmeting != ""){?>

                        <div id="dropdownafmeting" class="dropdown">
                            <button id="dropdownButton" class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Kies een afmeting<span class="caret"></span></button>
                            <ul id="afmetingParameters" class="dropdown-menu">
                                <input type="hidden" class="parametervalue" data-param-column="afmeting" value=""/>
                                <?php $afmetingarray = array();?>
                                @foreach($product->params as $key => $param)
                                    <?php if(!in_array($param->afmeting,$afmetingarray)){?>
                                    <li><a href="#" class="afmetingItem paramItemSelect itemDisabled" data-param-id="{{$param->id}}"  data-param-value="{{$param->afmeting}}" data-param-text-value="{{$param->afmeting}}" data-type="true" data-param-key="{{$key}}">{{$param->afmeting}}</a></li>
                                    <?php $afmetingarray[] = $param->afmeting;}?>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row spacersmall"></div>
                        <?php }?>

                        <?php if(count($product->params) != 0 && $product->params[0]->colorId != "NULL" && $product->params[0]->colorId != ""){?>
                        <div class="row">
                            <div class="col-md-1">
                                <p><b class="leftfloater">Kleur: </b></p>
                            </div>
                            <div class="col-md-9">
                                <ul id="colorsProductDetail">
                                    <?php $colorarray = array();?>
                                    <input type="hidden" class="parametervalue kleurItemValue" data-param-column="colorId" value=""/>
                                    @foreach($product->params as $key => $param)
                                        <?php if(!in_array($param->colorId,$colorarray)){?>
                                        <li style="background-color: {{$param->hex}}" class="kleurItem itemDisabled paramItemSelect" data-param-id="{{$param->id}}" data-param-value="{{$param->colorId}}" data-product-nr="{{$param->productNr}}" data-param-key="{{$key}}" data-type="true"  title="{{$param->naam_nl}}"></li>
                                        <?php $colorarray[] = $param->colorId;}?>
                                    @endforeach
                                </ul>
                            </div>
                            <input type="hidden" id="colorProductNr" value="">
                        </div>
                        <div class="row spacersmall"></div>
                        <?php }?>

                        <?php if(count($product->params) != 0 && $product->params[0]->coatingId != "NULL" && $product->params[0]->coatingId != ""){?>
                        <div id="dropdowncoating" class="dropdown">
                            <button id="dropdownButton" class="btn btn-secondary dropdown-toggle" type="button" value="" data-toggle="dropdown">Kies een coating<span class="caret"></span></button>
                            <ul id="coatingParameters" class="dropdown-menu">
                                <input type="hidden" class="parametervalue" data-param-column="coatingId" value=""/>
                                <?php $coatingarray = array();?>
                                @foreach($product->params as $key => $param)
                                    <?php if(!in_array($param->coatingId,$coatingarray)){?>
                                    <li><a href="#" class="coatingItem paramItemSelect itemDisabled" data-param-id="{{$param->id}}" data-param-value="{{$param->coatingId}}" data-param-text-value="{{$param->coatingnaam_nl}}" data-param-key="{{$key}}" >{{$param->coatingnaam_nl}}</a></li>
                                    <?php $coatingarray[] = $param->coatingId;}?>
                                @endforeach
                            </ul>
                        </div>
                        <div class="row spacersmall"></div>
                        <?php }?>
                        <a id="removeSelection" class="btn btn-danger" style="display:none;width:150px;">Selectie verwijderen</a>
                        <div class="row spacerbig"></div>
                        <p><b>Aantal: </b><input id="amounttextbox" name="amounttextbox" value="1" type="text" class="form-control" placeholder="Aantal" style="width:70px;"/></p>
                        <a href="#" id="addToCartButton" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>
                        <div class="row spacerbig"></div>
                        <!-- Productenrij -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row spacersmall"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading filterheading">Gedetailleerde informatie</div>
                    <div class="panel-body"><?php echo $product->{'beschrijving_lang_' . $lang}?></div>
                </div>
            </div>
        </div>
        <div class="row spacersmall"></div>
        <div class="row">
            <div id="relatedProductsContainer" class="col-md-8">
                <?php if(sizeof($related) != 0){?>
                <h2>Gerelateerde producten</h2>
                <?php $teller = 1;?>
                @foreach($related as $relatedproduct)
                    <?php if($teller == 1){
                        echo '<div class="row">';
                    }?>
                    <div class="col-md-4">
                        <a href="{{URL::asset('productdetailsSubProduct/' . $relatedproduct->product2 . '/' . $relatedproduct->productNr2)}}">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <?php if(!property_exists('relatedproduct', 'productNrImg')){?>
                                            <div class="productimg" style="background-image: url('{{ URL::asset("uploads/" . $relatedproduct->directory . "/" . $relatedproduct->naam)}}')"></div>
                                        <?php }else{ ?>
                                            <div class="productimg" style="background-image: url('{{ URL::asset("uploads/" . $relatedproduct->productNrImg[0]->directory . "/" . $relatedproduct->productNrImg[0]->naam)}}')"></div>
                                        <?php }?>
                                        <h2><?php echo $relatedproduct->{"naam_" . Session::get('lang', 'nl')} . $relatedproduct->afmeting; ?></h2>
                                        <p><?php echo $relatedproduct->{"beschrijving_kort_" . Session::get('lang', 'nl')}; ?></p>
                                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="{{URL::asset('productdetailsSubProduct/' . $relatedproduct->product2 . '/' . $relatedproduct->productNr2)}}"><i class="fa fa-plus-square"></i> Details bekijken</a></li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>

                    <?php if($teller % 2 == 0){
                        echo '</div>';
                        echo '<div class="row">';
                    }
                    if($teller % 2 != 0 && $teller == sizeof($related)){
                        echo '</div>';
                    }?>
                    <?php $teller++;?>
                @endforeach
                    <?php }?>
            </div>
        </div>
    </div>

    <div id="relatedProductTemplate" style="display:none;">
        <div class="col-md-4">
            <a id="linkProductNr" href="/productdetails/id">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <div class="productimg" style=""></div>
                            <h2 class="titleProduct">temp</h2>
                            <p class="descriptionProduct">temp</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>
                        </div>
                    </div>
                    <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                            <li><a id="linkProductNrDetails" href="/productdetails/id"><i class="fa fa-plus-square"></i> Details bekijken</a></li>
                        </ul>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <?php if(isset($currentProductParam)){ ?>
        <script>
            var currentProductParam = <?php echo json_encode($currentProductParam);?>;
        </script>
    <?php }?>

    <script type="text/javascript">
        var params = <?php echo json_encode($product->params);?>;
        var productimages = <?php echo json_encode($product->images);?>;
        var headImages = <?php echo json_encode($product->headImages);?>;
        var productType = "<?php echo $productType;?>";
        var colorkeyglobal = "null";
        var firstclicked = true;
        var base_url = "<?php echo URL::to('/');?>";
        var paramsSelected = "";
        var singleProduct = <?php echo json_encode($product->params);?>;
        var htmlImageGroot = "";
        var htmlImageKlein = "";
        var tellerActiveProductImage = 0;

        console.log("public_path :",base_url);

        $(document).on('click', '.paramItemSelect', function(){
            if($(this).hasClass("itemDisabled")){
                if($(this).hasClass("kleurItem")){
                    $(".kleurItem").each(function() {
                        $(this).css("border","none");
                    });
                    $(this).css("border","2px #FFCC00 solid");
                    $(this).parent().find(".parametervalue").val($(this).data("paramValue"));
                }else{
                    $(this).parent().parent().find(".parametervalue").val($(this).data("paramValue"));
                    $(this).parent().parent().parent().find("#dropdownButton").html($(this).data("paramTextValue") + '<span class="caret"></span>');
                }
                var allParamsSet = true;
                $(".parametervalue").each(function(){
                    if($(this).val() == ""){
                        allParamsSet = false;
                    }
                });
                if(allParamsSet == true){
                    console.log("All selected");
                    paramsSelected = params;
                    $(".parametervalue").each(function(){
                        if($(this).val() != ""){
                            var colomnaam = $(this).data("paramColumn");
                            var newArray = new Array();
                            for(i = 0; i < paramsSelected.length; i++){
                                if(paramsSelected[i][colomnaam] == $(this).val()){
                                    var parameter = paramsSelected[i];
                                    newArray.push(parameter);
                                }
                            }
                            paramsSelected = newArray;
                        }
                    });
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/getRelatedItems')}}",
                        data: {"productNr": paramsSelected[0].productNr,"productId": paramsSelected[0].productId},
                        cache: false,
                        success: function(data){
                            var jsonArray = jQuery.parseJSON(data);
                            var htmldata = "";
                            var teller = 1;
                            console.log(jsonArray);
                            if(jsonArray.images != null && jsonArray.images != "" && jsonArray.images.length > 0){
                                var htmlImageGrootProductNr = "";
                                var htmlImageKleinProductNr = "";
                                var tellerActiveProductNr = 0;
                                $.each(jsonArray.images, function(key, image){
                                    htmlImageGrootProductNr += '<div class="item';
                                    if(tellerActiveProductNr == 0){
                                        htmlImageGrootProductNr += ' active'
                                    }
                                    htmlImageGrootProductNr += '" style="height:100% !important;"><img src="' + base_url + '/uploads/' + image.directory + '/' + image.naam + '" style="height:100% !important; object-fit: contain;" alt="Chania"></div>';

                                    htmlImageKleinProductNr += '<div class="item"><a data-target="#productDetailsCarousel" data-slide-to="' + tellerActiveProductNr + '" class="active"> <img src="' + base_url + '/uploads/' + image.directory + '/' + image.naam + '" alt="Paalhoudergroot" /> </a> </div>';

                                    tellerActiveProductNr++;
                                });
                                $("#carouselProductDetailsInner").html(htmlImageGrootProductNr);
                                $("#itemslider-zoom").html(htmlImageKleinProductNr);
                            }else{
                                $("#carouselProductDetailsInner").html(htmlImageGroot);
                                $("#itemslider-zoom").html(htmlImageKlein);
                            }
                            if(jsonArray.relatedProducts.length > 0){
                            jsonArray.relatedProducts.forEach(function(product, index){
                                if(teller == 1){
                                    htmldata += "<h2>Gerelateerde producten</h2>";
                                    htmldata += "<div class='row'>";
                                }

                                var $productTemplate = $("#relatedProductTemplate").clone();

                                $productTemplate.find("#linkProductNr").attr("href","/productdetailsSubProduct/" + product.productId + "/" + product.productNr2);
                                $productTemplate.find(".productimg").css('background-image'," "+base_url+"/uploads/" + product.directory + "/" + product.naam + "");
                                $productTemplate.find(".titleProduct").html(product.naam_nl + " - " + product.afmeting);
                                $productTemplate.find(".descriptionProduct").html(product.beschrijving_nl);

                                $productTemplate.find("#linkProductNrDetails").attr("href","/productdetailsSubProduct/" + product.productId + "/" + product.productNr2);

                                htmldata += $productTemplate.html();

                                if(teller % 2 == 0){
                                    htmldata += '</div>';
                                    htmldata += '<div class="row">';
                                }

                                if(teller % 2 != 0 && teller == jsonArray.relatedProducts.length){
                                    htmldata += '</div>';
                                }

                                teller++;
                            });
                            $("#relatedProductsContainer").html(htmldata);
                            }else{
                                jsonArray.relatedProductsMain.forEach(function(product, index){
                                    if(teller == 1){
                                        htmldata += "<h2>Gerelateerde producten</h2>";
                                        htmldata += "<div class='row'>";
                                    }

                                    var $productTemplate = $("#relatedProductTemplate").clone();

                                    $productTemplate.find("#linkProductNr").attr("href","/productdetailsSubProduct/" + product.productId + "/" + product.productNr2);
                                    $productTemplate.find(".productimg").css('background-image',"url('"+base_url+"/uploads/" + product.directory + "/" + product.naam + "')");
                                    $productTemplate.find(".titleProduct").html(product.naam_nl + " - " + product.afmeting);
                                    $productTemplate.find(".descriptionProduct").html(product.beschrijving_nl);

                                    $productTemplate.find("#linkProductNrDetails").attr("href","/productdetailsSubProduct/" + product.productId + "/" + product.productNr2);

                                    htmldata += $productTemplate.html();

                                    if(teller % 2 == 0){
                                        htmldata += '</div>';
                                        htmldata += '<div class="row">';
                                    }

                                    if(teller % 2 != 0 && teller == jsonArray.relatedProducts.length){
                                        htmldata += '</div>';
                                    }

                                    teller++;
                                });
                                $("#relatedProductsContainer").html(htmldata);
                            }
                        }
                    });

                    if(paramsSelected[0].voorraad == 1){
                        var voorraadtext = "Op voorraad";
                        var voorraadcss = "green";
                    }else{
                        var voorraadtext = "Niet in voorraad";
                        var voorraadcss = "red";
                    }
                    $("#artikelnummer").html("<b>Artikelnummer:</b> " + paramsSelected[0].productNr);
                    /*$("#levertijd").html("<b>Levertijd:</b> " + paramsSelected[0].levertermijn);
                     $("#voorraad").html('<b>In voorraad: <span style="color:' + voorraadcss + '">' + voorraadtext + '</span></b>' );*/
                    $('#colorProductNr').val(paramsSelected[0].productNr);

                }else{
                    paramsSelected = params;
                    $(".parametervalue").each(function(){
                        if($(this).val() != "") {
                            var colomnaam = $(this).data("paramColumn");
                            var newArray = new Array();
                            for (i = 0; i < paramsSelected.length; i++) {
                                if (paramsSelected[i][colomnaam] == $(this).val()) {
                                    var parameter = paramsSelected[i];
                                    newArray.push(parameter);
                                }
                            }
                            paramsSelected = newArray;
                            console.log("GO");
                        }
                    });
                    $(".parametervalue").each(function(){

                        var colomnaam = $(this).data("paramColumn");
                        $paramValue = $(this);
                        console.log(paramsSelected);
                        var paramValues = [];
                        for(i = 0; i < paramsSelected.length; i++){
                            paramValues[i] = String(paramsSelected[i][colomnaam]);
                        }
                        $items = $(this).parent().find(".paramItemSelect");
                        $items.each(function(){
                            if ($.inArray(String($(this).data("paramValue")), paramValues) != -1){
                                console.log(paramValues);
                                console.log($(this).data("paramValue"));
                            }else{
                                if($(this).hasClass("kleurItem")){
                                    console.log(paramValues);
                                    $(this).addClass("grayoverlay");
                                    $(this).removeClass("itemDisabled");
                                }else{
                                    console.log(paramValues);
                                    $(this).parent().addClass("disabled");
                                    $(this).removeClass("itemDisabled");
                                }
                                $(this).off("click");
                                console.log($(this).data("paramValue") + "REMOVED");
                            }
                        });
                    });
                }
                $("#removeSelection").css("display","block");
            }
        });


        $(document).on('click', '#removeSelection', function(){
            $(".parametervalue").each(function(){
                $(this).val("");
                if($(this).data("paramColumn") == "colorId"){
                    $("#colorsProductDetail").find(".paramItemSelect").remove();
                    var alreadyDisplayed = [];
                    for(var p in params){
                        if ($.inArray(params[p].colorId, alreadyDisplayed) != -1){

                        }else {
                            $("#colorsProductDetail").append('<li style="background-color:' + params[p].hex + '" class="kleurItem paramItemSelect itemDisabled" data-param-id="' + params[p].id + '" data-param-value="' + params[p].colorId + '" data-product-nr="' + params[p].productNr + '" data-param-key="' + p + '" data-type="true"  title="' + params[p].naam_nl + '"></li>');
                            alreadyDisplayed[p] = params[p].colorId;
                        }
                    }
                }
                if($(this).data("paramColumn") == "afmeting"){
                    $("#afmetingParameters").find(".paramItemSelect").remove();
                    var alreadyDisplayed = [];
                    for(var p in params){
                        if ($.inArray(params[p].coatingId, alreadyDisplayed) != -1){

                        }else {
                            $("#afmetingParameters").append('<li><a href="#" class="afmetingItem paramItemSelect itemDisabled" data-param-id="' + params[p].id + '"  data-param-value="' + params[p].afmeting + '" data-param-text-value="' + params[p].afmeting + '" data-type="true" data-param-key="' + p + '">' + params[p].afmeting + '</a></li>');
                            alreadyDisplayed[p] = params[p].afmeting;
                        }
                    }
                    $(this).parent().parent().find("#dropdownButton").html('Kies een afmeting <span class="caret"></span>');
                }
                if($(this).data("paramColumn") == "coatingId"){
                    $("#coatingParameters").find(".paramItemSelect").remove();
                    var alreadyDisplayed = [];
                    for(var p in params){
                        if ($.inArray(params[p].coatingId, alreadyDisplayed) != -1){

                        }else{
                            $("#coatingParameters").append('<li><a href="#" class="coatingItem paramItemSelect itemDisabled" data-param-id="' + params[p].id + '" data-param-value="' + params[p].coatingId + '" data-param-text-value="' + params[p].coatingnaam_nl + '" data-param-key="' + p + '" >' + params[p].coatingnaam_nl + '</a></li>');
                            alreadyDisplayed[p] = params[p].coatingId;
                        }
                    }
                    $(this).parent().parent().find("#dropdownButton").html('Kies een coating <span class="caret"></span>');
                }
            });
            $(this).css("display","none");
            $("#artikelnummer").html("<b>Artikelnummer:</b> Kies eerst een kleur en een afmeting.");
            $("#levertijd").html("<b>Levertijd:</b> Kies eerst een kleur en een afmeting.");
            $("#voorraad").html('<b>In voorraad:</b> Kies eerst een kleur en een afmeting.' );
            $("#carouselProductDetailsInner").html(htmlImageGroot);
            $("#itemslider-zoom").html(htmlImageKlein);
        });



        /*$(document).on('click', '#removeSelection', function(){
         $("#artikelnummer").html("<b>Artikelnummer:</b> Kies eerst een kleur en een afmeting.");
         $("#levertijd").html("<b>Levertijd:</b> Kies eerst een kleur en een afmeting.");
         $("#voorraad").html('<b>In voorraad:</b> Kies eerst een kleur en een afmeting.' );
         colorkeyglobal = "null";
         $('#afmetingParameters').empty();
         $('#colorsProductDetail').empty();
         var colorsAlreadyDisplayed = [];
         $.each(params, function(keyeach, value) {
         if ($.inArray(params[keyeach].colorId, colorsAlreadyDisplayed) != -1){

         }else{
         $("#colorsProductDetail").append('<li style="background-color:' + params[keyeach].hex + '" class="kleurItem" data-param-id="' + params[keyeach].id + '" data-product-nr="' + params[keyeach].productNr + '" data-param-key="' + keyeach + '" data-type="' + productType + '"  title="' + params[keyeach].naam_nl + '"></li>');
         colorsAlreadyDisplayed[keyeach] = params[keyeach].colorId;
         }
         });
         $.each(params, function(keyeach, value){
         $("#afmetingParameters").append('<li><a href="#" class="afmetingItem" data-param-key="' + keyeach + '">' + params[keyeach].afmeting + '</a></li>')
         });
         $('#dropdownButton').html('Kies een afmeting<span class="caret"></span>');
         $(this).css("display","none");
         });

         $(document).on('click', '.kleurItem', function(){
         $('#removeSelection').css("display", "block");
         if(productType == "KleurEnAfmeting"){
         $(".kleurItem").each(function() {
         $(this).css("border","none");
         });
         $(this).css("border","2px #FFCC00 solid");
         var colorkey = $(this).data("paramKey");
         colorkeyglobal = colorkey;
         if($('#dropdownButton').html() == 'Kies een afmeting<span class="caret"></span>'){
         /!*afmetingNotSelectedYet*!/
         $("#artikelnummer").html("<b>Artikelnummer:</b> Kies eerst een kleur en een afmeting.");
         $("#levertijd").html("<b>Levertijd:</b> Kies eerst een kleur en een afmeting.");
         $("#voorraad").html('<b>In voorraad:</b> Kies eerst een kleur en een afmeting.' );
         $('#dropdownafmeting').css("display","block");
         $('#afmetingParameters').empty();
         $.each(params, function(keyeach, value){
         if(params[keyeach].colorId == params[colorkey].colorId){

         $("#afmetingParameters").append('<li><a href="#" class="afmetingItem" data-param-key="' + keyeach + '">' + params[keyeach].afmeting + '</a></li>')
         }
         });
         }else{
         console.log("test");
         if(params[colorkey].voorraad == 1){
         var voorraadtext = "Op voorraad";
         var voorraadcss = "green";
         }else{
         var voorraadtext = "Niet in voorraad";
         var voorraadcss = "red";
         }
         $("#artikelnummer").html("<b>Artikelnummer:</b> " + params[colorkey].productNr);
         $("#levertijd").html("<b>Levertijd:</b> " + params[colorkey].levertermijn);
         $("#voorraad").html('<b>In voorraad: <span style="color:' + voorraadcss + '">' + voorraadtext + '</span></b>' );
         $('#colorProductNr').val(params[colorkey].productNr);
         $('#afmetingParameters').empty();
         $.each(params, function(keyeach, value){
         if(params[keyeach].colorId == params[colorkey].colorId){
         $("#afmetingParameters").append('<li><a href="#" class="afmetingItem" data-param-key="' + keyeach + '">' + params[keyeach].afmeting + '</a></li>')
         }
         });
         }
         }else{
         var colorkey = $(this).data("paramKey");
         $(".kleurItem").each(function() {
         $(this).css("border","none");
         });
         $(this).css("border","2px #FFCC00 solid");
         console.log(params);
         if(params[colorkey].voorraad == 1){
         var voorraadtext = "Op voorraad";
         var voorraadcss = "green";
         }else{
         var voorraadtext = "Niet in voorraad";
         var voorraadcss = "red";
         }
         console.log("cool");
         console.log(colorkey);
         console.log(productimages);
         $("#artikelnummer").html("<b>Artikelnummer:</b> " + params[colorkey].productNr);
         $("#levertijd").html("<b>Levertijd:</b> " + params[colorkey].levertermijn);
         $("#voorraad").html('<b>In voorraad: <span style="color:' + voorraadcss + '">' + voorraadtext + '</span></b>' );
         $('#colorProductNr').val(params[colorkey].productNr);
         var productimagecounter = colorkey + headImages.length;
         if(productimages[productimagecounter].productNrImg == params[colorkey].productNr){
         $('#productDetailsCarousel').carousel(colorkey);
         $('#productDetailsCarousel').carousel('pause');
         console.log("test");
         }
         }*/

        /* $('#colorProductNr').val(params[colorkey].productNr);
         console.log($('#colorProductNr').val());
         if(params[colorkey].voorraad == 1){
         var voorraadtext = "Op voorraad";
         var voorraadcss = "green";
         }else{
         var voorraadtext = "Niet in voorraad";
         var voorraadcss = "red";
         }
         if($(this).data("type") == "Kleur"){
         $("#artikelnummer").html("<b>Artikelnummer:</b> " + params[colorkey].productNr);
         $("#levertijd").html("<b>Levertijd:</b> " + params[colorkey].levertermijn);
         $("#voorraad").html('<b>In voorraad: <span style="color:' + voorraadcss + '">' + voorraadtext + '</span></b>' );
         }else{
         $("#artikelnummer").html("<b>Artikelnummer:</b> Kies eerst een kleur en een afmeting.");
         $("#levertijd").html("<b>Levertijd:</b> Kies eerst een kleur en een afmeting.");
         $("#voorraad").html('<b>In voorraad:</b> Kies eerst een kleur en een afmeting.' );
         $('#dropdownafmeting').css("display","block");
         $('#afmetingParameters').empty();
         $.each(params, function(keyeach, value){
         if(params[keyeach].colorId == params[colorkey].colorId){
         $("#afmetingParameters").append('<li><a href="#" class="afmetingItem" data-param-key="' + keyeach + '">' + params[keyeach].afmeting + '</a></li>')
         }
         });
         }
         });*/



        $('#addToCartButton').click(function() {
            var allParamsSet = true;
            $(".parametervalue").each(function () {
                if ($(this).val() == "") {
                    allParamsSet = false;
                }
            });
            if(singleProduct.length == 0){
                var singleProductNr = $("#productNrValueSingle").html();
                var amount = $("#amounttextbox").val();
                $.ajax({
                    type: "GET",
                    url: "{{ url('/addtocart/single')}}",
                    data: {"productNr": singleProductNr, "amount": amount},
                    cache: false,
                    success: function(data){
                        toastr["success"]("Product aan offertemand toegevoegd!");
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "100",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        $('#cartAmountItems').html(data);
                    }
                });
            }else if(allParamsSet == true) {
                var amount = $("#amounttextbox").val();
                console.log(paramsSelected);
                $.ajax({
                    type: "GET",
                    
                    url:"{{ url('/addtocart/afmeting')}}",
                    data: {"productNr": paramsSelected[0].productNr, "amount": amount},
                    cache: false,
                    success: function(data){
                        toastr["success"]("Product aan offertemand toegevoegd!");
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-center",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "100",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        $('#cartAmountItems').html(data);
                    }
                });
            } else {
                toastr["warning"]("Selecteer eerst een kleur en/of afmeting en/of coating!");
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "100",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            }
        });

        /*$(document).on('click', '.afmetingItem', function() {
         $('#removeSelection').css("display", "block");
         $('#dropdownButton').html($(this).text() + '<span class="caret"></span>');
         var afmetingkey = $(this).data("paramKey");
         $("#dropdownButton").data( "productnr", params[afmetingkey].productNr );
         console.log(params);
         if(colorkeyglobal != "null" || productType == "Afmeting"){
         if(params[afmetingkey].voorraad == 1){
         var voorraadtext = "Op voorraad";
         var voorraadcss = "green";
         }else{
         var voorraadtext = "Niet in voorraad";
         var voorraadcss = "red";
         }
         $("#artikelnummer").html("<b>Artikelnummer:</b> " + params[afmetingkey].productNr);
         $("#levertijd").html("<b>Levertijd:</b> " + params[afmetingkey].levertermijn);
         $("#voorraad").html('<b>In voorraad: <span style="color:' + voorraadcss + '">' + voorraadtext + '</span></b>' );
         }else{
         $("#colorsProductDetail").empty();
         var colorsAlreadyDisplayed = [];
         $.each(params, function(keyeach, value) {
         if (params[keyeach].colorId == params[afmetingkey].colorId) {
         if ($.inArray(params[keyeach].colorId, colorsAlreadyDisplayed) != -1){

         }else{
         $("#colorsProductDetail").append('<li style="background-color:' + params[keyeach].hex + '" class="kleurItem" data-param-id="' + params[keyeach].id + '" data-product-nr="' + params[keyeach].productNr + '" data-param-key="' + keyeach + '" data-type="' + productType + '"  title="' + params[keyeach].naam_nl + '"></li>');
         colorsAlreadyDisplayed[keyeach] = params[keyeach].colorId;
         }
         }
         });
         }*/

        $.each(productimages, function(key, image){
            htmlImageGroot += '<div class="item';
            if(tellerActiveProductImage == 0){
                htmlImageGroot += ' active'
            }
            htmlImageGroot += '" style="height:100% !important;"><img src="' + base_url + '/uploads/' + image.directory + '/' + image.naam + '" style="height:100% !important; object-fit: contain;" alt="Chania"></div>';

            htmlImageKlein += '<div class="item"><a data-target="#productDetailsCarousel" data-slide-to="' + tellerActiveProductImage + '" class="active"> <img src="' + base_url + '/uploads/' + image.directory + '/' + image.naam + '" alt="Paalhoudergroot" /> </a> </div>';
            tellerActiveProductImage++;
        });
        if (typeof currentProductParam !== 'undefined') {
            console.log("currentParamSet");
            $(".paramItemSelect").each(function(){
                if($(this).data("paramValue") == currentProductParam.colorId || $(this).data("paramValue") == currentProductParam.afmeting || $(this).data("paramValue") == currentProductParam.coatingId){
                    $(this).trigger("click");
                }
            });
            if(currentProductParam.images != null && currentProductParam.images != ""){
                console.log("jawel");
                var htmlImageGrootProductNr = "";
                var htmlImageKleinProductNr = "";
                var tellerActiveProductNr = 0;
                $.each(currentProductParam.images, function(key, image){
                    htmlImageGrootProductNr += '<div class="item';
                    if(tellerActiveProductNr == 0){
                        htmlImageGrootProductNr += ' active'
                    }
                    htmlImageGrootProductNr += '" style="height:100% !important;"><img src="' + base_url + '/uploads/' + image.directory + '/' + image.naam + '" style="height:100% !important; object-fit: contain;" alt="Chania"></div>';

                    htmlImageKleinProductNr += '<div class="item"><a data-target="#productDetailsCarousel" data-slide-to="' + tellerActiveProductNr + '" class="active"> <img src="' + base_url + '/uploads/' + image.directory + '/' + image.naam + '" alt="Paalhoudergroot" /> </a> </div>';

                    tellerActiveProductNr++;
                });
                $("#carouselProductDetailsInner").html(htmlImageGrootProductNr);
                $("#itemslider-zoom").html(htmlImageKleinProductNr);
            }else{
                $("#carouselProductDetailsInner").html(htmlImageGroot);
                $("#itemslider-zoom").html(htmlImageKlein);
            }

        }
    </script>
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection
