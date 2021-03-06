@extends('admin.template.main')
@section('content')
	<!-- Default box -->
	@include('messages')
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Coatings</h3>
			<hr/>
			<p style="margin-top:10px;"><a id="newCoatingButton" class="btn btn-primary">Nieuwe coating</a></p>
		</div><!-- /.box-header -->
		<div class="box-body no-padding">
			@if(!$coatings->isEmpty())
				<table class="table">
					<tr>
						<th class="col-md-2">Naam NL</th>
						<th class="col-md-2">Naam FR</th>
						<th class="col-md-2">Naam DE</th>
						<th class="col-md-2">Naam EN</th>
					</tr>
					@foreach($coatings as $coating)
						<tr>
							<td class="naamnl formInput"><input type="text" class="form-control" value="{{$coating->coatingnaam_nl}}" disabled></td>
							<td class="naamfr formInput"><input type="text" class="form-control" value="{{$coating->coatingnaam_fr}}" disabled></td>
							<td class="naamde formInput"><input type="text" class="form-control" value="{{$coating->coatingnaam_de}}" disabled></td>
							<td class="naamen formInput"><input type="text" class="form-control" value="{{$coating->coatingnaam_en}}" disabled></td>
							<td>
								<input type="hidden" name="_method" value="delete" />
								<a class="btn btn-sml btn-default edit" value="{{$coating->id}}"><i class="fa fa-pencil"></i> Quick edit</a>
								<a class="btn btn-sml btn-danger delete" id="button" data-coating-id="{{$coating->id}}"><i class="fa fa-times"></i> Delete</a>
								<a class="btn btn-sml btn-success update" id="button{{$coating->id}}" data-param-id="{{$coating->id}}" style="display:none;"><i class="fa fa-pencil"></i> Save</a>
							</td>
						</tr>
					@endforeach
				</table>
			@else
				<div class="col-md-12"><p class="alert alert-warning">No post to show</p></div>
			@endif
		</div><!-- /.box-body -->
		@include('admin.template.pagination', ['pages' => $coatings])
	<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Modal Header</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control coatingInputNL" value="" placeholder="Naam (Nederlands)">
							</div>
						</div>
						<div class="row spacersmallest"></div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control coatingInputFR" value="" placeholder="Naam (Frans)">
							</div>
						</div>
						<div class="row spacersmallest"></div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control coatingInputDE" value="" placeholder="Naam (Duits)">
							</div>
						</div>
						<div class="row spacersmallest"></div>
						<div class="row">
							<div class="col-md-12">
								<input type="text" class="form-control coatingInputEN" value="" placeholder="Naam (Engels)">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-md-2 col-md-push-6">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
							<div class="col-md-3 col-md-push-6">
								<a id="saveColorButton" class="btn btn-sml btn-success">Coating toevoegen</a>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div><!-- /.box -->
	<script>


		$(document).on('click', '.update', function() {
				var url = "/updateCoating";
				var coatingId = $(this).data("paramId");
				console.log(coatingId);
				var naamNL = $(this).parent().parent().find('.naamnl').children().first().val();
				var naamFR = $(this).parent().parent().find('.naamfr').children().first().val();
				var naamDE = $(this).parent().parent().find('.naamde').children().first().val();
				var naamEN = $(this).parent().parent().find('.naamen').children().first().val();

				$.ajax({
					type: "GET",
					url: url,
					data: {"coatingId": coatingId,"naamNL": naamNL,"naamFR": naamFR,"naamDE": naamDE,"naamEN": naamEN},
					cache: false,
					success: function(data){
						window.location.href = "/admin/editCoating";
					}
				});
				return false;
			});

		$('#newCoatingButton').click(function(){
			$('#myModal').modal("show");
		});

		$('#saveColorButton').click(function(){
			var coatingnamenl = $('.coatingInputNL').val();
			var coatingnamefr = $('.coatingInputFR').val();
			var coatingnamede = $('.coatingInputDE').val();
			var coatingnameen = $('.coatingInputEN').val();
			var url = "/newCoating";
			$.ajax({
				type: "GET",
				url: url,
				data: {"coatingnamenl": coatingnamenl, "coatingnamefr": coatingnamefr, "coatingnamede": coatingnamede, "coatingnameen": coatingnameen},
				cache: false,
				success: function(data){
					window.location.href = "/admin/editCoating";
				}
			});
		});



		$(document).on('click', '.edit', function() {
			console.log("kleir");
			$(this).parent().parent().children(".formInput").each(function(){
				$(this).children().first().prop("disabled",false);
				console.log("beir");
			});
			$(this).next().next().css("display", "inline");
		});

		$(document).on('click', '.delete', function() {

			var url = "/checkDeleteCoating";
			var coatingId = $(this).data("coatingId");
			if(coatingId == "0"){
				toastr["error"]("Kan de coating niet verwijderen, want deze moet verplicht aanwezig zijn in het systeem.")

				toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": false,
					"progressBar": false,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "600",
					"hideDuration": "1000",
					"timeOut": "5000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
			}
			$.ajax({
				type: "GET",
				url: url,
				data: {"coatingId": coatingId},
				cache: false,
				success: function(data){
					if(data == "true"){
						window.location.href = "/admin/editCoating";
					}else{
						toastr["error"]("Kan de coating niet verwijderen, want deze hangt nog vast aan producten! Verwijder eerst de producten indien u de coating echt wilt verwijderen.")

						toastr.options = {
							"closeButton": false,
							"debug": false,
							"newestOnTop": false,
							"progressBar": false,
							"positionClass": "toast-top-right",
							"preventDuplicates": false,
							"onclick": null,
							"showDuration": "600",
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
		});

	</script>
	<script type="text/javascript" src="{{URL::asset('js/toastr.js')}}"></script>
	<link href="{{URL::asset('css/toastr.css')}}" rel="stylesheet">

@endsection
