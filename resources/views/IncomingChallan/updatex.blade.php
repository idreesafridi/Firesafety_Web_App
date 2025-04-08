@extends('layouts.app')

@section('content')


<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Update Incoming Challan</h1>
	</div>
</div>
<div id="wrapper">
	<div class="main-content">
		<form data-toggle="validator" action="{{ route('IncomingChallan.update', $Challan->id) }}" method="POST">
		@csrf()
		@method('PATCH')
		<div class="row small-spacing">
			<div class="col-12">
				@if ($errors->any())
	                <div class="alert alert-danger">
	                    <ul>
	                        @foreach ($errors->all() as $error)
	                            <li>{{ $error }}</li>
	                        @endforeach
	                    </ul>
	                </div>
	            @endif
				<div class="box-content">
					<h4 class="box-title">Generate Incomming Challan</h4>
					<h4 class="box-title">Product Details</h4>
					<div class="form-group">
						<div class="row">

							<div class="form-group col-md-6">
							    <label>Select Customer</label>
								<select class="form-control select2_1" name="customer_id">
									<option value="">Select Customer</option>
									@if($customers)
									@foreach($customers as $customer)
									<option value="{{ $customer->id }}" <?php echo ($Challan->customer_id == $customer->id) ? 'selected' : '' ?>>
									    {{ $customer->customer_name }} - {{ $customer->company_name }} - {{ $customer->city }}
									</option>
									@endforeach
									@endif
								</select>
								<div class="help-block with-errors"></div>
							</div> 

							<div class="form-group col-md-3">
							    <label>Customer Order No</label>
								<input type="text" class="form-control" id="customer_order_no" name="customer_order_no" value="{{ $Challan->customer_order_no }}" placeholder="Order No" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div> 
							<div class="form-group col-md-3">
							    <label>Customer Order Date</label>
								<input type="date" class="form-control" id="customer_order_date" name="customer_order_date" value="{{ $Challan->customer_order_date }}" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div> 

							<hr>

							<div class="col-md-12">
							 	<h4 class="box-title">Products</h4>
							</div>
							<?php 
							$descriptions = explode('@&%$# ', $Challan->descriptions);
							$qty 		  = explode('@&%$# ', $Challan->qty);
							$remarks 	  = explode('@&%$# ', $Challan->remarks);
							$unit 	  	  = explode('@&%$# ', $Challan->unit);
							$count = 0;
							?>
							<table class="table" id="productsTable">
								@foreach($descriptions as $description)
								<tr>
									<td>
									    @if($count==0)
									    <label>Product Description</label>
									    @endif
										<input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" value="{{ $description }}" style="height: 40px!important;">
									</td>
									<td>
									    @if($count==0)
									    <label>Product Quantity</label>
									    @endif
										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 40px!important;" value="{{ $qty[$count] }}">
									</td>
									<td>
									    @if($count==0)
									    <label>Remarks</label>
									    @endif
										<input type="text" class="form-control" id="" name="remarks[]" placeholder="Remarks" style="height: 40px!important;" value="{{ strip_tags($remarks[$count]) }}">
									</td>

									<td>
										@if($count==0)
										<label>UOM</label>
										@endif
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit"  value="{{ $unit[$count] }}" style="height: 40px!important;">
										<div class="help-block with-errors"></div>
									</td>

									<td>
										@if($count==0)
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff;margin-top:3em"><i class="fa fa-plus"></i></a>
										</div>
										@else
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light productBtnDelete" style="color: #fff"><i class="fa fa-minus"></i></a>
										</div>
										@endif
									</td>
								</tr>
								<?php $count++; ?>
								@endforeach
							</table>
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Update Challan</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>

		<footer class="footer">
			<ul class="list-inline">
				<li>©2020 Al Akhzir Tech.</li>
			</ul>
		</footer>
	</div>
</div>



<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
$( document ).ready(function() {

	$("#productsTable").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').remove();
	});

	var i=2;
	$(".addmoreProduct").on('click',function(){
	    var data="<tr>";
	    data +='<td><input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" style="height: 40px!important;"></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 40px!important;"></td>';
	    data +='<td><input type="text" class="form-control" id="remarks" name="remarks[]" placeholder="Remarks" style="height: 40px!important;"></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 40px!important;"></td>';
	    data +='<td><a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
	    data +='</tr>';
	    
	    $('table#productsTable').append(data);
	    i++;
	});

});

</script>
@endsection