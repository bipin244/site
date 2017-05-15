@extends('front.template.main')
@section('content')
    <div class="row spacerbig"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Categorie
                            </div>
                            <div class="panel-body">
                                <div class="collapse navbar-collapse navbar-ex1-collapse navbar-side-collapse">
                                    <ul class="nav navbar-nav side-nav">
                                        @foreach($categories as $category)
                                        <li>
                                            <label>
                                                <a href="{{ url('/subCategoryShow') }}/{{$category->id}}/{{$tellerFilter}}" data-toggle="collapse" data-target="#{{$tellerFilter}}" class="collapsed" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $category->{'naam_' . $lang};?>@if($category->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
                                            </label>
                                            @if($category->subCategories != null)
                                                <ul id="{{$tellerFilter}}" class="collapseItem collapse" aria-expanded="false" style="height: 0px;">
                                                    @foreach($category->subCategories as $subCategory)
                                                    <div class="checkbox categories" style="margin-left:10px;">
                                                        <label>
                                                            <input type="checkbox" class="subCategory" value="">
                                                            <span class="cr"></span>
                                                            <li><a href="{{ url('/subcategoryFilter') }}/{{$subCategory->id}}" data-param-id="{{$subCategory->id}}"><?php echo $subCategory->{'naam_' . $lang};?><span></span></a></li>
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                        <?php $tellerFilter++;?>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div id="allproductscontainer" class="col-md-9">
                <?php $teller = 1;?>
                @foreach($producten as $product)
                    <?php if($teller > 1){?>
                    @if($onthoud != $product->product_id)

                <?php if($teller == 1){
                   echo '<div class="row">';
                }?>
                    <div class="col-md-4">
                        <a href="{{ url('/productdetails') }}/{{$product->product_id}}">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <div class="productimg" style="background-image: url('{{ URL::asset("uploads/" . $product->directory . "/" . $product->naam )}}')"></div>
                                        <h2><?php echo $product->{"product_naam_" . $lang}; ?></h2>
                                        <p><?php echo $product->{"beschrijving_kort_" . $lang}; ?></p>
                                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>
                                    </div>
                                </div>
                                <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="{{ url('/productdetails') }}/{{$product->product_id}}"><i class="fa fa-plus-square"></i> Details bekijken</a></li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>

            <?php if($teller % 3 == 0){
                echo '</div>';
                echo '<div class="row">';
            }
            if($teller % 3 != 0 && $teller == sizeof($producten)){
                echo '</div>';
            }?>
                    <?php $teller++;  $onthoud = $product->product_id?>
                    @endif
                    <?php }else{ ?>
                        <?php if($teller == 1){
                            echo '<div class="row">';
                        }?>
                        <div class="col-md-4">
                            <a href="{{ url('/productdetails') }}/{{$product->product_id}}">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <div class="productimg" style="background-image: url('{{ URL::asset("uploads/" . $product->directory . "/" . $product->naam )}}')"></div>
                                            <h2><?php echo $product->{"product_naam_" . $lang}; ?></h2>
                                            <p><?php echo $product->{"beschrijving_kort_" . $lang}; ?></p>
                                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Toevoegen Offertemand</a>
                                        </div>
                                    </div>
                                    <div class="choose">
                                        <ul class="nav nav-pills nav-justified">
                                            <li><a href="{{ url('/productdetails') }}/{{$product->product_id}}"><i class="fa fa-plus-square"></i> Details bekijken</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php if($teller % 3 == 0){
                        echo '</div>';
                        echo '<div class="row">';
                    }
                    if($teller % 3 != 0 && $teller == sizeof($producten)){
                        echo '</div>';
                    }?>
                    <?php $teller++;  $onthoud = $product->product_id?>

                    <?php }?>
                @endforeach
                <!-- Productenrij -->
            </div>
        </div>
    </div>
    <script>
        var producten = <?php echo json_encode($producten);?>;
        var filterCategoryIds = new Array();
        var url = "/filterProducts";
        $(document).on('click', '.headCategory', function() {
            console.log(producten);
            if($(this).is(":checked")){
                $(this).parent().parent().parent().find(".subCategory").each(function(){
                    $(this).prop('checked', true);
                });
            }else{
                $(this).parent().parent().parent().find(".subCategory").each(function(){
                    $(this).prop('checked', false);
                });
            }
            filterCategoryIds.push($(this).data("paramId"));
            var jsonCategoryIds = JSON.stringify(filterCategoryIds);
            $.ajax({
                type: "GET",
                url: url,
                data: {"filterCategoryIds": jsonCategoryIds},
                cache: false,
                success: function(data){
                    console.log(data);
                    $("#allproductscontainer").html(data);
                }
            });
        });

        $('.add-to-cart').click(function(){
            toastr["error"]("U moet eerst aangemeld zijn!");
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        });



    </script>
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection
