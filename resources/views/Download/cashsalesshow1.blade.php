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

</html>
<div class="header">
  <img src="{{ asset('assets/header.jpg') }}" style="width: 55mm !important; height: 45mm !important;" />
</div>
    <!--style="width: 210mm!important;" -->
    <div class="footer"><img src="{{ asset('assets/footer.jpg') }}" style="width: 210mm!important;" /></div> <!-- height:170px;-- >

