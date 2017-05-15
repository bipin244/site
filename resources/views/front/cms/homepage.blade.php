@extends('front.template.main')

@section('content')
    <style>
        /* Note: Try to remove the following lines to see the effect of CSS positioning */
        .affix {
            top: 20px;
        }
        .ontop{
            z-index:1500;
        }
    </style>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-2">
            <div class="panel panel-default panel-default-home staticmenu ontop" data-spy="affix" data-offset-top="150" data-offset-bottom="250">
                <div class="panel-heading">
                    Ons assortiment
                </div>
                <div class="panel-body panel-body-home">
                    <div class="collapse navbar-collapse navbar-ex1-collapse navbar-side-collapse">
                        <ul class="nav navbar-nav side-nav">
                            @foreach($categories as $category)
                                <li class="margintrue">
                                    <label>
                                        <a href="{{ url('/subCategoryShow')}}/{{$category->id}}/{{$tellerFilter}}" data-toggle="collapse" data-target="#{{$tellerFilter}}" class="collapsed" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $category->{'naam_' . $lang};?>@if($category->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
                                    </label>
                                    @if($category->subCategories != null)
                                        <ul id="{{$tellerFilter}}" data-param-id="{{$category->id}}" class="collapseItem collapse" aria-expanded="false" style="height: 0px;">
                                            @foreach($category->subCategories as $subCategory)
                                                <div class="checkbox categories" style="margin-left:10px;">
                                                    <label>
                                                        <input type="checkbox" class="subCategory" value="">
                                                        <span class="cr"></span>
                                                        <?php if(sizeof($subCategory->subCategories) == 0){?>
                                                        <li><a href="#" data-param-id="{{$subCategory->id}}"><?php echo $subCategory->{'naam_' . $lang};?><span></span></a></li>
                                                        <?php }else{?>
                                                        <a href="{{ url('/subSubCategoryShow')}}/{{$subCategory->id}}/{{$tellerFilterSub}}" data-toggle="collapse" data-target="#{{$tellerFilterSub}}" class="collapsed" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $subCategory->{'naam_' . $lang};?>@if($subCategory->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
                                                        <ul id="{{$tellerFilterSub}}" data-param-id="{{$subCategory->id}}" class="collapseItem collapse" aria-expanded="false" style="height: 0px;">
                                                            @foreach($subCategory->subCategories as $subSubCategory)
                                                                <div class="checkbox categories" style="margin-left:10px;">
                                                                    <label>
                                                                        <input type="checkbox" class="subCategory" value="">
                                                                        <span class="cr"></span>
                                                                        <li><a href="#" data-param-id="{{$subSubCategory->id}}"><?php echo $subSubCategory->{'naam_' . $lang};?><span></span></a></li>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </ul>
                                                        <?php }?>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                                <li><div class="hrlinevertical"></div></li>
                                <?php $tellerFilter++;?>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row spacersmall"></div>
            <div class="panel panel-default">
                <div class="panel-heading filterheading">Nieuwe producten</div>
                {!! Breadcrumbs::render('Home') !!}

                <div class="panel-body">
                    <div class="newproductcontainer">
                        <div id="carousel-example-generic" class="carousel slide hidden-xs" data-ride="carousel">
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">

                                <?php $teller = 0;?>
                                @foreach($allProducts as $product)
                                    <?php if($teller == 0){?>
                                <div class="item active">
                                    <div class="row">
                                    <?php }elseif($teller % 3 == 0){ ?>
                                <div class="item">
                                    <div class="row">
                                    <?php }?>
                                        <?php if(property_exists($product,'singleProduct')){?>
                                        <div class="col-sm-4">
                                            <div class="col-item">
                                                <div class="product-image-wrapper-new">
                                                        <div class="productimg" style="background-image: url('{{URL::asset("uploads/" . $product->directory . "/" . $product->naam )}}')">
                                                        </div>
                                                              <div class="info">
                                                                    <div class="row">
                                                                        <div class="price col-md-6" style="font-size:1.05em;">
                                                                            <h4><?php $product->{'product_naam_' . $lang}?></h4>
                                                                        </div>
                                                                        <div class="rating hidden-sm col-md-6">
                                                                            <h5><?php $product->{'beschrijving_kort_' . $lang}?></h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="separator clear-left">
                                                                    <p class="btn-add">
                                                                        <i class="fa fa-shopping-cart"></i><a href="" class="hidden-sm addToCart" data-product-nr="{{$product->productNr}}">In offertemand</a></p>
                                                                    <p class="btn-details">
                                                                        <?php if ($product->productNr == "") {?>
                                                                            <i class="fa fa-list"></i><a class="hidden-sm" href="{{ url('/productdetailsSubProduct')}}/{{$product->product_id}}/{{$product->product_productNr}}">Bekijk details</a></p>;
                                                                        <?php } else {?>
                                                                            <i class="fa fa-list"></i><a class="hidden-sm" href="{{ url('/productdetailsSubProduct')}}/{{$product->product_id}}/{{$product->productNr}}">Bekijk details</a></p>;
                                                                        <?php }?>
                                                                </div>
                                                                <div class="clearfix">
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }else{?>
                                        <div class="col-sm-4">
                                            <div class="col-item">
                                                <div class="product-image-wrapper-new">
                                                        <div class="productimg" style="background-image: url('{{URL::asset("uploads/" . $product->directory . "/" . $product->naam  )}}')">
                                                        </div>
                                                        <div class="info">
                                                            <div class="row">
                                                                <div class="price col-md-6" style="font-size:1.05em;">
                                                                    <?php echo $product->{'product_naam_' . $lang};?>
                                                                </div>
                                                                <div class="rating hidden-sm col-md-6">
                                                                    <h5><?php
                                                                        if ($product->colorId != 0 && $product->colorId != null) {
                                                                            echo '<br/>' . $product->{"color_naam_" . $lang};
                                                                        }
                                                                        if ($product->coatingId != 0 && $product->coatingId != null) {
                                                                            echo '<br/>' . $product->{"coatingnaam_" . $lang};
                                                                        }
                                                                        if ($product->afmeting != '' && $product->afmeting != null) {
                                                                            echo '<br/>' . $product->afmeting;
                                                                        }?></h5>
                                                                </div>
                                                            </div>
                                                            <div class="separator clear-left">
                                                                <p class="btn-add">
                                                                    <i class="fa fa-shopping-cart"></i><a href="" class="hidden-sm addToCart" data-product-nr="{{$product->productNr}}">In offertemand</a></p>
                                                                <p class="btn-details">
                                                                    <?php if ($product->productNr == "") {?>
                                                                        <i class="fa fa-list"></i><a class="hidden-sm" href="{{ url('/productdetailsSubProduct')}}/{{$product->product_id}}/{{$product->product_productNr}}">Bekijk details</a></p>;
                                                                    <?php } else {?>
                                                                        <i class="fa fa-list"></i><a class="hidden-sm" href="{{ url('/productdetailsSubProduct')}}/{{$product->product_id}}/{{$product->productNr}}">Bekijk details</a></p>;
                                                                    <?php }?>
                                                            </div>
                                                            <div class="clearfix">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>
                                        <?php $teller++;
                                            if($teller % 3 == 0 || $teller == (count($allProducts) + 1)){?>
                                            </div>
                                        </div>
                                        <?php }?>
                                @endforeach
                            </div>
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php if(Session::get('loggedin',null) == null){ ?>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading filterheading">Klantenpaneel</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <label>Ik ben al klant</label><br/>
                                <a href="#" class="btn btn-default add-to-cart loginLoginDialog homepagepanel">Inloggen</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <label>Nieuwe klant</label><br/>
                                <a href="#" class="btn btn-default add-to-cart registerLoginDialog homepagepanel">Registreren</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }?>

    <div class="row spacersmall"></div>
    </div>
        </div>

<script>
    $('.collapseItem').on("click", function(){
        window.location.href = '/subCategoryShow/' + $(this).data("paramId");
    });

    $(document).ready(function(){
        var headerHeight = $('#custom-bootstrap-menu').outerHeight();
        var footerHeight = $('.footer_widgets_wrapper').outerHeight();
        console.log(headerHeight + " " + footerHeight);
        $('.staticmenu').affix({
            offset: {
                top: headerHeight,
                bottom: footerHeight
            }
        });
    });

    $(document).on('click','.addToCart',function(event){
        event.preventDefault();
        var productNrAddToCart = $(this).data('productNr');
        $.ajax({
            type: "GET",
            url: "/addtocart/afmeting",
            data: {"productNr": productNrAddToCart, "amount": 1},
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
    });
</script>
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection
