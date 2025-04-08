@extends('layouts.app')

@section('content')

<style type="text/css">
.select2-container { width: 100%!important; }
</style>
<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Add Product</h1>
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
				<h4 class="box-title">Add Product</h4>
					<form data-toggle="validator" action="{{ route('Products.store') }}"  enctype="multipart/form-data" method="POST">
					@csrf()
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-4">
									<label for="code">Product Code</label>
									<input type="text" class="form-control" id="code" name="code" value="{{ $newcode }}" placeholder="Product ID/Code" required>
								</div>
								<div class="form-group col-md-4">
									<label for="supplier_id">Supplier</label>
									<div>
									<select class="form-control select2_1" name="supplier_id" id="supplier_id" required style="display: inline-block;">
										<option value="">Select Supplier</option>
										@if($Suppliers)
										@foreach($Suppliers as $Supplier)
										<option value="{{ $Supplier->id }}" <?php echo (old('supplier_id') == $Supplier->id)? 'selected' : ''; ?>>{{$Supplier->name}}</option>
										@endforeach
										@endif
									</select>
									</div>
								</div>
								<div class="form-group col-md-4">
									<label for="name">Product Name</label>
									<input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Product Name" required>
								</div>
							</div>

							<div class="row mt-4">
								<div class="form-group col-md-4">
									<label for="category_id">Product Category</label>
									<select class="form-control select2_1" id="category_id" name="category_id" required>
										<option value="">Select Category</option>
										@if($Categories)
										@foreach($Categories as $Category)
										<option value="{{ $Category->id }}" <?php echo (old('category_id') == $Category->id)? 'selected' : ''; ?>>{{$Category->name}}</option>
										@endforeach
										@endif
									</select>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4"> 
									<label for="unit">Product Unit</label>
									<input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}" placeholder="Product Unit e.g(KG/LB/OZ)" required>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<label for="image">Product Image</label>
									<input type="file" id="image" name="image" class="form-control" accept="image/*" required>
								</div>	
							</div>

							<div class="row mt-4 mb-4">
								<div class="form-group col-md-4">
									<label for="dated">Date</label>
									<input type="date" id="dated" name="dated" class="form-control" value="{{ old('dated') }}" required>
								</div>

								<div class="form-group col-md-4">
									<label for="inventory">Inventory Qty</label>
									<input type="number" id="inventory" name="inventory" class="form-control" value="{{ old('inventory') }}" required>
								</div>
							</div>		
								
								
							<div class="row">
        							<table class="table" id="customerTable">
        							    <thead>
        							        <tr>
            							        <td><label>Capacity</label></td>
            							        <td><label>Buying Price</label></td>
            							        <td><label>Selling Price</label></td>
            							        <td><label>Add/Remove</label></td>
            							    </tr>
        							    </thead>
                                        <tbody>
                                            <tr>
            								    <td>
            										<div class="">
                    									<input type="text" class="form-control" id="capacity" name="capacity[]" value="{{ old('capacity') }}" placeholder="Capacity" required>
                    									<div class="help-block with-errors"></div>
                    								</div>
            									</td>
            									<td>
            										<div class="">
                    									<input type="number" step="0.01" class="form-control" name="buying_price_per_unit[]" value="{{ old('buying_price_per_unit') }}" placeholder="Buying Price Per Unit" required>
                    									<div class="help-block with-errors"></div>
                    								</div>
            									</td>
            									<td>
                    								<div class="">
                    									<input type="number" step="0.01" class="form-control" name="selling_price_per_unit[]" value="{{ old('selling_price_per_unit') }}" placeholder="Selling Price Per Unit" required>
                    									<div class="help-block with-errors"></div>
                    								</div>
            									</td>
            									<td>
            										<div class="">
            											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
            										</div>
            									</td>
            								</tr>
                                        </tbody>
        							</table>
        					 	</div>
								
								
								
									
								<!--<div class="form-group col-md-4">-->
								<!--	<label>Expiry Date</label>-->
								<!--	<input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>-->
								<!--</div>			-->
							<div class="row">
								<div class="form-group col-md-12">
									<textarea class="form-control" id="tinymce" name="description" placeholder="Product Description">{{ old('description') }}</textarea>
									<div class="help-block with-errors"></div>
								</div>
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Add Product</button>
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
$(".addmore").on('click',function(){
    var data="<tr>";
    data +='<td><input type="text" class="form-control" name="capacity[]" placeholder="Capacity" required></td>';
    
    data +='<td><input type="number" step="0.01" class="form-control" name="buying_price_per_unit[]" placeholder="Buying Price Per Unit" required>';
    
    data +='<td><input type="number" step="0.01" class="form-control" name="selling_price_per_unit[]" placeholder="Buying Price Per Unit" required>';
    
    data +='<td><a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a></td>';
    data +='</tr>';
    $('table#customerTable').prepend(data);
	$('.product_id').select2();
    i++;
});

});
</script>
@endsection