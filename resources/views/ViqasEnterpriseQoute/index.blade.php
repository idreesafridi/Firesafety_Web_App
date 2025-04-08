@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Quotes</h1>
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
    							<form action="{{ route('filter-viqas-enterprise') }}" method="GET" class="form-horizontal">
    								<div class="form-group row">
    									<div class="col-sm-3">
    										<select name="company" class="form-control select2_1" onchange="this.form.submit()">
                    			                <option value="">Select Company</option>
                    			                @foreach($companies as $company)
                    			                    <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                    			                @endforeach
                    			            </select>
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
					<div class="box-content table-responsive">
						<table id="example" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Date</th>
									<th>Quote Number</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Company Name</th>
									<th>Amount</th>
									<th>Action</th>
									<th>Excel</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Quote Number</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Company Name</th>
									<th>Amount</th>
									<th>Action</th>
									<th>Excel</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Quotes)
									@foreach($Quotes as $Quote)
										<tr>
											<td>{{ date("d M, Y", strtotime($Quote->dated)) }}</td>
											<td>{{ $Quote->id }}</td>
											<?php $customer = App\Models\Customer::find($Quote->customer_id); ?>
											<td>{{ $customer->customer_name }}</td>
											<td>{{ $customer->phone_no }}</td>
											<td>{{ $customer->company_name }}</td>


											<?php 
											$totalPrice1 = 0;
											$QouteProducts   = App\Models\ViqasEnterpriseQouteProducts::where('quote_id', $Quote->id)->get();


											// Transport
										    if(isset($Quote->transportaion)):
										    	$transportaion = $Quote->transportaion;
										    else:
										    	$transportaion = 0;
										    endif;

											foreach($QouteProducts as $Product):
											    $productData = App\Models\Product::find($Product->product_id);
											    $price1 = $Product->unit_price;
											    $totalPrice1 += $Product->qty*$price1;
											endforeach;

        									$totalPrice2 = 0;
											?>
											@if($Quote->other_products_name)
	        									<?php 
	        									$moreProductsNames = explode('@&%$# ', $Quote->other_products_name);
	        									$moreProductsQty   = explode('@&%$# ', $Quote->other_products_qty);
	        									$moreProductsPrice = explode('@&%$# ', $Quote->other_products_price);
	        									$moreProductsUnit = explode('@&%$# ', $Quote->other_products_unit);
	        									$count2 = 0;
	        									?>
	        									@foreach($moreProductsNames as $moreP)
		        									<?php 
		        									$qty 			=  $moreProductsQty[$count2]; 
		        									$price 			=  $moreProductsPrice[$count2];
		        									$totalPrice2 	+= $qty*$price;
		        									$count2++; 
		        									?>
	        									@endforeach
        									@endif   
        									
        									<?php 
        									$totalPrice = $totalPrice1+$totalPrice2;

        									// GST
        									if($Quote->GST == 'on'):
	        									$GST = $totalPrice*0.17;
	        								else:
	        									$GST = 0;
	        								endif;
        									$net = $totalPrice+$GST+$transportaion;
        									?>


											<td>{{ number_format($net,2) }}</td>
											<td>
												@if($Quote->attachment != '') 
												<a href="Quote/{{ $Quote->attachment }}" target="_blank" download style="display: inline-block;color: #000">
													<i class="fa fa-download"></i>
												</a>
												@else
												<!-- route('choose-template',$Quote->id)-->
												<a href="{{ route('ViqasEnterprise.show', $Quote->id) }}" target="_blank" style="display: inline-block;color: #000">
													<i class="menu-icon fa fa-download"></i>
												</a>
												
												<form action="{{ route('ViqasEnterprise.destroy', $Quote->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Quote?');" style="display: inline-block;background: transparent; border:none;">
							                   			<i class="fa fa-trash-alt"></i>
							                	</button>
								            	</form>
								            	@endif
											</td>
											<td>
												<a href="{{ route('ViqasEnterpriseQouteExport', $Quote->id) }}">Export</a>
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
		<!-- /.main-content -->
</div>

@endsection
