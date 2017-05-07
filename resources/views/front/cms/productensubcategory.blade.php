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
                                            <li class="margintrue">
                                                <label>
                                                    <a href="/subCategoryShow/{{$category->id}}/{{$tellerFilter}}" data-toggle="collapse" data-target="#{{$tellerFilter}}" class="collapsed headCatLink" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $category->{'naam_' . $lang};?>@if($category->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
                                                </label>
                                                @if($category->subCategories != null)
                                                    <ul id="{{$tellerFilter}}" data-param-id="{{$category->id}}" class="collapseItem collapse" aria-expanded="false" style="height: 0px;">
                                                        @foreach($category->subCategories as $subCategory)
                                                            <div class="checkbox categories" style="margin-left:10px;">
                                                                <label>
                                                                    <?php if(sizeof($subCategory->subCategories) == 0){?>
                                                                    <input type="checkbox" data-url="/subcategoryFilter/{{$subCategory->id}}"  data-param-id="{{$subCategory->id}}" class="subCategory" value="">
                                                                    <span class="cr"></span>
                                                                    <li><a href="/subcategoryFilter/{{$subCategory->id}}" data-param-id="{{$subCategory->id}}"><?php echo $subCategory->{'naam_' . $lang};?><span></span></a></li>
                                                                    <?php }else{?>
                                                                    <input type="checkbox" data-url="/subSubCategoryShow/{{$subCategory->id}}/{{$tellerFilterSub}}"  data-param-id="{{$subCategory->id}}" class="subCategory" value="">
                                                                    <span class="cr"></span>
                                                                    <a href="/subSubCategoryShow/{{$subCategory->id}}/{{$tellerFilterSub}}" data-toggle="collapse" data-target="#{{$tellerFilterSub}}" class="collapsed headCatLink" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $subCategory->{'naam_' . $lang};?>@if($subCategory->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
                                                                    <ul id="{{$tellerFilterSub}}" data-param-id="{{$subCategory->id}}" class="collapseItem collapse" aria-expanded="false" style="height: 0px;">
                                                                        @foreach($subCategory->subCategories as $subSubCategory)
                                                                            <div class="checkbox categories" style="margin-left:10px;">
                                                                                <label>
                                                                                    <input type="checkbox" data-url="/subcategoryFilter/{{$subSubCategory->id}}" class="subCategory" value="">
                                                                                    <span class="cr"></span>
                                                                                    <li><a href="/subcategoryFilter/{{$subSubCategory->id}}" data-param-id="{{$subSubCategory->id}}"><?php echo $subSubCategory->{'naam_' . $lang};?><span></span></a></li>
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
                </div>
            </div>
            <div id="allproductscontainer" class="col-md-9">
                <?php $teller = 1;?>
                @foreach($selectedCategories as $selectedCategory)
                    <?php if($teller > 1){?>

                <?php if($teller == 1){
                   echo '<div class="row">';
                }?>
                    <div class="col-md-4">
                        <?php if(isset($selectedCategory->hasChilds)){?>
                            <a href="/subSubCategoryShow/{{$selectedCategory->id}}/{{$tellerFilterSub2}}">
                        <?php }else{?>
                            <a href="/subcategoryFilter/{{$selectedCategory->id}}">
                        <?php }?>
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <?php if(isset($selectedCategory->image->naam)){?>
                                            <div class="productimg wortelhanger" style="background-image: url('{{ URL::asset("uploads/" . $selectedCategory->image->directory . "/" . $selectedCategory->image->naam )}}')"></div>
                                        <?php }else{?>
                                            <div class="productimg" ></div>
                                        <?php }?>
                                        <h2><?php echo $selectedCategory->{"naam_" . $lang}; ?></h2>
                                        <?php if(isset($selectedCategory->hasChilds)){?>
                                        <a href="/subSubCategoryShow/{{$selectedCategory->id}}/{{$tellerFilterSub2}}" class="btn btn-default add-to-cart">Bekijk deze categorie</a>
                                        <?php
                                        $tellerFilterSub2++;
                                        }else{?>
                                        <a href="/subcategoryFilter/{{$selectedCategory->id}}" class="btn btn-default add-to-cart">Bekijk deze categorie</a>
                                        <?php }?>
                                    </div>
                                </div>
                                <div class="choose">
                                </div>
                            </div>
                        </a>
                    </div>

            <?php if($teller % 3 == 0){
                echo '</div>';
                echo '<div class="row">';
            }
            if($teller % 3 != 0 && $teller == sizeof($selectedCategories)){
                echo '</div>';
            }?>
                    <?php $teller++;  $onthoud = $selectedCategory->id?>
                    <?php }else{ ?>
                        <?php if($teller == 1){
                            echo '<div class="row">';
                        }?>
                        <div class="col-md-4">
                            <?php if(isset($selectedCategory->hasChilds)){?>
                                <a href="/subSubCategoryShow/{{$selectedCategory->id}}/{{$tellerFilterSub2}}">
                            <?php }else{?>
                                <a href="/subcategoryFilter/{{$selectedCategory->id}}">
                            <?php }?>
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <?php if(isset($selectedCategory->image->naam)){?>
                                            <div class="productimg wortelhanger" style="background-image: url('{{ URL::asset("uploads/" . $selectedCategory->image->directory . "/" . $selectedCategory->image->naam )}}')"></div>
                                            <?php }else{?>
                                            <div class="productimg" ></div>
                                            <?php }?>
                                            <h2><?php echo $selectedCategory->{"naam_" . $lang}; ?></h2>
                                            <?php if(isset($selectedCategory->hasChilds)){?>
                                            <a href="/subSubCategoryShow/{{$selectedCategory->id}}/{{$tellerFilterSub2}}" class="btn btn-default add-to-cart">Bekijk deze categorie</a>
                                            <?php
                                            $tellerFilterSub2++;
                                            }else{?>
                                            <a href="/subcategoryFilter/{{$selectedCategory->id}}" class="btn btn-default add-to-cart">Bekijk deze categorie</a>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="choose">
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php if($teller % 3 == 0){
                        echo '</div>';
                        echo '<div class="row">';
                    }
                    if($teller % 3 != 0 && $teller == sizeof($selectedCategories)){
                        echo '</div>';
                    }?>
                    <?php $teller++;  $onthoud = $selectedCategory->id?>

                    <?php }?>
                @endforeach
                <!-- Productenrij -->
            </div>
        </div>
    </div>
    <script>
        var clickCategorie = "<?php echo Session::get('clickedCategory');?>";
        var clickSubCategorie = "<?php echo Session::get('clickedSubCategory');?>"
        var clickSubCategorieId = "<?php echo Session::get('clickedSubCategoryId');?>"
        $("#" + clickCategorie).collapse();

        $(document).on('click','.headCatLink',function(event){
            console.log("headCatLink");
            if($(this).attr("aria-expanded") == "false"){
                event.preventDefault();
                if($(this).attr("type") == "checkbox"){
                    $(this).next().next().collapse();
                }else{
                    $(this).collapse();
                }
            }
        });

        if(clickSubCategorie !== ''){
            $("#" + clickSubCategorie).collapse();
        }
        $(".subCategory").each(function(){
            if($(this).data("paramId") == clickSubCategorieId){
                $(this).prop('checked', true);
            }
        });
        $(document).on('click', '.subCategory', function(event) {
            /*if($(this).next().next().attr("aria-expanded") == "true"){
                $(this).next().next().collapse();
            }else{*/
            if($(this).prop('checked') == true){
                window.location.href = $(this).data("url") + "?removeFilterCat=1";
            }else{
                window.location.href = $(this).data("url");
            }

            /*}*/
        });
    </script>
@endsection
