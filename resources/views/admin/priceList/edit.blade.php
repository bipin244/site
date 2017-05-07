@extends('admin.template.main')
@section('content')
	<!-- Default box -->
	@include('messages')
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Blog posts</h3>
			<li id="langiconsadmin">
				<ul>
					<li><a href="/edit/nl"><img src="{{URL::asset('image/nl.svg')}}" class="flag"></a></li>
					<li><a href="/edit/fr"><img src="{{URL::asset('image/fr.svg')}}" class="flag"></a></li>
					<li><a href="/edit/de"><img src="{{URL::asset('image/de.svg')}}" class="flag"></a></li>
					<li><a href="/edit/en"><img src="{{URL::asset('image/en.svg')}}" class="flag"></a></li>
				</ul>
			</li>
		</div><!-- /.box-header -->
		<div class="box-body no-padding">
			<a href="/admin/pages/newTextKey/{{$pageId}}" class="btn btn-primary">Nieuw keyword</a>
			@if(!$posts->isEmpty())
				<table class="table">
					<tr>
						<th class="col-md-1">#</th>
						<th class="col-md-2">Test</th>
						<th class="col-md-3">Naam</th>
						<th class="col-md-3">Inhoud</th>
						<th class="col-md-3">Aanpassen</th>
					</tr>
					@foreach($posts as $post)
						<tr>
							<td>{{$post->id}}</td>
							<td>{{URL::to('/')}}</td>
							<td><a href="{{route('admin.post.edit', $post->id)}}">{{$post->key}}</a></td>
							<td><input type="text" class="form-control" id="{{$post->id}}" value="{!!nl2br(str_limit($post->content, 100))!!}" disabled></td>
							<td>
								<form action="{{route('admin.post.destroy', $post->id)}}" method="post">
									<input type="hidden" name="_method" value="delete" />
									<input type="hidden" name="_token" value="{{csrf_token()}}" />
									<a href="javascript:enableChange({{$post->id}})" class="btn btn-sml btn-default"><i class="fa fa-pencil"></i> Quick edit</a>
									<a href="{{route('admin.post.edit', $post->id)}}" class="btn btn-sml btn-primary"><i class="fa fa-pencil"></i> Full edit</a>
									<a href="javascript:update({{$post->id}})" class="btn btn-sml btn-success" id="button{{$post->id}}" style="display:none;"><i class="fa fa-pencil"></i> Save</a>
								</form>
							</td>
						</tr>
					@endforeach
				</table>
			@else
				<div class="col-md-12"><p class="alert alert-warning">No post to show</p></div>
			@endif
		</div><!-- /.box-body -->
		@include('admin.template.pagination', ['pages' => $posts])
	</div><!-- /.box -->
	<script>


		function update(id){
				var url = "/updateText";
				var text = $("#" + id).val();

				$.ajax({
					type: "GET",
					url: url,
					data: {"pageTextId": id,"pageText": text},
					cache: false,
					success: function(data){
						console.log(data);
						$("#button" + id).css("display", "none");
						$('#' + id).prop('disabled', true);
					}
				});
				return false;
			};

		function enableChange(id){
			if($('#' + id).prop('disabled')){
				$('#' + id).prop('disabled', false);
				$("#button" + id).css("display", "inline");
			}else{
				$('#' + id).prop('disabled', true);
				$("#button" + id).css("display", "none");
			}
		}

	</script>
@endsection
