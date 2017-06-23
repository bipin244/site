@extends('front.template.main')
@section('content')
	<div class="row rowheaderyellow">
        <div class="col-md-12 text-center">
            <h1 class="headerpage">CONTACT</h1>
        </div>
    </div>
    <div class="row spacersmall"></div>
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            {!! Breadcrumbs::render('contact') !!}
        </div>
    </div>
    <div class="row spacerbig"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="contacth3">CONTACTEER ONS</h3>
                    </div>
                </div>
                <div class="row border-top-row">
                    <div class="col-md-12">
                        <div class="border-top-col">
                            <div class="iconcontact">
                                <i style="font-size: 35px; color:#FFCC00; margin-right:20px;" class="fa fa-icon-stm_icon_pin-o iconfloat"></i>
                                <div class="contactsubitembox">
                                    <h5 class="contactsubitemtitle">Adres:</h5>
                                    <p class="contactsubitemtext">Hoge Mauw 700, Arendonk, België</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row border-top-row">
                    <div class="col-md-6">
                        <div class="border-top-col">
                            <div class="iconcontact">
                                <i style="font-size: 35px; color:#FFCC00; margin-right:20px;" class="fa fa-icon-stm_icon_phone-o iconfloat"></i>
                                <div class="contactsubitembox">
                                    <h5 class="contactsubitemtitle">Telefoon:</h5>
                                    <p class="contactsubitemtext">+32 (0)472 73 06 33</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-top-col">
                            <div class="iconcontact">
                                <img style="margin-right:20px;" src="{{URL::asset("fonts/facebook.svg")}}" alt="Kiwi standing on oval" class="iconfloat">
                                <div class="contactsubitembox">
                                    <h5 class="contactsubitemtitle">Facebook:</h5>
                                    <p class="contactsubitemtext">www.facebook.com/hermicBVBA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row border-top-row">
                    <div class="col-md-6">
                        <div class="border-top-col">
                            <div class="iconcontact">
                                <i style="font-size: 22px; color:#FFCC00; margin-right:20px; margin-top:8px;" class="fa fa-icon-stm_icon_mail-o iconfloat"></i>
                                <div class="contactsubitembox">
                                    <h5 class="contactsubitemtitle">Email:</h5>
                                    <p class="contactsubitemtext">eric.bax@hermic.be</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-top-col">
                            <div class="iconcontact">
                                <i style="font-size: 33px; color:#FFCC00; margin-right:20px; margin-top:4px;" class="fa fa-icon-stm_icon_earth iconfloat"></i>
                                <div class="contactsubitembox">
                                    <h5 class="contactsubitemtitle">Website:</h5>
                                    <p class="contactsubitemtext">www.hermic.be</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="contacth3">ONZE LOCATIE</h3>
                    </div>
                </div>
                <div class="row border-top-row">
                    <div class="col-md-12">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1763.8504804706577!2d5.106589083151244!3d51.302637392768105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6ca0d8abe8dd1%3A0x7561d892a07bc3de!2sHermic+BVBA!5e0!3m2!1snl!2sbe!4v1484390942182" width="100%" height="266" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="row spacersmall">
            <div class="col-md-12">
                <div class="border-top-col"></div>
            </div>
        </div>
        <div class="row spacersmall"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="berichth3">Stuur uw bericht</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="contact-us" action="/contact/send" method="post">
                            <!-- Left Inputs -->
                            <div class="col-xs-6 wow animated slideInLeft" data-wow-delay=".5s">
                                <!-- Name -->
                                <input type="text" name="name" id="name" required="required" class="form" placeholder="Naam" />
                                <!-- Email -->
                                <input type="email" name="email" id="email" required="required" class="form" placeholder="Email" />
                                <!-- Subject -->
                                <input type="text" name="subject" id="subject" required="required" class="form" placeholder="Onderwerp" />
                            </div><!-- End Left Inputs -->
                            <!-- Right Inputs -->
                            <div class="col-xs-6 wow animated slideInRight" data-wow-delay=".5s">
                                <!-- Message -->
                                <textarea name="tekst" id="tekst" class="form textarea"  placeholder="Bericht"></textarea>
                            </div><!-- End Right Inputs -->
                            <!-- Bottom Submit -->
                            <div class="relative fullwidth col-xs-12">
                                <!-- Send Button -->
                                <input type="submit" id="submit" name="submit" value="Verzenden" class="form-btn semibold"/>
                            </div><!-- End Bottom Submit -->
                            <!-- Clear -->
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
