@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Add Customer</h1>
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

				<form data-toggle="validator" action="{{ route('Customer.update', $id) }}" method="POST">
				@csrf()
				@method('PATCH')

				<div class="box-content">
					<h4 class="box-title">Company Name</h4>
					<div class="form-group">	
						<div class="row">
							<div class="form-group col-md-12">
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ $CustomerData->company_name }}" placeholder="Company Name"  required>
							</div>
						</div>
					</div>
				</div>

				<div class="box-content">
					<h4 class="box-title">Customer Details</h4>
					<div class="form-group">	
						<div class="row">

							<table class="table" id="customerDetailsTable">
								<?php $count=0; ?>
								<?php $customerIds = []; ?>
								@foreach($Customers as $Customer)
								<tr>
									<input type="hidden" name="customer_id[]" value="{{ $Customer->id }}">
									<td>
										<input type="text" class="form-control" id="customer_name" name="customer_name[]" value="{{ $Customer->customer_name }}" placeholder="Customer Name"  required>
									</td>
									<td>
										<input type="text" class="form-control" id="customer_ntn" name="customer_ntn[]" value="{{ $Customer->customer_ntn }}" placeholder="Customer NTN"  required>
									</td>
									<td>
										<input type="number" class="form-control" id="phone_no" name="phone_no[]" value="{{ $Customer->phone_no }}" placeholder="Phone No">
									</td>

									<td>
										<input type="email" class="form-control" id="email" name="email[]" value="{{ $Customer->email }}" placeholder="Email Address">
									</td>

									<td>
										<input type="text" class="form-control" id="address" name="address[]" value="{{ $Customer->address }}" placeholder="Address">
									</td>

									<td>
										<input type="text" class="form-control" id="city" name="city[]" value="{{ $Customer->city }}" placeholder="City"  required>
									</td>

									<td>
										@if($count == 0)
										<a class="btn btn-primary btn-xs waves-effect waves-light addmoreC" style="color: #fff"><i class="fa fa-plus"></i></a>
										@else
										<a class="btnDeleteC btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										@endif
									</td>
								</tr>
								<?php $count++; ?>
								<?php array_push($customerIds, $Customer->id); ?>
								@endforeach
							</table>
						</div>
					</div>
				</div>

				<div class="box-content">
					<h4 class="box-title">Update Special Prices</h4>
					<div class="form-group">
						<div class="row">
							<table class="table" id="customerTable">
								<?php
								$CustomerSpecialPrices = App\Models\CustomerSpecialPrices::whereIn('customer_id', $customerIds)->get();
								
								$count = App\Models\CustomerSpecialPrices::whereIn('customer_id', $customerIds)->count(); 
								
								$count1=0;
								?>
								@if($count>0)
								@foreach($CustomerSpecialPrices as $price)
								<?php							
								$productdata = App\Models\Product::find($price->product_id);
								$capacities = explode(', ', $productdata->capacity);
								?>
								<tr>
									<td></td>
									<td style="width:40%!important" class="productTD">
										<select class="form-control select2_1 productName" id="product_id" name="product_id[]">
											<option value="">Select Product</option>
											@if($products)
											@foreach($products as $product)
											<option value="{{ $product->id }}" <?php echo ($price->product_id == $product->id) ? 'selected' : ''  ?>>{{ $product->name }}</option>
											@endforeach
											@endif
										</select>
										<div class="help-block with-errors"></div>
									</td>
									
									<td style="width:25%!important" class="productCapacityTD">
										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" required>
										    @foreach($capacities as $capac)
										    <option value="{{ $capac }}" <?php echo ($price->productCapacity == $capac) ? 'selected' : ''?>>{{ $capac }}</option>
										    @endforeach
										</select>
										<div class="help-block with-errors"></div>
									</td>
									
									
									<td style="width:25%!important">
										<input type="number" step="0.01" class="form-control price" id="discount_price" name="discount_price[]" placeholder="Discounted Price" value="{{ $price->discount_price }}" required>
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:10%!important">
										<div class="input-group">
										    <a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										</div>
									</td>
								</tr>
								<?php $count1++ ?>
								@endforeach
								@endif
								
								<tr>
								    <td></td>
									<td style="width:40%!important" class="productTD">
										<select class="form-control select2_1 productName" id="product_id" name="product_id[]" style="height: 30px!important;width: 100%;">
											<option value="">Select Product</option>
											@if($products)
											@foreach($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
											@endforeach
											@endif
										</select>
										<div class="help-block with-errors"></div>
									</td>
									
									<td style="width:25%" class="productCapacityTD">
										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
										</select>
										<div class="help-block with-errors"></div>
									</td>
									
									<td style="width:25%!important">
										<input type="number" step="0.01" class="form-control price" id="discount_price" name="discount_price[]" placeholder="Discounted Price" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:10%!important">
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
										</div>
									</td>
								</tr>
							</table>
					 	</div>
					</div>
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Update Customer</button>
						</div>
					</div>
				</div>
						</div>
					</form>
				</div>
			</div>
		</div>	
		<footer class="footer">
			<ul class="list-inline">
				<li>2020 © Al Akhzir Tech.</li>
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
$(".addmore").on('click',function(){
    var data="<tr><td></td>";
    data +='<td class="productTD"><select class="form-control select2_1 product_id productName" id="product_id" required name="product_id[]" style="height: 40px!important;">';
    data +='<option value="">Select Product</option>';
    @if($products)
    @foreach($products as $product);
    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
    @endforeach 
    @endif 
    data +='</select></td>';
    
    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 40px!important;"></select></td>';
    
    
    data +='<td><input type="number" step="0.01" class="form-control price" id="discount_price" name="discount_price[]" placeholder="Discounted Price" style="height: 40px!important;"  required></td>';
    data +='<td><a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
    data +='</tr>';
    $('table#customerTable').append(data);
	$('.product_id').select2();
	$('.productName').select2();
    i++;
});

});




$( document ).ready(function() {

$("#customerDetailsTable").on('click', '.btnDeleteC', function () {
    $(this).closest('tr').remove();
});

var i=2;
$(".addmoreC").on('click',function(){
    var data="<tr>";
   
    data +='<td><input type="text" class="form-control" id="customer_name" name="customer_name[]" placeholder="Customer Name"  required></td>';

	data +='<td><input type="text" class="form-control" id="customer_ntn" name="customer_ntn[]" placeholder="Customer NTN"  required></td>';

	data +='<td><input type="number" class="form-control" id="phone_no" name="phone_no[]" placeholder="Phone No"></td>';

	data +='<td><input type="email" class="form-control" id="email" name="email[]" placeholder="Email Address"></td>';

	data +='<td><input type="text" class="form-control" id="address" name="address[]" placeholder="Address"></td>';

	data +='<td><input type="text" class="form-control" id="city" name="city[]" placeholder="City"  required></td>';

    data +='<td><a class="btnDeleteC btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
    
    data +='</tr>';

    $('table#customerDetailsTable').append(data);
	$('.product_id').select2();
    i++;
});

});

</script>
@endsection
