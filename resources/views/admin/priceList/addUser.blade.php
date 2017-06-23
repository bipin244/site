@extends('admin.template.main')
@section('content')
	<!-- Default box -->
	<?php $userAllId = [];?>
	@include('messages')
	<div class="box">
		<div class="box-header with-border">
		  <h3 class="box-title">Add a User</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
		    <form id="userForm" action="{{route('admin.showAllPriceLists.addUser')}}" role="form" class="form" method="post">
		    	<input type="hidden" name="_token" value="{{csrf_token()}}">
		    	<input type="hidden" name="priceId" value="{{$priceList->Id}}">
		    	<input type="hidden" name="selectedUserId[]" class="selectedUserId" value="0">
		        <!-- text input -->
		        
		        	<label for="name">Price List Name : {{$priceList->name}}</label>
		        
		        <table id="bootstrapDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
	                    <tr>
	                        <th>Select</th>
	                        <th>Bedrijfsnaam</th>
	                    </tr>
                    </thead>
                    <tbody>
                    	@foreach($data as $user)
			        		@if($user->bedrijfsnaam)
			        			<tr>
			                        <td>
			                        	<?php $array = explode(',', $user->priceListId);?> 
			                        	<label>
						          			<input id="isSelect_{{$user->id}}" name="isSelect[{{$user->id}}]" data-id="{{$user->id}}" type="checkbox" class="priceCheck" placeholder="isSelect" @if(in_array($priceList->Id , $array)) checked @endif/>
					          				@if(in_array($priceList->Id , $array))
					          				<?php
					          					array_push($userAllId,$user->id);
					          					
					          				?>
					          				@endif
						          		</label>
					          		</td>
			                        <td>
			                        	{{$user->bedrijfsnaam}}
		                        	</td>
			                    </tr>
			        		@endif
		 				@endforeach
                    </tbody>
                </table>
                <input type="hidden" name="beforeUser" id="beforeUser">
		        <a href="{{route('admin.showAllPriceLists.index')}}" class="btn btn-default">Back</a>
		        <button type="button" class="btn btn-sml btn-primary submitForm">Submit</button>
		    </form>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
	<script src="{{URL::asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('js/dataTables.bootstrap.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var table = $('#bootstrapDataTable').DataTable({
                "bLengthChange": true,
                "bPaginate": false,
                "bInfo" : false,
                "ordering": false,
            });
            var selectedUserId = [];
           	$('.priceCheck:checkbox:checked').each(function() {
                selectedUserId.push($(this).data('id'));
            });
            if(selectedUserId.length > 0){
            	$('.selectedUserId').val(selectedUserId);
            }
            $('.priceCheck').change(function(){
		    	var id = $(this).data('id');
		    	if($(this).prop("checked")){
		    		selectedUserId.push(id);
		    	}else{
		    		selectedUserId.splice(selectedUserId.indexOf(id),1);
		    	}
		    	$('.selectedUserId').val(selectedUserId);
		    });
		    $('.submitForm').click(function(){
				table
				 .search( '' )
				 .columns().search( '' )
				 .draw();
				var userAllId = '<?php echo implode(',', $userAllId); ?>';
				console.log("userAllId : ",userAllId);
				$("#beforeUser").val(userAllId);
				$("#PriorityCheckbox").val($("#ispriority").prop("checked"));
				$("#userForm").submit();
		    });
		});
	</script>
@endsection
