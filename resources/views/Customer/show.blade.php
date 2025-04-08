@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Company Details</h1>
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
										<th>Company Name</th>
										<th>Phone Number</th>
										<th>Email</th>
										<th>Address</th>
										<th>City</th>
									</tr>
									<?php $customerIds = []; ?>
									@foreach($Customers as $Customer)
									<?php 
										$customersData = App\Models\Customer::where('company_name', $Customer->company_name)->get();
										?>
										<tr>
											<td>{{ $Customer->customer_name }}</td>
											<td>{{ $Customer->company_name }}</td>

											<td>{{ $Customer->phone_no }}</td>
											<td>{{ $Customer->email }}</td>
											<td>{{ $Customer->address }}</td>
											<td>{{ $Customer->city }}</td>
										</tr>
										<?php array_push($customerIds, $Customer->id); ?>
									@endforeach
								<?php $prices = App\Models\CustomerSpecialPrices::whereIn('customer_id', $customerIds)->get(); ?>
							</tbody>
						</table>

						<h3></h3>

						<table class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th colspan="5">Special Prices</th>
								</tr>
								<tr>
									<th>Product</th>
									<th>Size</th>
									<th>Discount Price</th>
									<th>Regular Price</th>
								</tr>
								<tbody>
									@if($prices)
									@foreach($prices as $price)
										<?php $product = App\Models\Product::find($price->product_id); ?>
										<tr>
											<td>{{ $product->name }}</td>
											<td>{!! $price->productCapacity !!}</td>
											<td>{{ $price->discount_price }}</td>
											<?php 
											$capacities     = explode(', ', $product->capacity); 
											$selling_prices = explode(', ', $product->selling_price_per_unit); 
											
											$count=0;
											?>
											<td>
											    @foreach($capacities as $capac)
											        <!--if($price->productCapacity == $capac)-->
											        @if(stripos($capac, $price->productCapacity) !== FALSE)
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
