@extends('admin.template.main')
@section('content')
	<!-- Default box -->
    @include('messages')
    <div class="box">
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
                                <a href="{{route('admin.showAllPriceLists.edit', $page->Id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Edit Price List</a>
                                <a href="{{route('admin.showAllPriceLists.edit', $page->Id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Add User</a>
                          	</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="col-md-12"><p class="alert alert-warning">No page to show</p></div>
            @endif
        </div><!-- /.box-body -->
        @include('admin.template.pagination', ['pages' => $pages])
    </div><!-- /.box -->
@endsection
