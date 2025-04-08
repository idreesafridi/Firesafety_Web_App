@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Cash Memo</h1>
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
    							<form action="{{ route('filter-cash-memo') }}" method="GET" class="form-horizontal">
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
										<div class="col-sm-2">
										<a href="{{ route('cashmemobystatsshow', ['data_key' => 'data_value']) }}" id="viewButton" class="btn btn-primary btn-sm">View</a>
										</div>
    								</div>
    							</form>
    						</div>
    					</div>
    				</div>
    			</div>
    			
    			
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Date</th>
									<th>Our Quote No</th>
									<th>Ref No</th>
									<th>Total</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Action</th>
									<th>Duplicate</th>
									<th>Excel</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Our Quote No</th>
									<th>Ref No</th>
									<th>Total</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Action</th>
									<th>Duplicate</th>
									<th>Excel</th>
								</tr>
							</tfoot>
							<tbody>
								@if($CashMemos)
									@foreach($CashMemos as $CashMemo)
										<tr>
											<td>{{ date("d M, Y", strtotime($CashMemo->created_date)) }}</td>
											<td>{{ ($CashMemo->reference_no) ? $CashMemo->reference_no : 'N/A' }}</td>
											<td>{{ $CashMemo->id }}</td>

											<?php 
											$totalPrice=0;
											$CashmemoProducts   = App\Models\CashmemoProduct::where('cashmemo_id', $CashMemo->id)->orderBy('sequence', 'asc')->get();
											if($CashmemoProducts):
		                                    	foreach($CashmemoProducts as $Product):
		                                            $subtotal   = $Product->qty*$Product->unit_price;
		                                            $totalPrice += $subtotal;
		                                    	endforeach;
		                                	endif;

		                                	$Descriptions   = explode('@&%$# ', $CashMemo->descriptions);
			                                $Quantity       = explode('@&%$# ', $CashMemo->qty);
			                                $UnitPrice      = explode('@&%$# ', $CashMemo->unit_price);
			                                $productCapacity      = explode('@&%$# ', $CashMemo->productCapacity);
			                                $total = $totalPrice;

			                                if(!empty($Descriptions)):
			                                $count1=0;
	                                    	foreach($Descriptions as $Description):
	                                        if(!empty($Description)):
	                                        	$qty    = (float)$Quantity[$count1];
	                                            $price  = (float)$UnitPrice[$count1];
	                                            
	                                            $sub = $price;

	                                            $net = $qty*$sub;
	                                            $total += $net;
                                        	endif;
		                                    endforeach;
		                                	endif;

		                                	$new_total_price=$total; 
		                                	$transportaion = 0;

			                                if($CashMemo->discount_percent > 0 || $CashMemo->discount_fixed > 0):
			                                if(isset($CashMemo->discount_percent)):
			                                    $discount_value     = ($total / 100) * $CashMemo->discount_percent;
			                                    $new_total_price    = $total - $discount_value;
			                                elseif(isset($CashMemo->discount_fixed)):
			                                    $discount_value = $CashMemo->discount_fixed;
			                                    $new_total_price    = $total - $discount_value;
			                                endif;
			                               
			                                if (isset($CashMemo->transportaion) && $CashMemo->transportaion > 0):
			                                    $transportaion = $CashMemo->transportaion;
			                                endif;
			                                endif;
			                                $grand_total = $new_total_price+$transportaion;
		                                	?>
		                                	<td>{{ number_format($grand_total, 2) }}</td>

											<?php $customer = App\Models\Customer::find($CashMemo->customer_id); ?>
											<td>{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
											<td>
												<a href="{{ route('CashMemo.edit', $CashMemo->id) }}" >
													<i class="fa fa-pencil-alt" style="color: #000"></i>
												</a>
												<a href="{{ route('CashMemo.show', $CashMemo->id) }}" target="_blank">
													<i class="fa fa-eye" style="color: #000"></i>
												</a>
												<form action="{{ route('CashMemo.destroy', $CashMemo->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Cash Memo?');" style="display: inline-block;background: transparent; border:none;">
							                   			<i class="fa fa-trash-alt"></i>
							                	</button>
								            	</form>
											</td>
											
											<td>
											   <a href="{{ route('duplicate-cashmemo',$CashMemo->id) }}">Duplicate</a>
											</td>
											<td>
												<a href="{{ route('ExportCashMemo', $CashMemo->id) }}">Export</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('viewButton');
        
        viewButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the current URL and its query parameters
            var currentUrl = window.location.href;
            var currentParams = new URLSearchParams(window.location.search);

            // Get the base URL for the 'invoicesByStatshow' route
            var baseUrl = "{{ route('cashmemobystatsshow') }}";

            // Append the current query parameters to the base URL
            var updatedUrl = baseUrl + '?' + currentParams.toString();

            // Update the link's href attribute
            viewButton.href = updatedUrl;

            // Navigate to the updated URL
            window.location.href = updatedUrl;
        });
    });
</script>

@endsection
