@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Customer Report</h1>
	</div>
</div>


<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">
				<div class="box-content">
					<h4 class="box-title">Select Range</h4>
					<div class="row">
						<div class="col-xl-12">
							<form action="{{ route('expiryReport') }}" method="GET" class="form-horizontal">
								<div class="form-group row">
									<div class="col-sm-3">
										<div class="input-group">
											<input type="date" class="form-control" name="from_date" id="datepicker-autoclose">
										</div>
									</div>
									<div class="col-sm-3">
										<div class="input-group">
											<input type="date" class="form-control" name="to_date" id="datepicker-autoclose">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group">
											<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Search</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="box-content table-responsive" >
					<table id="example" class="table table-striped table-bordered display" style="width:100%">
						<thead>
							<tr>
							    <th>Product ID/Code</th>
								<th>Product Name</th>
								<th>Category</th>
								<th>Buying Price</th>
								<th>Selling Price</th>
								<th>Unit</th>
								<th>Expiry Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							   <th>Product ID/Code</th>
								<th>Product Name</th>
								<th>Category</th>
								<th>Buying Price</th>
								<th>Selling Price</th>
								<th>Unit</th>
								<th>Expiry Date</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody>
							@if($reports)
							@foreach($reports as $Product)
							<tr>
								<td>{{ $Product->code }}</td>
								<td>{{ $Product->name }}</td>
								<?php $category = App\Models\Category::find($Product->category_id); ?>
								<td>{{ $category->name }}</td>
								<td>{{ $Product->buying_price_per_unit }}</td>
								<td>{{ $Product->selling_price_per_unit }}</td>
								<td>{{ $Product->unit }}</td>
								<td>{{ $Product->expiry_date }}</td>
								<td>
									<a href="{{ route('Products.show',$Product->id) }}" style="display: inline-block;color: #000">
										<i class="fa fa-eye"></i>
									</a>
									<a href="{{ route('Products.edit',$Product->id) }}" style="display: inline-block;color: #000">
										<i class="fa fa-pencil-alt"></i>
									</a>
									<form action="{{ route('Products.destroy', $Product->id) }}" method="POST" style="display: inline-block;">
					                @csrf
					                @method('DELETE')
				                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Product?');" style="display: inline-block;background: transparent; border:none;">
				                   			<i class="fa fa-trash-alt"></i>
				                	</button>
					            	</form>
								</td>
							</tr>
							@endforeach
							@endif
						</tbody>	
					</table>
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
