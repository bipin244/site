@extends('admin.template.main')
@section('content')
    <link href="{{URL::asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
	<!-- Default box -->
    @include('messages')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Producten overzicht</h3>
        </div><!-- /.box-header -->
        <div class="box-body no-padding">
                <table id="bootstrapDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Artikel Nr</th>
                        <th>Naam</th>
                        <th>Kleur</th>
                        <th>Afmeting</th>
                        <th>Coating</th>
                        <th>Bewerk</th>
                        <th>Bewerk gemeenschappelijke info</th>
                        <th>Sub Product toevoegen</th>
                        <th>Nieuw product</th>
                        <th>In promotie</th>
                        <th>Uniek</th>
                        <th>Aangemaakt</th>
                        <th>Aangepast</th>
                        <th>X</th>
                    </tr>
                    </thead>
                    <tbody id="bodyItems">
                        {!! $html !!}
                    </tbody>
                </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <script src="{{URL::asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('js/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#bootstrapDataTable').DataTable({
                "bLengthChange": false,
                "columns": [
                    {"width" : "5%"},
                    {"width" : "13%"},
                    {"width" : "8%"},
                    {"width" : "10%"},
                    {"width" : "8%"},
                    {"width" : "5%"},
                    {"width" : "10%"},
                    {"width" : "8%"},
                    {"width" : "5%"},
                    {"width" : "5%"},
                    {"width" : "6%"},
                    {"width" : "6%"},
                    {"width" : "6%"},
                    {"width" : "3%"}
                ]
            });
        });

        $(document).on('change', '.nieuwproduct', function() {
            if($(this).is(":checked")){
                var productNr = $(this).data('productNr');

                $.ajax({
                    type: "GET",
                    url: "/setProductToNew",
                    data: {"productNr": productNr},
                    cache: false,
                    success: function(data){
                        if(data == "max"){
                            $(this).prop("checked", false);
                            toastr["error"]("Er zijn al 9 nieuwe producten, gelieven eerst andere nieuwe producten te verwijderen.");
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
                            toastr["success"]("Product toegevoegd aan nieuwe producten!")

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
                    }
                });
            }else{
                var productNr = $(this).data('productNr');

                $.ajax({
                    type: "GET",
                    url: "/setProductOfNew",
                    data: {"productNr": productNr},
                    cache: false,
                    success: function(data){
                        console.log("succes");
                    }
                });
            }

        });

        $(document).on('change', '.sale', function() {
            if($(this).is(":checked")){
                var productNr = $(this).data('productNr');

                $.ajax({
                    type: "GET",
                    url: "/setProductToSale",
                    data: {"productNr": productNr},
                    cache: false,
                    success: function(data){
                        if(data == "max"){
                            $(this).prop("checked", false);
                            toastr["error"]("Er zijn al 9 nieuwe producten, gelieven eerst andere nieuwe producten te verwijderen.");
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
                            toastr["success"]("Product toegevoegd aan nieuwe producten!")

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
                    }
                });
            }else{
                var productNr = $(this).data('productNr');

                $.ajax({
                    type: "GET",
                    url: "/setProductOfSale",
                    data: {"productNr": productNr},
                    cache: false,
                    success: function(data){
                        console.log("succes");
                    }
                });
            }

        });
    </script>
    <script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
    <link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">
@endsection
