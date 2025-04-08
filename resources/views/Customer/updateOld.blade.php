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

				<form data-toggle="validator" action="{{ route('Customer.update', $Customer->id) }}" method="POST">
				@csrf()
				@method('PATCH')

				<div class="box-content">
					<h4 class="box-title">Update Customer</h4>
					<div class="form-group">	
						<div class="row">
							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $Customer->customer_name }}" placeholder="Customer Name"  required>
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
								<input type="number" class="form-control" id="phone_no" name="phone_no" value="{{ $Customer->phone_no }}" placeholder="Phone No">
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
								<input type="email" class="form-control" id="email" name="email" value="{{ $Customer->email }}" placeholder="Email Address">
								<div class="help-block with-errors"></div>
							</div>

						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="address" name="address" value="{{ $Customer->address }}" placeholder="Address">
								<div class="help-block with-errors"></div>
						 	</div>

							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="city" name="city" value="{{ $Customer->city }}" placeholder="City"  required>
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ $Customer->company_name }}" placeholder="Company Name"  required>
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="box-content">
					<h4 class="box-title">Update Special Prices</h4>
					<div class="form-group">
						<div class="row">
							<table class="table" id="customerTable">
								<?php 
								$CustomerSpecialPrices = App\Models\CustomerSpecialPrices::where('customer_id', $Customer->id)->get(); 
								$count = App\Models\CustomerSpecialPrices::where('customer_id', $Customer->id)->count(); 
								
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
										    @if($count1 == 0)
											<a class="btn btn-primary btn-sm waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
											@else
											<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
											@endif
										</div>
									</td>
								</tr>
								<?php $count1++ ?>
								@endforeach
								@endif
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
    data +='<td><select class="form-control select2_1 product_id" id="product_id" required name="product_id[]" style="height: 40px!important;">';
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
    i++;
});

});
</script>
@endsection
