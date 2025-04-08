<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>{{$Invoice->invoice_no}}
        @if(isset($InvoiceCustomer))
        -{{$InvoiceCustomer->company_name}} @if(isset($InvoiceCustomer->city))-{{ $InvoiceCustomer->city }} @endif
        @endif</title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
                /*height: 162px!important;*/
                height: 134px!important;
            }

            .footer, .footer-space
            {
                height: 180px!important;
                /*height: 217px!important;*/
            }

            .header {
                position: fixed;
                top: 0;
                left: 10;
                right: 10;
            }
            .footer {
                position: fixed;
                bottom: 0; 
                left: 10;
                right: 10;
            }
            html, body {
                width: 210mm;
                height: 250mm; /* height: 297mm; */
                font-size:12px!important;
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
        </style>
    </head>
<body>

    <table style="width:106%!important">
        <thead>
            <tr>
                <td>
                    <div class="header-space">&nbsp;</div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="content" style="padding-left: 50px!important; padding-right: 50px!important">
                        <div class="row">
                            <div class="col-12">
                                <table style="white-space: nowrap;font-weight: bolder;font-size: 16px; width:100%!important">
		                            <tr>
		                                <th colspan="5" style="color: red;text-align: center;border-bottom: 2em;background: #fff;font-weight: bolder;font-size:15px!important;">INVOICE</th>
		                            </tr>
		                            
		                            <tr>
		                                <th style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:bolder; width:10%;font-size:12px!important;font-weight:normal">M/S</th>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ ($InvoiceCustomer) ? $InvoiceCustomer->company_name : 'N/A' }}</td>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Invoice NO</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;padding-left:0px;font-size:12px!important;">&nbsp;{{ $Invoice->invoice_no }}</td>

		                            </tr>
		                            
		                            <tr>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Address</td>
			                            <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important; max-width: 391px;">
			                                @if(isset($InvoiceCustomer))
			                                &nbsp;{{ $InvoiceCustomer->address }} @if(isset($InvoiceCustomer->city)),{{ $InvoiceCustomer->city }} @endif
			                                @endif
			                            </td>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Date</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">{{ date("d/m/Y", strtotime($Invoice->dated)) }}</td>

		                            </tr>
		                            
		                            <tr>
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Email</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important;">{{ ($InvoiceCustomer) ? $InvoiceCustomer->email : 'N/A' }}</td>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">From:</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">
		                                {{ Auth::User()->username }}
		                            	</td>
		                            </tr>
		                            
		                            <tr>
			                             <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Customer NTN</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important;">&nbsp;{{ $Invoice->customer_ntn_no }}</td>
		                            	
		                            	<td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Desig</td>
			                            <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">
		                                	{{ Auth::User()->custom_designation }}
		                                </td>
		                            </tr>
		                            
		                            <tr>
		                               <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Att:</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ ($InvoiceCustomer) ? $InvoiceCustomer->customer_name : 'N/A' }}</td>
		                                
		                            	
		                            	<td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Our NTN #</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">4738498-3</td>
		                            </tr>
		                            
		                            <tr>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Customer PO #</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ $Invoice->customer_po_no }}</td>
		                                
		                                <td style="padding: 10px;background: #fff;padding: 2px;font-size:12px!important;border: 1px solid #000;font-weight: normal; width:10%;">Our GST</td>
		                                <td colspan="2" style="padding: 10px;background: #fff; border: 1px solid #000;padding: 2px;font-size:12px!important;font-weight: normal; width:30%;">4738498-3</td>
		                            </tr>
		                            <?php
        							if(isset($Invoice->delievery_challan_no)):
        							    $delievery_challan_no = $Invoice->delievery_challan_no;
        							else:
                                        $quote_id           = $Invoice->quote_id;
                                        if(isset($quote_id)):
            	                            $delievery_challan  = App\Models\Challan::where('reference_no', $quote_id)->first();
            	                            if(isset($delievery_challan)):
            	                                $delievery_challan_no = $delievery_challan->id;
            	                            else:
            	                                $delievery_challan_no = '';
            	                            endif;
                                        else:
                                            $delievery_challan_no = '';
                                        endif;
                                    endif;
        							?>
		                            <tr>
                                        <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Delivery Challan #</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ $delievery_challan_no }}</td>
		                                
		                                <td style="padding: 10px;background: #fff;padding: 2px;font-size:12px!important;border: 1px solid #000;font-weight: normal; width:10%;">Our Quote No.</td>
		                                <td style="padding: 10px;background: #fff; border: 1px solid #000;padding: 2px;font-size:12px!important;font-weight: normal; width:30%;">{{ $Invoice->quote_id }}</td>
		                            </tr>
			                    </table>
                            </div>
                        </div>
                        <br>
                        
                            <table border="0" cellspacing="0" cellpadding="0" style="width:100%!important">
                                <thead>
                                    <tr>
                                        <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">S.No</th>
                                        <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px;font-size:12px!important;" class="text-left">Description</th>
                                        <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;font-size:12px!important; width:7%!important" class="text-center">Size</th>
                                        <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">Qty</th>
                                        <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">Unit</th>
                                        <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:10%!important;font-size:12px!important;" class="text-center">Unit Price</th>
                                        <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                               <?php 
$totalPrice = 0; 
$count = 1; 
$new_total_price = 0; 
?>
@if($InvoiceProducts)
    <?php $prevDescription = null; ?>
    @foreach($InvoiceProducts as $Product)
    @if(isset($Product->heading) && !empty($Product->heading))
    <tr>
        <th colspan="10" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: left!important;width:5%!important">{{ $Product->heading }}</th>
    </tr>
    @endif
        <tr>
            <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{$count}}</td>
            <?php $productData = App\Models\Product::find($Product->product_id); ?>
                <td style="background: #fff; border: 1px solid #000;padding: 2px;;font-size:12px!important;" id="descriptionDiv">{!! $productData->description !!}</td>
            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;;font-size:12px!important;width: 5%!important;" class="text-center">
                {{ $Product->productCapacity }}
            </td>
            
            <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{ number_format($Product->qty,2) }}</td>

            <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{ $productData->unit }}</td>

            <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 10%!important;font-size:12px!important;" class="text-center">{{ number_format($Product->unit_price,2) }}</td>

            <?php 
            $price1 = $Product->unit_price;
            $totalPrice += $Product->qty * $price1;
            ?>
            <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;;font-size:12px!important;" class="text-center">
                {{ number_format($price1 * $Product->qty, 2) }}
            </td>
        </tr>
        <?php $count++; $prevDescription = $productData->description; ?>
    @endforeach
@endif


                                @if($Invoice->other_products_name)
                                    <?php 
                                    $moreProductsNames  = explode('@&%$# ', $Invoice->other_products_name);
                                    $moreProductsQty    = explode('@&%$# ', $Invoice->other_products_qty);
                                    $moreProductsPrice  = explode('@&%$# ', $Invoice->other_products_price);
                                    $moreProductsUnit   = explode('@&%$# ', $Invoice->other_products_unit);
                                    $moreProductsSize   = explode('@&%$# ', $Invoice->other_products_size);
                                    $count2 = 0;
                                    ?>
                                    @foreach($moreProductsNames as $moreP)
                                    @if(!empty($moreP))
                                    <tr>
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:5%!important;font-size:12px!important;"  class="text-center">{{$count}}</td>
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-size:12px!important;">{{$moreP}}</td>
                                        <?php $qty =$moreProductsQty[$count2]; $price = $moreProductsPrice[$count2];?>
                                        
                                        
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;text-align:right;padding: 2px;;font-size:12px!important;" class="text-center">
                                        {{ (isset($moreProductsSize[$count2])) ? $moreProductsSize[$count2] : '1.00' }}
                                        </td>
                                        
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;;font-size:12px!important;"  class="qty">{{ number_format($moreProductsQty[$count2],2) }}</td>
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;;font-size:12px!important;" class="text-right">{{ ($moreProductsUnit[$count2]) ? $moreProductsUnit[$count2] : '' }}</td>
                                        
                                        
                                        
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;;font-size:12px!important;" class="text-right">{{ number_format($moreProductsPrice[$count2],2)  }}</td>

                                        

                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;text-align:right;padding: 2px;;font-size:12px!important;" class="text-right">
                                        {{ number_format($qty*$price,2)  }}
                                        </td>
                                    </tr>
                                    <?php $totalPrice += $qty*$price; $count++; $count2++; ?>
                                    @endif
                                    @endforeach
                                @endif  

                                
                                @if($Invoice->tax_rate > 0 || $Invoice->transportaion > 0 || $Invoice->discount_percent > 0 || $Invoice->discount_fixed > 0 || $Product->gst_product > 0)
                                <tr>
                                <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                    <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" style="text-align: right;">Subtotal</th>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($totalPrice, 2) }}</td>
                                </tr>
                                @endif 
                                
                                <?php
		                        $Net_totalPrice = $totalPrice;
		                        //$Net_Tranporation = $Invoice->transportaion;
		                        $new_total_price = $Net_totalPrice;
		                        ?>
		                        
		                        @if($Invoice->discount_percent > 0 || $Invoice->discount_fixed > 0)
			                        <?php
			                        if(isset($Invoice->discount_percent)):
    			                        $discount_value     = ($Net_totalPrice / 100) * $Invoice->discount_percent;
                                        $new_total_price    = $Net_totalPrice - $discount_value;
                                    elseif(isset($Invoice->discount_fixed)):
                                        $discount_value = $Invoice->discount_fixed;
                                        $new_total_price    = $Net_totalPrice - $discount_value;
                                    endif;
			                        ?>
			                        <tr>
                                        <td colspan="4"></td>
                                        <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" style="text-align: right;">Discount ({{$Invoice->discount_percent}}%)</th>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($discount_value,2) }}</td>
                                    </tr>
                                    
                                    @if($Invoice->tax_rate > 0)
                                    <tr>
                                    <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                        <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" style="text-align: right;">Total</th>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($new_total_price, 2) }}</td>
                                    </tr>
                                    @endif
		                        @endif
                                <?php 
                                if($Invoice->GST == 'on'):
                                    $tax_rate = $Invoice->tax_rate/100;
                                    $GST = $new_total_price*$tax_rate; 
                                else:
                                    $GST = 0;
                                    $tax = 0;
                                endif;
                                ?>
                                @if($Invoice->GST == 'on')
                                    <tr>
                                    <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                        <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" style="text-align: right;">{{ $Invoice->gst_text }} {{ $Invoice->tax_rate }}%</th>
                                        <?php $tax = $tax_rate*$new_total_price;?>
                                        <td style="background: #fff; border: 1px solid #000;adding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($tax, 2) }}</td>
                                    </tr>
                                @endif

                                @if($Invoice->gst_fixed > 0)
                                    <tr>
                                    <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                        <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" style="text-align: right;">Subtotal</th>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($totalPrice, 2) }}</td>
                                    </tr>
                                    <tr>
                                    <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                        <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" style="text-align: right;">GST Fixed</th>
                                        <?php
                                        $gstfixed = 0; 
                                        $gstfixed = $Invoice->gst_fixed;?>
                                        <td style="background: #fff; border: 1px solid #000;adding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($Invoice->gst_fixed, 2) }}</td>
                                    </tr>
                                @endif
                                <?php
                                    $totalPrice = 0;
                                    $allprouctgst = 0; // Initialize the total GST amount outside the loop
                            
                                ?>
                                @if(isset($Product) && $Product->gst_product > 0)
                                    <tr>
                                        <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                        <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;padding: 2px;font-size:12px!important;" style="text-align: right;">Gst</th>
                                        <?php
                                            $allprouctgst = 0; // Initialize variable
                                            foreach ($InvoiceProducts as $Product) {
                                                $Gstamount = $Product->gst_product / 100;
                                                $price1 = $Product->unit_price;

                                                $totalPrice += $Product->qty * $price1;
                                                $new = $price1 * $Product->qty;
                                                $totalgst = $new * $Gstamount;
                                                $allprouctgst += $totalgst; // Accumulate the GST amount for each product
                                            }
                                        ?>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;padding: 2px;font-size:12px!important;"  class="text-right">{{ number_format($allprouctgst, 2) }}</td>
                                    </tr>
                                @endif
                                @if($Invoice->WHT == 'on')
			                        <?php 
			                        $wh_tax = $Invoice->wh_tax/100;
			                        if($Invoice->WHT == 'on'):
			                        	$wh_tax_amount = $new_total_price*$wh_tax;  
			                    	else:
			                    		$wh_tax_amount = 0;
			                    	endif;
			                    	$tax = $tax+$wh_tax_amount;
			                        ?>
			                        <tr>
			                            <td colspan="4" style="border:none"></td>
			                            <th colspan="2" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;width:20%!important">W.H Tax@ {{ $Invoice->wh_tax}}%</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($wh_tax_amount,2) }}</td>
			                        </tr>
		                        @endif

                                @if($Invoice->transportaion > 0)
                                <tr>
                                <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>

                                    <th colspan="2" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;">Transportation</th>
                                    <?php 
                                    $gstfixed = 0; 
                                    $gstfixed = $Invoice->gst_fixed;
                                    $transportaion  = $Invoice->transportaion;
                                    $netTotal       = $new_total_price+$tax+$transportaion+$gstfixed+$allprouctgst;
                                    ?>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ ($transportaion) ? number_format($transportaion, 2) : '0.00' }}</td>
                                </tr>
                                @else
                                <?php
                                $gstfixed = 0; 
                                $gstfixed = $Invoice->gst_fixed;
                                $netTotal = $new_total_price+$tax+$gstfixed+$allprouctgst; ?>
                                @endif
                                <tr>
                                <td colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '4' : '4' }}"></td>
                                    <th colspan="2" style="background: #fff; border: 1px solid #000; padding-left: 5.5em; padding: 2px; font-size: 12px !important;">Total Amount</th>
                                    <td style="background: #fff; border: 1px solid #000; padding-left: 5.5em; padding: 2px; font-size: 12px !important;" class="text-right">{{ number_format($netTotal, 2) }}</td>
                                </tr>
                              </tbody>
                            </table>
                            

                            <div class="row">
                                <div class="col-5">
                                    <img src="/signature/{{ Auth::user()->signature }}" style="width:100px; height:60px">
    		                        <p>Direct All Inquiries To: <br>
                                    {{ Auth::user()->username }}<br>
                                    |Tel: {{ Auth::user()->Tel }} | Cell: {{ Auth::user()->phone_number }}|  <br>
			                        |E-Mail: sales@firesafetytrading.com.pk|
                                </div>
                                <div class="col-1"></div>
                                <div class="col-6">
                                    <br><br><br>
                                    <p> <br>
                                    _____________________________________________________<br>
                                    Received By: (Name & Stamp)
                                    </p>
                                </div>
                            </div>
                            
                            <p class="text-center" style="font-size:12px!important;">
                                <strong>Make All Payable to <u>Fire Safety Trading (Pvt) Ltd</u></strong><br>
                                Thank You For Your Business ...!
                            </p>
                    </div>
                    
                    
                    <div class="no-print" style="text-align:center!important">
                        <a href="{{ route('downloadInvoice',$Invoice->id) }}" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i>Download With Header & Footer</a>
                        <a href="{{ route('downloadInvoice2',$Invoice->id) }}" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-download"></i>Download With Out Header & Footer</a>
                    </div>
                </td>
            </tr>
        </tbody>


        <tfoot>
            <tr>
                <td>
                    <div class="footer-space">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="header"><img src="{{ asset('assets/header2.jpeg') }}" style="width: 124%; height:100%;" /></div>
    <!--style="width: 210mm!important;" -->
<div class="footer"><img src="{{ asset('assets/footer2.jpeg') }}" style="width: 123%; height:100%;" /></div>

</div>
  
</body>
</html>
