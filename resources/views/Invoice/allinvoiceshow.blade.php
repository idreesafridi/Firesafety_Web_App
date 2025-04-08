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
	<div class="container-fluid">
   <div class="row">
   <div class="col-sm-3">
    @php
        $startDate = null;
        $endDate = null;
    @endphp
    
    @foreach ($Invoices as $invoice)
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
		        <div class="col-sm-5"><h3>Invoices Show</h3></div>
		        <div class="col-sm-3"></div>
		    </div>
		</div>
        <table>
           <thead>
				<tr>
     				<th>Invoice Number</th>
					<th>Date</th>
                    <th>Customer Po No</th>
                    <th>Customer NTN No</th>
					<th>Customer Name</th>
					<th>Customer Phone</th>
					<th>Company Name</th>
					<th>Payment Status</th>
					<th>Total Amount</th>	
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
        <th></th>
        <th></th>
        @if($Invoices)
            <?php
            $totalAmount = 0; // Initialize total amount outside the loop
            ?>
            @foreach($Invoices as $Invoice)
                <!-- Your existing calculations for each invoice -->
                <?php
                $InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get();
                $qty = 0;
                $unitPrice = 0;
                $totalPrice1 = 0;

                foreach ($InvoiceProducts as $InvoiceProduct) {
                    $qty += $InvoiceProduct->qty;
                    $unitPrice = $InvoiceProduct->unit_price;
                    $totalPrice1 += $InvoiceProduct->qty * $unitPrice;
                }

                $totalPrice2 = 0;
                if ($Invoice->other_products_name) {
                    $moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
                    $moreProductsQty = explode('@&%$# ', $Invoice->other_products_qty);
                    $moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
                    $moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
                    $count2 = 0;

                    foreach ($moreProductsNames as $moreP) {
                        $qty = $moreProductsQty[$count2];
                        $price = $moreProductsPrice[$count2];

                        if (is_numeric($qty) && is_numeric($price)) {
                            $totalPrice2 += $qty * $price;
                        }

                        $count2++;
                    }
                }

                $totalPrice = $totalPrice1 + $totalPrice2;

                // GST
                if ($Invoice->GST == 'on') {
                    $tax_rate = $Invoice->tax_rate / 100;
                    $tax = $totalPrice * $tax_rate;
                } else {
                    $tax = 0;
                }

                $Net_totalPrice = $totalPrice + $tax;

                if (isset($Invoice->discount_percent)) {
                    $discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
                } elseif (isset($Quote->discount_fixed)) {
                    $discount_value = $Invoice->discount_fixed;
                } else {
                    $discount_value = 0;
                }

                // Transport
                if (isset($Invoice->transportation)) {
                    $transportation = $Invoice->transportation;
                } else {
                    $transportation = 0;
                }

                $netTotal = $Net_totalPrice + $transportation - $discount_value;
                $totalAmount += $netTotal; // Add the netTotal to the totalAmount
                ?>
            @endforeach
            <th>
                {{ number_format($allinvoicesSalesAmount, 2) }}
            </th>
        @endif
    </tr>
</tfoot>			
            <tbody>
			@if($Invoices)
				@foreach($Invoices as $Invoice)
					<tr>
     					<td>{{ $Invoice->invoice_no }}</td>
						<td>{{ date("d M, Y", strtotime($Invoice->dated)) }}</td>
                        <td>{{$Invoice->customer_po_no}}</td>
                        <td>{{$Invoice->customer_ntn_no}}</td>
						<?php $customer = App\Models\Customer::find($Invoice->customer_id); ?>
						<td>{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
						<td>{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
						<td>{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
						<td>{{$Invoice->payment_status}}</td>
						<?php 
						$InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
						$qty = 0;
						$unitPrice = 0;
						$totalPrice1 = 0;
						foreach($InvoiceProducts as $InvoiceProduct):
							$qty       += $InvoiceProduct->qty;
							$unitPrice = $InvoiceProduct->unit_price;
							$totalPrice1 += $InvoiceProduct->qty*$unitPrice;
						endforeach;

						$totalPrice2 = 0;
						if($Invoice->other_products_name):
							$moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
							$moreProductsQty   = explode('@&%$# ', $Invoice->other_products_qty);
							$moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
							$moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
							$count2 = 0;
							foreach($moreProductsNames as $moreP):
								$qty 			=  $moreProductsQty[$count2]; 
								$price 			=  $moreProductsPrice[$count2];
								if(is_numeric($qty) && is_numeric($price)):
								$totalPrice2 	+= $qty*$price; 
								endif;
								$count2++; 
							endforeach;
						endif;

						$totalPrice = $totalPrice1+$totalPrice2; 
						
						// GST
						if($Invoice->GST == 'on'):
							$tax_rate = $Invoice->tax_rate/100;
							$tax = $totalPrice*$tax_rate;
						else:
							$tax = 0;
						endif;

						$Net_totalPrice = $totalPrice+$tax;
						
						if(isset($Invoice->discount_percent)):
							$discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
						elseif(isset($Quote->discount_fixed)):
							$discount_value    = $Invoice->discount_fixed;
						else:
							$discount_value = 0;
						endif;
						

						// Transport
						if(isset($Invoice->transportaion)):
							$transportaion = $Invoice->transportaion;
						else:
							$transportaion = 0;
						endif;
						
						$netTotal  = $Net_totalPrice+$transportaion-$discount_value;
						?>
						<td> 
							{{ number_format($netTotal, 2) }}
						</td>
					</tr>
				@endforeach
			@endif
		</tbody> 
    </table>
</body>
</html>

<div class="no-print" style="text-align:center!important">
<a href="{{ route('downloadallinvoice1', ['data_key' => 'data_value']) }}" id="allinvoiceshow1" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i>Download With  Header & Footer</a>
<a href="{{ route('downloadallinvoice', ['data_key' => 'data_value']) }}" id="allinvoiceshow" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-download"></i>Download Without  Header & Footer</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('allinvoiceshow1');
        
        viewButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the current URL and its query parameters
            var currentUrl = window.location.href;
            var currentParams = new URLSearchParams(window.location.search);

            // Get the base URL for the 'invoicesByStatshow' route
            var baseUrl = "{{ route('downloadallinvoice1') }}";

            // Append the current query parameters to the base URL
            var updatedUrl = baseUrl + '?' + currentParams.toString();

            // Update the link's href attribute
            viewButton.href = updatedUrl;

            // Navigate to the updated URL
            window.location.href = updatedUrl;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('allinvoiceshow');
        
        viewButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the current URL and its query parameters
            var currentUrl = window.location.href;
            var currentParams = new URLSearchParams(window.location.search);

            // Get the base URL for the 'invoicesByStatshow' route
            var baseUrl = "{{ route('downloadallinvoice') }}";

            // Append the current query parameters to the base URL
            var updatedUrl = baseUrl + '?' + currentParams.toString();

            // Update the link's href attribute
            viewButton.href = updatedUrl;

            // Navigate to the updated URL
            window.location.href = updatedUrl;
        });
    });
</script>