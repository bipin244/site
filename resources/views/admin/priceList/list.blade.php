@extends('admin.template.main')
@section('content')
	<!-- Default box -->
    @include('messages')
    <div class="box">
        <!-- <ul class="list-unstyled">
            <li class="alert alert-success">HELLO<button type="button" class="close" data-dismiss="alert">&times;</button></li>
        </ul> -->
        <div class="box-header">
            <h3 class="box-title">Price List</h3>
        </div><!-- /.box-header -->
        <div class="box-body no-padding">
            @if(!$pages->isEmpty())
                <table class="table">
                    <tr>
                      <th class="col-md-3">Name</th>
                    </tr>
                    @foreach($pages as $page)
                        <tr>
                          <td><a href="{{route('admin.showAllPriceLists.edit', $page->Id)}}">{{$page->name}}</a></td>
                          <td>
                                <a href="{{route('admin.showAllPriceLists.edit', $page->Id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Prijslijst bewerken</a>
                                <a href="{{route('admin.showAllPriceLists.user', $page->Id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Gekoppelde gebruikers bewerken</a>
                          	</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="col-md-12"><p class="alert alert-warning">Nog geen prijslijsten aangemaakt ga naar het menu links om er één aan te maken.</p></div>
            @endif
        </div><!-- /.box-body -->
        @include('admin.template.pagination', ['pages' => $pages])
    </div><!-- /.box -->
@endsection
