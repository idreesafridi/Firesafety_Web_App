@extends('layouts.app')

@section('content')


<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Add Delivery Challan</h1>
	</div>
</div>
<div id="wrapper">
	<div class="main-content">
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
				<h4 class="box-title">Generate Delivery Challan</h4>
					<form data-toggle="validator" action="{{ route('DeliveryChallanStore') }}"  enctype="multipart/form-data" method="POST">
					@csrf()
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-4">
								    <label>Select Quote</label>
									<select class="form-control select2_1" name="quote_id" id="quote_id" required>
										<option value="">Select Quote</option>
										@if($Quotes)
										@foreach($Quotes as $Quote)
										<option value="{{ $Quote->id }}">Quote No: {{$Quote->id}}</option>
										@endforeach
										@endif
									</select>
									<div class="help-block with-errors"></div>
								</div>
								<div class="col-sm-3">
									<div class="input-group">
										<label style="visibility: hidden;">Select Quote</label>
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Generate Challan</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>	

		<form data-toggle="validator" action="{{ route('DeliveryChallan.store') }}" method="POST">
		@csrf()
		<div class="row small-spacing">
			<div class="col-12">

				<div class="box-content">
					<h4 class="box-title">Generate Delivery Challan</h4>
					<h4 class="box-title">Customer Details</h4>
					<div class="form-group">
					    <div class="row">
					        <!--Company Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_company_name" id="customer_company_name">
									<option value="">Select Customer</option>
									@if($customers)
									@foreach($customers as $customer)
									<option value="{{ $customer->company_name }}" <?php echo (old('company_name') == $customer->company_name) ? 'selected' : '' ?>>
									    {{ $customer->company_name }}
									</option>
									@endforeach
									@endif
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<!--City-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_city" id="customer_city">
								</select>
							</div>
							<!--Address-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_address" id="customer_address">
								</select>
							</div>
							<!--Customer Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_id" id="customer_id">
								</select>
								<div class="help-block with-errors"></div>
							</div>
					    </div>
					    
						<div class="row">
							<div class="form-group col-md-3">
								<label>Customer Order No</label>
								<input type="text" class="form-control" id="customer_order_no" name="customer_order_no" placeholder="Customer Order No" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div> 
							<div class="form-group col-md-3">
								<label>Customer Order Date</label>
								<input type="date" class="form-control" id="customer_order_date" name="customer_order_date" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div> 

							<hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Products</h4>
							</div>
							
							<?php
							$product_id         = old('product_id');
                            $qty                = old('qty');
                            $productCapacity    = old('productCapacity');
                            $price              = old('price');
                            $count=0;
                            $sequence       	= 1;
                            ?>

							<table class="table" id="customerTable">
							    @if(isset($product_id[0]))
    							    @foreach($product_id as $productid)
    								    <tr>
    								    	<td style="width:5%;border: none;">
        										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
        									</td>
        									<td style="width:25%" class="productTD">
        										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
        											<option value="">Select Product</option>
        											@if($products)
        											@foreach($products as $product)
        											<option value="{{ $product->id }}" <?php echo ($productid == $product->id) ? 'selected' : ''; ?>>{{ $product->name }}</option>
        											@endforeach
        											@endif
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
        									<td style="width:20%">
        										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" value="{{ $qty[$count] }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<?php
        									$productData = App\Models\Product::find($productid);
        									$capacities  = explode(', ', $productData->capacity);
        									?>
        									<td style="width:20%" class="productCapacityTD">
        										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
        										@foreach($capacities as $capacity) 
        										    <option value="{{ $capacity }}" <?php echo ($capacity == $productCapacity[$count]) ? 'selected' : ''; ?>>{{ $capacity }}</option>
        										@endforeach
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
                                            <td style="width:20%">
        										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" value="{{ $price[$count] }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									
        									<td style="width:20%">
        										<div class="input-group">
        											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
        											&nbsp;
        											<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
        										</div>
        									</td>
        								</tr>
        							<?php $sequence++; ?>
    								@endforeach
    							@else
    							    <tr>
    							    	<td style="width:5%;">
    										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
    									</td>

    									<td style="width:25%" class="productTD">
    										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 30px!important;width:100%">
    											<option value="">Select Product</option>
    											@if($products)
    											@foreach($products as $product)
    											<option value="{{ $product->id }}">{{ $product->name }}</option>
    											@endforeach
    											@endif
    										</select>
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%">
    										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%" class="productCapacityTD">
    										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
    										</select>
    										<div class="help-block with-errors"></div>
    									</td>
                                        <td style="width:20%">
    										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									
    									<td style="width:20%">
    										<div class="input-group">
    											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
    											&nbsp;
    											<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
    										</div>
    									</td>
    								</tr>
								@endif
							</table>
					
							<hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Other Products</h4>
							</div>
							<?php $sequence_2 = 1; ?>
							<table class="table" id="productsTable">
								<tr>
									<td style="width:5%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence_2 }}" style="height: 30px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="descriptions" name="descriptions[]" placeholder="Product Description" style="height: 30px!important;">
									</td>
									<td>
										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="remarks" name="remarks[]" placeholder="Remarks" style="height: 30px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff"><i class="fa fa-plus"></i></a>
											&nbsp;
											<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										</div>
									</td>
								</tr>
							</table>


							<div class="col-md-12">
							 	<h4 class="box-title">Walking Customer Details</h4>
							</div>
						 
							<div class="form-group col-md-4">
						 	    <label>Company Name</label>
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-4">
						 	    <label>Phone No</label>
								<input type="number" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no') }}" placeholder="Phone No"> 
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
						 	    <label>Email</label>
								<input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address">
								<div class="help-block with-errors"></div>
							</div>

						 	<div class="form-group col-md-4">
						 	    <label>Address</label>
								<input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Address">
								<div class="help-block with-errors"></div>
						 	</div>

							<div class="form-group col-md-4">
						 	    <label>City</label>
								<input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="City">
								<div class="help-block with-errors"></div>
							</div>
							
						 	<div class="form-group col-md-4">
						 	    <label>Name</label>
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Customer Name">
								<div class="help-block with-errors"></div>
							</div>

						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Generate Challan</button>
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
	$("#customerTable").on('click', '.btnDelete', function () {
	    $(this).closest('tr').remove();
	});

	var i=2;

	// this is for default plus button
	$(".addmore").on('click',function(){
	    var data="<tr>";

	    data +='<td style="width:5%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" placeholder="Sequence" value="'+i+'" style="height: 30px!important;">';
		data +='</td>';
	 
	    data +='<td class="productTD"><select class="form-control select2_1 productName product_id"  id="product_id_'+i+'" required name="product_id[]" style="height: 40px!important;width:100%">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"  required></td>';

	    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 30px!important;"></select></td>';

        data +='<td><input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;"></td>';

	    data +='<td style="width: 20%;">';
	    data +='<a class="addmore2 btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data += '&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';

	    data +='</tr>';
	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().parent().after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
	    i++;
	});

	// this is for run time added plus button
	$("#customerTable").on('click', '.addmore2', function(){
	    var data="<tr>";

	    data +='<td style="width:5%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" placeholder="Sequence" value="'+i+'" style="height: 30px!important;">';
		data +='</td>';
	 
	    data +='<td class="productTD"><select class="form-control select2_1 productName product_id"  id="product_id_'+i+'" required name="product_id[]" style="height: 40px!important;width:100%">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"  required></td>';

	    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 30px!important;"></select></td>';

        data +='<td><input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;"></td>';

	    data +='<td style="width: 20%;">';
	    data +='<a class="addmore2 btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data += '&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';

	    data +='</tr>';
	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
	    i++;
	});
});

$( document ).ready(function() {

	$("#productsTable").on('click', '.productBtnDelete', function () {
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