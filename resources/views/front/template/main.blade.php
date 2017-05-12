<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Hermic BVBA</title>

    <!-- Bootstrap -->
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('css/stijl.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{{URL::asset('js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
    {{--<script src="{{URL::asset('js/setHeight.js')}}"></script>--}}

</head>
<body>
<div class="header_top_bar">
    <div class="row">
        <div class="col-md-2 col-md-push-2">
            <div class="dropdown">
                <div id="langicons">
                    <ul>
                        @if($mainConfigValues["langnl"] == "1")
                            <li><a href="/{{$page->slug}}/nl"><img src="{{URL::asset('image/nl.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable nllang"><img src="{{URL::asset('image/nl.svg')}}" class="flag"></a></li>
                        @endif

                        @if($mainConfigValues["langfr"] == "1")
                            <li><a href="/{{$page->slug}}/fr"><img src="{{URL::asset('image/fr.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable frlang"><img src="{{URL::asset('image/fr.svg')}}" class="flag"></a></li>
                        @endif

                        @if($mainConfigValues["langde"] == "1")
                            <li><a href="/{{$page->slug}}/de"><img src="{{URL::asset('image/de.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable delang"><img src="{{URL::asset('image/de.svg')}}" class="flag"></a></li>
                        @endif

                        @if($mainConfigValues["langen"] == "1")
                            <li><a href="/{{$page->slug}}/en"><img src="{{URL::asset('image/en.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable enlang"><img src="{{URL::asset('image/en.svg')}}" class="flag"></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-md-push-5">
            <ul id="topbarul">
                <li>
                    <i class="fa fa-phone headericonmargin"></i>+32 (0)14 65 91 39
                </li>
                <li>
                    <i class="fa fa-clock-o headericonmargin"></i>Ma - Vr 8.00 - 17.00
                </li>
                <?php if(Session::has('loggedin')){
                    $user = Session::get('loggedin',null);
                if($user != null){?>
                        <li class="dropdown open">
                            {{--<a href="{{ url('/user/orderHistoryList')}}" class="dropdown-toggle" data-toggle="dropdown">--}}
                            <div class="yellowcolor">
                                <a href="{{ url('/user/orderHistoryList')}}">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <strong>{{$user->bedrijfsnaam}}</strong>
                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                </a>
                            </div>
                            <ul class="dropdown-menu accountmenu">
                                <li><a href="#">Account Settings <span class="glyphicon glyphicon-cog pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="#">User stats <span class="glyphicon glyphicon-stats pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="#">Messages <span class="badge pull-right"> 42 </span></a></li>
                                <li class="divider"></li>
                                <li><a href="#">Favourites Snippets <span class="glyphicon glyphicon-heart pull-right"></span></a></li>
                                <li class="divider"></li>
                                <li><a href="#">Sign Out <span class="glyphicon glyphicon-log-out pull-right"></span></a></li>
                            </ul>
                        </li>
                <?php }else{ ?>
                <li>
                    <a href="#" class="langdropdown loginLoginDialog"><i class="fa fa-user headericonmargin"></i> Login</a><span class="vertical_divider"></span><a href="#" class="langdropdown registerLoginDialog">Registreer</a>
                </li>
                <?php }
                }else{ ?>
                <li>
                    <a href="#" class="langdropdown loginLoginDialog"><i class="fa fa-user headericonmargin"></i> Login</a><span class="vertical_divider"></span><a href="#" class="langdropdown registerLoginDialog">Registreer</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div id="custom-bootstrap-menu" class="navbar navbar-default " role="navigation">
    <div class="row" style="height:100px;">
        <div class="col-md-1 col-md-push-1">
            <div class="navbar-header"><a class="navbar-brand" href="{{ url('/index')}}"><img id="logobrand" src="{{URL::asset('image/LogoBig.png')}}" width="60" height="75"/> </a>
                <li id="langiconsmobile">
                    <ul>
                        @if($mainConfigValues["langnl"] == "1")
                            <li><a href="/{{$page->slug}}/nl"><img src="{{URL::asset('image/nl.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable nllang"><img src="{{URL::asset('image/nl.svg')}}" class="flag"></a></li>
                        @endif

                        @if($mainConfigValues["langfr"] == "1")
                            <li><a href="/{{$page->slug}}/fr"><img src="{{URL::asset('image/fr.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable frlang"><img src="{{URL::asset('image/fr.svg')}}" class="flag"></a></li>
                        @endif

                        @if($mainConfigValues["langde"] == "1")
                            <li><a href="/{{$page->slug}}/de"><img src="{{URL::asset('image/de.svg')}}" class="flag"></a></li>
                        @else
                                <li><a href="#" class="langnotavailable delang"><img src="{{URL::asset('image/de.svg')}}" class="flag"></a></li>
                        @endif

                        @if($mainConfigValues["langen"] == "1")
                        <li><a href="/{{$page->slug}}/en"><img src="{{URL::asset('image/en.svg')}}" class="flag"></a></li>
                        @else
                            <li><a href="#" class="langnotavailable enlang"><img src="{{URL::asset('image/en.svg')}}" class="flag"></a></li>
                        @endif
                    </ul>
                </li>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
                </button>
            </div>
        </div>
        <div class="col-md-10 col-md-push-2">
        <div class="collapse navbar-collapse navbar-menubuilder">
                <div class="row">
                    <div class="col-md-7 col-md-push-1">
                     <ul class="nav navbar-nav navbar-left">
                         <li>
                             <div class="menutitle salebutton">
                                 <a class="menutitle" href="{{ url('/promotionPage') }}">Promo's</a>
                             </div>
                         </li>
                         <li>
                             <div id="imaginary_container">
                                 <div class="input-group stylish-input-group input_container">
                                     <input type="text" id="searchproducts" class="form-control searchboxheader"  placeholder="Zoek een product" >
                                     <ul id="product_list_id" style="margin-top:55px; display:none;">

                                     </ul>
                                     <span class="input-group-addon">
                                <button type="submit">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                                 </div>
                             </div>
                         </li>
                        <li><a class="menutitle" href="{{ url('/contact') }}">Contact</a>
                            <div class="magicline"></div>
                        </li>
                     </ul>
                    </div>
                    <div class="col-md-1 col-md-offset-2">
                        <div class="container_cart">
                            <a href="{{ url('/cart/open')}}" id="cart"><i class="fa fa-shopping-cart"></i>  <span class="badge" id="cartAmountItems">{{Session::get('cartAmount', '0')}}</span></a>
                        </div>
                    </div>

                {{--<li id="langicons">
                    <ul>
                        <li><a href="/{{$page->slug}}/nl"><img src="{{URL::asset('image/nl.svg')}}" class="flag"></a></li>
                        <li><a href="/{{$page->slug}}/fr"><img src="{{URL::asset('image/fr.svg')}}" class="flag"></a></li>
                        <li><a href="/{{$page->slug}}/de"><img src="{{URL::asset('image/de.svg')}}" class="flag"></a></li>
                        <li><a href="/{{$page->slug}}/en"><img src="{{URL::asset('image/en.svg')}}" class="flag"></a></li>
                    </ul>
                </li>--}}
                </div>
            </div>
            </div>
        </div>
</div>
<div id="modalRegisterLogin" class="modal fade" role="dialog">
    <div class="row spacersmall"></div>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-login">
                <div class="panel-heading">
                    <div class="row spacersmallest" style="background-color: #FFCC00;"></div>
                    <div class="row" style="background-color: #FFCC00;">
                        <div class="col-md-1 col-md-offset-11">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="#" class="active" id="login-form-link">Inloggen</a>
                        </div>
                        <div class="col-xs-6">
                            <a href="#" id="register-form-link">Registreer</a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="login-form" action="/user/loginUser" method="post" role="form" style="display: block;">
                                <input type="hidden" name="url" value="{{Request::url()}}">
                                <div class="form-group">
                                    <input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="E-mail" value="">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Wachtwoord">
                                </div>
                                <div class="form-group text-center">
                                    <input type="checkbox" tabindex="3" class="" name="remember" id="remember">
                                    <label for="remember"> Onthoud mij</label>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="text-center">
                                                <a href="http://phpoll.com/recover" tabindex="5" class="forgot-password">Wachtwoord vergeten?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form id="register-form" action="/user/registerUser" method="post" role="form" style="display: none;">
                                <input type="hidden" name="url" value="{{Request::url()}}">
                                <div class="form-group">
                                    <input type="text" name="email" id="emailRegister" tabindex="1" class="form-control" placeholder="E-mail" value="" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="bedrijfsnaam" id="bedrijfsnaam" tabindex="1" class="form-control" placeholder="Bedrijfsnaam" value="" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="btwnr" id="btwnr" tabindex="2" class="form-control" placeholder="BTW Nr." required>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="gsm" id="gsm" tabindex="2" class="form-control" placeholder="Telefoon / GSM" required>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-3">
                                            <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Registreer nu">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    $(document).ready(function() {

        $('#login-form-link').click(function(e) {
            $("#login-form").delay(100).fadeIn(100);
            $("#register-form").fadeOut(100);
            $('#register-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });

        $('#register-form-link').click(function(e) {
            $("#register-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $('#login-form-link').removeClass('active');
            $(this).addClass('active');
            e.preventDefault();
        });

    });

    $(document).on('click','.loginLoginDialog',function(){
        $("#modalRegisterLogin").modal("show");
        $("#login-form").delay(100).fadeIn(100);
        $("#register-form").fadeOut(100);
        $('#register-form-link').removeClass('active');
        $('#login-form-link').addClass('active');
        e.preventDefault();
    });

    $(document).on('click','.registerLoginDialog',function(){
        $("#modalRegisterLogin").modal("show");
        $("#register-form").delay(100).fadeIn(100);
        $("#login-form").fadeOut(100);
        $('#login-form-link').removeClass('active');
        $('#register-form-link').addClass('active');
        e.preventDefault();
    });

    $(".menutitle").hover(function(){
        $(this).next().addClass("line_visible");
    });
    $(".menutitle").mouseout(function(){
        $(this).next().removeClass("line_visible");
    });

    $("#searchproducts").on("keyup", function() {
        var min_length = 2;
        console.log("test");
        var keyword = $(this).val();
        if (keyword.length >= min_length) {
            $.ajax({
                type: "GET",
                url: "/search",
                data: {"keyword": keyword},
                cache: false,
                success:function(data){
                    $('#product_list_id').css("display","block");
                    $('#product_list_id').html(data);
                }
            });
        } else {
            $('#product_list_id').css("display","none");
        }
    });

    $(document).click(function(e) {
        if( e.target.id != 'product_list_id') {
            $("#product_list_id").css("display","none");
        }
    });

    function userAlreadyExist(){
        Command: toastr["error"]("Er bestaat al een account met deze e-mail. Gebruik een andere e-mail of druk op 'Wachtwoord vergeten'.", "Registratie mislukt!")

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "500",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

    function userRegistrationSuccess(){
        Command: toastr["success"]("Je account moet eerst manueel geactiveerd worden door ons personeel. Je ontvangt een mail van zodra dit gebeurd is.", "Registratie gelukt!")

        toastr.options = {
            "closeButton": false,
            "debug": true,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "800",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

    $(document).ready(function(){
        var className = window.location.hash;
        if(className == "#userAlreadyExist"){
            userAlreadyExist();
        }else if(className == "#registrationComplete"){
            userRegistrationSuccess();
        }else{
            className = className.substring(1);
            console.log("className : ",className)
            if(className != ""){
                $("." + className).trigger("click");
            }
        }
    });

    $(document).on('click','.langnotavailable',function(e){
        e.preventDefault();
        if($(this).hasClass("nllang")){
            Command: toastr["warning"]("", "Nederlandse taal is nog niet beschikbaar.")

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
        }else if($(this).hasClass("frlang")){
            Command: toastr["warning"]("", "Français n'est pas encore disponible.")

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
        }else if($(this).hasClass("delang")){
            Command: toastr["warning"]("", "Deutsch ist noch nicht verfügbar.")

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
        }else{
            Command: toastr["warning"]("", "English is not yet available.")

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
        }
    });

</script>


    <!-- Page Content -->
<div class="container-full">
    @yield('content')
</div>

        <!-- /.row -->


        <div class="row spacerbig"></div>
<div class="footer_widgets_wrapper footer-distributed">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
            <aside id="text-2" class="widget widget_text col-md-3 col-md-offset-1"><div class="widget_title"><h3>Andere footertext</h3></div>
                <div class="textwidget">
                    <p>Footer text onder voorbehoud.
                    </p>
                </div>
            </aside><aside id="stm_pages-2" class="widget widget_pages col-md-2"><div class="widget_title"><h3>Links</h3></div>			<ul class="style_links">
                    <li class="page_item page-item-1217"><a href="http://masterstudy.stylemixthemes.com/alerts-and-stuff/"><span class="h6">Home</span></a></li>
                    <li class="page_item page-item-1376"><a href="http://masterstudy.stylemixthemes.com/custom-shortcodes/"><span class="h6">Over Ons</span></a></li>
                    <li class="page_item page-item-779"><a href="http://masterstudy.stylemixthemes.com/pricing-plans/"><span class="h6">Producten</span></a></li>
                    <li class="page_item page-item-961"><a href="http://masterstudy.stylemixthemes.com/toggle-elements/"><span class="h6">Verdeler van Hermic</span></a></li>
                    <li class="page_item page-item-1245"><a href="http://masterstudy.stylemixthemes.com/typography/"><span class="h6">Contact</span></a></li>
                </ul>
            </aside><aside id="contacts-2" class="widget widget_contacts col-md-3"><div class="widget_title"><h3>Waarom Hermic?</h3></div>
                    <div class="textwidget"><p>Meer info over ons verder aan te vullen.</p>
                        <p>Al heel wat jaren ervaring in deze markt. Info zelf verder aan te vullen of veranderen.<br>
                           </p>
                    </div></aside><aside id="working_hours-4" class="widget widget_working_hours col-md-3"><div class="widget_title"><h3>Contact</h3></div>
                    <table class="table_working_hours">
                        <tbody class="contact_items_footer">
                        <div class="row">
                            <div class="col-md-1">
                                    <div class="icon"><i class="fa-icon-stm_icon_pin"></i></div>
                            </div>
                            <div class="col-md-10">
                                    <div class="text">Hoge Mauw 709, 2370 Arendonk, België</div>
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="icon"><i class="fa-icon-stm_icon_phone"></i></div>
                            </div>
                            <div class="col-md-10">
                                <div class="text">+32 (0)14 65 91 39</div>
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="icon"><i class="fa-icon-stm_icon_fax"></i></div>
                            </div>
                            <div class="col-md-10">
                                <div class="text">+32 (0)14 65 91 39</div>
                            </div>
                        </div>
                        <div class="row spacersmallest"></div>
                        <div class="row">
                            <div class="col-md-1">
                                <div class="icon"><i class="fa fa-envelope"></i></div>
                            </div>
                            <div class="col-md-10">
                                <div class="text"><a href="mailto:eric.bax@hermic.be">eric.bax@hermic.be</a>
                                </div>
                            </div>
                            </li>
                        </div>
                        </tbody>
                    </table>
            </aside>
            </div>
        </div>
    </div>
</div>
<script>
//$(function () { objectFitImages() });
</script>
</body>

</html>
