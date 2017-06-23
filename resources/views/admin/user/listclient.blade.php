@extends('admin.template.main')
@section('content')
    <!-- Default box -->
    @include('messages')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Blog page</h3>
        </div><!-- /.box-header -->
        <div class="box-body no-padding">
            @if(!$visitors->isEmpty())
                <table class="table">
                    <tr>
                        <th class="col-md-2">#KlantNr</th>
                        <th class="col-md-2">Naam</th>
                        <th class="col-md-4">E-mail</th>
                        <th class="col-md-4">Actions</th>
                    </tr>
                    @foreach($visitors as $visitor)
                        <tr>
                            <td><input type="text" class="klantNr" data-visitor-id="{{$visitor->id}}" value="{{$visitor->klantNr}}" disabled></td>
                            <td>{{$visitor->bedrijfsnaam}}</td>
                            <td>{{$visitor->email}}</td>
                            <td>
                                <form action="/admin/clientDelete/{{$visitor->id}}" method="post">
                                    <a href="#" class="btn btn-sml btn-primary editKlantNr"><i class="fa fa-pencil"></i> Edit</a>
                                    <a href="#" class="btn btn-sml btn-primary passReset"><i class="fa fa-refresh"></i> Wachtwoord resetten</a>
                                    <button type="submit" class="btn btn-sml btn-danger" onClick="return confirm('ALLE GEGEVENS VAN DEZE GEBRUIKER GAAN VERLOREN, zeker dat je deze gebruiker wil verwijderen?')"><i class="fa fa-timex"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="col-md-12"><p class="alert alert-warning">No page to show</p></div>
            @endif

            <script>
                $(document).on('click','.editKlantNr',function(){
                    $(this).parent().parent().parent().find('.klantNr').prop('disabled', false);
                    $(this).html("Save");
                    $(this).removeClass("editKlantNr");
                    $(this).addClass("saveKlantNr");
                });

                $(document).on('click','.passReset',function(){
                    var visitorId = $(this).parent().parent().parent().find('.klantNr').data("visitorId");
                    $.ajax({
                        url : "/admin/passReset",
                        type: "GET",
                        data : {"visitorId": visitorId },
                        success: function(data, textStatus, jqXHR)
                        {
                            console.log("Pass reset");
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            console.log("Pass failed to reset");
                        }
                    });
                });

                $(document).on('click','.saveKlantNr',function(){
                    $buttonClicked = $(this);
                    var value = $(this).parent().parent().parent().find('.klantNr').val();
                    var visitorId = $(this).parent().parent().parent().find('.klantNr').data("visitorId");
                    console.log("Value = " + value + " AND visitorId = " + visitorId);
                    $.ajax({
                        url : "/admin/saveKlantNr",
                        type: "GET",
                        data : {"klantNr": value, "visitorId": visitorId },
                        success: function(data, textStatus, jqXHR)
                        {
                            $buttonClicked.html('<i class="fa fa-pencil"></i> Edit');
                            $buttonClicked.removeClass("saveKlantNr");
                            $buttonClicked.addClass("editKlantNr");
                            $buttonClicked.parent().parent().parent().find('.klantNr').prop('disabled', true);
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            $(this).html('<i class="fa fa-pencil"></i> Edit');
                            $(this).removeClass("saveKlantNr");
                            $(this).addClass("editKlantNr");
                            console.log("Error in saving klantNr");
                        }
                    });
                });
            </script>
        </div><!-- /.box-body -->
        @include('admin.template.pagination', ['pages' => $visitors])
    </div><!-- /.box -->
@endsection
