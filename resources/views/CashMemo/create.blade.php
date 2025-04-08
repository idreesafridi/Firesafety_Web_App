@extends('layouts.app')

@section('content')


<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Add Cash Memo</h1>
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
				<h4 class="box-title">Convert Cash Memo from Quote</h4>
					<form data-toggle="validator" action="{{ route('CashMemoStore') }}"  enctype="multipart/form-data" method="POST">
					@csrf()
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-4">
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
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Add Cash Memo</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>	

		 <form data-toggle="validator" action="{{ route('CashMemo.store') }}" method="POST">
		@csrf()
		<div class="row small-spacing">
			<div class="col-12">

				<div class="box-content">
					<h4 class="box-title">Generate Cash Memo</h4>
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
        					<div class="col-md-3">
								<label>Id</label>
        					    <input type="number" class="form-control" id="" name="id" placeholder="Cashmemo No" value="{{ (old('cashmemo')) ? old('cashmemo') : $cashmemo+1 }}" style="height: 30px!important;" required>
        					</div>
							<div class="form-group col-md-3">
								<label>Branch</label>
								<select class="form-control select2_1" name="branch" id="branch" required>
									<option value="">Select Branch</option>
									@if($Branches)
										@foreach($Branches as $Branch)
											<option value="{{ $Branch->branch_name }}" {{ (old('branch') == $Branch->branch_name || (isset($user->branch) && $user->branch == $Branch->branch_name)) ? 'selected' : '' }}>
												{{ $Branch->branch_name }}
											</option>
										@endforeach
									@endif
								</select>
								
								@error('branch')
									<div class="text-danger">{{ $message }}</div>
								@enderror
								<div class="help-block with-errors"></div>
							</div>
							<div class="col-md-3">
        					    <input type="text" class="form-control" id="customer_po_no" name="customer_po_no" placeholder="Customer Po No" value="{{ old('customer_po_no') }}" style="height: 30px!important;">
        					</div>
							<div class="col-md-3">
								<input type="number" class="form-control" id="delievery_challan_no" name="delievery_challan_no" value="{{ old('delievery_challan_no') }}" placeholder="Delievery Challan No" style="height: 30px!important;">
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
							$heading 			= old('heading');
                            $description 		= old('description');
                            $sequence       	= 1;
                            $count=0;
                            $countSequence = 1;
                            ?>

							<table class="table" id="customerTable">
							    @if(isset($product_id[0]))
    							    @foreach($product_id as $productid)
									<tr>
										<th colspan="6"><input type="text" name="heading[]" class="form-control" value="{{ $heading[$count] }}"></th>
									</tr>
    								    <tr> 
    								    	<td style="width:5%;border: none;">
        										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence[$count] }}" style="height: 30px!important;">
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
										<tr  data-trid="{{ $sequence }}">
											<th colspan="6">
        								<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>
										<div class="txtdescription_wrapper">
											<textarea id="tmce_{{ $sequence }}" placeholder="Description" name="description[]" class="form-control description3 txtdescription" style="height: 200px;">{{ $description[$count] }}</textarea>
										</div>
        								
        								</th>
        								</tr>
    								@endforeach
    							@else
								<tr>
									<th colspan="6"><input type="text" name="heading[]" class="form-control" placeholder="Heading"></th>
								</tr>
    							    <tr>
								    	<td style="width:5%;">
    										<input type="number" class="form-control" id="sequence" name="sequence[]" placeholder="Sequence" value="{{ $countSequence }}" style="height: 30px!important;">
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
									<tr  data-trid="1">
        								<th colspan="6">
        								<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>
										<div class="txtdescription_wrapper">
											<textarea id="tmce_1" placeholder="Description" name="description[]" class="form-control description3 txtdescription" style="height: 200px;"></textarea>
										</div>
        								
        								</th>
        								
    								</tr>
								@endif
							</table>
							
							<div class="row" style="padding-left: 1rem;padding-right: 1rem;margin-top:1rem; margin-bottom:2rem;">
    							<div class="form-group col-md-4">
    								<input type="number" class="form-control" id="discount_fixed" name="discount_fixed" value="{{ old('discount_fixed') }}" placeholder="Discount Fixed"  style="height: 30px!important;">
    							</div>
    
    							 
    							<div class="form-group col-md-4">
    								<input type="number" step="0.01" class="form-control" id="transportaion" name="transportaion" placeholder="Transportaion" style="height: 30px!important;">
    							</div>
    							
    							<div class="form-group col-md-4">
    								<input type="date" class="form-control" id="dated" name="dated" value="{{ date('Y-m-d') }}" style="height: 30px!important;">
    							</div>
    						</div>

							<hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Other Products</h4>
							</div>

							<?php
							$productName    = old('productName');
                            $productQty     = old('productQty');
                            $productPric    = old('productPric');
                            $unit           = old('unit');
                            $sequence       = old('sequence');
                            
                            $count1=0;
                            $count=$countSequence;
                            ?>
                            
							<table class="table" id="productsTable">
							    @if(isset($productName[0]))
    							    @foreach($productName as $productD)
    								    <tr>
    								        <td>
        										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
        									</td>
        									<td> 
        										<input type="text" class="form-control" id="productName" name="productName[]" value="{{ $productD }}" placeholder="Product Name" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="number" class="form-control" id="productqty" name="productQty[]" value="{{ $productQty[$count1] }}" placeholder="Quantity" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="number" step="0.01" class="form-control" id="productPric" value="{{ $productPric[$count1] }}" name="productPric[]" placeholder="Price" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="text" class="form-control" id="unit" name="unit[]" value="{{ $unit[$count1] }}" placeholder="Unit" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        
        									<td>
        										<input type="file" class="form-control" id="other_products_image" name="other_products_image[]" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        
        									<td>
        										<div class="input-group">
        											<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>
													&nbsp;
													<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
        										</div>
        									</td>
        								</tr>
    								    <?php $count1++; $count++; ?>
    								@endforeach
    							@else
    							<tr>
						            <td>
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $count }}" style="height: 30px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="productName" name="productName[]" placeholder="Product Name" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
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
								@endif
							</table>


							<div class="col-md-12">
							 	<h4 class="box-title">Walking Customer Details</h4>
							</div>
						 
							
							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name">
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
								<input type="number" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no') }}" placeholder="Phone No">
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
								<input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email Address">
								<div class="help-block with-errors"></div>
							</div>

						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Address">
								<div class="help-block with-errors"></div>
						 	</div>

							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" placeholder="City">
								<div class="help-block with-errors"></div>
							</div>
							
						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Customer Name">
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Generate Cash Memo</button>
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

<style>
.txtdescription_wrapper {
	display:none;
}	
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>

$( document ).ready(function() {

	tinyMCE.init({
        selector: '.txtdescription'
      });


	$("#customerTable").on('click', '.btnDelete', function () {
	    $(this).closest('tr').remove();
	});

	var i=2;


	// this is for default plus button
	$(".addmore").on('click',function(){
		var data='<tr><th colspan="7"><input type="text" name="heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";

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
		data +='<tr data-trid="'+i+'">'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>';
		data += '<div class="txtdescription_wrapper">';
		data +='<textarea id="tmce_'+i+'" name="description[]" class="form-control description txtdescription" placeholder="Description" style="height: 50px"></textarea>';
		data += '</div>';
		data +='</th>';
		data +='</tr>';


	    //$('table#customerTable').prepend(data);
		$(this).parent().parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
		tinyMCE.init({
        	selector: '.txtdescription'
      	});
	    i++;
	});

	// this is for run time added plus button
	$("#customerTable").on('click', '.addmore2', function(){
		var data='<tr><th colspan="7"><input type="text" name="heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";

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
		data +='<tr data-trid="'+i+'">'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>';
		data += '<div class="txtdescription_wrapper">';
		data +='<textarea id="tmce_'+i+'" name="description[]" class="form-control description txtdescription" placeholder="Description" style="height: 50px"></textarea>';
		data += '</div>';
		data +='</th>';
		data +='</tr>';

	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
		tinyMCE.init({
        	selector: '.txtdescription'
      	});
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
$(document).ready(function() {
        $('form[data-toggle="validator"]').submit(function(event) {
            var branchValue = $('#branch').val();
            
            if (!branchValue) {
                alert('Please select a branch before generating the cash memo.');
                event.preventDefault(); // Prevent the form submission
            }
        });
    });

</script>
@endsection