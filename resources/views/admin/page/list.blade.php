@extends('admin.template.main')
@section('content')
	<!-- Default box -->
    @include('messages')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Blog page</h3>
        </div><!-- /.box-header -->
        <div class="box-body no-padding">
            @if(!$pages->isEmpty())
                <table class="table">
                    <tr>
                      <th class="col-md-3">Name</th>
                    </tr>
                    @foreach($pages as $page)
                        <tr>
                          <td><a href="{{route('admin.page.edit', $page->id)}}">{{$page->title_nl}}</a></td>
                          <td>
                                <form action="{{route('admin.page.destroy', $page->id)}}" method="post">
                                	<input type="hidden" name="_method" value="delete" />
                                	<input type="hidden" name="_token" value="{{csrf_token()}}" />
                                    <a href="{{route('admin.page.edit', $page->id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Text</a>
                                    <a href="{{route('admin.page.edit', $page->id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Afbeeldingen</a>
                                </form>
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
