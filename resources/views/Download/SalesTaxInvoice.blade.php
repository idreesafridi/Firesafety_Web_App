<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>
            {{$Invoice->invoice_no}}
            @if(isset($InvoiceCustomer))
            -{{$InvoiceCustomer->company_name}} @if(isset($InvoiceCustomer->city))-{{ $InvoiceCustomer->city }} @endif
            @endif
        </title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
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
                left: 10;
                right: 10;
            }
            .footer {
                position: fixed;
                bottom: 0; 
                left: 10;
                right: 10;
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
                    <div class="content" style="padding-left: 50px!important; padding-right: 50px!important">
                        <div class="row">
                            <div class="col-12">
                                <table style="white-space: nowrap;font-weight: bolder;font-size: 16px;">
		                            <tr>
		                                <th colspan="5" style="color: red;text-align: center;border-bottom: 2em;background: #fff;font-weight: bolder;font-size:15px!important;">Sales Tax Invoice</th>
		                            </tr>
		                            
		                            <tr>
		                                <th style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:bolder; width:10%;font-size:12px!important;font-weight:normal">M/S</th>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{ ($InvoiceCustomer) ? $InvoiceCustomer->company_name : 'N/A' }}</td>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Invoice NO</td>
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;padding-left:0px;font-size:12px!important;">&nbsp;{{ $Invoice->invoice_no }}</td>
		                            </tr>
		                            
		                            <tr>
		                                
		                                <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Address</td>
			                            <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important;">&nbsp;
			                            @if(isset($InvoiceCustomer))
			                            {{ $InvoiceCustomer->address }} @if(isset($InvoiceCustomer->city)),{{ $InvoiceCustomer->city }} @endif
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
		                                <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;">{{  ($InvoiceCustomer) ? $InvoiceCustomer->customer_name : 'N/A' }}</td>
		                                
		                            	
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
                
                                        @if(isset($InvoiceProducts) && $InvoiceProducts->isNotEmpty() && ($Invoice->tax_rate > 0 || $InvoiceProducts->contains('gst_product', '>', 0)))
                                            <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">Amount W/O gst</th>
                                            <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">GST %</th>
                                            <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">GST Value</th>
                                           <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">Amount with Gst</th>
                                        @endif
                                        @if(isset($InvoiceProducts) && $InvoiceProducts->isNotEmpty() && $Invoice->tax_rate <= 0)
                                            @php $hasNoGstProduct = true; @endphp
                                            @foreach($InvoiceProducts as $product)
                                                @if($product->gst_product > 0)
                                                    @php $hasNoGstProduct = false; break; @endphp
                                                @endif
                                            @endforeach

                                            @if($hasNoGstProduct)
                                                <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">Total</th>
                                            @endif
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $totalPrice = 0; $count=1; $new_total_price=0; ?>
                                @if($InvoiceProducts)
                                @foreach($InvoiceProducts as $Product)
                                <tr>
                                    <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{$count}}</td>
                                    
                                     <?php $productData = App\Models\Product::find($Product->product_id); ?>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-left">
				                                {!! (isset($Product->description)) ? $Product->description : $productData->description !!}
				                            </td>                                    
                                    
                                    <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;;font-size:12px!important;width: 5%!important;" class="text-center">
                                        {{ $Product->productCapacity }}
                                    </td>
                                    
                                    <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{ number_format($Product->qty,2) }}</td>
                                    <?php
                                    $allprouctgst = 0; 
                                    $amountwithoutgst = 0;
                                    $totalamountwithoutgst = 0;
                                    $gstproducttotal = 0;
                                    $Gstamount = $Product->gst_product/100;
                                    $tax_rate = $Invoice->tax_rate/100;
                                    
                                    $price1 = $Product->unit_price;

                                    $totalPrice += $Product->qty*$price1;
                                    $new =  $price1*$Product->qty;
                                    $gstproduct = $new*$Gstamount;
                                    $totaltax = $new*$tax_rate;
                                    $allprouctgst +=$gstproduct;
                                    
                                    $price1*$Product->qty;
                                    $amountwithoutgst += $price1;                            
                                ?>
                                <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{ $productData->unit }}</td>
                                <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 10%!important;font-size:12px!important;" class="text-center">{{ number_format($Product->unit_price,2) }}</td>
                                <?php 
                                    // Capacity
                                    // if(isset($Product->productCapacity)):
                                    //     $price1 = $Product->unit_price*$Product->productCapacity;
                                    // else:
                                    //     $price1 = $Product->unit_price;
                                    // endif;
                                    
                                    $price1 = $Product->unit_price;

                                    $totalPrice += $Product->qty*$price1;
                                    $price1*$Product->qty;
                                ?>
                                @if(isset($InvoiceProducts) && $InvoiceProducts->isNotEmpty() && ($Invoice->tax_rate > 0 || $InvoiceProducts->contains('gst_product', '>', 0)))
                                    <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;;font-size:12px!important;" class="text-center">
                                        {{ number_format($price1*$Product->qty,2)  }}
                                    </td>

                                    <td style="background: #fff; border: 1px solid #000; padding: 2px; width: 5%!important; font-size: 12px!important;" class="text-center">
                                        @if ($Product->gst_product)
                                            {{ $Product->gst_product }}%
                                        @else
                                            {{ $Invoice->tax_rate }}%
                                        @endif
                                    </td>

                                    <td style="background: #fff; border: 1px solid #000; padding: 2px; width: 5%!important; font-size: 12px!important;" class="text-center">
                                        @if ($gstproduct)
                                            {{ number_format($gstproduct, 2) }}
                                        @else
                                            {{ number_format($totaltax, 2) }}
                                        @endif
                                    </td>
                                    <?php 
                                    // Capacity
                                    // if(isset($Product->productCapacity)):
                                    //     $price1 = $Product->unit_price*$Product->productCapacity;
                                    // else:
                                    //     $price1 = $Product->unit_price;
                                    // endif;
                                    
                                    $price1 = $Product->unit_price;

                                    $totalPrice += $Product->qty*$price1;
                                    ?>
                                    <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;;font-size:12px!important;" class="text-center">
                                    {{ number_format(($price1 * $Product->qty + ($gstproduct ?: $totaltax)), 2) }}
                                    </td>
                                @endif
                                    @if(!$Invoice->tax_rate > 0 && $Product->gst_product <= 0) 
                                    <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;;font-size:12px!important;" class="text-center">
                                        {{ number_format($price1*$Product->qty,2)  }}
                                    </td>
                                    @endif
                                </tr>
                                <?php $count++; ?>
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
                                <?php
                                    $totalPricewithoutgst = 0; // Initialize total price variable
                                    $allprouctgst = 0; 
                                    $amountwithoutgst = 0;
                                    $totalamountwithoutgst = 0;
                                    $gstproducttotal = 0;
                                    $totaltaxrate = 0;
                                    $netTotal = 0;
                                    $grandtotal = 0;
                                      
                                    foreach ($InvoiceProducts as $Product) { // Assuming $products is an array of Product objects
                                     $price1 = $Product->unit_price;
                                    $totalPricewithoutgst += $Product->qty * $price1;
                                    $gstproducttotal +=$gstproduct;
                                    $Gstamount = $Product->gst_product/100;
                                    $tax_rate = $Invoice->tax_rate/100;
                                    $totalPrice += $Product->qty*$price1;
                                    $new =  $price1*$Product->qty;
                                    $gstproduct = $new*$Gstamount;
                                    $totaltax = $new*$tax_rate;
                                    $allprouctgst +=$gstproduct;
                                    $price1*$Product->qty;
                                    $amountwithoutgst += $price1; 
                                    $totaltaxrate += $totaltax; 
                                    }
                                ?>  
                                @if(isset($InvoiceProducts) && $InvoiceProducts->isNotEmpty() && ($Invoice->tax_rate > 0 || $InvoiceProducts->contains('gst_product', '>', 0)))
                            <tr>
                                <th colspan="6" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" class="text-right">Total</th>                                
                                <th style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{$totalPricewithoutgst}}</th>
                                <th style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right"></th>

                                @if($Invoice->discount_percent > 0 || $Invoice->discount_fixed > 0)
                                    <?php
                                    // if(isset($Invoice->discount_percent)):
                                    //     $discount_value     = ($totalPrice / 100) * $Invoice->discount_percent;
                                    //     $new_total_price    = $totalPrice - $discount_value;
                                    // elseif(isset($Invoice->discount_fixed)):
                                    //     $discount_value = $Invoice->discount_fixed;
                                    //     $new_total_price    = $totalPrice - $discount_value;
                                    // endif;
                                    // $after_discount=$new_total_price;
                                    ?>
                                    <th style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{ number_format($net_discount, 2) }}</th>
                                @endif

                                <th style="background: #fff; border: 1px solid #000;adding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">
                                @if ($gstproduct)
                                            {{ number_format($allprouctgst, 2) }}
                                        @else
                                            {{ number_format($totaltaxrate, 2) }}
                                        @endif
                                </th>
                                <th style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">
                                {{$totaltaxrate +$allprouctgst + $totalPricewithoutgst}}
                               </th>                                        
                            </tr>
                            @endif
                            
                            <?php
	                        // $Net_totalPrice = $totalPrice;; //+$gst;
	                        // $Net_Tranporation = $Invoice->transportaion;
	                        // $new_total_price = $Net_totalPrice; 

                         //    $after_discount=$new_total_price;
	                        ?>    
                            <?php $transportaion = 0; ?>
                            @if($Invoice->transportaion > 0)
                            <tr>
                                <th colspan="9" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;width:10px!important;font-size:12px!important;"class="text-right">Transportation</th>
                                <?php 
                                $transportaion = $Invoice->transportaion;
                                ?> 
                                <th style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" class="text-right">{{ ($transportaion) ? number_format($transportaion, 2) : '0.00' }}</th>
                            </tr>
                            @else
                            @endif
                            <?php 
                            
                            // $gst = ($Invoice->tax_rate/100)*$after_discount;
                            // $afer_gst = $after_discount+$gst; 
                            // $afer_gst += $transportaion;
                            ?>
                            <?php 
                            $grandtotal = $totaltaxrate +$allprouctgst + $totalPricewithoutgst + $transportaion;
                            ?>
                            <tr>
                                <th colspan="{{ ($Invoice->tax_rate > 0 || $Product->gst_product > 0) ? '9' : '6' }}" style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;width:10px!important;font-size:12px!important;" class="text-right">Total Amount</th>
                                <th style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;" class="text-right">{{$grandtotal}}</th>              
                             </tr>
                          </tbody>
                        </table>
                        

                        <div class="row">
                            <div class="col-5" style=";font-size:12px!important;">
                                <!--<img src="/signature/1623227989.jpeg" style="width:100px; height:60px">-->
                                <!--<p>Direct All Inquiries To: <br>-->
                                <!--Khadim Hussain <br>-->
                                <!--+92315-4233313<br>-->
                                <!--info@universalfireprotection.com.pk-->
                                <img src="/signature/{{ Auth::user()->signature }}" style="width:100px; height:60px">
		                        <p>Direct All Inquiries To: <br>
                                {{ Auth::user()->username }}<br>
                                |Tel: {{ Auth::user()->Tel }} | Cell: {{ Auth::user()->phone_number }}|  <br>
			                    |E-Mail: sales@firesafetytrading.com.pk|
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6" style=";font-size:12px!important;">
                                <br><br><br>
                                <p style=";font-size:12px!important;"> <br>
                                _____________________________________________________<br>
                                Received By: (Name & Stamp)
                                </p>
                            </div>
                        </div>
                        

                        <p class="text-center">
                            <strong>Make All Payable to <u>Fire Safety Trading (Pvt) Ltd</u></strong><br>
                            Thank You For Your Business ...!
                        </p>

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
    <div class="header"><img src="{{ asset('assets/header2.jpeg') }}" style="width: 790px; height:100%;" /></div>
    <!--style="width: 210mm!important;" -->
    <div class="footer"><img src="{{ asset('assets/footer2.jpeg') }}" style="width: 120%; height:100%;" /></div>

</div>
  
</body>
</html>