<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

   <title>Invoice Show</title>
	<style type="text/css">
    	table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ddd;
    }
    
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        border-right: 1px solid #ddd; /* Add this line to create vertical borders */
    }
    
    th {
        background-color: #f2f2f2;
        border-right: 1px solid #ddd; /* Add this line to create vertical borders */
    }
    
    tfoot {
        background-color: #f2f2f2;
    }
    
	.btn-primary {
			color: #fff;
			background-color: #0275d8;
			border-color: #0275d8;
		}
		.btn-success {
			color: #fff;
			background-color: #5cb85c;
			border-color: #5cb85c;
		}
        @page {
              size: A4;
              margin: 0;
              margin-top: 0;
              margin-bottom: 0;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 250mm; /* height: 297mm; */
                }
            }
            #descriptionDiv p{
                margin-bottom: 0!important;
            }
			   h2 {
            text-align: center;
        }
		.date-box {
		border: 1px solid #ccc;
		background-color: #f0f0f0;
		padding: 3px;
		/* Add any other styling properties you want */
	}

</style>
</head>
<body>
<body>
	<div class="container-fluid">
   <div class="row">
   <div class="col-sm-3">
    @php
        $startDate = null;
        $endDate = null;
    @endphp
    
    @foreach ($CashMemos as $CashMemo)
        @if ($startDate === null)
            @php
                $startDate = $CashMemo->created_date;
            @endphp
        @endif
        
        @php
            $endDate = $CashMemo->created_date;
        @endphp
    @endforeach
    
    @if ($startDate !== null && $endDate !== null)
        <div class="date-box">
            <span>Date: {{ date("d-M-y", strtotime($endDate)) }} - {{ date("d-M-y", strtotime($startDate)) }}</span><br>
        </div>
    @endif
</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-5"><h3>Cash Memo Show</h3></div>
	<div class="col-sm-3"></div>
	
  </div>
</div>
<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
							    	<th>Ref No</th>
							    	<th>Our Quote No</th>
									<th>Date</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Total</th>

								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Total</th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									@if($CashMemos)
									@foreach($CashMemos as $CashMemo)

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
											@endforeach
		                                	<th>{{ number_format($cashSales, 2) }}</th>
											@endif
								</tr>
							</tfoot>
							<tbody>
								@if($CashMemos)
									@foreach($CashMemos as $CashMemo)
										<tr>
											<td>{{ $CashMemo->id }}</td>
											<td>{{ ($CashMemo->reference_no) ? $CashMemo->reference_no : 'N/A' }}</td>	
											<td>{{ date("d M, Y", strtotime($CashMemo->created_date)) }}</td>
											<?php $customer = App\Models\Customer::find($CashMemo->customer_id); ?>
											<td>{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
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

										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
</body>
</html>

<div class="no-print" style="text-align:center!important">
<a href="{{ route('downloadcashsaleshow1', ['data_key' => 'data_value']) }}" id="cashsaleshow1" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i>Download With  Header & Footer</a>
<a href="{{ route('downloadcashsaleshow', ['data_key' => 'data_value']) }}" id="cashsaleshow" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-download"></i>Download Without  Header & Footer</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('cashsaleshow1');
        
        viewButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the current URL and its query parameters
            var currentUrl = window.location.href;
            var currentParams = new URLSearchParams(window.location.search);

            // Get the base URL for the 'invoicesByStatshow' route
            var baseUrl = "{{ route('downloadcashsaleshow1') }}";

            // Append the current query parameters to the base URL
            var updatedUrl = baseUrl + '?' + currentParams.toString();

            // Update the link's href attribute
            viewButton.href = updatedUrl;

            // Navigate to the updated URL
            window.location.href = updatedUrl;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('cashsaleshow');
        
        viewButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the current URL and its query parameters
            var currentUrl = window.location.href;
            var currentParams = new URLSearchParams(window.location.search);

            // Get the base URL for the 'invoicesByStatshow' route
            var baseUrl = "{{ route('downloadcashsaleshow') }}";

            // Append the current query parameters to the base URL
            var updatedUrl = baseUrl + '?' + currentParams.toString();

            // Update the link's href attribute
            viewButton.href = updatedUrl;

            // Navigate to the updated URL
            window.location.href = updatedUrl;
        });
    });
</script>