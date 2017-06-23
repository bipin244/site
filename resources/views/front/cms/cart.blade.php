@extends('front.template.main')
@section('content')
	<div class="row rowheaderyellow">
        <div class="col-md-12 text-center">
            <h1 class="headerpage">OFFERTEMAND</h1>
        </div>
    </div>
    <div class="row spacersmall"></div>
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            {!! Breadcrumbs::render('cart') !!}
        </div>
    </div>
    <div id="cloneNoItems" style="display:none">
        <table>
        <tr>
            <td class="col-md-12 text-center" colspan="4">
                <h2>Er zitten nog geen producten in uw offertemand.</h2>
            </td>
        </tr>
        <tr>
            <td> </td>
            <td> </td>
            <td> </td>
            <td> </td>
            <td>
                <a href="/index" type="button" class="btn btn-default">
                    <span class="glyphicon glyphicon-shopping-cart"></span> Verder winkelen
                </a>
            </td>
        </tr>
        </table>
    </div>
    <div class="row spacerbig"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Barcode</th>
                        <th class="text-center">Verpakkingseenheid</th>
                        <th>Aantal</th>
                        <th> </th>
                    </tr>
                    </thead>
                    <tbody id="bodyCart">
                    <?php  if($producten == null && $productenSingle == null){?>
                        <tr>
                            <td class="col-md-12 text-center" colspan="4"><h2>Er zitten nog geen producten in uw offertemand.</h2></td>
                        </tr>
                        <tr>
                            <td>   </td>
                            <td>   </td>
                            <td>   </td>
                            <td>   </td>
                            <td>
                                <a href="/index" type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-shopping-cart"></span> Verder winkelen
                                </a>
                            </td>
                        </tr>
                    <?php }else{ ?>
                    @foreach($productenSingle as $product)
                        <tr>
                            <td class="col-md-6">
                                <div class="media">
                                    <a class="thumbnail pull-left" href="#"> <img class="media-object" src="{{ URL::asset("uploads/" . $product->directory . "/" . $product->naam )}}" style="width: 72px; height: 72px;"> </a>
                                    <div class="media-body">
                                        <h4 class="media-heading"><a href="#" class="productheading" data-product-id="{{$product->product_id}}" data-product-nr="{{$product->productNr}}"><?php echo $product->productNr;?></a></h4>
                                        <h5 class="media-heading"><a href="#" class="productheading" data-product-id="{{$product->product_id}}" data-product-nr="{{$product->product_id}}"><?php echo $product->{"product_naam_" . $lang} ?></a></h5>
                                    </div>
                                </div>
                            </td>
                            <td class="col-md-2 text-center">
                                <?php if($product->EANNumber != "" && $product->EANNumber != null){
                                    echo $product->EANNumber;
                                }else{
                                    echo "Geen";
                                }?>
                            </td>
                            <td class="col-md-1 " style="text-align: center">{{$product->verpakkingsEenheid}}</td>
                            <td class="col-md-1 " style="text-align: center">
                                <input type="email" class="form-control amountInput" data-cart-id="{{$product->cart_item_id}}" id="exampleInputEmail1" value="{{$product->amount}}">
                            </td>
                            <td class="col-md-1">
                                <button type="button" class="btn btn-danger removeButtonCartItem" data-cart-id="{{$product->cart_item_id}}">
                                    <span class="glyphicon glyphicon-remove"></span> Verwijderen
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($producten as $product)
                    <tr>
                        <td class="col-md-6">
                            <div class="media">
                                <a class="thumbnail pull-left" href="#"> <img class="media-object" src="{{ URL::asset("uploads/" . $product->directory . "/" . $product->naam )}}" style="width: 72px; height: 72px;"> </a>
                                <div class="media-body">
                                    <h4 class="media-heading"><a href="#" class="productheading" data-product-id="{{$product->product_id}}" data-product-nr="{{$product->paramProductNr}}"><?php echo $product->paramProductNr;?></a></h4>
                                    <h5 class="media-heading"><a href="#" class="productheading" data-product-id="{{$product->product_id}}" data-product-nr="{{$product->product_id}}"><?php echo $product->{"product_naam_" . $lang} . " " . $product->{"color_naam_" . $lang} . " " . $product->{"coatingnaam_" . $lang} . " (" . $product->afmeting . ")"?></a></h5>
                                </div>
                            </div>
                        </td>
                        <td class="col-md-2 text-center">{{$product->EANNumber}}</td>
                        <td class="col-md-1 " style="text-align: center">{{$product->verpakkingsEenheid}}</td>
                        <td class="col-md-1 " style="text-align: center">
                            <input type="email" class="form-control amountInput" id="exampleInputEmail1" data-cart-id="{{$product->cart_item_id}}" value="{{$product->amount}}">
                        </td>
                        <td class="col-md-1">
                            <button type="button" class="btn btn-danger removeButtonCartItem" data-cart-id="{{$product->cart_item_id}}">
                                <span class="glyphicon glyphicon-remove"></span> Verwijderen
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td><h3>Totaal prijs</h3></td>
                        <td class="text-right"><h4><strong>Beschikbaar na versturen</strong></h4></td>
                    </tr>
                    <tr>
                        <td>   </td>
                        <td>   </td>
                        <td>   </td>
                        <td>
                            <a href="/index" type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-shopping-cart"></span> Verder winkelen
                            </a></td>
                        <td>
                            <button href="#" type="button" class="btn btn-success offertemandVersturen">
                                Offertemand versturen <span class="glyphicon glyphicon-play"></span>
                            </button></td>
                    </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
            <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Nog graag even het volgende formulier aanvullen.</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form class="form-horizontal col-sm-10 col-sm-offset-1" method="post" action="/send/sendOrder">
                                    <div class="form-group"><label>Bedrijfsnaam</label><input class="form-control required" placeholder="bedrijfsnaam" name="bedrijfsnaam" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text" required></div>
                                    <div class="form-group"><label>E-mail</label><input class="form-control required" placeholder="email" name="email" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="email" required></div>
                                    <div class="form-group"><label>Straat</label><input class="form-control required" placeholder="straatnaam" name="straatnaam" data-placement="top" data-trigger="manual" data-content="Must be at least 3 characters long, and must only contain letters." type="text" required></div>
                                    <div class="form-group"><label>Huisnummer</label><input class="form-control" placeholder="huisnummer" name="huisnummer" data-placement="top" data-trigger="manual" required/></div>
                                    <div class="form-group"><label>Postcode</label><input class="form-control phone" placeholder="provincie" name="postcode" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (999-999-9999)" type="text" required></div>
                                    <div class="form-group"><label>Stad</label><input class="form-control email" placeholder="stad/plaats" name="plaats" data-placement="top" data-trigger="manual" data-content="Must be a valid e-mail address (user@gmail.com)" type="text" required></div>
                                    <div class="form-group"><label>Land</label><input class="form-control phone" placeholder="land" name="land" data-placement="top" data-trigger="manual" data-content="Must be a valid phone number (999-999-9999)" type="text" required></div>
                                    <input type="hidden" name="visitorId" value="0">
                                    <div class="checkbox">
                                        <label><input name="isdefault" type="checkbox" value="1" checked>Dit adres als standaard leveradres instellen.</label>
                                    </div>
                                    <div class="form-group"><input type="submit" value="Bevestig" class="btn btn-success pull-right"><p class="help-block pull-left text-danger hide" id="form-error">&nbsp; The form is not valid. </p></div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ModalProduct" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="ModalProductLabel">Vul nog even het leveradres in.</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Afmeting</th>
                                        <th class="text-center">Kleur</th>
                                        <th class="text-center">Coating</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="col-md-3">
                                                <div class="media">
                                                    <a class="thumbnail pull-left" href="#"> <img id="ModalProductImg" class="media-object" src="" style="width: 72px; height: 72px;"> </a>
                                                </div>
                                            </td>
                                            <td class="col-md-1 text-center" id="ModalProductAfmeting">894984566221105</td>
                                            <td class="col-md-1 " style="text-align: center" ><li id="ModalProductKleur" style="background-color:#000" class="kleurItem itemDisabled paramItemSelect"></li></td>
                                            <td class="col-md-1 " style="text-align: center" id="ModalProductCoating"></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var producten = <?php echo json_encode($producten);?>;
        var uploadFolder = '<?php echo URL::asset("uploads/");?>';
        var user = <?php echo json_encode(Session::get('loggedin','0'));?>;
        $(".productheading").on("click", function() {
            console.log(producten);
            var newproducts = producten;
            var productId = $(this).data("productId");
            console.log("Clicked product Id: " + productId);
            var productChosen = "";
            $.each(producten, function(i, product){
                if(productId.toString() === product.product_id.toString()){
                    productChosen = product;
                }
            });
            console.log(productChosen);
            $('#ModalProductLabel').html("Detailweergave voor " + productChosen.product_naam_nl + " " + productChosen.color_naam_nl + " (" + productChosen.coatingnaam_nl + ")");
            $('#ModalProductImg').attr("src",uploadFolder + "/" + productChosen.directory + "/" + productChosen.naam);
            $('#ModalProductAfmeting').html(productChosen.afmeting);
            $('#ModalProductKleur').css("background-color", productChosen.hex);
            $('#ModalProductCoating').html(productChosen.coatingnaam_nl);
            $("#ModalProduct").modal("show");
        });

        $(document).on('click','.removeButtonCartItem',function(){
            $(this).parent().parent().remove();
            var cartIdToRemove = $(this).data("cartId");
            $.ajax({
                type: "GET",
                url: "/removeCartItem",
                data: {"cartId": cartIdToRemove},
                cache: false,
                success: function(data){
                    console.log("remove succes");
                    $("#cartAmountItems").html(data);
                }
            });
            if($(".removeButtonCartItem").length == 0){
                var item = document.getElementById("cloneNoItems");
                console.log(item.cloneNode(true));
                $("#bodyCart").html($("#cloneNoItems").html());
            }
        });

        var timeoutId = 0;
        $('.amountInput').keyup(function () {
            clearTimeout(timeoutId); // doesn't matter if it's 0
            timeoutId = setTimeout(getFilteredResultCount, 500);
            var cartId = $(this).data("cartId");
            var newAmount = $(this).val();
            function getFilteredResultCount(){
                $.ajax({
                    type: "GET",
                    url: "/updateAmountCartItem",
                    data: {"cartId": cartId, "newAmount": newAmount},
                    cache: false,
                    success: function(data){
                        console.log("amount update succes");
                    }
                });
            }
        });

        $(document).on('click','.offertemandVersturen',function(e){
            e.preventDefault();
            console.log(user);
            if(user.toString() != "0"){
                $('input[name="visitorId"]').val(user.id);
                $.ajax({
                    type: "GET",
                    url: "/visitor/getDefaultAddress",
                    data: {"visitorId": user.id},
                    cache: false,
                    success: function(data){
                        if(data == 0){
                            console.log("")
                        }else{
                            console.log("address found!");
                            console.log(data.straat);
                            data = JSON.parse(data);
                            $('input[name="straatnaam"]').val(data.straat);
                            $('input[name="huisnummer"]').val(data.huisnummer);
                            $('input[name="plaats"]').val(data.stad);
                            $('input[name="postcode"]').val(data.postcode);
                            $('input[name="land"]').val(data.land);
                        }
                        $('input[name="bedrijfsnaam"]').val(user.bedrijfsnaam);
                        $('input[name="email"]').val(user.email);
                        $('#myModal').modal('show');
                    }
                });
                console.log("User set")
            }else{
                $('#myModal').modal('show');
                console.log("User NOT set");
            }
        });
    </script>
@endsection