<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>
        </title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd; /* Add this line for vertical lines */
			
        }

		td {
		background-color: #f5f9f5; /* Lighter shade color */	}
        
        th {
			background-color: #d6ecd6; 
        }        
        
        tfoot {
            background-color: #f2f2f2;
        }
        h2 {
            margin-left: 500px;
        }
	    
        .header, .header-space
            {
              height: 134px!important;
              /*height: 162px!important;*/
            }

        .footer, .footer-space
            {
                height: 180px!important;
              /*height: 200px!important;*/
            }

        .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            }
        .footer {
                position: fixed;
                bottom: 0; 
                left: 0;
                right: 0;
                /* bottom:-30px; */
            }
        html, body {
                width: 210mm;
                height: 200mm; /*height: 297mm;*/
                font-size:12px!important;
              }
        @page {
                size: A4 landscape;
                margin: 10mm; /* Add some margin to all sides */ 
                margin-top:30mm;
                margin-bottom:30mm;
               }

        @media print {
                html, body {
                    width: 277mm; /* Adjust width to account for margins */
                    height: 190mm; /* Adjust height to account for margins */
                    margin: 0; /* Reset margins for the content inside body */
                }
            }
        p{
            margin-top:0px!important;
            margin-bottom:0px!important;
            padding-top:0px!important;
            padding-bottom:0px!important;
        }
        td {
            margin-top:0px!important;
            margin-bottom:0px!important;
            padding-top:0px!important;
            padding-bottom:0px!important;
        }
		.date-box {
		border: 1px solid #ccc;
		background-color: #f0f0f0;
		padding: 3px;
		/* Add any other styling properties you want */
	}
    </style>
    </head>
<body onload="print()">

<div class="container-fluid">
   <div class="row">
   <div class="col-sm-4">
    @php
        $startDate = null;
        $endDate = null;

        $urlDate = request('date'); // Get the date from the URL parameter
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

    @if ($urlDate)
        <div class="date-box">
            <span>Date: {{ date("d-M-y", strtotime($urlDate)) }}</span><br>
        </div>
    @elseif ($startDate !== null && $endDate !== null)
        <div class="date-box">
            <span>Date Range: {{ date("d-M-y", strtotime($endDate)) }} - {{ date("d-M-y", strtotime($startDate)) }}</span><br>
        </div>
    @endif
</div>

<div class="col-sm-2"></div>
	<div class="col-sm-4"><h3>Sales Report Show</h3></div>
	<div class="col-sm-2"></div>	
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
			?>
			<th style="text-align: right">{{number_format($totalWithoutGST, 2)}}</th>
			<th style="text-align: right">{{number_format($totalTaxAmount, 2)}}</th>
			<th style="text-align: right">{{number_format($totalSalesAmount, 2)}}</th>
        </tr>
    </tfoot>
@endif
	@if($reports)
    @foreach($reports as $invoice)
    <tbody>
        <tr>
		    <td>{{ $loop->iteration }}</td>
            <td>{{$invoice->user->branch }}</td>
			<td>{{ $invoice->customer->company_name }}</td>
			<td>{{ $invoice->customer->customer_name }}</td>
			<td>{{ ($invoice->customer_ntn_no) ? $invoice->customer_ntn_no : 'N/A' }}</td>
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
			<td style="text-align: right">{{ number_format($salesTax, 2) }}</td>
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
            <td style="text-align: right">{{ number_format($whTax, 2) }}</td>
			<td style="text-align: right">{{ number_format($totalTaxDeducted, 2) }}</td>
			<td style="text-align: right">{{ number_format($amountRecieved, 2) }}</td>
			<td>{{ $payment_mode }}</td>
            <td>{{ $invoice->payment_status }}</td>
			<td style="text-align: right">{{ number_format(getTotalInvoiceExTax($invoice->id), 2) }}</td> <!-- without gst -->
			<td style="text-align: right">{{ number_format(getInvoiceTaxAmount($invoice->id), 2) }}</td>
			<td style="text-align: right">{{ number_format(getTotalInvoiceSales($invoice->id), 2) }}</td>

	     </tr>
    </tbody>
    @endforeach
@endif
</table>

</body>
</html>
