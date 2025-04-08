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

<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
                            <tr>
									<th style="min-width: 120px;">Date</th>
                                    <th>Customer Po No</th>
                                    <th>Customer NTN No</th>
                                    <th>Invoice Number</th>
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
                {{ number_format($pendingInvoices, 2) }}
            </th>
        @endif
		</tr>
						</tfoot>
							<tbody>
								@if($Invoices)
									@foreach($Invoices as $Invoice)
										<tr>
											<td>{{ date("d M, Y", strtotime($Invoice->dated)) }}</td>
                                            <td>{{$Invoice->customer_po_no}}</td>
                                            <td>{{$Invoice->customer_ntn_no}}</td>
                                            <td>{{ $Invoice->invoice_no }}</td>
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
</div>
  
</body>
</html>