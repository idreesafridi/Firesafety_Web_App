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
					<h4 class="box-title">Update Incomming Challan</h4>
					<h4 class="box-title">Customer Details</h4>
					<div class="form-group">
						<div class="row">

							<?php 
						    $customer = App\Models\Customer::find($Challan->customer_id);
						    if(isset($customer)):
    						    if($customer->type == 'regular'):
    						        $c_company  = $customer->company_name;
    						        $c_city     = $customer->city;
    						        $c_address  = $customer->address;
    						        $c_id       = $customer->id;
    						        $c_name       = $customer->customer_name;
    						    else:
    						        $c_company  = '';
    						        $c_city     = '';
    						        $c_address  = '';
    						        $c_id       = '';
    						        $c_name       = '';
    						    endif;
						    else:
						        $c_company  = '';
						        $c_city     = '';
						        $c_address  = '';
						        $c_id       = '';
						        $c_name       = '';
						    endif;
						    ?>
						    <!--Company Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_company_name" id="customer_company_name">
									<option value="">Select Customer</option>
									@if($customers)
									@foreach($customers as $cust)
									<option value="{{ $cust->company_name }}" <?php echo ($c_company == $cust->company_name) ? 'selected' : '';?>>
									    {{ $cust->company_name }}
									</option>
									@endforeach
									@endif
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<!--City-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_city" id="customer_city">
								    @if(isset($c_city))
								    <option value="{{ $c_city }}">{{ $c_city }}</option>
								    @endif
								</select>
							</div>
							<!--Address-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_address" id="customer_address">
								    @if(isset($c_address))
								    <option value="{{ $c_address }}">{{ $c_address }}</option>
								    @endif
								</select>
							</div>
							<!--Customer Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_id" id="customer_id">
								    @if(isset($c_id))
								    <option value="{{ $c_id }}">{{ $c_name }}</option>
								    @endif
								</select>
								<div class="help-block with-errors"></div>
							</div>
							
                            <hr>

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
							$sequence = 1;
							?>
							<table class="table" id="productsTable">
							    <tr>
							        <td><label>Product Description</label></td>
							        <td><label>Product Quantity</label></td>
							        <td><label>Remarks</label></td>
							        <td><label>UOM</label></td>
							    </tr>
							@if(isset($description))
								@foreach($descriptions as $description)
								<tr>
							    	<td style="width:10%;border: none;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" value="{{ $description }}" style="height: 30px!important;">
									</td>
									<td>
										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;" value="{{ $qty[$count] }}">
									</td>
									<td>
										<input type="text" class="form-control" id="" name="remarks[]" placeholder="Remarks" style="height: 30px!important;" value="{{ strip_tags($remarks[$count]) }}">
									</td>

									<td>
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit"  value="{{ $unit[$count] }}" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>

									<td>
									    <a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff"><i class="fa fa-plus"></i></a>
										&nbsp;
										<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
									</td>
								</tr>
								<?php $count++; $sequence++; ?>
								@endforeach
							@else
								<tr>
									<td style="width:10%;border: none;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
									</td>
								    <td>
										<input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" style="height: 30px!important;">
									</td>
									<td>
										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="" name="remarks[]" placeholder="Remarks" style="height: 30px!important;">
									</td>

									<td>
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
								    <td>
								    	<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff"><i class="fa fa-plus"></i></a>
										&nbsp;
										<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
								    </td>
								</tr>
							@endif
							</table>
							
							
							
							<div class="col-md-12">
							 	<h4 class="box-title">Walkin Customer</h4>
							</div>
						 	<input type="hidden" name="walkCustomer" value="{{ ($customer) ? $customer->id : '' }}">
						 	<?php
						 	if(isset($customer)):
						    if($customer->type == 'walkin'):
						        $customer_name  = $customer->customer_name;
						        $phone_no       = $customer->phone_no;
						        $email          = $customer->email;
						        $address        = $customer->address;
						        $city           = $customer->city;
						        $company_name   = $customer->company_name;
						    else:
						        $customer_name  = '';
						        $phone_no       = '';
						        $email          = '';
						        $address        = '';
						        $city           = '';
						        $company_name   = '';
						    endif;
						    else:
						        $customer_name  = '';
						        $phone_no       = '';
						        $email          = '';
						        $address        = '';
						        $city           = '';
						        $company_name   = '';
						    endif;
						 	?>
						 	
							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company_name }}" placeholder="Company Name">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-4">
								<input type="number" class="form-control" id="phone_no" name="phone_no" value="{{ $phone_no }}" placeholder="Phone No">
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
								<input type="email" class="form-control" id="email" name="email" value="{{ $email }}" placeholder="Email Address">
								<div class="help-block with-errors"></div>
							</div>

						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="address" name="address" value="{{ $address }}" placeholder="Address">
								<div class="help-block with-errors"></div>
						 	</div>

							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="city" name="city" value="{{ $city }}" placeholder="City">
								<div class="help-block with-errors"></div>
							</div>
							
						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $customer_name }}" placeholder="Customer Name">
								<div class="help-block with-errors"></div>
							</div>

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
	
	$("#productsTableNew").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').remove();
	});

	var i=2;

	$(".addmoreProduct").on('click',function(){
	    var data="<tr>";

	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" placeholder="Sequence" value="'+i+'" style="height: 30px!important;">';
		data +='</td>';
		
	    data +='<td><input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" style="height: 30px!important;"></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"></td>';
	    data +='<td><input type="text" class="form-control" id="remarks" name="remarks[]" placeholder="Remarks" style="height: 30px!important;"></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>';
	    
	    data +='<td>';
	    data += '<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data += '<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';							
	    data += '</td>';

	    data +='</tr>';
	    
	    //$('table#productsTable').prepend(data);
	    $(this).parent().parent().parent().after(data);
	    i++;
	});

	$("#productsTable").on('click',  '.addmoreProduct2', function(){
	    var data="<tr>";

	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" placeholder="Sequence" value="'+i+'" style="height: 30px!important;">';
		data +='</td>';
		
	    data +='<td><input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" style="height: 30px!important;"></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"></td>';
	    data +='<td><input type="text" class="form-control" id="remarks" name="remarks[]" placeholder="Remarks" style="height: 30px!important;"></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>';
	    
	    
	    data +='<td>';
	    data += '<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data += '<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';							
	    data += '</td>';

	    data +='</tr>';
	    
	    //$('table#productsTable').prepend(data);
	    $(this).parent().parent().after(data);
	    i++;
	});
});
</script>
@endsection