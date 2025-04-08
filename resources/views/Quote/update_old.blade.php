@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Update Quote</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('Quotes.update', $quote->id) }}" method="POST">
@csrf()
@method('PATCH')


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
					<h4 class="box-title">Update Quote</h4>
					<div class="form-group">
						<div class="row">
						    <?php 
						    $customer = App\Models\Customer::find($quote->customer_id);
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
							        $c_name     = '';
							    endif;
						    else:
						        $c_company  = '';
						        $c_city     = '';
						        $c_address  = '';
						        $c_id       = '';
						        $c_name     = '';
						    endif;
						    ?>
						    <!--Company Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_company_name" id="customer_company_name">
									<option value="">Select Company</option>
									@if($customers)
									@foreach($customers as $cust)
									<option value="{{ $cust->company_name }}" <?php echo ($cust->company_name == $c_company) ? 'selected' : '';?>>
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
							<div class="col-md-12">
							 	<h4 class="box-title">Products</h4>
							</div>
                            
                            <table class="table" id="customerTable">
                                <?php $count=0; $sequence=1; ?>
                                
                                @if($QouteProducts->count() > 0)
    							    @foreach($QouteProducts as $QProduct)
    								    <tr>
    								        <td style="width:10%;border: none;">
        										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
        									</td>
    								        
        									<td style="width:20%;border: none;" class="productTD">
        										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
        											<option value="">Select Product</option>
        											@if($products)
        											@foreach($products as $product)
        											<option value="{{ $product->id }}" <?php echo ($product->id == $QProduct->product_id) ? 'selected' : ''; ?>>{{ $product->name }}</option>
        											@endforeach
        											@endif
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
        									<td style="width:20%;border: none;">
        										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" value="{{ $QProduct->qty }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<?php
        									$productData = App\Models\Product::find($QProduct->product_id);
        									$capacities  = explode(', ', $productData->capacity);
        									?>
        									<td style="width:20%;border: none;" class="productCapacityTD">
        										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
        										@foreach($capacities as $capacity) 
        										    <option value="{{ $capacity }}" <?php echo ($capacity == $QProduct->productCapacity) ? 'selected' : ''; ?>>{{ $capacity }}</option>
        										@endforeach
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
                                            <td style="width:20%;border: none;">
        										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" value="{{ $QProduct->unit_price }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									
        									<td style="width:20%;border: none;">
        										<div class="input-group">
        											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
        											&nbsp;
        										    <a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
        										</div>
        									</td>
        								</tr>
        								<?php $count++; $sequence++; ?>
    								@endforeach
								@endif
								<tr>
							        <td style="width:10%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence++ }}" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%" class="productTD">
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
							</table>
							
							<hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Other Products</h4>
							</div>
                            
                            <?php
							$ops_name   = explode('@&%$# ', $quote->other_products_name);
                            $op_qty     = explode('@&%$# ', $quote->other_products_qty);
                            $op_price   = explode('@&%$# ', $quote->other_products_price);
                            $op_unit    = explode('@&%$# ', $quote->other_products_unit);
                            // $op_image   = explode('@&%$# ', $quote->other_products_image);
                            
                            $count1=0;
                            $sequence_2=1;
                            ?>
                            
							<table class="table" id="productsTable">
							    @if(isset($ops_name[0]))
    							    @foreach($ops_name as $op_name)
    							        @if(!empty($op_name))
    								    <tr>
    								    	<td style="width:10%;">
												<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence_2 }}" style="height: 30px!important;">
												<div class="help-block with-errors"></div>
											</td>

        									<td>
        										<input type="text" class="form-control" id="productName" name="productName[]" value="{{ $op_name }}" placeholder="Product Name" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="number" class="form-control" id="productqty" name="productQty[]" value="{{ $op_qty[$count1] }}" placeholder="Quantity" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="number" step="0.01" class="form-control" id="productPric" value="{{ $op_price[$count1] }}" name="productPric[]" placeholder="Price" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<td>
        										<input type="text" class="form-control" id="unit" name="unit[]" value="{{ $op_unit[$count1] }}" placeholder="Unit" style="height: 40px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									
        									<td>
        										<div class="input-group">
        											<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
        										</div>
        									</td>
        								</tr>
    								    <?php $count1++;$sequence++; ?>
    								    @endif
    								@endforeach
    							@endif
    						</table>
    						<table class="table" id="productsTableNew">
    							<tr>
    								<td style="width:10%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence_2 }}" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td>
										<input type="text" class="form-control" id="productName" name="productName[]" placeholder="Product Name" style="height: 40px!important;">
									</td>
									<td>
										<input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;">
									</td>
									<td>
										<input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;">
									</td>
									<td>
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 40px!important;">
									</td>

									<!--<td>
										<input type="file" class="form-control" id="other_products_image" name="other_products_image[]" style="height: 40px!important;">
										<div class="help-block with-errors"></div>
									</td>-->

									
									<td>
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff"><i class="fa fa-plus"></i></a>
										</div>
									</td>
								</tr>
							</table>
							
							

							<div class="col-md-12">
							 	<h4 class="box-title">Customer Details</h4>
							</div>
						 	<input type="hidden" name="walkCustomer" value="{{ ($customer) ? $customer->id : 'walkin' }}">
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
							
							
						  	
						  	<div class="form-group col-md-12">
								<input type="text" class="form-control" id="subject" name="subject" value="{{ $quote->subject }}" placeholder="Subject">
								<div class="help-block with-errors"></div>
							</div>
							<hr>
            				<div class="col-md-12">
            				    <div class="row">
            				        <div class="form-group col-md-1">
            							<input type="checkbox" id="GST" name="GST" <?php echo ($quote->GST == 'on') ? 'checked' : '' ?>> GST
            							<div class="help-block with-errors"></div>
            						</div>

            						<div class="form-group col-md-2">
										<input type="number" step="0.01" class="form-control" id="tax_rate" name="tax_rate" <?php echo ($quote->GST == 'on') ? 'required' : '' ?> value="{{ $quote->tax_rate }}" placeholder="Tax Rate"  style="height: 40px!important;">
									</div>

            						<div class="form-group col-md-2">
            							<input type="number" class="form-control" id="discount_percent" name="discount_percent" value="{{ $quote->discount_percent }}" placeholder="Discount (%)"  style="height: 40px!important;">
            							<div class="help-block with-errors"></div>
            						</div>
            						
            						<div class="form-group col-md-2">
        								<input type="number" step="0.01" class="form-control" id="discount_fixed" name="discount_fixed" value="{{ $quote->discount_fixed }}" placeholder="Discount Fixed"  style="height: 40px!important;">
        								<div class="help-block with-errors"></div>
        							</div>
            						
            						<div class="form-group col-md-3">
            							<input type="number" step="0.01" class="form-control" id="transportaion" name="transportaion" value="{{ $quote->transportaion }}"  placeholder="Transportaion" style="height: 40px!important;">
            							<div class="help-block with-errors"></div>
            						</div>
            						
            						<div class="form-group col-md-2">
        								<input type="date" class="form-control" id="dated" name="dated" value="{{ $quote->dated }}" style="height: 40px!important;">
        							</div>
            					</div>
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
								<textarea class="form-control" id="tinymce" name="termsConditions"  placeholder="Terms & Conditions">{{ $quote->termsConditions }}</textarea>
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Update Quote</button>
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
		<li>{{ date('Y') }} © Al Akhzir Tech.</li>
		
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
	    $(this).closest('tr').remove();
	});
	
	var i=<?php echo $sequence; ?>;
	$(".addmore").on('click',function(){
	    var data="<tr>";
	    
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
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
	    data +='<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    
	    data +='</tr>';
	    $('table#customerTable').prepend(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
	    i++;
	});

});


$( document ).ready(function() {

	$("#productsTable").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').remove();
	});
	$("#productsTableNew").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').remove();
	});

	var i=<?php echo $sequence_2+1; ?>;
	$(".addmoreProduct").on('click',function(){
	    var data="<tr>";


	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>';

	   // data +='<td><input type="file" class="form-control" id="other_products_image" name="other_products_image[]" required style="height: 40px!important;"></td>';

	    data +='<td><a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
	    data +='</tr>';
	    
	    $('table#productsTableNew').prepend(data);
	    i++;
	});

});

// ------------------------------------------------------------------------------------------------------------------------------------ //

</script>
@endsection
