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
			<div class="col-12">
				<div class="box-content table-responsive">
					<table id="exampleQuote" class="table table-striped table-bordered display" style="width:100%">
						<thead>
							<tr>
								<th>Date</th>
							    <th>Product ID/Code</th>
								<th>Product Name</th>
								<th>Category</th>
								<th>Capacity</th>
								<th>Buying Price</th>
								<th>Selling Price</th>
								<th>Unit</th>
								<th>Inventory Qty</th>
								<th>Action</th>
						</thead>
						<tfoot>
							<tr>
								<th>Date</th>
							   <th>Product ID/Code</th>
								<th>Product Name</th>
								<th>Category</th>
								<th>Capacity</th>
								<th>Buying Price</th>
								<th>Selling Price</th>
								<th>Unit</th>
								<th>Inventory Qty</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody>
							@if($Products)
							@foreach($Products as $Product)
							<tr>
								<td>{{ $Product->dated }}</td>
								<td>{{ $Product->code }}</td>
								<td>{{ $Product->name }}</td>
								<?php $category = App\Models\Category::find($Product->category_id); ?>
								<td>{{ $category->name }}</td>
						            
						        <?php 
						        $capacity = $Product->capacity;
						        $capacityArr = explode(', ', $capacity);
						        ?>
						        <td>
						            @foreach($capacityArr as $cap)
						                {{ $cap }} <br>
						            @endforeach
						        </td>
						        <?php 
						        $buying_price_per_unit      = $Product->buying_price_per_unit;
						        $buying_price_per_unitArr   = explode(', ', $buying_price_per_unit);
						        ?>
								<td>
								    @foreach($buying_price_per_unitArr as $buying_price)
						                {{ $buying_price }} <br>
						            @endforeach
						        </td>
								<?php 
						        $selling_price_per_unit      = $Product->selling_price_per_unit;
						        $selling_price_per_unitArr   = explode(', ', $selling_price_per_unit);
						        ?>
								<td>
								    @foreach($selling_price_per_unitArr as $selling_price)
						                {{ $selling_price }} <br>
						            @endforeach
						        </td>
						        
								<td>{{ $Product->unit }}</td>
								<td>{{ $Product->inventory }}</td>
						<!-- 		<td>
									<i class="menu-icon fa fa-eye">
								<i class="menu-icon fas fa-pencil-alt">
								<i class="menu-icon fas fa-trash-alt"></td> -->
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