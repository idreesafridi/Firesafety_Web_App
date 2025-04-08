@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Add Quote</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('Quotes.store') }}" method="POST" enctype="multipart/form-data">
@csrf()
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
					<h4 class="box-title">Generate Quote</h4>
					<h4 class="box-title">Select Customer</h4>
					<div class="form-group">
						<div class="row">
						    <!--Company Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_company_name" id="customer_company_name">
									<option value="">Select Company</option>
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
							<div class="row">							
            					<div class="col-md-1">
            					    <input type="hidden" class="form-control" id="customer_ntn_no" name="customer_ntn_no" placeholder="Customer NTN No." value="{{ old('customer_ntn_no') }}" style="height: 30px!important;">
            					</div>
            					<div class="col-md-8">
            					    <input type="number" class="form-control" id="" name="id" placeholder="Quote No" value="{{ (old('qoutes')) ? old('qoutes') : $qoutes+1 }}" style="height: 30px!important;" required>
            					</div>
							</div>
							
							<?php
							$product_id         = old('product_id');
                            $qty                = old('qty');
                            $productCapacity    = old('productCapacity');
                            $price              = old('price');
                            $sequence 			= old('sequence');
                            $heading 			= old('heading');
                            $description 		= old('description');
                            $count=0;
                            $sequence = 1;
                            ?>
                            
                            <hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Products</h4>
							</div>
							
							<table class="table" id="customerTable">
							    @if(isset($product_id[0]))
    							    @foreach($product_id as $productid)
    							    	<?php $sequence += $sequence[$count]; ?>
    							    	<tr>
    							    		<th colspan="6"><input type="text" name="heading[]" class="form-control" value="{{ $heading[$count] }}"></th>
    							    	</tr>
    								    <tr>
    								        <td style="width:10%;border: none;">
        										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence[$count] }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
    								        
        									<td style="width:20%!important;border: none;" class="productTD">
        										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;">
        											<option value="">Select Product</option>
        											@if($products)
        											@foreach($products as $product)
        											<option value="{{ $product->id }}" <?php echo ($productid == $product->id) ? 'selected' : ''; ?>>{{ $product->name }}</option>
        											@endforeach
        											@endif
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
        									<td style="width:20%;border: none;">
        										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" value="{{ $qty[$count] }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<?php
        									$productData = App\Models\Product::find($productid);
        									$capacities  = explode(', ', $productData->capacity);
        									?>
        									<td style="width:20%;border: none;" class="productCapacityTD">
        										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
        										@foreach($capacities as $capacity) 
        										    <option value="{{ $capacity }}" <?php echo ($capacity == $productCapacity[$count]) ? 'selected' : ''; ?>>{{ $capacity }}</option>
        										@endforeach
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
                                            <td style="width:20%;border: none;">
        										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" value="{{ $price[$count] }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									
        									<td style="width:10%;border: none;">
        										<div class="input-group">
        											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
        											&nbsp;
        											<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
        										</div>
        									</td>
        								</tr>
        								<tr>
        								<th colspan="6">
        								<a class="btn btn-xs btn-primary view_hide" style="color: #fff;">View/Hide Description</a>
        								<textarea placeholder="Description" name="description[]" class="form-control description" style="height: 50px;display: none;">{{ $description[$count] }}</textarea>
        								</th>
        								</tr>
    								@endforeach
    							@else
    							<tr>
							    	<th colspan="6">
							    		<input type="text" name="heading[]" placeholder="Heading" class="form-control" placeholder="Description">
							    	</th>
						    	</tr>
    							<tr>
							        <td style="width:10%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;" class="productTD">
										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
											<option value="">Select Product</option>
											@if($products)
											@foreach($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
											@endforeach
											@endif
										</select>
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;">
										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;" class="productCapacityTD">
										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
										</select>
										<div class="help-block with-errors"></div>
									</td>
                                    <td style="width:20%;">
										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									
									<td style="width:10%;">
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
											&nbsp;
        									<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										</div>
									</td>
								</tr>
								<tr>
									<th colspan="6">
        								<a class="btn btn-xs btn-primary view_hide" style="color: #fff;">View/Hide Description</a>
										<textarea name="description[]" class="form-control description" placeholder="Description" style="height: 50px;display: none;"></textarea>
									</th>
								</tr>
								@endif
							</table>

							<hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Other Products</h4>
							</div>
                            
                            <?php
							$productName    = old('productName');
                            $productQty     = old('productQty');
                            $productPric    = old('productPric');
                            $unit           = old('unit');
                            $size           = old('size');
                            $heading 			= old('heading');
                            $count1=0;
                            ?>
                            
							<table class="table" id="productsTable">
							    @if(isset($productName[0]))
    							    @foreach($productName as $productD)
    							    	<tr>
    							    		<th colspan="7"><input type="text" name="other_products_heading[]" class="form-control" value="{{ $heading[$count1] }}"></th>
    							    	</tr>
    								    <tr>
    								    	<td style="width:10%;">
												<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
												<div class="help-block with-errors"></div>
											</td>
        									<td> 
        										<input type="text" class="form-control" id="productName" name="productName[]" value="{{ $productD }}" placeholder="Product Name" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="number" class="form-control" id="productqty" name="productQty[]" value="{{ $productQty[$count1] }}" placeholder="Quantity" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="text" class="form-control" id="size" name="size[]" value="{{ $size[$count1] }}" placeholder="Size" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="number" step="0.01" class="form-control" id="productPric" value="{{ $productPric[$count1] }}" name="productPric[]" placeholder="Price" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="text" class="form-control" id="unit" name="unit[]" value="{{ $unit[$count1] }}" placeholder="Unit" style="height: 40px!important;">
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
    								    <?php $count1++ ?>
    								@endforeach
    							@else
    							<tr>
						    		<th colspan="7"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th>
						    	</tr>
    							<tr>
							    	<td style="width:10%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="text" class="form-control" id="productName" name="productName[]" placeholder="Product Name" style="height: 40px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 40px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 40px!important;">
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
								<input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ old('phone_no') }}" placeholder="Phone No">
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
							
							
							
						  	<div class="form-group col-md-12">
								<input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Subject">
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-1" style="text-align: right;padding: 0;">
								<input type="checkbox" id="GST" name="GST" <?php echo (old('GST') == 'on') ? 'checked' : 'checked' ?>>
							</div>
							
							<div class="form-group col-md-2" style="text-align: left;padding: 10;px">
								<input type="text" id="gst_text" name="gst_text" class="form-control" value="GST">
							</div>
								
							<div class="form-group col-md-2">
								<input type="number" step="0.01" class="form-control" id="tax_rate" name="tax_rate" value="{{ (old('tax_rate')) ? old('tax_rate') : 18 }}" placeholder="Tax Rate"  style="height: 30px!important;">
							</div>
							
							<div class="form-group col-md-1" style="text-align: right;padding: 0;">
								<input type="checkbox" id="WHT" name="WHT" value="on" <?php echo (old('WHT') == 'on') ? 'checked' : '' ?>> WH.Tax
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-2" style="text-align: left;padding: 10;px">
								<input type="number" step="0.01" class="form-control" id="wh_tax" name="wh_tax" value="{{ (old('wh_tax')) ? old('wh_tax') : 4 }}" required placeholder="W.H Tax"  style="height: 30px!important;">
							</div>
							
							<div class="form-group col-md-2">
								<input type="number" class="form-control" id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}" placeholder="Discount (%)"  style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-2">
								<input type="number" step="0.01" class="form-control" id="discount_fixed" name="discount_fixed" value="{{ old('discount_fixed') }}" placeholder="Discount Fixed"  style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-3">
								<input type="number" step="0.01" class="form-control" id="transportaion" name="transportaion" placeholder="Transportaion" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-2">
								<input type="date" class="form-control" id="dated" name="dated" value="{{ date('Y-m-d') }}" style="height: 30px!important;">
							</div>

							<div class="form-group col-md-12 mt-5">
								<label>Terms & Conditions</label>
								@if($TermsAndConditions->count() > 0)
									<select name="terms" id="terms" class="form-control">
										<option value="">select an option</option>
										@foreach($TermsAndConditions as $TermsAndCondition)
										<option value="{{ $TermsAndCondition->termsAndConditions }}">{{ $TermsAndCondition->name }}</option>
										@endforeach
									</select>
								@endif
							</div>

						  	<div class="form-group col-md-12">
								<textarea class="form-control" id="tinymce" name="termsConditions" placeholder="Terms & Conditions">{{ old('termsConditions') }}</textarea>
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Generate Quote</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

<footer class="footer">
	<ul class="list-inline">
		<li>2020 © Al Akhzir Tech.</li>
		
	</ul>
</footer>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>

	$("#terms").on('change', function(){
		var terms = $('#terms').find(":selected").val();
		tinyMCE.activeEditor.setContent(terms);
	});

$( document ).ready(function() {

	$("#customerTable").on('click', '.btnDelete', function () {
	    $(this).closest('tr').prev('tr').remove();
	    $(this).closest('tr').next('tr').remove();
	    $(this).closest('tr').remove();
	});

	var i=2;

	// this is for default plus button
	$("#customerTable").on('click', '.addmore', function(){
		var data='<tr><th colspan="6"><input type="text" name="heading[]" placeholder="Heading" class="form-control"></th></th></tr>';

	    data +="<tr>";
	    
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
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

	    data +='<td style="width: 10%;">';
	    data +='<a class="addmore2 btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data += '&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    data +='</tr>';

		data +='<tr>'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide" style="color: #fff;">View/Hide Description</a>';
		data +='<textarea name="description[]" class="form-control description" placeholder="Description" style="height: 50px;display:none;"></textarea>';
		data +='</th>';
		data +='</tr>';

	    // $('table#customerTable').prepend(data);
	    $(this).parent().parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
	    i++;
	});
  
	// this is for run time added plus button
	$("#customerTable").on('click', '.addmore2', function(){
		var data='<tr><th colspan="7"><input type="text" name="heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
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

	    data +='<td style="width: 10%;">';
	    data +='<a class="addmore2 btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data += '&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    data +='</tr>';

	    data +='<tr>'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide" style="color: #fff;">View/Hide Description</a>';
		data +='<textarea name="description[]" class="form-control description" placeholder="Description" style="height: 50px;display:none;"></textarea>';
		data +='</th>';
		data +='</tr>';

	    // $('table#customerTable').prepend(data);
	    //$(this).parent().parent().parent().next('tr').after(data);
	    $(this).parent().parent().next('tr').after(data);
	    console.log(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
	    i++;
	});

});


$( document ).ready(function() {

	$("#productsTable").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').prev('tr').remove();
	    $(this).closest('tr').remove();
	});

	var i=2;

	// this is for default plus button
	$(".addmoreProduct").on('click',function(){
		var data='<tr><th colspan="7"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;"  required></td>'; 
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
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
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;"  required></td>'; 
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
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

// ------------------------------------------------------------------------------------------------------------------------------------ //

</script>
@endsection
