@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Sales Report</h1>
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
							<form action="{{ route('salesReport') }}" method="GET" class="form-horizontal">
							    <input type="hidden" class="type" value="{{ $type }}">
								<div class="form-group row">
									<div class="col-sm-2">
										<div class="input-group">
											<input type="date" class="form-control" name="from_date" id="from_date" style="height:40px!important;" value="{{ request('from_date') }}">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group">
											<input type="date" class="form-control" name="to_date" id="to_date" style="height:40px!important;" value="{{ request('to_date') }}">
										</div>
									</div>
									<div class="form-group col-md-0">
										<input type="hidden" class="form-control select2_1">
									</div>
									<div class="col-sm-2">
										<div class="input-group">
											<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light" style="height:40px!important;">Search</button>
										</div>
									</div>
									<div class="col-sm-1">
										<a href="{{ route('salesReport', ['from_date' => $from_date, 'to_date' => $to_date,'type' => 'clear1']) }}" class="btn btn-danger btn-sm">Pending</a>
									</div>
									<div class="col-sm-1">
										<a href="{{ route('salesReport', ['from_date' => $from_date, 'to_date' => $to_date,'type' => 'clear']) }}" class="btn btn-primary btn-sm">Clear</a>
									</div>
									<div class="col-sm-2">
										<a href="{{ route('SalesReportShow', ['data_key' => 'data_value']) }}" id="viewButton" class="btn btn-primary btn-sm">View</a>
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
								<th>Sr.</th>
								<th>Branch</th>
								<th>Parties Name</th> <!-- company name -->
								<th>Contact Person</th>
								<th>Contact Number</th>
								<th>Email</th>
								<th>Details</th> <!-- view invoice button -->
								<th>NTN</th>
								<th>Invoice Date</th>
								<th>Inv #</th>
								<th>Ex. GST Value</th>
								<th>Tax Rate</th> <!-- GST will be input in invoice & quote -->
								<th>Tax Amount</th>
								<th>Inc. Tax Value</th>
								<th>W.H Tax Deduct</th>
								<th>Sales Tax Deduct</th>
								<th>Total Tax Deducted</th>
								<th>Amount Recieved</th>
								<th>Payment Mode</th>
								<th>Excel</th>
								<th>Payment Status</th>
								<th>Recieve Payment</th>
								<th>Payments Details</th>
							</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Sr.</th>
									<th>Branch</th>
									<th>Parties Name</th> <!-- company name -->
									<th>Contact Person</th>
									<th>Contact Number</th>
									<th>Email</th>
									<th>Details</th> <!-- view invoice button -->
									<th>NTN</th>
									<th>Invoice Date</th>
									<th>Inv #</th>
									<th>Ex. GST Value</th>
									<th>Tax Rate</th> <!-- GST will be input in invoice & quote -->
									<th>Tax Amount</th>
									<th>Inc. Tax Value</th>
									<th>W.H Tax Deduct</th>
									<th>Sales Tax Deduct</th>
									<th>Total Tax Deducted</th>
									<th>Amount Recieved</th>
									<th>Payment Mode</th>
									<th>Excel</th>
									<th>Payment Status</th>
									<th>Recieve Payment</th>
									<th>Payments Details</th>
								</tr>
							</tfoot>
							<tbody>
								@if($reports)
									@foreach($reports as $Invoice)
									@if($type == 'pending')
									    @if($Invoice->payment_status == "Cleared")
										<tr>
										@if (isset($Invoice->customer))
										<td>{{ $loop->iteration }}</td>
											<td>{{ $Invoice->branch }}</td>
											<td>{{ $Invoice->customer->company_name }}</td>
											<td>{{ $Invoice->customer->customer_name }}</td>
											<td>{{ ($Invoice->customer->phone_no) ? $Invoice->customer->phone_no : 'N/A' }}</td>
											<td>{{ ($Invoice->customer->email) ? $Invoice->customer->email : 'N/A' }}</td>

											<td>
												<a href="{{ route('Invoice.show',$Invoice->id) }}" target="_blank" class="btn btn-xs btn-info">view</a>
											</td> 
											<td>{{ ($Invoice->customer_ntn_no) ? $Invoice->customer_ntn_no : 'N/A' }}</td>
											<td>{{ date("d-M-y", strtotime($Invoice->dated)) }}</td>
											<td>{{ $Invoice->invoice_no }}</td> 
                                        @endif
											<?php 
											$qty = 0;
											$unitPrice = 0;
											$totalPrice = 0; 
											$count=1;
											$subtotal = 0;
											if($Invoice->products->count() > 0):
											foreach($Invoice->products as $product):
												$qty       += $product->qty;
												$unitPrice += $product->unit_price;

												$subtotal = $qty*$unitPrice;
											endforeach;
											endif;

											if($Invoice->other_products_name):
			                                    $moreProductsNames  = explode('@&%$# ', $Invoice->other_products_name);
			                                    $moreProductsQty    = explode('@&%$# ', $Invoice->other_products_qty);
			                                    $moreProductsPrice  = explode('@&%$# ', $Invoice->other_products_price);
			                                    $moreProductsUnit   = explode('@&%$# ', $Invoice->other_products_unit);
			                                    $count2 = 0;
			                                    foreach($moreProductsNames as $moreP):
			                                        if(!empty($moreP)):
			                                    	$qty 	= $moreProductsQty[$count2]; 
			                                    	$price 	= $moreProductsPrice[$count2];
			                                    	$totalPrice += $qty*$price; 
			                                    	$count++; 
			                                    	$count2++;
			                                    	endif;
	                                    		endforeach;
                                			endif;

                                			$Net_totalPrice = $totalPrice+$subtotal;
					                        $new_total_price = $Net_totalPrice;

					                        if(isset($Invoice->discount_percent) || isset($Invoice->discount_fixed)):
					                        	if(isset($Invoice->discount_percent)):
			    			                        $discount_value     = ($Net_totalPrice / 100) * $Invoice->discount_percent;
			                                        $new_total_price    = $Net_totalPrice - $discount_value;
			                                    elseif(isset($Invoice->discount_fixed)):
			                                        $discount_value = $Invoice->discount_fixed;
			                                        $new_total_price    = $Net_totalPrice - $discount_value;
			                                    endif;
					                        endif; 

		                                    $subtotal_before_tax = $new_total_price;

		                                    // tax
					                        $tax_rate = $Invoice->tax_rate/100;
					                        if($Invoice->GST == 'on'):
		                                        $tax = $subtotal_before_tax*$tax_rate;
		                                    else:
		                                        $tax = 0;
		                                    endif;
		                                    
		                                    $subtotal_after_tax = $new_total_price+$tax;

		                                    // transportaion
					                        if($Invoice->transportaion != 0):
		                                    	$transportaion  = $Invoice->transportaion;
		                                    else:
		                                    	$transportaion  = 0;
		                                    endif;

		                                    $subtotal_after_tax = $new_total_price+$tax+$transportaion;
											?>
											<td>{{ number_format($subtotal_before_tax, 2) }}</td> <!-- without gst -->
											<td>{{ $Invoice->tax_rate }}%</td>
											<td>{{ number_format($tax, 2) }}</td>
											<td>{{ number_format(getTotalInvoiceSales($Invoice->id), 2) }}</td>
											<?php 
											if($Invoice->payments):
												$whTax 		= $Invoice->payments->sum('wh_tax');
												$salesTax 	= $Invoice->payments->sum('sales_tax');
												$amountRecieved = $Invoice->payments->sum('amount_recieved');

												$lastdata = $Invoice->payments->first();
												if(isset($lastdata)):
													$payment_mode 	= $lastdata->payment_mode;
												else:
													$payment_mode = '';
												endif;
											else:
												$whTax 		= 0;
												$salesTax 	= 0;
												$amountRecieved = 0;
												$payment_mode = '';
											endif;
											$totalTaxDeducted = $whTax+$salesTax;
											?>
											<td>{{ number_format($whTax, 2) }}</td>
											<td>{{ number_format($salesTax, 2) }}</td>
											<td>{{ number_format($totalTaxDeducted, 2) }}</td>
											<td>{{ number_format($amountRecieved, 2) }}</td>
											<td>{{ $payment_mode }}</td>
											<td>
												<a href="{{ route('SalesreportExport', $Invoice->id) }}">Export</a>
											</td>
											<td>{{ $Invoice->payment_status }}</td>
											<td>
												@if($Invoice->payment_status == "Cleared")
												<button class="btn btn-xs btn-warning">{{ $Invoice->payment_status }}</button>
												@else
												<a href="{{ route('invoice.recieve.payment', $Invoice->id) }}" class="btn btn-xs btn-success">Recieve</a>
												@endif
											</td>
											<td>
												<a href="{{ route('invoice.payments', $Invoice->id) }}" class="btn btn-xs btn-info">View</a>
											</td>
										</tr>
										@endif
									@else
									    <tr>
										@if (isset($Invoice->customer))
										<td>{{ $loop->iteration }}</td>
											<td>{{ $Invoice->branch }}</td>
											<td>{{ $Invoice->customer->company_name }}</td>
											<td>{{ $Invoice->customer->customer_name }}</td>
											<td>{{ ($Invoice->customer->phone_no) ? $Invoice->customer->phone_no : 'N/A' }}</td>
											<td>{{ ($Invoice->customer->email) ? $Invoice->customer->email : 'N/A' }}</td>

											<td>
												<a href="{{ route('Invoice.show',$Invoice->id) }}" target="_blank" class="btn btn-xs btn-info">view</a>
											</td> 
											<td>{{ ($Invoice->customer_ntn_no) ? $Invoice->customer_ntn_no : 'N/A' }}</td>
											<td>{{ date("d-M-y", strtotime($Invoice->dated)) }}</td>
											<td>{{ $Invoice->invoice_no }}</td> 
											<td>{{ number_format(getTotalInvoiceExTax($Invoice->id), 2) }}</td> <!-- without gst -->
											<td>{{ $Invoice->tax_rate }}%</td>
											<td>{{ number_format(getInvoiceTaxAmount($Invoice->id), 2) }}</td>
											<td>{{ number_format(getTotalInvoiceSales($Invoice->id), 2) }}</td>
 										@endif
											<?php 
											if($Invoice->payments):
												$whTax 		= $Invoice->payments->sum('wh_tax');
												$salesTax 	= $Invoice->payments->sum('sales_tax');
												$amountRecieved = $Invoice->payments->sum('amount_recieved');

												$lastdata = $Invoice->payments->first();
												if(isset($lastdata)):
													$payment_mode 	= $lastdata->payment_mode;
												else:
													$payment_mode = '';
												endif;
											else:
												$whTax 		= 0;
												$salesTax 	= 0;
												$amountRecieved = 0;
												$payment_mode = '';
											endif;
											$totalTaxDeducted = $whTax+$salesTax;
											?>
											<td>{{ number_format($whTax, 2) }}</td>
											<td>{{ number_format($salesTax, 2) }}</td>
											<td>{{ number_format($totalTaxDeducted, 2) }}</td>
											<td>{{ number_format($amountRecieved, 2) }}</td>
											<td>{{ $payment_mode }}</td>
											<td>
												<a href="{{ route('SalesreportExport', $Invoice->id) }}">Export</a>
											</td>
											<td>{{ $Invoice->payment_status }}</td>
											<td>
												@if($Invoice->payment_status == "Cleared")
												<button class="btn btn-xs btn-warning">{{ $Invoice->payment_status }}</button>
												@else
												<a href="{{ route('invoice.recieve.payment', $Invoice->id) }}" class="btn btn-xs btn-success">Recieve</a>
												@endif
											</td>
											<td>
												<a href="{{ route('invoice.payments', $Invoice->id) }}" class="btn btn-xs btn-info">View</a>
											</td>
										</tr>
									@endif
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('viewButton');
        
        viewButton.addEventListener('click', function(e) {
            var currentUrl = window.location.href; // Get current URL
            var baseUrl = "{{ route('SalesReportShow', ['' => '']) }}"; // Base URL without data_key
            
            // Extract query parameters from current URL
            var urlParts = currentUrl.split('?');
            var queryParameters = urlParts[1]; // Get query parameter portion
            
            // Check if query parameters are present
            if (queryParameters) {
                e.preventDefault(); // Prevent default link behavior
                
                // Split query parameters into individual key-value pairs
                var queryParamsArray = queryParameters.split('&');
                
                // Initialize variables to store decoded values
                var fromDate = "";
                var toDate = "";
                var type = ""; // Added type variable
                
                // Loop through the query parameters to find from_date, to_date, and type
                for (var i = 0; i < queryParamsArray.length; i++) {
                    var keyValuePair = queryParamsArray[i].split('=');
                    var key = keyValuePair[0];
                    var value = decodeURIComponent(keyValuePair[1]);
                    
                    if (key === "from_date") {
                        fromDate = value;
                    } else if (key === "to_date") {
                        toDate = value;
                    } else if (key === "type") {
                        type = value; // Update type variable
                    }
                }
                
                // Construct dynamic URL with decoded values if available
                var dynamicUrl = baseUrl + "?"; // Start with "?"
                if (fromDate !== "" && toDate !== "") {
                    dynamicUrl += "from_date=" + fromDate + "&to_date=" + toDate + "&";
                }
                
                // Append additional query parameters based on type
                if (type === "clear") {
                    dynamicUrl += "type=clear";
                } else if (type === "clear1") {
                    dynamicUrl += "type=clear1";
                }
   
                // Update link's href attribute
                viewButton.href = dynamicUrl;

                // Navigate to the dynamic URL
                window.location.href = dynamicUrl.replace('SalesReportShow?=', 'SalesReportShow');
            }
            // If no query parameters are present, the button click will have no effect
        });
    });
</script>

@endsection