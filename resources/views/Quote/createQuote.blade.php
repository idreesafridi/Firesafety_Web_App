@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Upload Quote</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('save-qoute') }}" method="POST" enctype="multipart/form-data">
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
					<div class="form-group">
						<div class="row">
							<div class="form-group col-md-4">
								<select class="form-control select2_1" name="customer_id">
									<option value="">Select Customer</option>
									@if($customers)
									@foreach($customers as $customer)
									<option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
									@endforeach
									@endif
								</select>
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="col-md-12">
							 	<h4 class="box-title">Quote Attachment</h4>
							</div>
							<div class="form-group col-md-12">
								<input type="file" class="form-control" id="attachment" name="attachment" required style="height:50px">
								<div class="help-block with-errors"></div>
							</div>


							<div class="col-md-12">
							 	<h4 class="box-title">Customer Details</h4>
							</div>
						 
						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Customer Name">
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
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name">
								<div class="help-block with-errors"></div>
							</div>
						  	<div class="form-group col-md-4"></div>

						  	<?php $terms = App\Models\TermsAndConditions::find(1); ?>
						  	<div class="form-group col-md-12">
								<textarea class="form-control" id="termsConditions" name="termsConditions" placeholder="Terms & Conditions">{{ $terms->termsAndConditions }}</textarea>
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

$( document ).ready(function() {

	$("#customerTable").on('click', '.btnDelete', function () {
	    $(this).closest('tr').remove();
	});

	var i=2;
	$(".addmore").on('click',function(){
	    var data="<tr>";
	    data +='<td><select class="form-control select2_1" id="product_id" required name="product_id[]" style="height: 40px!important;">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 40px!important;"  required></td>';
	    data +='<td><a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
	    data +='</tr>';
	    $('table#customerTable').append(data);
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
	    data +='<td><input type="text" class="form-control" id="productName" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
	    data +='<td><a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
	    data +='</tr>';
	    
	    $('table#productsTable').append(data);
	    i++;
	});

});

</script>
@endsection
