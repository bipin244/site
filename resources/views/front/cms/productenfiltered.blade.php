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
                                                    <a href="/subCategoryShow/{{$category->id}}/{{$tellerFilter}}" data-toggle="collapse" data-target="#{{$tellerFilter}}" class="collapsed" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $category->{'naam_' . $lang};?>@if($category->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
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
                                                                    <input type="checkbox" data-url="/subSubCategoryShow/{{$subCategory->id}}/{{$tellerFilterSub}}" data-toggle="collapse" data-target="#{{$tellerFilterSub}}"  data-param-id="{{$subCategory->id}}" class="subCategory" value="">
                                                                    <span class="cr"></span>
                                                                    <a href="/subSubCategoryShow/{{$subCategory->id}}/{{$tellerFilterSub}}" data-toggle="collapse" data-target="#{{$tellerFilterSub}}" class="collapsed" data-param-id="{{$category->id}}" aria-expanded="false"><?php echo $subCategory->{'naam_' . $lang};?>@if($subCategory->subCategories != null)<i class="fa fa-plus" style="margin-left:10px;"></i>@endif</a>
                                                                    <ul id="{{$tellerFilterSub}}" data-param-id="{{$subCategory->id}}" class="collapseItem collapse" aria-expanded="false" style="height: 0px;">
                                                                        @foreach($subCategory->subCategories as $subSubCategory)
                                                                            <div class="checkbox categories" style="margin-left:10px;">
                                                                                <label>
                                                                                    <input type="checkbox" class="subCategory" data-param-id="{{$subSubCategory->id}}" value="">
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
                <div id="filterPanels">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading filterheading">
                                    Afmeting
                                </div>
                                <div class="panel-body">
                                    <div class="collapse navbar-collapse navbar-ex1-collapse navbar-side-collapse">
                                        <ul class="nav navbar-nav side-nav">
                                            <li id="afmetingenFilters">
                                                @foreach($afmetingen as $afmeting)
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="afmetingFilter" data-param-id="{{$afmeting}}" value="">
                                                        <span class="cr"></span>
                                                        <?php if($afmeting == "" || $afmeting == null){?>
                                                            Geen afmeting
                                                        <?php }else{?>
                                                            {{$afmeting}}
                                                        <?php }?>
                                                    </label>
                                                </div>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading filterheading">
                                    Kleur
                                </div>
                                <div class="panel-body">
                                    <div class="collapse navbar-collapse navbar-ex1-collapse navbar-side-collapse">
                                        <ul class="nav navbar-nav side-nav">
                                            <li id="colorFilters">
                                                @foreach($colors as $color)
                                                <div class="checkbox" data-param-id="{{$color->id}}">
                                                    <label>
                                                        <input type="checkbox" class="colorFilter" data-param-id="{{$color->id}}" value="">
                                                        <span class="cr"></span>
                                                        <?php echo $color->{"color_naam_" . $lang}?>
                                                    </label>
                                                </div>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading filterheading">
                                    Coating
                                </div>
                                <div class="panel-body">
                                    <div class="collapse navbar-collapse navbar-ex1-collapse navbar-side-collapse">
                                        <ul class="nav navbar-nav side-nav">
                                            <li id="coatingFilters">
                                                @foreach($coatings as $coating)
                                                    <div class="checkbox" data-param-id="{{$coating->id}}">
                                                        <label >
                                                            <input type="checkbox" class="coatingFilter" data-param-id="{{$coating->id}}" value="">
                                                            <span class="cr"></span>
                                                            <?php echo $coating->{"coatingnaam_" . $lang}?>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="allproductscontainer" class="col-md-9">
                {!! $html !!}
            </div>
        </div>
    </div>
    <script>
        var producten = <?php echo json_encode($producten);?>;
        var filterCategoryIds = new Array();
        var url = "/filterProducts";
        var filterData = <?php echo json_encode($filterData);?>;
        var lang = "<?php echo $lang;?>";
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


        var clickCategorie = "<?php echo Session::get('clickedCategory');?>";
        $("#" + clickCategorie).collapse();
        var clickedCategory = "<?php echo $categoryClicked;?>";
        var clickSubCategorie = "<?php echo Session::get('clickedSubCategory');?>";
        var clickSubCategorieId = "<?php echo Session::get('clickedSubCategoryId');?>";


        $(".subCategory").each(function(){
            if($(this).data("paramId") == clickedCategory){
                $(this).prop('checked', true);
            }
        });
        if(clickSubCategorie !== ''){
            $("#" + clickSubCategorie).collapse();
        }
        console.log(clickSubCategorieId);
        $(".subCategory").each(function(){
            if($(this).data("paramId") == clickSubCategorieId){
                $(this).prop('checked', true);
            }
        });
        console.log(filterData);
        $(document).on('click', '.subCategory', function() {
            console.log($(this).is(':checked'));
            console.log(filterData["selectedCategories"]);
            if($(this).is(':checked')){
                var clickedCategoryId = $(this).data("paramId");
                filterData["selectedCategories"].push(clickedCategoryId.toString());
                console.log("CatId's after Add: " + filterData["selectedCategories"]);
                filterData.selectedColors = [];
                filterData.selectedCoatings = [];
                filterData.selectedAfmetingen = [];
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                        var afmetingenHtml = "";
                        $.each(currentData.afmetingen, function(index, value){
                            if(value == "" || value == null) {
                                afmetingenHtml += '<div class="checkbox"> <label> <input type="checkbox" class="afmetingFilter" data-param-id="' + value + '" value=""> <span class="cr"></span>Geen afmeting</label> </div>';
                            }else{
                                afmetingenHtml += '<div class="checkbox"> <label> <input type="checkbox" class="afmetingFilter" data-param-id="' + value + '" value=""> <span class="cr"></span>' + value + ' </label> </div>';
                            }
                        });
                        $("#afmetingenFilters").html(afmetingenHtml);

                        var colorsHtml = "";
                        $.each(currentData.colors, function(index, value){
                            colorsHtml += '<div class="checkbox" data-param-id="' + value["id"] + '"> <label> <input type="checkbox" class="colorFilter" data-param-id="' + value["id"] + '" value=""> <span class="cr"></span>' + value["color_naam_" + lang] + '</label> </div>';
                        });
                        $("#colorFilters").html(colorsHtml);

                        var coatingsHtml = "";
                        $.each(currentData.coatings, function(index, value){
                            coatingsHtml += '<div class="checkbox" data-param-id="' + value["id"] + '"> <label> <input type="checkbox" class="coatingFilter" data-param-id="' + value["id"] + '" value=""> <span class="cr"></span>' + value["coatingnaam_" + lang] + '</label> </div>';
                        });
                        $("#coatingFilters").html(coatingsHtml);
                    }
                });
            }else{
                console.log("unchecked");
                var index = filterData.selectedCategories.indexOf($(this).data("paramId").toString());
                console.log("id to Remove: " + $(this).data("paramId"));
                filterData.selectedCategories.splice(index, 1);
                console.log("CatId's after Removal: " + filterData["selectedCategories"]);
                filterData.selectedColors = [];
                filterData.selectedCoatings = [];
                filterData.selectedAfmetingen = [];
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                         var afmetingenHtml = "";
                         if(currentData.noCategories == "true"){
                         $("#filterPanels").hide();
                         }
                         $.each(currentData.afmetingen, function(index, value){
                         if(value == "" || value == null) {
                         afmetingenHtml += '<div class="checkbox"> <label> <input type="checkbox" class="afmetingFilter" data-param-id="' + value + '" value=""> <span class="cr"></span>Geen afmeting</label> </div>';
                         }else{
                         afmetingenHtml += '<div class="checkbox"> <label> <input type="checkbox" class="afmetingFilter" data-param-id="' + value + '" value=""> <span class="cr"></span>' + value + ' </label> </div>';
                         }
                         });
                         $("#afmetingenFilters").html(afmetingenHtml);

                         var colorsHtml = "";
                         $.each(currentData.colors, function(index, value){
                         colorsHtml += '<div class="checkbox" data-param-id="' + value["id"] + '"> <label> <input type="checkbox" class="colorFilter" data-param-id="' + value["id"] + '" value=""> <span class="cr"></span>' + value["color_naam_" + lang] + '</label> </div>';
                         });
                         $("#colorFilters").html(colorsHtml);

                         var coatingsHtml = "";
                         $.each(currentData.coatings, function(index, value){
                         coatingsHtml += '<div class="checkbox" data-param-id="' + value["id"] + '"> <label> <input type="checkbox" class="coatingFilter" data-param-id="' + value["id"] + '" value=""> <span class="cr"></span>' + value["coatingnaam_" + lang] + '</label> </div>';
                         });
                         $("#coatingFilters").html(coatingsHtml);
                    }
                });
            }
        });
        $(document).on('click', '.afmetingFilter', function() {
            console.log(filterData);
            if($(this).is(':checked')){
                var clickedAfmeting = $(this).data("paramId");
                filterData["selectedAfmetingen"].push(clickedAfmeting);
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                    }
                });
            }else{
                console.log("unchecked");
                var index = filterData.selectedAfmetingen.indexOf($(this).data("paramId"));
                filterData.selectedAfmetingen.splice(index, 1);
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){

                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                    }
                });
            }
        });
        $(document).on('click', '.colorFilter', function() {
            console.log(filterData);
            if($(this).is(':checked')){
                var clickedColor = $(this).data("paramId");
                filterData["selectedColors"].push(clickedColor);
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                    }
                });
            }else{
                console.log("unchecked");
                var index = filterData.selectedColors.indexOf($(this).data("paramId"));
                filterData.selectedColors.splice(index, 1);
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                    }
                });
            }
        });
        $(document).on('click', '.coatingFilter', function() {
            console.log(filterData);
            if($(this).is(':checked')){
                var clickedCoating = $(this).data("paramId");
                filterData["selectedCoatings"].push(clickedCoating);
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                    }
                });
            }else{
                console.log("unchecked");
                var index = filterData.selectedCoatings.indexOf($(this).data("paramId"));
                filterData.selectedCoatings.splice(index, 1);
                $.ajax({
                    type: "GET",
                    url: "/filteredProductsPOST",
                    data: {"filterData": JSON.stringify(filterData)},
                    cache: false,
                    success: function(data){
                        var currentData = JSON.parse(data);
                        console.log(currentData);
                        $("#allproductscontainer").html(currentData.htmlout);
                    }
                });
            }
        });
    </script>

   {{-- <script>
        $(document).ready(function () {
            setHeight($('.productparent .productchild'));
        });

        $(window).on('resize', function () {
            setHeight($('.productparent .productchild'));
        });
    </script>--}}
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection
