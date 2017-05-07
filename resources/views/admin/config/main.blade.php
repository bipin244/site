@extends('admin.template.main')
@section('content')
    <!-- Default box -->
    @include('messages')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Kleuren</h3>
            <hr/>
        </div><!-- /.box-header -->
        <div class="box-body no-padding">
            <div class="row">
                <div class="col-md-4 marginleft">
                    <label>Admin e-mail (deze mail ontvangt info over contact formulier, registratie, orders, ...)</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 marginleftandbottomadmin">
                    <input id="adminemail" name="adminemail" type="text" class="form-control" placeholder="ProductNr" value="{{$adminmail}}" />
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary updateadmin">Update</button>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-4 marginleftmore">
                        <label>Nederlandse taal</label>
                    </div>
                </div>
                <div class="col-md-4 marginleftandbottomadmin">
                    <select name="langnl" class="form-control">
                        @if($values["langnl"] == 1)
                        <option value="1" selected>Aan</option>
                        <option value="0">Uit</option>
                        @else
                            <option value="1">Aan</option>
                            <option value="0" selected>Uit</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary updatelang updatelangnl">Update</button>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-4 marginleftmore">
                        <label>Franse taal</label>
                    </div>
                </div>
                <div class="col-md-4 marginleftandbottomadmin">
                    <select name="langfr" class="form-control">
                        @if($values["langfr"] == 1)
                            <option value="1" selected>Aan</option>
                            <option value="0">Uit</option>
                        @else
                            <option value="1">Aan</option>
                            <option value="0" selected>Uit</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary updatelang updatelangfr">Update</button>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-4 marginleftmore">
                        <label>Duitse taal</label>
                    </div>
                </div>
                <div class="col-md-4 marginleftandbottomadmin">
                    <select name="langde" class="form-control">
                        @if($values["langde"] == 1)
                            <option value="1" selected>Aan</option>
                            <option value="0">Uit</option>
                        @else
                            <option value="1">Aan</option>
                            <option value="0" selected>Uit</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary updatelang updatelangde">Update</button>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="col-md-4 marginleftmore">
                        <label>Engelse taal</label>
                    </div>
                </div>
                <div class="col-md-4 marginleftandbottomadmin">
                    <select name="langde" class="form-control">
                        @if($values["langen"] == 1)
                            <option value="1" selected>Aan</option>
                            <option value="0">Uit</option>
                        @else
                            <option value="1">Aan</option>
                            <option value="0" selected>Uit</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary updatelang updatelangen">Update</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click','.updateadmin',function(){
            var adminemail = $('#adminemail').val();

            $.ajax({
                type: "GET",
                url: "/admin/configUpdate",
                data: {"adminemail": adminemail},
                cache: false,
                success: function(data){
                    Command: toastr["success"]("", "Configuratie bijgewerkt!")

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
        });

        $(document).on('click','.updatelang',function(){
            var lang;
            if($(this).hasClass("updatelangnl")){
                lang = "langnl";

            }else if($(this).hasClass("updatelangfr")){
                lang = "langfr"
            }else if($(this).hasClass("updatelangde")){
                lang = "langde"
            }else{
                lang = "langen"
            }

            var value = $(this).parent().parent().find('select').val();

                $.ajax({
                type: "GET",
                url: "/admin/langupdate",
                data: {"lang": lang,"value": value},
                cache: false,
                success: function(data){
                    Command: toastr["success"]("", "Configuratie bijgewerkt!")

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
        });
    </script>
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection