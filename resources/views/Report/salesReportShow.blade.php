<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
 <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

   <title>SalesReport</title>
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
<div class="container-fluid">
   <div class="row">
   <div class="col-sm-3">
    @php
        $startDate = null;
        $endDate = null;
    @endphp
    
    @foreach ($reports as $invoice)
        @if ($startDate === null)
            @php
                $startDate = $invoice->dated;
            @endphp
        @endif
        
        @php
            $endDate = $invoice->dated;
        @endphp
    @endforeach
    
    @if ($startDate !== null && $endDate !== null)
        <div class="date-box">
            <span>Date: {{ date("d-M-y", strtotime($endDate)) }} - {{ date("d-M-y", strtotime($startDate)) }}</span><br>
        </div>
    @endif
</div>
	<div class="col-sm-1"></div>
	<div class="col-sm-5"><h3>Sales Report Show</h3></div>
	<div class="col-sm-3"></div>
	
  </div>
</div>
<table>
    <thead>
        <tr>
            <th>S:no</th>
            <th>Branch</th>
            <th>Parties Name</th>
            <th>Contact Person</th>
            <th>Ntn</th>
			<th>Invoice Date</th>
			<th>Inv</th>
			<th>Tax Rate</th>
			<th>Sales Tax Deduct</th>
			<th>W.H Tax Deduct</th>
			<th>Total Tax Deducted</th>
			<th>Amount Recieved</th>
			<th>Payment Mode</th>
			<th>Payment Status</th>	
			<th>Ex.GST Value</th>
			<th>Tax Amount</th>
			<th>Inc.Tax Value</th>		
        </tr>
    </thead>
@if($reports)

    @foreach($reports as $invoice)
        <!-- Your existing calculations for each invoice -->
	@endforeach

    <!-- Outside of the loop, display the totals in tfoot -->
	<tfoot>
        <tr>
     		<th>Total</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<?php 
			$qty = 0;
			$unitPrice = 0;
			$totalPrice = 0; 
			$count=1;
			$subtotal = 0;
			if($invoice->products->count() > 0):
			foreach($invoice->products as $product):
				$qty       += $product->qty;
				$unitPrice += $product->unit_price;

				$subtotal = $qty*$unitPrice;
			endforeach;
			endif;

			if($invoice->other_products_name):
				$moreProductsNames  = explode('@&%$# ', $invoice->other_products_name);
				$moreProductsQty    = explode('@&%$# ', $invoice->other_products_qty);
				$moreProductsPrice  = explode('@&%$# ', $invoice->other_products_price);
				$moreProductsUnit   = explode('@&%$# ', $invoice->other_products_unit);
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

			if(isset($invoice->discount_percent) || isset($invoice->discount_fixed)):
				if(isset($invoice->discount_percent)):
					$discount_value     = ($Net_totalPrice / 100) * $invoice->discount_percent;
					$new_total_price    = $Net_totalPrice - $discount_value;
				elseif(isset($invoice->discount_fixed)):
					$discount_value = $invoice->discount_fixed;
					$new_total_price    = $Net_totalPrice - $discount_value;
				endif;
			endif; 

			$subtotal_before_tax = $new_total_price;

			// tax
			$tax_rate = $invoice->tax_rate/100;
			if($invoice->GST == 'on'):
				$tax = $subtotal_before_tax*$tax_rate;
			else:
				$tax = 0;
			endif;

			$subtotal_after_tax = $new_total_price+$tax;

			// transportaion
			if($invoice->transportaion != 0):
				$transportaion  = $invoice->transportaion;
			else:
				$transportaion  = 0;
			endif;

			$subtotal_after_tax = $new_total_price+$tax+$transportaion;
			?>
			
			<?php 
			if($invoice->payments):
				$whTax 		= $invoice->payments->sum('wh_tax');
				$salesTax 	= $invoice->payments->sum('sales_tax');
				$amountRecieved = $invoice->payments->sum('amount_recieved');

				$lastdata = $invoice->payments->first();
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
			<th></th>	
			<th></th>
			<?php 
			// ... (previous code)

			// Calculate totals
			$totalWithoutGST = 0;
			$totalTaxAmount = 0;
			$totalSalesAmount = 0;

			// Loop through each invoice
			foreach ($reports as $invoice) {
				// ... (previous code)

				// Accumulate values for each invoice
				$totalWithoutGST += getTotalInvoiceExTax($invoice->id);
				$totalTaxAmount += getInvoiceTaxAmount($invoice->id);
				$totalSalesAmount += getTotalInvoiceSales($invoice->id);
			}

			// Display the calculated totals
			echo '<td>' . number_format($totalWithoutGST, 2) . '</td>';
			echo '<td>' . number_format($totalTaxAmount, 2) . '</td>';
			echo '<td>' . number_format($totalSalesAmount, 2) . '</td>';
			?>						
        </tr>
    </tfoot>
@endif
	@if($reports)
    @foreach($reports as $invoice)
    <tbody>
        <tr>
		    <td>{{ $loop->iteration }}</td>
            <td>{{$invoice->user->branch }}</td>
			@if (isset($invoice->customer))
			<td>{{ $invoice->customer->company_name }}</td>
			<td>{{ $invoice->customer->customer_name }}</td>
			<td>{{ ($invoice->customer_ntn_no) ? $invoice->customer_ntn_no : 'N/A' }}</td>
             @endif
			<td>{{ date("d-M-y", strtotime($invoice->dated)) }}</td>
			<td>{{ $invoice->invoice_no }}</td> 			
			<?php 
			$qty = 0;
			$unitPrice = 0;
			$totalPrice = 0; 
			$count=1;
			$subtotal = 0;
			if($invoice->products->count() > 0):
			foreach($invoice->products as $product):
				$qty       += $product->qty;
				$unitPrice += $product->unit_price;

				$subtotal = $qty*$unitPrice;
			endforeach;
			endif;

			if($invoice->other_products_name):
				$moreProductsNames  = explode('@&%$# ', $invoice->other_products_name);
				$moreProductsQty    = explode('@&%$# ', $invoice->other_products_qty);
				$moreProductsPrice  = explode('@&%$# ', $invoice->other_products_price);
				$moreProductsUnit   = explode('@&%$# ', $invoice->other_products_unit);
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

			if(isset($invoice->discount_percent) || isset($invoice->discount_fixed)):
				if(isset($invoice->discount_percent)):
					$discount_value     = ($Net_totalPrice / 100) * $invoice->discount_percent;
					$new_total_price    = $Net_totalPrice - $discount_value;
				elseif(isset($invoice->discount_fixed)):
					$discount_value = $invoice->discount_fixed;
					$new_total_price    = $Net_totalPrice - $discount_value;
				endif;
			endif; 

			$subtotal_before_tax = $new_total_price;

			// tax
			$tax_rate = $invoice->tax_rate/100;
			if($invoice->GST == 'on'):
				$tax = $subtotal_before_tax*$tax_rate;
			else:
				$tax = 0;
			endif;

			$subtotal_after_tax = $new_total_price+$tax;

			// transportaion
			if($invoice->transportaion != 0):
				$transportaion  = $invoice->transportaion;
			else:
				$transportaion  = 0;
			endif;

			$subtotal_after_tax = $new_total_price+$tax+$transportaion;
			?>
			<td>{{ $invoice->tax_rate }}%</td>
			<td>{{ number_format($salesTax, 2) }}</td>
			<?php 
			if($invoice->payments):
				$whTax 		= $invoice->payments->sum('wh_tax');
				$salesTax 	= $invoice->payments->sum('sales_tax');
				$amountRecieved = $invoice->payments->sum('amount_recieved');

				$lastdata = $invoice->payments->first();
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
			<td>{{ number_format($totalTaxDeducted, 2) }}</td>
			<td>{{ number_format($amountRecieved, 2) }}</td>
			<td>{{ $payment_mode }}</td>
            <td>{{ $invoice->payment_status }}</td>
			<td>{{ number_format(getTotalInvoiceExTax($invoice->id), 2) }}</td> <!-- without gst -->
			<td>{{ number_format(getInvoiceTaxAmount($invoice->id), 2) }}</td>
			<td>{{ number_format(getTotalInvoiceSales($invoice->id), 2) }}</td>

	     </tr>
    </tbody>
    @endforeach
@endif
</table>
<br>
<br>
<br>
<div class="no-print" style="text-align:center!important">
	<a href="{{ route('downloadsalesreport1', ['data_key' => 'data_value']) }}" id="salesreport1" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i>Download With  Header & Footer</a>
	<a href="{{ route('downloadsalesreport', ['data_key' => 'data_value']) }}" id="salesreport" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-download"></i>Download With Out Header & Footer</a>

</div>
</body>
</html>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('salesreport');
        
        viewButton.addEventListener('click', function(e) {
            var currentUrl = window.location.href; // Get current URL
            var baseUrl = "{{ route('downloadsalesreport', ['' => '']) }}"; // Base URL without data_key
            
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
				window.location.href = dynamicUrl.replace('downloadsalesreport?=', 'downloadsalesreport');
            }
            // If no query parameters are present, the button click will have no effect
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('salesreport1');
        
        viewButton.addEventListener('click', function(e) {
            var currentUrl = window.location.href; // Get current URL
            var baseUrl = "{{ route('downloadsalesreport1', ['' => '']) }}"; // Base URL without data_key
            
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
				window.location.href = dynamicUrl.replace('downloadsalesreport1?=', 'downloadsalesreport1');
            }
            // If no query parameters are present, the button click will have no effect
        });
    });
</script>
