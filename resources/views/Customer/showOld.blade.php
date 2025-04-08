@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Customer</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content table-responsive">
						<table class="table table-striped table-bordered display" style="width:100%">
							<tbody>

								<tr>
									<th>Customer Name</th>
									<td>{{ $Customer->customer_name }}</td>
								</tr>
								<tr>
									<th>Phone Number</th>
									<td>{{ $Customer->phone_no }}</td>
								</tr>
								<tr>
									<th>Email</th>
									<td>{{ $Customer->email }}</td>
								</tr>
								</tr>
									<th>Address</th>
									<td>{{ $Customer->address }}</td>
								</tr>
								<tr>
									<th>City</th>
									<td>{{ $Customer->city }}</td>
								</tr>
								<tr>
									<th>Company Name</th>
									<td>{{ $Customer->company_name }}</td>
								</tr>

								<tr>
									<th colspan="2">Special Prices</th>
								</tr>
							</tbody>
						</table>

						<table class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Product</th>
									<th>Size</th>
									<th>Discount Price</th>
									<th>Regular Price</th>
								</tr>
								<tbody>
									@if($prices)
									@foreach($prices as $price)
										<?php $product = App\Models\Product::find($price->product_id) ?>
										<tr>
											<td>{{ $product->name }}</td>
											<td>{{ $price->productCapacity }}</td>
											<td>{{ $price->discount_price }}</td>
											<?php 
											$capacities     = explode(', ', $product->capacity); 
											$selling_prices = explode(', ', $product->selling_price_per_unit); 
											
											$count=0;
											?>
											<td>
											    @foreach($capacities as $capac)
											        @if($price->productCapacity == $capac)
											            {{ $selling_prices[$count] }}
											        @endif
											        <?php $count++; ?>
											    @endforeach
											</td>
										</tr>
									@endforeach
									@endif
								</tbody>
							</thead>
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
		<!-- /.main-content -->
</div>

@endsection
