@extends('admin.template.main')
@section('content')
	<!-- Default box -->
	@include('messages')
	<div class="box">
		<div class="box-header with-border">
		  <h3 class="box-title">Add a price List</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
		    <form action="{{route('admin.showAllPriceLists.store')}}" role="form" class="form" method="post">
		    	<input type="hidden" name="_token" value="{{csrf_token()}}">
		        <!-- text input -->
		        <div class="row form-group">
		        	<div class="col-md-10">
	          			<label for="name">Name<span class="text text-danger">*</span></label>
	          			<input id="name" name="name" type="text" class="form-control" placeholder="Price list name" value="{{old('name')}}" />
          			</div>
	          		<div class="col-md-2" style="left: 50px;top: 25px;">
			        	<label class="checkbox">
		          			<input id="ispriority" name="ispriority" type="checkbox" placeholder="ispriority" value="{{old('ispriority')}}" />Priority
		          		</label>
			        </div>
		        </div>
		        <center>Product</center><hr>
		        <table id="bootstrapDataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
	                    <tr>
	                        <th>Select</th>
	                        <th>ProductNr</th>
	                        <th>Price</th>
	                    </tr>
                    </thead>
                    <tbody>
                    	@foreach($productNr as $product)
			        		@if($product->productNr)
			        			<tr>
			                        <td>
			                        	<label>
						          			<input id="isSelect_{{$product->Id}}" name="isSelect[]" data-id="{{$product->Id}}" type="checkbox" class="priceCheck" placeholder="isSelect" />
						          		</label>
					          		</td>
			                        <td>
			                        	{{$product->productNr}}
		                        	</td>
			                        <td>
			                        	<input id="price_{{$product->Id}}" name="price[{{$product->productNr}}]" type="number" class="form-control txtboxToFilter" min="0" placeholder="Enter Price" value="0"  disabled="disabled"/>
			                        </td>
			                    </tr>
			        		@endif
		 				@endforeach
                    </tbody>
                </table>
		        <a href="{{route('admin.showAllPriceLists.index')}}" class="btn btn-default">Back</a>
		        <button type="submit" class="btn btn-sml btn-primary">Submit</button>
		    </form>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
	<script src="{{URL::asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('js/dataTables.bootstrap.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#bootstrapDataTable').DataTable({
                "bLengthChange": true,
                "bPaginate": false,
                "bInfo" : false,
                "ordering": false,
            });
            $(".txtboxToFilter").keydown(function (e) {
		        // Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl+A, Command+A
		            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
		             // Allow: home, end, left, right, down, up
		            (e.keyCode >= 35 && e.keyCode <= 40)) {
		                 // let it happen, don't do anything
		                 return;
		        }
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
		    });
		    $('.priceCheck').change(function(){
		    	var id = $(this).data('id');
		    	if($(this).prop("checked")){
		    		$("#price_"+id).prop("disabled", false);
		    	}else{
		    		$("#price_"+id).val('0');
		    		$("#price_"+id).prop("disabled", true);
		    	}
		    })
		});
	</script>
@endsection
