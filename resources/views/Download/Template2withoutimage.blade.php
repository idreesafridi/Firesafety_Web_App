<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>{{$quote->id}}

        @if($quoteCustomer)
        -{{$quoteCustomer->company_name}} @if(isset($quoteCustomer->city))-{{ $quoteCustomer->city }} @endif
        @endif
    	</title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
              	height: 220px!important;
            }

            .footer, .footer-space
            {
               height: 200px!important;
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
              size: A4;
              margin: 0;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 250mm; /*height: 297mm;*/
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
            .content {
                width: 900px;
            }
            
        </style>
    </head>
<body onload="print()">

    <table>
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
                    <div class="content" style="padding-left: 20px!important; padding-right: 20px!important;">
	                        <div class="row" style="margin-bottom: -2em;">
			                    <div class="col-12">
			                        <table style="white-space: nowrap;font-weight: bolder;font-size: 16px; width: 100%!important;">
			                            <tr>
			                                <th colspan="5" style="color: red;text-align: center;border-bottom: 2em;background: #fff;font-weight: bolder;font-size:15px!important; padding-left: 12em;">QUOTATION</th>
			                            </tr>                  
			                            <tr>
			                                <th style="padding: 2px;background: #fff; border: 1px solid #000;width:10%;font-size:12px!important;font-weight:normal">M/S</th>
			                                <td colspan="3" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ ($quoteCustomer) ? $quoteCustomer->company_name : 'N/A' }}</td>
			                                
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:18%;font-size:12px!important;">Date</td>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">{{ date("d/m/Y", strtotime($quote->dated)) }}</td>
			                            </tr>
			                            
			                            <tr>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Address</td>
			                                <td colspan="3" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important;"> {{ ($quoteCustomer) ? $quoteCustomer->address : 'N/A' }}</td>
			                                
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:18%;font-size:12px!important;">From</td>
    		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">
    		                                {{ Auth::User()->username }}
    		                            	</td>
    		                            	
			                            </tr>
			                            
			                            <tr>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Email</td>
			                                <td colspan="3" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important;">&nbsp;{{ ($quoteCustomer) ? $quoteCustomer->email : 'N/A' }}</td>
			                                
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:18%;font-size:12px!important;">Desig</td>
    			                            <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">{{ Auth::User()->custom_designation }}</td>
			                            
			                                
			                            </tr>
			                            
			                            <tr>
			                                
    			                           <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Ph No</td>
			                                <td colspan="3" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ ($quoteCustomer) ? $quoteCustomer->phone_no : 'N/A' }}</td>
			                                
    		                            	
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:18%;font-size:12px!important;">Our NTN #</td>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;padding-left:0px;font-size:12px!important;">&nbsp;4738498-3</td>
			                            </tr>
			                            
			                            <tr>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Att:</td>
			                                <td colspan="3" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ ($quoteCustomer) ? $quoteCustomer->customer_name : 'N/A' }}</td>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:18%;font-size:12px!important;">Quote Ref:</td>
			                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">{{ $quote->id }}</td>
			                            </tr>
			                        </table>
			                        
			                        <!--<hr style="width:5px; background:red">-->
			                        <h4 style="margin-top: 1em!important;font-size:15px!important;">{{$quote->subject}}</h4>
			                        <!--<p>-->
			                        <!--    Sir, <br>-->
			                        <!--    We have the pleasure in quoting our rates as under. We thank you for interest in our products and look forward for the opportunity to serve your demand.-->
			                        <!--</p>-->
			                    </div>
			                </div>
			                
			                <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 3em!important; width: 100%!important;overflow-wrap: anywhere;">
			                    <thead>
			                        <tr>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:5%!important">S.No</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: left;!important;width:35%!important" class="text-left" colspan="3">Description</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:8%!important" class="text-center">Size</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:7%!important" class="text-right">Qty</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:7%!important" class="text-center">UOM</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:10%!important" class="text-right">Unit/Price</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:10%!important" class="text-right">Total</th>
										
			                        </tr>
			                    </thead>
			                    <tbody>    
				                    @if($QouteProducts)
									<?php $totalPrice = 0; $count = 1; ?>
									@foreach($QouteProducts as $Product)   
									    @if(isset($Product->heading) && !empty($Product->heading))
									    <tr>
				                            <th colspan="10" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: left!important;width:5%!important">{{ $Product->heading }}</th>
				                        </tr>
				                        @endif
					                    <tr>
                                            <td style="background: #fff; border: 1px solid #000; font-weight: 400; padding: 2px; text-align: center;">
                                                {{$count}}
                                            </td>
				                            <?php $productData = App\Models\Product::find($Product->product_id); ?>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-left" colspan="3">
				                                {!! (isset($Product->description)) ? $Product->description : $productData->description !!}
				                            </td>
				                            
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-center">
				                            	{{ $Product->productCapacity }}
				                            </td>
				                            
											
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-center" class="qty">{{ number_format($Product->qty, 2) }}</td>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-center">{{ $productData->unit }}</td>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-right">{{ number_format($Product->unit_price,2)  }}</td>

				                            <?php 
				                            // Capacity
										  //  if(isset($Product->productCapacity)):
										  //  	$price1 = $Product->unit_price*$Product->productCapacity;
										  //  else:
										  //  	$price1 = $Product->unit_price;
										  //  endif;
										    
										    $price1 = $Product->unit_price;

										    $totalPrice += $Product->qty*$price1;
				                            ?>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;" class="text-right">
				                            	{{ number_format($price1*$Product->qty,2)  }}
				                            </td>
				                        </tr>
							        	<?php $count++ ?>
									@endforeach
									@endif      

									@if($quote->other_products_name)
									<?php 
									$moreProductsNames = explode('@&%$# ', $quote->other_products_name);
									$moreProductsQty   = explode('@&%$# ', $quote->other_products_qty);
									$moreProductsPrice = explode('@&%$# ', $quote->other_products_price);
									$moreProductsUnit = explode('@&%$# ', $quote->other_products_unit);
									$moreProductsSize = explode('@&%$# ', $quote->other_products_size);
									$other_products_image = explode('@&%$# ', $quote->other_products_image);
									$other_products_heading = explode('@&%$# ', $quote->other_products_heading);
									$count2 = 0;
									?>
									@foreach($moreProductsNames as $moreP)
									@if(!empty($moreP))
									@if(isset($other_products_heading[$count2]) && !empty($other_products_heading[$count2]))
								    <tr>
			                            <th colspan="10" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: left!important;width:5%!important">{{ $other_products_heading[$count2] }}</th>
			                        </tr>
			                        @endif
									<tr>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;">{{$count}}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" colspan="3">
			                        	{{$moreP}}
			                        	</td>
                                        
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;text-align:right;padding: 2px;" class="text-center">
			                            {{ (isset($moreProductsSize[$count2])) ? $moreProductsSize[$count2] : '1.00' }}
			                        	</td>
			                        	
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;;padding-left: 1px;padding-right: 1px;" class="text-center">
			                            	@if(isset($other_products_image[$count2]) AND !empty($other_products_image[$count2]))
			                            	<img src="/other_products_image/{{ $other_products_image[$count2] }}" style="width:15px; height: 14px">
			                            	@endif
			                            </td>
			                            
			                            <?php 
			                            $qty    =   ($moreProductsQty[$count2]) ? $moreProductsQty[$count2] : 0; 
			                            $price  =   ($moreProductsPrice[$count2]) ? $moreProductsPrice[$count2] : 0.00;
			                            ?>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;"  class="qty text-center">{{ ($moreProductsQty[$count2]) ? number_format($moreProductsQty[$count2],2) : '0.00' }}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-center">{{ ($moreProductsUnit[$count2]) ? $moreProductsUnit[$count2] : '' }}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-center">{{ ($moreProductsPrice[$count2]) ? number_format($moreProductsPrice[$count2],2) : '0.00 ' }}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;text-align:right;padding: 1px;" class="text-center">
			                            {{ number_format($qty*$price,2)  }}
			                        	</td>
			                        </tr>
			                        <?php $totalPrice += $qty*$price; $count++; $count2++; ?>
			                        @endif
									@endforeach
									@endif                                 
			                    </tbody>
								<table border="0" cellspacing="0" cellpadding="0" style="float: right;width: 100%!important;">
			                    <tfoot> 
			                    	<?php
			                        $Net_totalPrice = $totalPrice;
			                        $Net_Tranporation = $quote->transportaion;
			                        $new_total_price = $Net_totalPrice;
									$gstfixed = 0; 
									$gstfixed = $quote->gst_fixed;
			                        $Net_Grand = $Net_totalPrice;
			                        ?>

			                        @if($quote->tax_rate > 0 OR $quote->transportaion > 0 OR $quote->discount_percent > 0 || $quote->discount_fixed > 0)
			                        <tr> 
			                            <td colspan="5" style="border:none"></td>
			                            <th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">SUB-Total</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{number_format($totalPrice,2) }}</td>
			                        </tr>
			                        @endif 
			                        
									@if($quote->discount_percent > 0 || $quote->discount_fixed > 0)
										<?php
										if(isset($quote->discount_percent)):
											$discount_value     = ($Net_totalPrice / 100) * $quote->discount_percent;
											$new_total_price    = $Net_totalPrice - $discount_value;
										elseif(isset($quote->discount_fixed)):
											$discount_value = $quote->discount_fixed;
											$new_total_price    = $Net_totalPrice - $discount_value;
										endif;
										?>
										<tr>
											<td colspan="5" style="border:none"></td>
											<th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">Discount ({{$quote->discount_percent}}%)</th>
											<td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($discount_value,2) }}</td>
										</tr>

										@if(!$quote->GST == 'on')
											<?php 
											$tax_rate = $quote->tax_rate/100;
											$GST = $new_total_price * $tax_rate;
											$Net_Grand = $new_total_price + $GST;
											?>

											<tr>
												<td colspan="5" style="border:none"></td>
												<th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">Grand Total</th>
												<td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($Net_Grand,2) }}</td>
											</tr>
										@endif

    			                        @if($quote->tax_rate > 0 OR $quote->transportaion > 0 ))
    			                        <tr>
    			                            <td colspan="5" style="border:none"></td>
    			                            <th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">Total</th>
    			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($new_total_price,2) }}</td>
    			                        </tr>
    			                        @endif
			                        @endif
			                           
			                        @if($quote->GST == 'on')
    			                        <?php 
    			                        $tax_rate = $quote->tax_rate/100;
    			                        if($quote->GST == 'on'):
    			                        	$GST = $new_total_price*$tax_rate;  
    			                    	else:
    			                    		$GST = 0;
    			                    	endif;
										$gstfixed = 0; 
                                        $gstfixed = $quote->gst_fixed;
    			                    	$Net_Grand = $new_total_price+$gstfixed+$GST;
    			                        ?>
    			                        <tr>
    			                            <td colspan="5" style="border:none"></td>
    			                            <th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;width:20%!important">{{ $quote->gst_text }}@ {{ $quote->tax_rate}}%</th>
    			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($GST,2) }}</td>
    			                        </tr>
										@if($quote->gst_fixed > 0)
                                    <tr>
     									<td colspan="5" style="border:none"></td>
										 <th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;width:20%!important">GST Fixed</th>
                                        <?php
                                        $gstfixed = 0; 
                                        $gstfixed = $quote->gst_fixed;?>
                                        <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($quote->gst_fixed, 2) }}</td>
                                    </tr>
                                @endif
											                        
								@if($quote->WHT == 'on')
			                        <?php 
			                        $wh_tax = $quote->wh_tax/100;
			                        if($quote->WHT == 'on'):
			                        	$wh_tax_amount = $new_total_price*$wh_tax;  
			                    	else:
			                    		$wh_tax_amount = 0;
			                    	endif;
			                    	$Net_Grand = $Net_Grand+$wh_tax_amount;
			                        ?>
			                        <tr>
			                            <td colspan="5" style="border:none"></td>
			                            <th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;width:20%!important">W.H Tax@ {{ $quote->wh_tax}}%</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($wh_tax_amount,2) }}</td>
			                        </tr>
    			                    @else
        			                    <?php// $Net_Grand = $Net_Grand+$Net_Tranporation; ?> 
			                        @endif
										<tr>
											<td colspan="5" style="border:none"></td>
											<th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">Grand Total</th>
											<td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($Net_Grand,2)}}</td>
										</tr>
    			                    @else
        			                    <?php //$Net_Grand = $new_total_price+$Net_Tranporation; ?>
			                        @endif
									
			                        
                                     @if($quote->transportaion > 0)
			                        <tr>
			                            <td colspan="5" style="border:none"></td>
			                            <th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;width:20%!important">Transportaion</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format( $quote->transportaion,2) }}</td>
										
			                        </tr>
			                        <?php
									$gstfixed = 0; 
									$gstfixed = $quote->gst_fixed;
									$Net_Grand = $Net_Grand+$Net_Tranporation+$gstfixed; ?>
									<tr>
											<td colspan="5" style="border:none"></td>
											<th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">Grand Total</th>
											<td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($Net_Grand,2)}}</td>
										</tr> 
			                        @endif    
									@if(!($quote->discount_percent > 0 || $quote->discount_fixed > 0 || $quote->GST == 'on'))
                                        <tr>
											<td colspan="5" style="border:none"></td>
											<th colspan="4" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;width:20%!important">Grand Total</th>
											<td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;width:10%!important" class="text-right">{{ number_format($Net_Grand,2)}}</td>
										</tr>
									@endif
			                    </tfoot>
			                </table>
			                <div style="word-break: unset;"><p>{!! $quote->termsConditions !!}</p></div>
			                
			                <div class="thanks">Thank you!</div>
			                
			                 <!--style="height: 160px;margin-top: 2em;"-->
			                <div class="notices" style="height: 150px;margin-top: 10px;">
			                    <div style="color:red">{{ Auth::user()->username }}</div>
			                    <div>
			                    |{{ Auth::user()->custom_designation }}| Fire Safety Trading (Pvt.) Ltd.| <br>
			                    |Tel: {{ Auth::user()->Tel }} | Cell: {{ Auth::user()->phone_number }}|  <br>
			                    |E-Mail: {{ Auth::user()->email }}|
			                    </div>
			                </div>
			                
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
</div>
  
</body>
</html>