@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">All Products</h1>
	</div>
</div>
<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<!-- Main table -->
			<div class="col-8">
				<div class="box-content table-responsive">
					<table class="table table-striped table-bordered display" style="width:100%">
						<tbody>
							<tr>
								<th style="width:40%">Product ID/Code</th>
								<td style="width:60%" colspan="2">{{ $Product->code }}</td>
							</tr>
							<tr>
								<th style="width:40%">Product Name</th>
								<td style="width:60%" colspan="2">{{ $Product->name }}</td>
							</tr>
							<tr>
								<th style="width:40%">Category</th>
								<?php $category = App\Models\Category::find($Product->category_id); ?>
								<td style="width:60%" colspan="2">{{ $category->name }}</td>
							</tr>
							<tr>
								<th style="width:40%">Supplier</th>
								<?php $Supplier = App\Models\Supplier::find($Product->supplier_id); ?>
								<td style="width:60%" colspan="2">{{ $Supplier->name }}</td>
							</tr>
							
							<tr>
								<th style="width:40%">Product Name</th>
								<th style="width:30%">Buying Price</th>
								<th style="width:30%">Selling Price</th>
							</tr>
							
							<?php 
					        $capacity = $Product->capacity;
					        $capacityArr = explode(', ', $capacity);
					        
					        $buying_price_per_unit      = $Product->buying_price_per_unit;
						    $buying_price_per_unitArr   = explode(', ', $buying_price_per_unit);
						    
						    $selling_price_per_unit      = $Product->selling_price_per_unit;
						    $selling_price_per_unitArr   = explode(', ', $selling_price_per_unit);
						    
						    $count=0;
					        ?>
					        @foreach($capacityArr as $cap)
							<tr>
								<td>{{ $cap }}</td>
								<td>{{ $buying_price_per_unitArr[$count] }}</td>
								<td>{{ $selling_price_per_unitArr[$count] }}</td>
							</tr>
							<?php $count++; ?>
							@endforeach
							
							<tr>
								<th style="width:40%">Unit</th>
								<td style="width:60%" colspan="2">{{ $Product->unit }}</td>
							</tr>
							<tr>
								<th style="width:40%">Date</th>
								<td style="width:60%" colspan="2">{!! $Product->dated !!}</td>
							</tr>
							<!--<tr>-->
							<!--	<th>Expiry Date</th>-->
							<!--	<td>{!! $Product->expiry_date !!}</td>-->
							<!--</tr>-->
							<tr>
								<th style="width:40%">Description</th>
								<td style="width:60%" colspan="2">{!! $Product->description !!}</td>
							</tr>
							<tr>
								<th style="width:40%">Action</th>
								<td style="width:60%" colspan="2">
									<a href="{{ route('Products.index') }}" class="btn btn-warning" style="display: inline-block;color: #fff">
										<i class="fa fa-arrow-left"></i> Go Back
									</a>
									<a href="{{ route('Products.edit',$Product->id) }}" class="btn btn-primary" style="display: inline-block;">
										<i class="fa fa-pencil-alt"></i> Update
									</a>
									<form action="{{ route('Products.destroy', $Product->id) }}" method="POST" style="display: inline-block;">
					                @csrf
					                @method('DELETE')
				                	<button  type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Product?');" style="display: inline-block; border:none;">
				                   			<i class="fa fa-trash-alt"></i> Delete
				                	</button>
					            	</form>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- Image table -->
			<div class="col-4">
				<div class="box-content table-responsive" style="text-align: center;">
					<thead>
						<tr>
							<th><strong>Product Image</strong></th>
							<hr>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td> <img src=" /Product/{{$Product->image }}"></td>
						</tr>
					</tbody>
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
@endsection